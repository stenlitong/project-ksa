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
    //view Form Claim page
    public function formclaim(){
        $tempcarts = tempcart::where('user_id', Auth::user()->id)->get();
        $latestcarts = tempcart::where('user_id', Auth::user()->id)->first();
        return view('picincident.formclaim' , compact('tempcarts' , 'latestcarts'));
    }
   
    // addtoCart 
    public function submitformclaim(Request $request){
        // dd($request);
        // $request->validate([
        //     'name'  => 'nullable|alpha_num',
        //     'dateincident' => 'nullable|date',
        //     'dateclaim' => 'nullable|date', 
        //     'FormClaim' => 'nullable|alpha_num', 
        //     'barge' => 'nullable|alpha_num',
        //     'TugBoat' => 'nullable|alpha_num',
        //     'TSI_barge' => 'numeric|nullable',
        //     'TSI_TugBoat' => 'numeric|nullable',
        //     'surveyor' => 'nullable|alpha_num',

        //     'Item_name'=> 'required|alpha_num',
        //     'incident'=> 'required|alpha_num',
        //     'description'=> 'required|alpha_num',
        //     'deductible' => 'nullable|numeric',
        //     'amount'=> 'required|numeric',
        // ]);
        tempcart::create([
            'user_id' => Auth::user()->id,
            'tgl_insiden' => $request->dateincident ,
            'tgl_formclaim' => $request->dateclaim , 
            'name'=> $request->name ,
            'jenis_incident'=> $request->jenisincident ,
            'item' => $request->Item_name ,
            'no_FormClaim'=> $request->FormClaim , 
            'barge'=> $request->barge ,
            'tugBoat'=> $request->TugBoat,
            'TSI_barge'=> $request->TSI_barge,
            'TSI_TugBoat'=> $request->TSI_TugBoat,
            'deductible'=>$request->Deductible ,
            'mata_uang_amount'=>$request->mata_uang_amount,
            'mata_uang_TSI'=>$request-> mata_uang_TSI,
            'amount'=> $request->Amount,
            'surveyor'=> $request->Surveyor,
            'incident'=> $request->Incident ,
            'description'=> $request->reasonbox ,
        ]);

        // return redirect('/picincident/formclaim')->with('success', 'Form Telah Berhasil Di Tambahkan.');
        return redirect()->back()->withInput()->with('success', 'Form Telah Berhasil Di Tambahkan.');
    }

    //delete post on Form Claim page
    public function destroy(tempcart $temp){
        tempcart::destroy($temp->id); 
        formclaims::destroy($temp->id); 
        return redirect('/picincident/formclaim')->with('success', 'post telah dihapus.'); 
    }

    // create finallize/historyFCI
    public function createformclaim(Request $request){
        // tempcart::truncate();
        $temp = tempcart::where('user_id', Auth::user()->id)->get();
        $temphead = tempcart::where('user_id', Auth::user()->id)->pluck('no_FormClaim')[0];

        if (count($temp) == 0){
            return redirect()->back()->with('ERR' , "Cart is empty , Please ADD to list" );
        }

        
        $headerid = headerformclaim::create([
            'user_id' => Auth::user()->id,
            'nama_file'=> $temphead ,
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
                'mata_uang_TSI'=>$temp-> mata_uang_TSI,
                'deductible'=>$temp->deductible ,
                'amount'=> $temp->amount,
                'mata_uang_amount'=>$temp->mata_uang_amount,
                'surveyor'=> $temp->surveyor,
                'tugBoat'=> $temp->tugBoat,
                'incident'=> $temp->incident ,
                'description'=> $temp->description ,
            ]);
        }
        
        tempcart::where('user_id', Auth::user()->id)->delete();
        
        return redirect('/picincident/history');
    }
    //view FCI for export page
    public function formclaimhistory() {
        $Headclaim = headerformclaim::all();
        return view('picincident.historyFCI' , compact('Headclaim'));
    }
    //delete Export post
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
        $name = $request->file_name;
        $identify = $request->file_id;
        return $this->excel::download(new FCIexport($identify), 'FCI'.$name.'.xlsx');
        // return (new PRExport($orderHeads -> order_id))->download('PR-' . $orderHeads -> order_id . '_' .  date("d-m-Y") . '.pdf', Excel::DOMPDF);
    }

   //Notes spgr page  
    public function notespgr(){
        $UploadNotes =  DB::table('note_spgrs')->get();
        return view('picincident.NoteSpgr', compact('UploadNotes'));
    }

    //create notes
    public function uploadnotespgr(Request $request){
        // dd($request);
        $request->validate([
            'No_SPGR'=> 'required',
            'NamaKapal'=> 'required',
            'No_FormClaim'=> 'required',
            'status_pembayaran'=> 'required',
            'NilaiClaim'=> 'required',
            // 'DateNote'=> 'required',
        ]);

        NoteSpgr::create([
            'user_id' => Auth::user()->id,
            'DateNote' => $request->Datebox ,
            'No_SPGR' => $request->No_SPGR ,
            'No_FormClaim' => $request->No_FormClaim ,
            'Nama_Kapal' => $request->NamaKapal ,
            'status_pembayaran' => $request->status_pembayaran ,
            'Nilai' => $request->Nilai ,
            'mata_uang_nilai' => $request->mata_uang_nilai ,
            'Nilai_Claim' => $request->NilaiClaim ,
            'mata_uang_claim' => $request->mata_uang_claim ,
        ]);
        return redirect('/picincident/NoteSpgr')->with('success', 'Note telah ditambahkan.');
    }

    // Notes spgr delete
    public function destroynote(NoteSpgr $UpNotes){
        NoteSpgr::destroy($UpNotes->id); 
        return redirect('/picincident/NoteSpgr')->with('success', 'post telah dihapus.'); 
    }

    //edit page
    public function editnotespgr(NoteSpgr $UpNotes){
        // dd($request);
        return view('picincident.editNoteSpgr',compact('UpNotes'));
    }

    //update notes
    public function updatenote(Request $request, NoteSpgr $UpNotes){
        $UpNotes = NoteSpgr::find($UpNotes->id);
        $UpNotes->DateNote = $request->Datebox;
        $UpNotes->No_SPGR = $request->No_SPGR;
        $UpNotes->No_FormClaim = $request->No_FormClaim;
        $UpNotes->Nama_Kapal = $request->NamaKapal;
        $UpNotes->status_pembayaran = $request->status_pembayaran;
        $UpNotes->mata_uang_nilai = $request->mata_uang_nilai;
        $UpNotes->Nilai = $request->Nilai;
        $UpNotes->mata_uang_claim = $request->mata_uang_claim;
        $UpNotes->Nilai_Claim = $request->NilaiClaim;
        $UpNotes->update();
        // dd($request);
        return redirect('/picincident/NoteSpgr')->with('success', 'post telah terupdate.'); 
    }
    
   

    // upload spgr file
    public function spgr(){
        // $uploadspgr = spgrfile::with('user')->where('id', '>=' , 1)->latest()->get();
        return view('picincident.spgr');
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
            'spgrfile7' => 'mimes:pdf|max:3072' ,
            'no_formclaim' => 'required'
        ]);
        
        if ($request->hasFile('spgrfile1')) {
            $file = $request->File('spgrfile1');
            $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
            $path = $request->file('spgrfile1')->storeas('spgr/'. $year . "/". $month , $name1, 's3');

            if (spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->update([
                    'status1' => 'on review',
                    'time_upload1' => date("Y-m-d"),
                    'spgr' => basename($path),]);
            }else{
                spgrfile::create([
                    'no_formclaim' => $request->no_formclaim,
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

            if (spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->update([
                    'status2' => 'on review',
                    'time_upload2' => date("Y-m-d"),
                    'Letter_of_Discharge' => basename($path),]);
                }else{
                spgrfile::create([
                    'no_formclaim' => $request->no_formclaim,
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
            if (spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->update([
                    'status3' => 'on review',
                    'time_upload3' => date("Y-m-d"),
                    'CMC' => basename($path),]);
                }else{
                spgrfile::create([
                    'no_formclaim' => $request->no_formclaim,
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
            if (spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->update([
                    'status4' => 'on review',
                    'time_upload4' => date("Y-m-d"),
                    'surat_laut' => basename($path),]);
                }else{
                spgrfile::create([
                    'no_formclaim' => $request->no_formclaim,
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
            if (spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->update([
                    'status5' => 'on review',
                    'time_upload5' => date("Y-m-d"),
                    'spb' => basename($path),]);
                }else{
                spgrfile::create([
                    'no_formclaim' => $request->no_formclaim,
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
            if (spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->update([
                    'status6' => 'on review',
                    'time_upload6' => date("Y-m-d"),
                    'load_line' => basename($path),]);
                }else{
                spgrfile::create([
                    'no_formclaim' => $request->no_formclaim,
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
            if (spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
                Storage::disk('s3')->delete($path."/".$name1);
                spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->update([
                    'status7' => 'on review',
                    'time_upload7' => date("Y-m-d"),
                    'surat_keterangan_bank' => basename($path),]);
                }else{
                spgrfile::create([
                    'no_formclaim' => $request->no_formclaim,
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,

                    'status7' => 'on review',
                    'time_upload7' => date("Y-m-d"),
                    'surat_keterangan_bank' => basename($path),
                ]);
            }
        }
        // if ($request->hasFile('spgrfile8')) {
        //     $file = $request->File('spgrfile8');
        //     $name1 = 'SPGR-'. Auth::user()->name . '-' . $file->getClientOriginalName();
        //     $path = $request->file('spgrfile8')->storeas('spgr/'. $year . "/". $month , $name1, 's3');
        //     if (spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->exists()){
        //         Storage::disk('s3')->delete($path."/".$name1);
        //         spgrfile::where('no_formclaim', 'Like', '%' . $request->no_formclaim . '%')->where('cabang', 'Jakarta')->update([
        //             'status8' => 'on review',
        //             'time_upload8' => date("Y-m-d"),
        //             'surat_keterangan_bank' => basename($path),]);
        //         }else{
        //         spgrfile::create([
        //             'no_formclaim' => $request->no_formclaim,
        //             'cabang' => Auth::user()->cabang ,
        //             'user_id' => Auth::user()->id,

        //             'status8' => 'on review',
        //             'time_upload8' => date("Y-m-d"),
        //             'surat_keterangan_bank' => basename($path),
        //         ]);
        //     }
        // }
        return redirect('picincident/spgr')->with('success', 'Upload success!');
    }

}
