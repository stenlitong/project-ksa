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
        $document = DB::table('documents')->latest()->get();
        $documentberau = DB::table('documents')->latest()->get();
        $documentbanjarmasin = DB::table('documents')->latest()->get();
        $documentsamarinda = DB::table('documents')->latest()->get();
        return view('picadmin.picAdminDoc' , compact('document')); 
    }
    public function checkrpk(){
        $docrpk = DB::table('rpkdocuments')->latest()->get();
        return view('picadmin.picAdminRpk' , compact('docrpk'));
    }

    public function rejectFile(Request $request){
        // dd($request->reason);
        if($doc->cabang == 'Babelan'){       
            $request->validate([
                'reason2' => 'required' ,
                'reason3' => 'required' ,
                'reason4' => 'required' ,
                'reason5' => 'required' , 
                'reason6' => 'required' ,
                'reason7' => 'required' ,
                'reason8' => 'required' ,
                'reason9' => 'required' ,
                'reason10' => 'required' ,
                'reason11' => 'required' ,
                'reason12' => 'required' ,
                'reason13' => 'required' ,
                'reason14' => 'required' ,
                'reason15' => 'required' ,
                'reason16' => 'required' ,
            ]);
            
            if ($doc->status1 != null) {
                // does exist
                $doc = Document::where($doc->status1)->first();
                $request->validate([
                    'reason1' => 'required' ,
                ]);
                documents::where('cabang', "Babelan")->where('sertifikat_keselamatan')->update([
                    'status1' => 'Rejected' ,
                    'reason1' => $request->reason ,
                 ]);
            }

            documents::where('cabang', "Babelan")->where('sertifikat_keselamatan')->update([

                'status2' => 'Rejected' ,
                'reason2' => $request->reason ,

                'status3' => 'Rejected' ,
                'reason3' => $request->reason ,

                'status4' => 'Rejected' ,
                'reason4' => $request->reason ,

                'status5' => 'Rejected' , 
                'reason5' => $request->reason ,

                'status6' => 'Rejected' ,
                'reason6' => $request->reason ,

                'status7' => 'Rejected' ,
                'reason7' => $request->reason ,

                'status8' => 'Rejected' ,
                'reason8' => $request->reason ,

                'status9' => 'Rejected' ,
                'reason9' => $request->reason ,

                'status10' => 'Rejected' ,
                'reason10' => $request->reason ,

                'status11' => 'Rejected' ,
                'reason11' => $request->reason ,

                'status12' => 'Rejected' ,
                'reason12' => $request->reason ,

                'status13' => 'Rejected' ,
                'reason13' => $request->reason ,

                'status14' => 'Rejected' ,
                'reason14' => $request->reason ,

                'status15' => 'Rejected' ,
                'reason15' => $request->reason ,

                'status16' => 'Rejected' ,
                'reason16' => $request->reason ,
            ]);
        }
        return redirect('/picadmin/dana');
    }

    public function approvefile(documents $doc){
        return view('logistic.logisticApproveOrder', compact('doc'));
    }

    public function download(){
        $path = Storage::path('assets/stenli-picsite-3' );
        return Response::download($path, 'stenli-picsite-3.txt');
    }
    
    public function view(){
        $content = Storage::disk('s3')->get($path);

        $header = [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="'.$file->name.'"'
        ];
        return Response::make($content, 200, $header);

    }
}