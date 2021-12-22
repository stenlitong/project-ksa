<?php

namespace App\Http\Controllers;

use Storage;
use Response;
use validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\Gmail;
use App\Models\User;
use App\Models\spgrfile;
use App\Models\formclaims;
use App\Models\headerformclaim;
use App\Models\tempcart;
use App\Models\NoteSpgr;
use App\Exports\FCIexport;


class picincidentController extends Controller
{
    //view FCI
    public function formclaim(){
        $tempcarts = tempcart::where('user_id', Auth::user()->id)->get();
        $latestcarts = tempcart::where('user_id', Auth::user()->id)->first();
        return view('picincident.formclaim' , compact('tempcarts' , 'latestcarts'));
    }

    public function destroy(tempcart $temp){
        tempcart::destroy($temp->id); 
        formclaims::destroy($temp->id); 
        return redirect('/picincident/formclaim')->with('success', 'post telah dihapus.'); 
    }

    // addtoCart 
    public function submitformclaim(Request $request){
        // dd(Auth::user()->id);
        // dd($request->TSI_TugBoat);
        $user = Auth::user()->id;
        tempcart::create([
            'user_id' => $user,
            'tgl_insiden' => $request->dateincident ,
            'tgl_formclaim' => $request->dateclaim , 
            'name'=> $request->name ,
            'jenis_incident'=> $request->jenisincident ,
            'item' => $request->Item_name ,
            'no_FormClaim'=> $request->FormClaim , 
            'barge'=> $request->barge ,
            'TSI_barge'=> $request->TSI_barge,
            'TSI_TugBoat'=> $request->TSI_TugBoat,
            'deductible'=>$request->Deductible ,
            'amount'=> $request->Amount,
            'surveyor'=> $request->Surveyor,
            'tugBoat'=> $request->TugBoat,
            'incident'=> $request->Incident ,
            'description'=> $request->reasonbox ,
        ]);

        // return redirect('/picincident/formclaim')->with('success', 'Form Telah Berhasil Di Tambahkan.');
        return redirect()->back()->with('success', 'Form Telah Berhasil Di Tambahkan.');
    }

    // create finallize/historyFCI
    public function createformclaim(Request $request){
        // tempcart::truncate();
        $temp = tempcart::where('user_id', Auth::user()->id)->get();

        if (count($temp) == 0){
            return redirect()->back()->with('ERR' , "Cart is empty , Please ADD to list" );
        }

        $headerid = headerformclaim::create([
            'user_id' => Auth::user()->id,
            'nama_file'=> $request->nama_file ,
        ]);

        foreach( $temp as $temp){
            formclaims::create([
                'user_id' => Auth::user()->id,
                'header_id' => $headerid -> id,
                'tgl_insiden' => $temp->tgl_insiden ,
                'tgl_formclaim' => $temp->tgl_formclaim , 
                'name'=> $temp->name ,
                'jenis_incident'=> $temp->jenis_incident ,
                'item' => $temp->item ,
                'no_FormClaim'=> $temp->no_FormClaim , 
                'barge'=> $temp->barge ,
                'TSI_barge'=> $temp->TSI_barge,
                'TSI_TugBoat'=> $temp->TSI_TugBoat,
                'deductible'=>$temp->deductible ,
                'amount'=> $temp->amount,
                'surveyor'=> $temp->surveyor,
                'tugBoat'=> $temp->tugBoat,
                'incident'=> $temp->incident ,
                'description'=> $temp->description ,
            ]);
        }
        
        tempcart::where('user_id', Auth::user()->id)->delete();
        
        return redirect('/picincident/history');
    }

    public function formclaimhistory() {
        $Headclaim = headerformclaim::all();
        return view('picincident.historyFCI' , compact('Headclaim'));
    }
    
    public function DestroyExcel(headerformclaim $claims){
        headerformclaim::destroy($claims->id); 
        return redirect('/picincident/history')->with('success', 'File telah dihapus.'); 
    }

    // export function
    private $excel;
    public function __construct(Excel $excel){
        $this->excel = $excel;
    }

    public function export(Request $request) {
        // dd($request);
        $identify = $request->file_id;
        return $this->excel::download(new FCIexport($identify), 'FCI.xlsx');
        // return (new PRExport($orderHeads -> order_id))->download('PR-' . $orderHeads -> order_id . '_' .  date("d-m-Y") . '.pdf', Excel::DOMPDF);
    }

    
    // note spgr
    public function destroynote(NoteSpgr $UpNotes){
        NoteSpgr::destroy($UpNotes->id); 
        return redirect('/picincident/NoteSpgr')->with('success', 'post telah dihapus.'); 
    }

    public function updatenote(Request $request, NoteSpgr $UpNotes){
        $update = NoteSpgr::find($UpNotes->id);
        $update->DateNote = $request->Datebox;
        $update->No_SPGR = $request->No_SPGR;
        $update->No_FormClaim = $request->No_FormClaim;
        $update->Nama_Kapal = $request->NamaKapal;
        $update->status_pembayaran = $request->status_pembayaran;
        $update->Nilai = $request->Nilai;
        $update->Nilai_Claim = $request->NilaiClaim;
        $update->update();
        return redirect('/picincident/NoteSpgr')->with('success', 'post telah terupdate.'); 
    }

    public function uploadnotespgr(Request $request){
        // dd($request);
        $request->validate([
            'No_SPGR'=> 'required|max:255',
            'NamaKapal'=> 'required|max:255',
            'NilaiClaim'=> 'required',
            // 'DateNote'=> 'required',
        ]);

        NoteSpgr::create([
            'DateNote' => $request->Datebox ,
            'No_SPGR' => $request->No_SPGR ,
            'No_FormClaim' => $request->No_FormClaim ,
            'Nama_Kapal' => $request->NamaKapal ,
            'status_pembayaran' => $request->status_pembayaran ,
            'Nilai' => $request->Nilai ,
            'Nilai_Claim' => $request->NilaiClaim ,
        ]);
        return redirect('/picincident/NoteSpgr')->with('success', 'Note telah ditambahkan.');
    }
    
