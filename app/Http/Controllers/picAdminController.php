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
            $request->reasonbox => 'required|max:180',
        ]);

            documents::where('cabang',$request->cabang)->update([
                $request->status => 'rejected',
                $request->reason => $request->reasonbox ,
            ]);
            documentberau::where('cabang',$request->cabang)->update([
                $request->status => 'rejected',
                $request->reason => $request->reasonbox ,
            ]);
            documentbanjarmasin::where('cabang',$request->cabang)->update([
                $request->status => 'rejected',
                $request->reason => $request->reasonbox ,
            ]);
            documentsamarinda::where('cabang',$request->cabang)->update([
                $request->status => 'rejected',
                $request->reason => $request->reasonbox ,
            ]);
            documentrpk::where('cabang',$request->cabang)->update([
                $request->status => 'rejected',
                $request->reason => $request->reasonbox ,
            ]);
    
    
        return redirect('/picadmin/dana');
    }

    public function approve(Request $request){
        // dd($request);
        documents::where('cabang', $request->cabang)->update([
            $request->status => 'approved'
        ]);
        documentberau::where('cabang', $request->cabang)->update([
            $request->status => 'approved'
        ]);
        documentbanjarmasin::where('cabang', $request->cabang)->update([
            $request->status => 'approved'
        ]);
        documentsamarinda::where('cabang', $request->cabang)->update([
            $request->status => 'approved'
        ]);
        documentrpk::where('cabang', $request->cabang)->update([
            $request->status => 'approved'
        ]);
        
        return redirect('/picadmin/dana');
    }

    public function download(){
        $path = Storage::path('assets/stenli-picsite-3' );
        return Response::download($path, 'stenli-picsite-3.txt');
    }
    
    public function view(){
        // $content = Storage::disk('s3')->get($path);
        // $header = [
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' => 'inline; filename="'.$file->name.'"'
        // ];
        // return Response::make($content, 200, $header);
        $path = "storage/files";
        $filename = "file_pdf.".$request->fileInput->getClientOriginalExtension();
        $file = $request->file('fileInput');

        $url = Storage::disk('s3')->url($path."/".$filename);
        //dd($url);

        Storage::disk('s3')->delete($path."/".$filename);

        $file->storeAs(
            $path,
            $filename,
            's3'
        );
    }
}
