<?php

namespace App\Http\Controllers;

use Storage;
use Response;
use validator;
use Carbon\Carbon;
use App\Mail\Gmail;
use App\Models\User;
use App\Models\documents;
use App\Models\Rekapdana;
use App\Models\documentrpk;
use Illuminate\Http\Request;
use App\Models\documentberau;
use App\Models\documentsamarinda;
use Illuminate\Support\Facades\DB;
use App\Models\documentbanjarmasin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class picAdminController extends Controller
{
    public function checkform(){
        if (request('search') == 'All') {
            $document = DB::table('documents')->whereMonth('created_at', date('m'))->latest()->get();
            $documentberau = DB::table('beraudb')->whereMonth('created_at', date('m'))->latest()->get();
            $documentbanjarmasin = DB::table('banjarmasindb')->whereMonth('created_at', date('m'))->latest()->get();
            $documentsamarinda = DB::table('samarindadb')->whereMonth('created_at', date('m'))->latest()->get();
        }
        elseif (request('search')) {
            $document = DB::table('documents')->where('cabang', request('search'))->latest()->get();
            $documentberau = DB::table('beraudb')->where('cabang', request('search'))->latest()->get();
            $documentbanjarmasin = DB::table('banjarmasindb')->where('cabang', request('search'))->latest()->get();
            $documentsamarinda = DB::table('samarindadb')->where('cabang', request('search'))->latest()->get();
        }
        else{{
            $document = DB::table('documents')->whereMonth('created_at', date('m'))->latest()->get();
            $documentberau = DB::table('beraudb')->whereMonth('created_at', date('m'))->latest()->get();
            $documentbanjarmasin = DB::table('banjarmasindb')->whereMonth('created_at', date('m'))->latest()->get();
            $documentsamarinda = DB::table('samarindadb')->whereMonth('created_at', date('m'))->latest()->get();
        }};

        return view('picadmin.picAdminDoc' , compact('document', 'documentberau' , 'documentbanjarmasin', 'documentsamarinda')); 
    }
    
    public function checkrpk(){
        if (request('search') == 'All') {
            $docrpk = DB::table('rpkdocuments')->whereMonth('created_at', date('m'))->latest()->get();
        }
        elseif (request('search')) {
            $docrpk = DB::table('rpkdocuments')->where('cabang', request('search'))->latest()->get();
        }
        else{
            $docrpk = DB::table('rpkdocuments')->whereMonth('created_at', date('m'))->latest()->get();
        }

        return view('picadmin.picAdminRpk' , compact('docrpk'));
    }

    public function reject(Request $request){
        //  dd($request);
        $request->validate([
            'reasonbox' => 'required|max:180',
        ]);
        if ($request->cabang == 'Babelan'){
            documents::where('cabang',$request->cabang)->update([
                $request->status => 'rejected',
                $request->reason => $request->reasonbox ,
            ]);
        }

        if ($request->cabang == 'Berau'){
            documentberau::where('cabang',$request->cabang)->update([
               $request->status => 'rejected',
               $request->reason => $request->reasonbox ,
           ]);
        }

        if ($request->cabang == 'Banjarmasin'){
            documentbanjarmasin::where('cabang',$request->cabang)->update([
                $request->status => 'rejected',
                $request->reason => $request->reasonbox ,
            ]);
        }

        if ($request->cabang == 'Samarinda'){
            documentsamarinda::where('cabang',$request->cabang)->update([
                $request->status => 'rejected',
                $request->reason => $request->reasonbox ,
            ]);
            // Storage::disk('s3')->delete('path/aaaaaaaa.webp');
        }
        
        return redirect('/picadmin/dana');
    }
    
    public function approve(Request $request){
        // dd($request);

        if ($request->cabang == 'Babelan'){
            documents::where('cabang', $request->cabang)->update([
                $request->status => 'approved'
            ]);
        }

        if ($request->cabang == 'Berau'){
            documentberau::where('cabang', $request->cabang)->update([
                $request->status => 'approved'
            ]);
        }

        if ($request->cabang == 'Banjarmasin'){
            documentbanjarmasin::where('cabang', $request->cabang)->update([
                $request->status => 'approved'
            ]);
        }

        if ($request->cabang == 'Samarinda'){
            documentsamarinda::where('cabang', $request->cabang)->update([
                $request->status => 'approved'
            ]);
        }
        
        return redirect('/picadmin/dana');
    }
    
    public function approverpk(Request $request){
        documentrpk::where('cabang', $request->cabang)->update([
            $request->status => 'approved'
        ]);
        return redirect('/picadmin/rpk');
    }

    public function rejectrpk(Request $request){
        // dd($request);
        $request->validate([
            'reasonbox' => 'required|max:255',
        ]);

        documentrpk::where('cabang',$request->cabang)->update([
            $request->status => 'rejected',
            $request->reason => $request->reasonbox ,
        ]);

        return redirect('/picadmin/rpk');
    }
    
    public function view(Request $request){
        
        $year = date('Y');
        $month = date('m');
        
        if ($request->cabang == 'Babelan'){
            $filename = $request->viewdoc;
            $viewer = documents::whereMonth('updated_at', $month)->latest()->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/" . $viewer);
        }
        // elseif ($request->cabang == 'Babelan' && ){
        //     $viewer = documents::whereMonth('updated_at', $month)->latest()->pluck($filename)[0];
        //     // dd($viewer);
        //     return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/" . $viewer);
        // }

        if ($request->cabang == 'Berau'){
            $filename = $request->viewdoc;
            $viewer = documentberau::whereMonth('updated_at', $month)->latest()->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('berau/' . $year . "/". $month . "/" . $viewer);
        }

        if ($request->cabang == 'Banjarmasin'){
            $filename = $request->viewdoc;
            $viewer = documentbanjarmasin::whereMonth('updated_at', $month)->latest()->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('banjarmasin/' . $year . "/". $month . "/" . $viewer);
        }
        if ($request->cabang == 'Samarinda'){
            $filename = $request->viewdoc;
            $viewer = documentsamarinda::whereMonth('updated_at', $month)->latest()->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('samarinda/' . $year . "/". $month . "/" . $viewer);
        }
    }

    public function viewrpk(Request $request){ 
        $year = date('Y');
        $month = date('m');
        $filename = $request->viewdoc;
        
        if ($request->cabang == 'Babelan'){
            $viewer = documentrpk::where('cabang' , $request->cabang)->whereMonth('updated_at', $month)->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/RPK" . "/" . $viewer);
        }
        if ($request->cabang == 'Berau'){
            $viewer = documentrpk::where('cabang' , $request->cabang)->whereMonth('updated_at', $month)->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('berau/' . $year . "/". $month . "/RPK" . "/" . $viewer);
        }
        if ($request->cabang == 'Banjarmasin'){
            $viewer = documentrpk::where('cabang' , $request->cabang)->whereMonth('updated_at', $month)->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('banjarmasin/' . $year . "/". $month . "/RPK" . "/" . $viewer);
        }
        if ($request->cabang == 'Samarinda'){
            $viewer = documentrpk::where('cabang' , $request->cabang)->whereMonth('updated_at', $month)->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('samarinda/' . $year . "/". $month . "/RPK" . "/" . $viewer);
        }
    }

    // RekapulasiDana delete
    public function destroyrekap(Rekapdana $rekap){
        Rekapdana::destroy($rekap->id); 
        return redirect('/picadmin/RekapulasiDana')->with('success', 'post telah dihapus.'); 
    }
    //update RekapulasiDana
    public function updaterekap(Request $request,Rekapdana $rekap){
        $update = Rekapdana::findorfail($rekap->id);
        $update->DateNote = $request->Datebox;
        $update->Cabang = $request->Cabang;
        $update->No_FormClaim = $request->No_FormClaim;
        $update->Nama_Kapal = $request->NamaKapal;
        $update->status_pembayaran = $request->status_pembayaran;
        $update->Nilai = $request->Nilai;
        $update->mata_uang_nilai = $request->mata_uang_nilai;
        $update->Nilai_Claim = $request->NilaiClaim;
        $update->mata_uang_claim = $request->mata_uang_claim;
        $update->update();
        return redirect('/picadmin/RekapulasiDana')->with('success', 'post telah terupdate.'); 
    }
    //create RekapulasiDana
    public function uploadrekap(Request $request){
        // dd($request);
        $request->validate([
            'Cabang'=> 'required|max:255',
            'NamaKapal'=> 'required|max:255',
            'NilaiClaim'=> 'required',
            // 'DateNote'=> 'required',
        ]);

        Rekapdana::create([
            'user_id'=> Auth::user()->id,
            'DateNote' => $request->Datebox ,
            'Cabang' => $request->Cabang ,
            'No_FormClaim' => $request->No_FormClaim ,
            'Nama_Kapal' => $request->NamaKapal ,
            'status_pembayaran' => $request->status_pembayaran ,
            'Nilai' => $request->Nilai ,
            'mata_uang_nilai' => $request->mata_uang_nilai ,
            'Nilai_Claim' => $request->NilaiClaim ,
            'mata_uang_claim' => $request->mata_uang_claim ,
            
        ]);
        return redirect('/picadmin/RekapulasiDana')->with('success', 'Note telah ditambahkan.');
    }

    public function RekapulasiDana(){
        $rekapdana= Rekapdana::all();
        return view('picadmin.picAdminRekapulasiDana', compact('rekapdana'));
    }
}