    public function notespgr(){
        $UploadNotes =  DB::table('note_spgrs')->latest()->get();
        return view('picincident.NoteSpgr', compact('UploadNotes'));
    }


    // upload spgr file
    public function spgr(){
        $uploadspgr = spgrfile::with('user')->where('id', '>=' , 1)->latest()->get();
        return view('picincident.spgr', compact('uploadspgr'));
    }

    public function spgrupload(Request $request){
        // $uploadspgr = spgrfile::with('user')->where('id', '>=' , 1)->latest()->get();
        // dd($request);
        $year = date('Y');
        $month = date('m');
        $request->validate([
            'spgrfile1' => 'mimes:pdf|max:3072', 
            'spgrfile2' => 'mimes:pdf|max:3072', 
            'spgrfile3' => 'mimes:pdf|max:3072', 
            'spgrfile4' => 'mimes:pdf|max:3072', 
            'spgrfile5' => 'mimes:pdf|max:3072', 
            'spgrfile6' => 'mimes:pdf|max:3072', 
            'spgrfile7' => 'mimes:pdf|max:3072' 
        ]);
            
        if ($request->hasFile('spgrfile1')) {
            $file = $request->File('spgrfile1');
            $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
            $path = $request->file('spgrfile1')->storeas('spgr/'. $year . "/". $month , $name1, 's3');

            if (spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('cabang', 'Jakarta')->update([
                    'status1' => 'on review',
                    'time_upload1' => date("Y-m-d"),
                    'spgr' => basename($path),]);
            }else{
                spgrfile::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'status1' => 'on review',
                    'time_upload1' => date("Y-m-d"),
                    'spgr' => basename($path),
                ]);
            }
        }
        if ($request->hasFile('spgrfile2')) {
            $file = $request->File('spgrfile2');
            $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
            $path = $request->file('spgrfile2')->storeas('spgr/'. $year . "/". $month , $name1, 's3');

            if (spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('cabang', 'Jakarta')->update([
                    'status2' => 'on review',
                    'time_upload2' => date("Y-m-d"),
                    'Letter_of_Discharge' => basename($path),]);
                }else{
                spgrfile::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'status2' => 'on review',
                    'time_upload2' => date("Y-m-d"),
                    'Letter_of_Discharge' => basename($path),
                ]);
            }
        }
        if ($request->hasFile('spgrfile3')) {
            $file = $request->File('spgrfile3');
            $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
            $path = $request->file('spgrfile3')->storeas('spgr/'. $year . "/". $month , $name1, 's3');
            if (spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('cabang', 'Jakarta')->update([
                    'status3' => 'on review',
                    'time_upload3' => date("Y-m-d"),
                    'CMC' => basename($path),]);
                }else{
                    spgrfile::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'status3' => 'on review',
                    'time_upload3' => date("Y-m-d"),
                    'CMC' => basename($path),
                ]);
            }
        }
        if ($request->hasFile('spgrfile4')) {
            $file = $request->File('spgrfile4');
            $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
            $path = $request->file('spgrfile4')->storeas('spgr/'. $year . "/". $month , $name1, 's3');
            if (spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('cabang', 'Jakarta')->update([
                    'status4' => 'on review',
                    'time_upload4' => date("Y-m-d"),
                    'surat_laut' => basename($path),]);
                }else{
                spgrfile::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'status4' => 'on review',
                    'time_upload4' => date("Y-m-d"),
                    'surat_laut' => basename($path),
                ]);
            }
        }
        if ($request->hasFile('spgrfile5')) {
            $file = $request->File('spgrfile5');
            $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
            $path = $request->file('spgrfile5')->storeas('spgr/'. $year . "/". $month , $name1, 's3');
            if (spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('cabang', 'Jakarta')->update([
                    'status5' => 'on review',
                    'time_upload5' => date("Y-m-d"),
                    'spb' => basename($path),]);
                }else{
                spgrfile::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'status5' => 'on review',
                    'time_upload5' => date("Y-m-d"),
                    'spb' => basename($path),
                ]);
            }
        }
        if ($request->hasFile('spgrfile6')) {
            $file = $request->File('spgrfile6');
            $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
            $path = $request->file('spgrfile6')->storeas('spgr/'. $year . "/". $month , $name1, 's3');
            if (spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('cabang', 'Jakarta')->update([
                    'status6' => 'on review',
                    'time_upload6' => date("Y-m-d"),
                    'load_line' => basename($path),]);
                }else{
                spgrfile::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'status6' => 'on review',
                    'time_upload6' => date("Y-m-d"),
                    'load_line' => basename($path),
                ]);
            }
        }
        if ($request->hasFile('spgrfile7')) {
            $file = $request->File('spgrfile7');
            $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
            $path = $request->file('spgrfile7')->storeas('spgr/'. $year . "/". $month , $name1, 's3');
            if (spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('cabang', 'Jakarta')->update([
                    'status7' => 'on review',
                    'time_upload7' => date("Y-m-d"),
                    'surat_keterangan_bank' => basename($path),]);
                }else{
                spgrfile::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'status7' => 'on review',
                    'time_upload7' => date("Y-m-d"),
                    'surat_keterangan_bank' => basename($path),
                ]);
            }
        }
        return redirect('picincident/spgr')->with('success', 'Upload success!');
    }

}
