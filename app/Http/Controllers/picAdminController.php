<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Storage;
use Response;
use validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Gmail;
use App\Models\documents;
use App\Models\documentberau;
use App\Models\documentbanjarmasin;
use App\Models\documentrpk;
use App\Models\documentsamarinda;
use App\Models\User;

class picAdminController extends Controller
{
    public function checkform(){
        if (request('search') == 'All') {
            $document = DB::table('documents')->latest()->get();
            $documentberau = DB::table('beraudb')->latest()->get();
            $documentbanjarmasin = DB::table('banjarmasindb')->latest()->get();
            $documentsamarinda = DB::table('samarindadb')->latest()->get();
        }
        elseif (request('search')) {
            $document = DB::table('documents')->where('cabang', request('search'))->latest()->get();
            $documentberau = DB::table('beraudb')->where('cabang', request('search'))->latest()->get();
            $documentbanjarmasin = DB::table('banjarmasindb')->where('cabang', request('search'))->latest()->get();
            $documentsamarinda = DB::table('samarindadb')->where('cabang', request('search'))->latest()->get();
        }
        else{
            $document = DB::table('documents')->latest()->get();
            $documentberau = DB::table('beraudb')->latest()->get();
            $documentbanjarmasin = DB::table('banjarmasindb')->latest()->get();
            $documentsamarinda = DB::table('samarindadb')->latest()->get();
        }

        return view('picadmin.picAdminDoc' , compact('document', 'documentberau' , 'documentbanjarmasin', 'documentsamarinda')); 
    }
    public function checkrpk(){
        if (request('search') == 'All') {
            $docrpk = DB::table('rpkdocuments')->latest()->get();
        }
        elseif (request('search')) {
            $docrpk = DB::table('rpkdocuments')->where('cabang', request('search'))->latest()->get();
        }
        else{
            $docrpk = DB::table('rpkdocuments')->latest()->get();
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
   

    public function download(){
        $path = Storage::path('assets/stenli-picsite-3' );
        return Response::download($path, 'stenli-picsite-3.txt');
    }
    
    public function view(Request $request){
        
        $year = date('Y');
        $month = date('m');
        
        if ($request->cabang == 'Babelan'){
            $filename = $request->viewdoc;
            $viewer = documents::whereMonth('updated_at', $month)->first()->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/" . $viewer);
        }
        // elseif ($request->cabang == 'Babelan' && ){
        //     $viewer = documents::whereMonth('updated_at', $month)->first()->pluck($filename)[0];
        //     // dd($viewer);
        //     return Storage::disk('s3')->response('babelan/' . $year . "/". $month . "/" . $viewer);
        // }

        if ($request->cabang == 'Berau'){
            $filename = $request->viewdoc;
            $viewer = documentberau::whereMonth('updated_at', $month)->first()->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('berau/' . $year . "/". $month . "/" . $viewer);
        }

        if ($request->cabang == 'Banjarmasin'){
            $filename = $request->viewdoc;
            $viewer = documentbanjarmasin::whereMonth('updated_at', $month)->first()->pluck($filename)[0];
            // dd($viewer);
            return Storage::disk('s3')->response('banjarmasin/' . $year . "/". $month . "/" . $viewer);
        }
        if ($request->cabang == 'Samarinda'){
            $filename = $request->viewdoc;
            $viewer = documentsamarinda::whereMonth('updated_at', $month)->first()->pluck($filename)[0];
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

        // $path = "storage/files";
        // $filename = "file_pdf.".$request->fileInput->getClientOriginalExtension();
        // $file = $request->file('fileInput');

        // $url = Storage::disk('s3')->url($path."/".$filename);
        // dd($url);

        // Storage::disk('s3')->delete($path."/".$filename);

        // $file->storeAs(
        //     $path,
        //     $filename,
        //     's3'
        // );
}
