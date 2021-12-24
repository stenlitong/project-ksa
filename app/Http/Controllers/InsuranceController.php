<?php

namespace App\Http\Controllers;

use Storage;
use Response;
use validator;
use Carbon\Carbon;
use App\Mail\Gmail;
use App\Models\User;
use App\Models\NoteSpgr;
use App\Models\spgrfile;
use App\Models\tempcart;
use App\Models\Rekapdana;
use App\Exports\FCIexport;
use App\Models\formclaims;
use Illuminate\Http\Request;
use App\Models\headerformclaim;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class InsuranceController extends Controller
{
    //check SPGR UPLOAD,approved,rejected,view
    public function checkspgr(){
        $uploadspgr = spgrfile::where('cabang', 'Jakarta')->whereMonth('created_at', date('m'))->latest()->get();
        return view('insurance.insuranceSpgr', compact('uploadspgr'));
    }

    public function approvespgr(Request $request){
        spgrfile::where('cabang', $request->cabang)->whereMonth('created_at', date('m'))->update([
            $request->status => 'approved'
        ]);
        return redirect('/insurance/CheckSpgr');
    }

    public function rejectspgr(Request $request){
        // dd($request);
        $request->validate([
            'reasonbox' => 'required|max:255',
        ]);

        spgrfile::where('cabang',$request->cabang)->whereMonth('created_at', date('m'))->update([
            $request->status => 'rejected',
            $request->reason => $request->reasonbox ,
        ]);

        return redirect('/insurance/CheckSpgr');
    }

    public function viewspgr(Request $request){
        $year = date('Y');
        $month = date('m');
        $cabang = $request->cabang;
        $filename = $request->viewspgrfile;
        $viewer = spgrfile::where('cabang', 'Jakarta')->whereMonth('updated_at', $month)->latest()->pluck($filename)[0];
        // dd($request);
        // dd($viewer);
        return Storage::disk('s3')->response('spgr/' . $year . "/". $month . "/" . $viewer);
    }
    
    //history note SPGR page
    public function historynotespgr(){
        $UploadNotes = DB::table('note_spgrs')->latest()->get();
        return view('insurance.insuranceHistoryNotes', compact('UploadNotes'));
    }
    //update history note SPGR page
    // public function Updatehistorynotespgr(Request $request, NoteSpgr $UpNotes){
    //     $update = NoteSpgr::find($UpNotes->id);
    //     $update->DateNote = $request->Datebox;
    //     $update->No_SPGR = $request->No_SPGR;
    //     $update->No_FormClaim = $request->No_FormClaim;
    //     $update->Nama_Kapal = $request->NamaKapal;
    //     $update->status_pembayaran = $request->status_pembayaran;
    //     $update->mata_uang_nilai = $request->mata_uang_nilai;
    //     $update->Nilai = $request->Nilai;
    //     $update->mata_uang_claim = $request->mata_uang_claim;
    //     $update->Nilai_Claim = $request->NilaiClaim;
    //     $update->update();
    //     return redirect('/insurance/HistoryNoteSpgr')->with('success', 'post telah terupdate.'); 
    // }
    // //delete history note SPGR page
    // public function Deletehistorynotespgr(NoteSpgr $UpNotes){
    //     NoteSpgr::destroy($UpNotes->id);
    //     return redirect('/insurance/HistoryNoteSpgr')->with('success', 'post telah dihapus.'); 
    // }

    //History form claim page
    public function historyFormclaim(){
        $Headclaim = headerformclaim::all();
        return view('insurance.insuranceHistoryFormclaim', compact('Headclaim'));
    }
   
    //History form claim delete load func
    public function historyFormclaimDelete(headerformclaim $claims){
        headerformclaim::destroy($claims->id); 
        return redirect('/insurance/historyFormclaim')->with('success', 'File telah dihapus.'); 
    }

    //History form claim download func
    private $excel;
    public function __construct(Excel $excel){
        $this->excel = $excel;
    }
    public function historyFormclaimDownload(Request $request) {
        // dd($request);
        $identify = $request->file_id;
        return $this->excel::download(new FCIexport($identify), 'FCI.xlsx');
    }

    // RekapulasiDana delete
    public function DestroyHistoryRekap(Rekapdana $rekap){
        Rekapdana::destroy($rekap->id); 
        return redirect('/insurance/HistoryRekapulasiDana')->with('success', 'post telah dihapus.'); 
    }
    //update RekapulasiDana
    public function UpdateHistoryRekap(Request $request, Rekapdana $rekap){
        $update = Rekapdana::find($rekap->id);
        $update->DateNote = $request->Datebox;
        $update->Cabang = $request->Cabang;
        $update->No_FormClaim = $request->No_FormClaim;
        $update->Nama_Kapal = $request->NamaKapal;
        $update->status_pembayaran = $request->status_pembayaran;
        $update->Nilai = $request->Nilai;
        $update->Nilai_Claim = $request->NilaiClaim;
        $update->update();
        return redirect('/insurance/HistoryRekapulasiDana')->with('success', 'post telah terupdate.'); 
    }
    //History Rekapulsi Dana page
    public function historyRekapulasiDana(){
        $rekapdana= Rekapdana::all();
        return view('insurance.insuranceRekapulasiDana', compact('rekapdana'));
    }
}
