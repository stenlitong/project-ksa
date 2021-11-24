<?php

namespace App\Http\Controllers;

use Google\Cloud\Storage\StorageClient;
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
use App\Models\documentsamarinda;
use App\Models\User;

class PicsiteController extends Controller
{
    public function uploadform(){
        $document = documents::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        $documentberau = documentberau::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        $documentbanjarmasin = documentbanjarmasin::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        $documentsamarinda = documentsamarinda::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        //dd($document);
        
        return view('picsite.upload',compact('document' , 'documentberau','documentbanjarmasin','documentsamarinda'));
    }
    
    public function uploadfile(Request $request){

        $document = documents::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        $documentberau = documentberau::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        $documentbanjarmasin = documentbanjarmasin::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        $documentsamarinda = documentsamarinda::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        //dd($document->created_at);
        if (Auth::user()->cabang == 'Babelan') {
            $request->validate([
                'ufile1' => 'mimes:pdf|max:1024' ,
                'ufile2' => 'mimes:pdf|max:1024' ,
                'ufile3' => 'mimes:pdf|max:1024' ,
                'ufile4' => 'mimes:pdf|max:1024' ,
                'ufile5' => 'mimes:pdf|max:1024' , 
                'ufile6' => 'mimes:pdf|max:1024' ,
                'ufile7' => 'mimes:pdf|max:1024' ,
                'ufile8' => 'mimes:pdf|max:1024' ,
                'ufile9' => 'mimes:pdf|max:1024' ,
                'ufile10' => 'mimes:pdf|max:1024' ,
                'ufile11' => 'mimes:pdf|max:1024' ,
                'ufile12' => 'mimes:pdf|max:1024' ,
                'ufile13' => 'mimes:pdf|max:1024' ,
                'ufile14' => 'mimes:pdf|max:1024' ,
                'ufile15' => 'mimes:pdf|max:1024' ,
                'ufile16' => 'mimes:pdf|max:1024' ,
            ]);

            if ($request->hasFile('ufile1')) {
                $file1 = $request->file('ufile1');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/sertifikat_keselamatan';
                $request->file('ufile1')->storeAs('babelan/sertifikat_keselamatan', $name1).'.pdf';   
                $pathbabelan1 = Storage::path('babelan/sertifikat_keselamatan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                
                if (documents::where('cabang', 'Babelan')->exists()){
                    documents::where('cabang', 'Babelan')->update([
                        //babelan
                        'status1' => 'on review',
                        'time_upload1' => date("Y-m-d"),
                        'sertifikat_keselamatan' => $pathbabelan1,]);
                }else{
                    documents::create([
                        //babelan
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        'status1' => 'on review',
                        'time_upload1' => date("Y-m-d"),
    
                        'sertifikat_keselamatan' => $pathbabelan1,]);
                }
            }

            if ($request->hasFile('ufile2')) {
                $file2 = $request->file('ufile2');
                $name2 =  $file2->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/sertifikat_garis_muat';
                $request->file('ufile2')->storeAs($tujuan_upload, $name2.'.pdf');
                $pathbabelan2 = Storage::path('babelan/sertifikat_garis_muat/'.$file2->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                
                if (documents::where('cabang', 'Babelan')->exists()){
                    documents::where('cabang', 'Babelan' )->update([                                        
                        'sertifikat_garis_muat' => $pathbabelan2,
                        'status2' => 'on review',
                        'time_upload2' => date("Y-m-d"),
                    ]);
                }else{
                    documents::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'sertifikat_garis_muat' => $pathbabelan2,
                        'status2' => 'on review',
                        'time_upload2' => date("Y-m-d"),
                    ]);
                }
            }

            if ($request->hasFile('ufile3')) {
                $file3 = $request->file('ufile3');
                $name3 =  $file3->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/penerbitan_sekali_jalan';
                $request->file('ufile3')->storeAs($tujuan_upload, $name3.'.pdf');
                $pathbabelan3 = Storage::path('babelan/penerbitan_sekali_jalan/'.$file3->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);

                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan') ->update([
                        //babelan                       
                            'penerbitan_sekali_jalan' => $pathbabelan3,
                            'status3' => 'on review',
                            'time_upload3' => date("Y-m-d"),
                    ]);
                }else {
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'penerbitan_sekali_jalan' => $pathbabelan3,
                        'status3' => 'on review',
                        'time_upload3' => date("Y-m-d"),
                    ]); 
                }
            }
            
            if ($request->hasFile('ufile4')) {
                $file4 = $request->file('ufile4');
                $name4 =  $file4->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/sertifikat_safe_manning';
                $request->file('ufile4')->storeAs($tujuan_upload, $name4.'.pdf');
               $pathbabelan4 = Storage::path('babelan/sertifikat_safe_manning/'.$file4->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);

               if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                    //babelan
                    'sertifikat_safe_manning'=> $pathbabelan4,
                    'status4' => 'on review',
                    'time_upload4' => date("Y-m-d"),
                ]);
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        
                        'sertifikat_safe_manning'=> $pathbabelan4,
                        'status4' => 'on review',
                        'time_upload4' => date("Y-m-d"),
                    ]);
                }
            }
            
            if ($request->hasFile('ufile5')) {
                $file5 = $request->file('ufile5');
                $name5 =  $file5->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/endorse_surat_laut';
                //$file5->move($tujuan_upload,$name5);
                $request->file('ufile5')->storeAs($tujuan_upload, $name5.'.pdf');
                $pathbabelan5 = Storage::path('babelan/endorse_surat_laut/'.$file5->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        'endorse_surat_laut'=> $pathbabelan5,
                        'status5' => 'on review',
                        'time_upload5' => date("Y-m-d"),
                    ]);   
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'endorse_surat_laut'=> $pathbabelan5,
                        'status5' => 'on review',
                        'time_upload5' => date("Y-m-d"),
                    ]);
                }
            }
            
            if ($request->hasFile('ufile6')) {
                $file6 = $request->file('ufile6');
                $name6 =  $file6->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/perpanjangan_sertifikat_sscec';
                //$file6->move($tujuan_upload,$name6);
                $request->file('ufile6')->storeAs($tujuan_upload, $name6.'.pdf');
                $pathbabelan6 = Storage::path('babelan/perpanjangan_sertifikat_sscec/'.$file6->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        'perpanjangan_sertifikat_sscec'=> $pathbabelan6,
                        'status6' => 'on review',
                        'time_upload6' => date("Y-m-d"),
                    ]);
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'perpanjangan_sertifikat_sscec'=> $pathbabelan6,
                        'status6' => 'on review',
                        'time_upload6' => date("Y-m-d"),
                    ]);
                }
            }
            
            if ($request->hasFile('ufile7')) {
                $file7 = $request->file('ufile7');
                $name7 =  $file7->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/perpanjangan_sertifikat_p3k';
                $request->file('ufile7')->storeAs($tujuan_upload, $name7.'.pdf');
                $pathbabelan7 = Storage::path('babelan/perpanjangan_sertifikat_p3k/'.$file7->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'perpanjangan_sertifikat_p3k'=> $pathbabelan7,
                        'status7' => 'on review',
                        'time_upload7' => date("Y-m-d"),
                    ]);
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        //babelan
                        'perpanjangan_sertifikat_p3k'=> $pathbabelan7,
                        'status7' => 'on review',
                        'time_upload7' => date("Y-m-d"),
                    ]);
                }
                
            }
            
            if ($request->hasFile('ufile8')) {
                $file8 = $request->file('ufile8');
                $name8 =  $file8->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/biaya_laporan_dok';
                $request->file('ufile8')->storeAs($tujuan_upload, $name8.'.pdf');
                $pathbabelan8 = Storage::path('babelan/biaya_laporan_dok/'.$file8->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'biaya_laporan_dok'=> $pathbabelan8,
                        'status8' => 'on review',
                        'time_upload8' => date("Y-m-d"),
                    ]);     
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        //babelan
                        'biaya_laporan_dok'=> $pathbabelan8,
                        'status8' => 'on review',
                        'time_upload8' => date("Y-m-d"),
                    ]); 
                }  
            }
            
            if ($request->hasFile('ufile9')) {
                $file9 = $request->file('ufile9');
                $name9 =  $file9->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/pnpb_sertifikat_keselamatan';
                $request->file('ufile9')->storeAs($tujuan_upload, $name9.'.pdf');
                $pathbabelan9 = Storage::path('babelan/pnpb_sertifikat_keselamatan/'.$file9->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'pnpb_sertifikat_keselamatan'=> $pathbabelan9,
                        'status9' => 'on review',
                        'time_upload9' => date("Y-m-d"),
                    ]);                   
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        //babelan
                        'pnpb_sertifikat_keselamatan'=> $pathbabelan9,
                        'status9' => 'on review',
                        'time_upload9' => date("Y-m-d"),
                    ]);
                }
            }
            
            if ($request->hasFile('ufile10')) {
                $file10 = $request->file('ufile10');
                $name10 =  $file10->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/pnpb_sertifikat_garis_muat';
                // $file10->move($tujuan_upload,$name10);
                $request->file('ufile10')->storeAs($tujuan_upload, $name10.'.pdf');
                $pathbabelan10 = Storage::path('babelan/pnpb_sertifikat_garis_muat/'.$file10->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'pnpb_sertifikat_garis_muat'=> $pathbabelan10,
                        'status10' => 'on review',
                        'time_upload10' => date("Y-m-d"),
                    ]);          
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        //babelan
                        'pnpb_sertifikat_garis_muat'=> $pathbabelan10,
                        'status10' => 'on review',
                        'time_upload10' => date("Y-m-d"),
                    ]);
                }
            }
            
            if ($request->hasFile('ufile11')) {
                $file11 = $request->file('ufile11');
                $name11 =  $file11->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/pnpb_surat_laut';
                // $file11->move($tujuan_upload,$name11);
                $request->file('ufile11')->storeAs($tujuan_upload, $name11.'.pdf');
                $pathbabelan11 = Storage::path('babelan/pnpb_surat_laut/'.$file11->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'pnpb_surat_laut'=> $pathbabelan11,
                        'status11' => 'on review',
                        'time_upload11' => date("Y-m-d"),
                    ]);
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'pnpb_surat_laut'=> $pathbabelan11,
                        'status11' => 'on review',
                        'time_upload11' => date("Y-m-d"),
                    ]);
                }
            }
            
            if ($request->hasFile('ufile12')) {
                $file12 = $request->file('ufile12');
                $name12 =  $file12->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/sertifikat_snpp';
                // $file12->move($tujuan_upload,$name12);
                $request->file('ufile12')->storeAs($tujuan_upload, $name12.'.pdf');
                $pathbabelan12 = Storage::path('babelan/sertifikat_snpp/'.$file12->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'sertifikat_snpp'=> $pathbabelan12,
                        'status12' => 'on review',
                        'time_upload12' => date("Y-m-d"),
                    ]);
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'sertifikat_snpp'=> $pathbabelan12,
                        'status12' => 'on review',
                        'time_upload12' => date("Y-m-d"),
                    ]);
                }
            }
            
            if ($request->hasFile('ufile13')) {
                $file13 = $request->file('ufile13');
                $name13 =  $file13->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/sertifikat_anti_teritip';
                // $file13->move($tujuan_upload,$name13);
                $request->file('ufile13')->storeAs($tujuan_upload, $name13.'.pdf');
                $pathbabelan13 = Storage::path('babelan/sertifikat_anti_teritip/'.$file13->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'sertifikat_anti_teritip'=> $pathbabelan13,
                        'status13' => 'on review',
                        'time_upload13' => date("Y-m-d"),
                    ]);
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        //babelan
                        'sertifikat_anti_teritip'=> $pathbabelan13,
                        'status13' => 'on review',
                        'time_upload13' => date("Y-m-d"),
                    ]);
                }
                
            }
            
            if ($request->hasFile('ufile14')) {
                $file14 = $request->file('ufile14');
                $name14 =  $file14->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/pnbp_snpp&snat';
                // $file14->move($tujuan_upload,$name14);
                $request->file('ufile14')->storeAs($tujuan_upload, $name14.'.pdf');
                $pathbabelan14 = Storage::path('babelan/pnbp_snpp&snat/'.$file14->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);

                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'pnbp_snpp&snat'=> $pathbabelan14,
                        'status14' => 'on review',
                        'time_upload14' => date("Y-m-d"),
                    ]);
                }else {
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        //babelan
                        'pnbp_snpp&snat'=> $pathbabelan14,
                        'status14' => 'on review',
                        'time_upload14' => date("Y-m-d"),
                    ]);
                }
            }  
            if ($request->hasFile('ufile15')) {
                $file15 = $request->file('ufile15');
                $name15 =  $file15->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/biaya_survey';
                $request->file('ufile15')->storeAs($tujuan_upload, $name15.'.pdf');
                $pathbabelan15 = Storage::path('babelan/biaya_survey/'.$file15->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);
                
                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'biaya_survey'=> $pathbabelan15,
                        'status15' => 'on review',
                        'time_upload15' => date("Y-m-d"),
                    ]);
                }else{
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        //babelan
                        'biaya_survey'=> $pathbabelan15,
                        'status15' => 'on review',
                        'time_upload15' => date("Y-m-d"),
                    ]);                    
                }
            }
            
            if ($request->hasFile('ufile16')) {
                $file16 = $request->file('ufile16');
                $name16 =  $file16->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'babelan/pnpb_sscec';
                $request->file('ufile16')->storeAs($tujuan_upload, $name16.'.pdf');
                $pathbabelan16 = Storage::path('babelan/pnpb_sscec/'.$file16->getClientOriginalName() . '-picsite-' . Auth::user()->cabang);

                if (documents::where('cabang', 'Babelan')->exists()){
                    DB::table('documents')->where('cabang', 'Babelan')->update([
                        //babelan
                        'pnpb_sscec'=> $pathbabelan16,
                        'status16' => 'on review',
                        'time_upload16' => date("Y-m-d"),
                    ]);
                }else {
                    DB::table('documents')->insert([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'pnpb_sscec'=> $pathbabelan16,
                        'status16' => 'on review',
                        'time_upload16' => date("Y-m-d"),
                    ]);
                }
            }
            
            return redirect('/picsite/upload')->with('message', 'Upload Success!');
        }

        if (Auth::user()->cabang == 'Berau') {
            // dd($request);
            $request->validate([
                'beraufile1'=> 'mimes:pdf|max:1024' ,
                'beraufile2'=> 'mimes:pdf|max:1024' ,
                'beraufile3'=> 'mimes:pdf|max:1024' ,
                'beraufile4'=> 'mimes:pdf|max:1024' ,
                'beraufile5'=> 'mimes:pdf|max:1024' ,
                'beraufile6'=> 'mimes:pdf|max:1024' ,
                'beraufile7'=> 'mimes:pdf|max:1024' ,
                'beraufile8'=> 'mimes:pdf|max:1024' ,
                'beraufile9'=> 'mimes:pdf|max:1024' ,
                'beraufile10'=> 'mimes:pdf|max:1024' ,
                'beraufile11'=> 'mimes:pdf|max:1024' ,
                'beraufile12'=> 'mimes:pdf|max:1024' ,
                'beraufile13'=> 'mimes:pdf|max:1024' ,
                'beraufile14'=> 'mimes:pdf|max:1024' ,
                'beraufile15'=> 'mimes:pdf|max:1024' ,
                'beraufile16'=> 'mimes:pdf|max:1024' ,
                'beraufile17'=> 'mimes:pdf|max:1024' ,
                'beraufile18'=> 'mimes:pdf|max:1024' ,
                'beraufile19'=> 'mimes:pdf|max:1024' ,
                'beraufile20'=> 'mimes:pdf|max:1024' , 
                'beraufile21'=> 'mimes:pdf|max:1024' ,
                'beraufile22'=> 'mimes:pdf|max:1024' ,
                'beraufile23'=> 'mimes:pdf|max:1024' ,
                'beraufile24'=> 'mimes:pdf|max:1024' , 
                'beraufile25'=> 'mimes:pdf|max:1024' ,
                'beraufile26'=> 'mimes:pdf|max:1024' ,
            ]);
            if ($request->hasFile('beraufile1')) {
                $file1 = $request->file('beraufile1');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_sertifikat_konstruksi';
                $request->file('beraufile1')->storeAs('berau/pnbp_sertifikat_konstruksi', $name1).'.pdf';   
                $pathberau1 = Storage::path('berau/pnbp_sertifikat_konstruksi/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([
                    'cabang' => Auth::user()->cabang ,
                    'status1' => 'on review',
                    'due_time' => "28-" . date("m-Y") ,]);

                }else{
                    documentberau::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'due_time' => "28-" . date("m-Y") ,
                    
                    'status1' => 'on review',
                    'time_upload1' => date("Y-m-d"),
                    'pnbp_sertifikat_konstruksi' => $pathberau1,]);
                }
            }
            if ($request->hasFile('beraufile2')) {
                $file1 = $request->file('beraufile2');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/jasa_urus_sertifikat';
                $request->file('beraufile2')->storeAs('berau/jasa_urus_sertifikat', $name1).'.pdf';
                $pathberau2 = Storage::path('berau/jasa_urus_sertifikat/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'jasa_urus_sertifikat' => $pathberau2,
                        'status2' => 'on review',
                        'time_upload2' => date("Y-m-d"),
                    ]);
                }else{
                    documentberau::create([       
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'jasa_urus_sertifikat' => $pathberau2,
                        'status2' => 'on review',
                        'time_upload2' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile3')) {
                $file1 = $request->file('beraufile3');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_sertifikat_perlengkapan';
                $request->file('beraufile3')->storeAs('berau/pnbp_sertifikat_perlengkapan', $name1).'.pdf';
                $pathberau3 = Storage::path('berau/pnbp_sertifikat_perlengkapan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnbp_sertifikat_perlengkapan' => $pathberau3,
                        'status3' => 'on review',
                        'time_upload3' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'pnbp_sertifikat_perlengkapan' => $pathberau3,
                        'status3' => 'on review',
                        'time_upload3' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile4')) {
                $file1 = $request->file('beraufile4');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_sertifikat_radio';
                $request->file('beraufile4')->storeAs('berau/pnbp_sertifikat_radio', $name1).'.pdf';
                $pathberau4 = Storage::path('berau/pnbp_sertifikat_radio/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnbp_sertifikat_radio' => $pathberau4,
                        'status4' => 'on review',
                        'time_upload4' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'pnbp_sertifikat_radio' => $pathberau4,
                        'status4' => 'on review',
                        'time_upload4' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile5')) {
                $file1 = $request->file('beraufile5');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_sertifikat_ows';
                $request->file('beraufile5')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau5 = Storage::path('berau/pnbp_sertifikat_ows/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnbp_sertifikat_ows' => $pathberau5,
                        'status5' => 'on review',
                        'time_upload5' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'pnbp_sertifikat_ows' => $pathberau5,
                        'status5' => 'on review',
                        'time_upload5' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile6')) {
                $file1 = $request->file('beraufile6');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_garis_muat';
                $request->file('beraufile6')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau6 = Storage::path('berau/pnbp_garis_muat/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnbp_garis_muat' => $pathberau6,
                        'status6' => 'on review',
                        'time_upload6' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'pnbp_garis_muat' => $pathberau6,
                        'status6' => 'on review',
                        'time_upload6' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile7')) {
                $file1 = $request->file('beraufile7');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_pemeriksaan_endorse_sl';
                $request->file('beraufile7')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau7 = Storage::path('berau/pnbp_pemeriksaan_endorse_sl/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnbp_pemeriksaan_endorse_sl' => $pathberau7,
                        'status7' => 'on review',
                        'time_upload7' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'pnbp_pemeriksaan_endorse_sl' => $pathberau7,
                        'status7' => 'on review',
                        'time_upload7' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile8')) {
                $file1 = $request->file('beraufile8');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pemeriksaan_sertifikat';
                $request->file('beraufile8')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau8 = Storage::path('berau/pemeriksaan_sertifikat/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pemeriksaan_sertifikat' => $pathberau8,
                        'status8' => 'on review',
                        'time_upload8' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'pemeriksaan_sertifikat' => $pathberau8,
                        'status8' => 'on review',
                        'time_upload8' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile9')) {
                $file1 = $request->file('beraufile9');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/marine_inspektor';
                $request->file('beraufile9')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau9 = Storage::path('berau/marine_inspektor/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'marine_inspektor' => $pathberau9,
                        'status9' => 'on review',
                        'time_upload9' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'marine_inspektor' => $pathberau9,
                        'status9' => 'on review',
                        'time_upload9' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile10')) {
                $file1 = $request->file('beraufile10');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/biaya_clearance';
                $request->file('beraufile10')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau10 = Storage::path('berau/biaya_clearance/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'biaya_clearance' => $pathberau10,
                        'status10' => 'on review',
                        'time_upload10' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'biaya_clearance' => $pathberau10,
                        'status10' => 'on review',
                        'time_upload10' => date("Y-m-d"),
                    ]);
                }
            }          
            if ($request->hasFile('beraufile11')) {
                $file1 = $request->file('beraufile11');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_master_cable';
                $request->file('beraufile11')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau5 = Storage::path('berau/pnbp_master_cable/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnbp_master_cable' => $pathberau11,
                        'status11' => 'on review',
                        'time_upload11' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'pnbp_master_cable' => $pathberau11,
                        'status11' => 'on review',
                        'time_upload11' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile12')) {
                $file1 = $request->file('beraufile12');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/cover_deck_logbook';
                $request->file('beraufile12')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau6 = Storage::path('berau/cover_deck_logbook/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'cover_deck_logbook' => $pathberau12,
                        'status12' => 'on review',
                        'time_upload12' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'cover_deck_logbook' => $pathberau12,
                        'status12' => 'on review',
                        'time_upload12' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile13')) {
                $file1 = $request->file('beraufile13');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/cover_engine_logbook';
                $request->file('beraufile13')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau7 = Storage::path('berau/cover_engine_logbook/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'cover_engine_logbook' => $pathberau13,
                        'status13' => 'on review',
                        'time_upload13' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'cover_engine_logbook' => $pathberau13,
                        'status13' => 'on review',
                        'time_upload13' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile14')) {
                $file1 = $request->file('beraufile14');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/exibitum_dect_logbook';
                $request->file('beraufile14')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau8 = Storage::path('berau/exibitum_dect_logbook/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'exibitum_dect_logbook' => $pathberau14,
                        'status14' => 'on review',
                        'time_upload14' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'exibitum_dect_logbook' => $pathberau14,
                        'status14' => 'on review',
                        'time_upload14' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile15')) {
                $file1 = $request->file('beraufile15');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/exibitum_engine_logbook';
                $request->file('beraufile15')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau9 = Storage::path('berau/exibitum_engine_logbook/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'exibitum_engine_logbook' => $pathberau9,
                        'status15' => 'on review',
                        'time_upload15' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'exibitum_engine_logbook' => $pathberau9,
                        'status15' => 'on review',
                        'time_upload15' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile16')) {
                $file1 = $request->file('beraufile16');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_deck_logbook';
                $request->file('beraufile16')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau10 = Storage::path('berau/pnbp_deck_logbook/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnbp_deck_logbook' => $pathberau16,
                        'status16' => 'on review',
                        'time_upload16' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'pnbp_deck_logbook' => $pathberau16,
                        'status16' => 'on review',
                        'time_upload16' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile17')) {
                $file1 = $request->file('beraufile17');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_engine_logbook';
                $request->file('beraufile17')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathberau17 = Storage::path('berau/pnbp_engine_logbook/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([
                        'status17' => 'on review',
                        'time_upload17' => date("Y-m-d"),
                        'pnbp_engine_logbook' => $pathberau17,
                    ]);
                }else {
                    documentberau::create([
                    'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,     
                        'status17' => 'on review',

                        'time_upload17' => date("Y-m-d"),
                        'pnbp_engine_logbook' => $pathberau17,
                    ]);
                }
            }
            if ($request->hasFile('beraufile18')) {
                $file1 = $request->file('beraufile18');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/biaya_docking';
                $request->file('beraufile18')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau18 = Storage::path('berau/biaya_docking/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'biaya_docking' => $pathberau18,
                        'status18' => 'on review',
                        'time_upload18' => date("Y-m-d"),
                    ]);
                }else {
                    documentberau::create([
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'biaya_docking' => $pathberau18,
                        'status18' => 'on review',
                        'time_upload18' => date("Y-m-d"),
                    ]);
                }
            }
            if ($request->hasFile('beraufile19')) {
                $file1 = $request->file('beraufile19');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/lain-lain';
                $request->file('beraufile19')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau19 = Storage::path('berau/lain-lain/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'lain-lain' => $pathberau19,
                        'status19' => 'on review',
                        'time_upload19' => date("Y-m-d"),]); 
                }else {
                    documentberau::create([                   
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,   

                        'lain-lain' => $pathberau19,
                        'status19' => 'on review',
                        'time_upload19' => date("Y-m-d"),
                    ]);
                }
            }
                if ($request->hasFile('beraufile20')) {
                $file1 = $request->file('beraufile20');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/biaya_labuh_tambat';
                $request->file('beraufile20')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau20 = Storage::path('berau/biaya_labuh_tambat/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'biaya_labuh_tambat' => $pathberau20,
                        'status20' => 'on review',
                        'time_upload20' => date("Y-m-d"),]);
                }else {
                    documentberau::create([                   
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,             
                        'biaya_labuh_tambat' => $pathberau20,
   
                        'status20' => 'on review',
                        'time_upload20' => date("Y-m-d"),
                    ]);
                }
            }
                if ($request->hasFile('beraufile21')) {
                $file1 = $request->file('beraufile21');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/biaya_rambu';
                $request->file('beraufile21')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau21 = Storage::path('berau/biaya_rambu/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'biaya_rambu' => $pathberau21,
                        'status21' => 'on review',
                        'time_upload20' => date("Y-m-d"),]);
                }else {
                    documentberau::create([                   
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'biaya_rambu' => $pathberau21,
                        'status21' => 'on review',
                        'time_upload20' => date("Y-m-d"),
                    ]);
                }
            }
                if ($request->hasFile('beraufile22')) {
                $file1 = $request->file('beraufile22');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnbp_pemeriksaan';
                $request->file('beraufile22')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau22 = Storage::path('berau/pnbp_pemeriksaan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnbp_pemeriksaan' => $pathberau22,
                        'status22' => 'on review',
                        'time_upload22' => date("Y-m-d"),]);
                }else {
                    documentberau::create([                   
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,   

                        'pnbp_pemeriksaan' => $pathberau22,
                        'status22' => 'on review',
                        'time_upload22' => date("Y-m-d"),
                    ]);
                }
            }
                if ($request->hasFile('beraufile23')) {
                $file1 = $request->file('beraufile23');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/sertifikat_bebas_sanitasi&p3k';
                $request->file('beraufile23')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau23 = Storage::path('berau/sertifikat_bebas_sanitasi&p3k/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'sertifikat_bebas_sanitasi&p3k' => $pathberau23,
                        'status23' => 'on review',
                        'time_upload23' => date("Y-m-d"),]);
                }else {
                    documentberau::create([                   
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,   

                        'sertifikat_bebas_sanitasi&p3k' => $pathberau23,
                        'status23' => 'on review',
                        'time_upload23' => date("Y-m-d"),
                    ]);
                }
            }
                if ($request->hasFile('beraufile24')) {
                $file1 = $request->file('beraufile24');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/sertifikat_garis_muat';
                $request->file('beraufile24')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau24 = Storage::path('berau/sertifikat_garis_muat/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'sertifikat_garis_muat' => $pathberau24,
                        'status24' => 'on review',
                        'time_upload24' => date("Y-m-d"),]);
                }else {
                    documentberau::create([                   
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,

                        'sertifikat_garis_muat' => $pathberau24,
                        'status24' => 'on review',
                        'time_upload24' => date("Y-m-d"),
                    ]);
                }
            }
                if ($request->hasFile('beraufile25')) {
                $file1 = $request->file('beraufile25');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/ijin_sekali_jalan';
                $request->file('beraufile25')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau25 = Storage::path('berau/ijin_sekali_jalan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'ijin_sekali_jalan' => $pathberau25,
                        'status25' => 'on review',
                        'time_upload25' => date("Y-m-d"),]);
                }else {
                    documentberau::create([                   
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,      

                        'ijin_sekali_jalan' => $pathberau25,
                        'status25' => 'on review',
                        'time_upload25' => date("Y-m-d"),
                    ]);
                }
            }
                if ($request->hasFile('beraufile26')) {
                $file1 = $request->file('beraufile26');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'berau/pnpb_sscec';
                $request->file('beraufile26')->storeAs($tujuan_upload, $name1).'.pdf';
                $pathberau26 = Storage::path('berau/pnpb_sscec/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                if (documentberau::where('cabang', 'Berau')->exists()){
                    documentberau::where('cabang', 'Berau' )->update([                   
                        'pnpb_sscec' => $pathberau26,
                        'status26' => 'on review',
                        'time_upload26' => date("Y-m-d"),]);
                }else {
                    documentberau::create([                   
                        'cabang' => Auth::user()->cabang ,
                        'user_id' => Auth::user()->id,
                        'due_time' => "28-" . date("m-Y") ,        

                        'pnpb_sscec' => $pathberau26,
                        'status26' => 'on review',
                        'time_upload26' => date("Y-m-d"),
                    ]);
                }
            }
 
            return redirect('/picsite/upload')->with('message', 'Upload success!');
        }
            
        if (Auth::user()->cabang == 'Banjarmasin') {
            $request->validate([
                //Banjarmasin
                'banjarmasinfile1'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile2'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile3'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile4'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile5'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile6'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile7'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile8'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile9'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile10'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile11'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile12'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile13'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile14'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile15'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile16'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile17'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile18'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile19'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile20'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile21'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile22'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile23'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile24'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile25'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile26'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile27'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile28'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile29'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile30'=> 'mimes:pdf|max:1024' ,
                'banjarmasinfile31'=> 'mimes:pdf|max:1024' ,
            ]);
            if ($request->hasFile('banjarmasinfile1')) {
                $file1 = $request->file('banjarmasinfile1');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/perjalanan';
                $request->file('banjarmasinfile1')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin1 = Storage::path('banjarmasin/perjalanan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );  
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status1' => 'on review',
                        'time_upload1' => date("Y-m-d"),
                        'perjalanan' => $pathbanjarmasin1,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status1' => 'on review',
                        'time_upload1' => date("Y-m-d"),
                        'perjalanan' => $pathbanjarmasin1,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile2')) {
                $file1 = $request->file('banjarmasinfile2');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/sertifikat_keselamatan';
                $request->file('banjarmasinfile2')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin2 = Storage::path('banjarmasin/sertifikat_keselamatan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if (documents::where('cabang', 'Banjarmasin')->exists()){
                   documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status2' => 'on review',
                        'time_upload2' => date("Y-m-d"),
                        'sertifikat_keselamatan' => $pathbanjarmasin2,]);
                    }else{
                        documentbanjarmasin::create([
                       'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status2' => 'on review',
                        'time_upload2' => date("Y-m-d"),
                        'sertifikat_keselamatan' => $pathbanjarmasin2,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile3')) {
                $file1 = $request->file('banjarmasinfile3');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/sertifikat_anti_fauling';
                $request->file('banjarmasinfile3')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin3 = Storage::path('banjarmasin/sertifikat_anti_fauling/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if (documents::where('cabang', 'Banjarmasin')->exists()){
                   documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status3' => 'on review',
                        'time_upload3' => date("Y-m-d"),
                        'sertifikat_anti_fauling' => $pathbanjarmasin3,]);
                    }else{
                        documentbanjarmasin::create([
                       'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status3' => 'on review',
                        'time_upload3' => date("Y-m-d"),
                        'sertifikat_anti_fauling' => $pathbanjarmasin3,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile4')) {
                $file1 = $request->file('banjarmasinfile4');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/surveyor';
                $request->file('banjarmasinfile4')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin4 = Storage::path('banjarmasin/surveyor/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if (documents::where('cabang', 'Banjarmasin')->exists()){
                   documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status4' => 'on review',
                        'time_upload4' => date("Y-m-d"),      
                        'surveyor' => $pathbanjarmasin4,]);
                    }else{
                        documentbanjarmasin::create([
                       'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status4' => 'on review',
                        'time_upload4' => date("Y-m-d"),      
                        'surveyor' => $pathbanjarmasin4,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile5')) {
                $file1 = $request->file('banjarmasinfile5');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/drawing&stability';
                $request->file('banjarmasinfile5')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin5 = Storage::path('banjarmasin/drawing&stability/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if (documents::where('cabang', 'Banjarmasin')->exists()){
                   documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status5' => 'on review',
                        'time_upload5' => date("Y-m-d"),        
                        'drawing&stability' => $pathbanjarmasin5,]);
                    }else{
                        documentbanjarmasin::create([
                       'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status5' => 'on review',
                        'time_upload5' => date("Y-m-d"),        
                        'drawing&stability' => $pathbanjarmasin5,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile6')) {
                $file1 = $request->file('banjarmasinfile6');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/laporan_pengeringan';
                $request->file('banjarmasinfile6')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin6 = Storage::path('banjarmasin/laporan_pengeringan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if (documents::where('cabang', 'Banjarmasin')->exists()){
                   documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status6' => 'on review',
                        'time_upload6' => date("Y-m-d"),       
                        'laporan_pengeringan' => $pathbanjarmasin6,]);
                    }else{
                        documentbanjarmasin::create([
                       'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status6' => 'on review',
                        'time_upload6' => date("Y-m-d"),       
                        'laporan_pengeringan' => $pathbanjarmasin6,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile7')) {
                $file1 = $request->file('banjarmasinfile7');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/laporan_pemeriksaan_nautis';
                $request->file('banjarmasinfile7')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin7 = Storage::path('banjarmasin/laporan_pemeriksaan_nautis/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if (documents::where('cabang', 'Banjarmasin')->exists()){
                   documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status7' => 'on review',
                        'time_upload7' => date("Y-m-d"),   
                        'laporan_pemeriksaan_nautis' => $pathbanjarmasin7,]);
                    }else{
                        documentbanjarmasin::create([
                       'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status7' => 'on review',
                        'time_upload7' => date("Y-m-d"),   
                        'laporan_pemeriksaan_nautis' => $pathbanjarmasin7,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile8')) {
                $file1 = $request->file('banjarmasinfile8');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/laporan_pemeriksaan_anti_faulin';
                $request->file('banjarmasinfile8')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin8 = Storage::path('banjarmasin/laporan_pemeriksaan_anti_faulin/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if (documents::where('cabang', 'Banjarmasin')->exists()){
                   documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status8' => 'on review',
                        'time_upload8' => date("Y-m-d"),      
                        'laporan_pemeriksaan_anti_faulin' => $pathbanjarmasin8,]);
                    }else{
                        documentbanjarmasin::create([
                       'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status8' => 'on review',
                        'time_upload8' => date("Y-m-d"),      
                        'laporan_pemeriksaan_anti_faulin' => $pathbanjarmasin8,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile9')) {
                $file1 = $request->file('banjarmasinfile9');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/laporan_pemeriksaan_radio';
                $request->file('banjarmasinfile9')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin9 = Storage::path('banjarmasin/laporan_pemeriksaan_radio/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if (documents::where('cabang', 'Banjarmasin')->exists()){
                   documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status9' => 'on review',
                        'time_upload9' => date("Y-m-d"),       
                        'laporan_pemeriksaan_radio' => $pathbanjarmasin9,]);
                    }else{
                        documentbanjarmasin::create([
                       'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status9' => 'on review',
                        'time_upload9' => date("Y-m-d"),       
                        'laporan_pemeriksaan_radio' => $pathbanjarmasin9,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile10')) {
                $file1 = $request->file('banjarmasinfile10');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/berita_acara_lambung';
                $request->file('banjarmasinfile10')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin10 = Storage::path('banjarmasin/berita_acara_lambung/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status10' => 'on review',
                        'time_upload10' => date("Y-m-d"),
                        'berita_acara_lambung' => $pathbanjarmasin10,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status10' => 'on review',
                        'time_upload10' => date("Y-m-d"),
                        'berita_acara_lambung' => $pathbanjarmasin10,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile11')) {
                $file1 = $request->file('banjarmasinfile11');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/laporan_pemeriksaan_snpp';
                $request->file('banjarmasinfile11')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin11 = Storage::path('banjarmasin/laporan_pemeriksaan_snpp/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status11' => 'on review',
                        'time_upload11' => date("Y-m-d"),
                        'laporan_pemeriksaan_snpp' => $pathbanjarmasin11,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status11' => 'on review',
                        'time_upload11' => date("Y-m-d"),
                        'laporan_pemeriksaan_snpp' => $pathbanjarmasin11,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile12')) {
                $file1 = $request->file('banjarmasinfile12');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/bki';
                $request->file('banjarmasinfile12')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin12 = Storage::path('banjarmasin/bki/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status12' => 'on review',
                        'time_upload12' => date("Y-m-d"),
                        'bki' => $pathbanjarmasin12,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status12' => 'on review',
                        'time_upload12' => date("Y-m-d"),
                        'bki' => $pathbanjarmasin12,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile13')) {
                $file1 = $request->file('banjarmasinfile13');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/snpp_permanen';
                $request->file('banjarmasinfile13')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin13 = Storage::path('banjarmasin/snpp_permanen/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status13' => 'on review',
                        'time_upload13' => date("Y-m-d"),
                        'snpp_permanen' => $pathbanjarmasin13,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status13' => 'on review',
                        'time_upload13' => date("Y-m-d"),
                        'snpp_permanen' => $pathbanjarmasin13,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile14')) {
                $file1 = $request->file('banjarmasinfile14');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/snpp_endorse';
                $request->file('banjarmasinfile14')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin14 = Storage::path('banjarmasin/snpp_endorse/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status14' => 'on review',
                        'time_upload14' => date("Y-m-d"),
                        'snpp_endorse' => $pathbanjarmasin14,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status14' => 'on review',
                        'time_upload14' => date("Y-m-d"),
                        'snpp_endorse' => $pathbanjarmasin14,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile15')) {
                $file1 = $request->file('banjarmasinfile15');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/surat_laut_endorse';
                $request->file('banjarmasinfile15')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin15 = Storage::path('banjarmasin/surat_laut_endorse/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status15' => 'on review',
                        'time_upload15' => date("Y-m-d"),
                        'surat_laut_endorse' => $pathbanjarmasin15,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status15' => 'on review',
                        'time_upload15' => date("Y-m-d"),
                        'surat_laut_endorse' => $pathbanjarmasin15,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile16')) {
                $file1 = $request->file('banjarmasinfile16');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/surat_laut_permanen';
                $request->file('banjarmasinfile16')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin16 = Storage::path('banjarmasin/surat_laut_permanen/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status16' => 'on review',
                        'time_upload16' => date("Y-m-d"),
                        'surat_laut_permanen' => $pathbanjarmasin16,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status16' => 'on review',
                        'time_upload16' => date("Y-m-d"),
                        'surat_laut_permanen' => $pathbanjarmasin16,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile17')) {
                $file1 = $request->file('banjarmasinfile17');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/compas_seren';
                $request->file('banjarmasinfile17')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin17 = Storage::path('banjarmasin/compas_seren/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status17' => 'on review',
                        'time_upload17' => date("Y-m-d"),
                        'compas_seren' => $pathbanjarmasin17,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status17' => 'on review',
                        'time_upload17' => date("Y-m-d"),
                        'compas_seren' => $pathbanjarmasin17,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile18')) {
                $file1 = $request->file('banjarmasinfile18');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/keselamatan_(tahunan)';
                $request->file('banjarmasinfile18')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin18 = Storage::path('banjarmasin/keselamatan_(tahunan)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status18' => 'on review',
                        'time_upload18' => date("Y-m-d"),
                        'keselamatan_(tahunan)' => $pathbanjarmasin18,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status18' => 'on review',
                        'time_upload18' => date("Y-m-d"),
                        'keselamatan_(tahunan)' => $pathbanjarmasin18,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile19')) {
                $file1 = $request->file('banjarmasinfile19');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/keselamatan_(pengaturan_dok)';
                $request->file('banjarmasinfile19')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin19 = Storage::path('banjarmasin/keselamatan_(pengaturan_dok)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status19' => 'on review',
                        'time_upload19' => date("Y-m-d"),
                        'keselamatan_(pengaturan_dok)' => $pathbanjarmasin19,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status19' => 'on review',
                        'time_upload19' => date("Y-m-d"),
                        'keselamatan_(pengaturan_dok)' => $pathbanjarmasin19,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile20')) {
                $file1 = $request->file('banjarmasinfile20');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/keselamatan_(dok)';
                $request->file('banjarmasinfile20')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin20 = Storage::path('banjarmasin/keselamatan_(dok)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status20' => 'on review',
                        'time_upload20' => date("Y-m-d"),
                        'keselamatan_(dok)' => $pathbanjarmasin20,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status20' => 'on review',
                        'time_upload20' => date("Y-m-d"),
                        'keselamatan_(dok)' => $pathbanjarmasin20,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile21')) {
                $file1 = $request->file('banjarmasinfile21');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/garis_muat';
                $request->file('banjarmasinfile21')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin21 = Storage::path('banjarmasin/garis_muat/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status21' => 'on review',
                        'time_upload21' => date("Y-m-d"),
                        'garis_muat' => $pathbanjarmasin21,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status21' => 'on review',
                        'time_upload21' => date("Y-m-d"),
                        'garis_muat' => $pathbanjarmasin21,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile22')) {
                $file1 = $request->file('banjarmasinfile22');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/dispensasi_isr';
                $request->file('banjarmasinfile22')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin22 = Storage::path('banjarmasin/dispensasi_isr/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status22' => 'on review',
                        'time_upload22' => date("Y-m-d"),
                        'dispensasi_isr' => $pathbanjarmasin22,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status22' => 'on review',
                        'time_upload22' => date("Y-m-d"),
                        'dispensasi_isr' => $pathbanjarmasin22,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile23')) {
                $file1 = $request->file('banjarmasinfile23');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/life_raft_1_2_pemadam';
                $request->file('banjarmasinfile23')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin23 = Storage::path('banjarmasin/life_raft_1_2_pemadam/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status23' => 'on review',
                        'time_upload23' => date("Y-m-d"),
                        'life_raft_1_2_pemadam' => $pathbanjarmasin23,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status23' => 'on review',
                        'time_upload23' => date("Y-m-d"),
                        'life_raft_1_2_pemadam' => $pathbanjarmasin23,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile24')) {
                $file1 = $request->file('banjarmasinfile24');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/sscec';
                $request->file('banjarmasinfile24')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin24 = Storage::path('banjarmasin/sscec/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status24' => 'on review',
                        'time_upload24' => date("Y-m-d"),
                        'sscec' => $pathbanjarmasin24,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status24' => 'on review',
                        'time_upload24' => date("Y-m-d"),
                        'sscec' => $pathbanjarmasin24,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile25')) {
                $file1 = $request->file('banjarmasinfile25');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/seatrail';
                $request->file('banjarmasinfile25')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin25 = Storage::path('banjarmasin/seatrail/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status25' => 'on review',
                        'time_upload25' => date("Y-m-d"),
                        'seatrail' => $pathbanjarmasin25,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status25' => 'on review',
                        'time_upload25' => date("Y-m-d"),
                        'seatrail' => $pathbanjarmasin25,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile26')) {
                $file1 = $request->file('banjarmasinfile26');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/laporan_pemeriksaan_umum';
                $request->file('banjarmasinfile26')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin26 = Storage::path('banjarmasin/laporan_pemeriksaan_umum/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status26' => 'on review',
                        'time_upload26' => date("Y-m-d"),
                        'laporan_pemeriksaan_umum' => $pathbanjarmasin26,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status26' => 'on review',
                        'time_upload26' => date("Y-m-d"),
                        'laporan_pemeriksaan_umum' => $pathbanjarmasin26,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile27')) {
                $file1 = $request->file('banjarmasinfile27');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/laporan_pemeriksaan_mesin';
                $request->file('banjarmasinfile27')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin27 = Storage::path('banjarmasin/laporan_pemeriksaan_mesin/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status27' => 'on review',
                        'time_upload27' => date("Y-m-d"),
                        'laporan_pemeriksaan_mesin' => $pathbanjarmasin27,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status27' => 'on review',
                        'time_upload27' => date("Y-m-d"),
                        'laporan_pemeriksaan_mesin' => $pathbanjarmasin27,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile28')) {
                $file1 = $request->file('banjarmasinfile28');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/nota_dinas_perubahan_kawasan';
                $request->file('banjarmasinfile28')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin28 = Storage::path('banjarmasin/nota_dinas_perubahan_kawasan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status28' => 'on review',
                        'time_upload28' => date("Y-m-d"),
                        'nota_dinas_perubahan_kawasan' => $pathbanjarmasin28,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status28' => 'on review',
                        'time_upload28' => date("Y-m-d"),
                        'nota_dinas_perubahan_kawasan' => $pathbanjarmasin28,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile29')) {
                $file1 = $request->file('banjarmasinfile29');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/PAS';
                $request->file('banjarmasinfile29')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin29 = Storage::path('banjarmasin/PAS/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status29' => 'on review',
                        'time_upload29' => date("Y-m-d"),
                        'PAS' => $pathbanjarmasin29,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status29' => 'on review',
                        'time_upload29' => date("Y-m-d"),
                        'PAS' => $pathbanjarmasin29,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile30')) {
                $file1 = $request->file('banjarmasinfile30');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/invoice_bki';
                $request->file('banjarmasinfile30')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin30 = Storage::path('banjarmasin/invoice_bki/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status30' => 'on review',
                        'time_upload30' => date("Y-m-d"),
                        'invoice_bki' => $pathbanjarmasin30,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status30' => 'on review',
                        'time_upload30' => date("Y-m-d"),
                        'invoice_bki' => $pathbanjarmasin30,
                    ]);
                }
            }
            if ($request->hasFile('banjarmasinfile31')) {
                $file1 = $request->file('banjarmasinfile31');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'banjarmasin/safe_manning';
                $request->file('banjarmasinfile31')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathbanjarmasin31 = Storage::path('banjarmasin/safe_manning/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if (documents::where('cabang', 'Banjarmasin')->exists()){
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                        'status31' => 'on review',
                        'time_upload31' => date("Y-m-d"),
                        'safe_manning' => $pathbanjarmasin31,]);
                    }else{
                        documentbanjarmasin::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status31' => 'on review',
                        'time_upload31' => date("Y-m-d"),
                        'safe_manning' => $pathbanjarmasin31,
                    ]);
                }
            }
            return redirect('/picsite/upload')->with('message', 'Upload success!');
        }
            
        if (Auth::user()->cabang == 'Samarinda') {
                //Samarinda
                $request->validate([
                    'samarindafile1' => 'mimes:pdf|max:1024' , 
                    'samarindafile2' => 'mimes:pdf|max:1024' ,
                    'samarindafile3' => 'mimes:pdf|max:1024' ,
                    'samarindafile4' => 'mimes:pdf|max:1024' ,
                    'samarindafile5' => 'mimes:pdf|max:1024' ,
                    'samarindafile6' => 'mimes:pdf|max:1024' ,
                    'samarindafile7' => 'mimes:pdf|max:1024' ,
                    'samarindafile8' => 'mimes:pdf|max:1024' ,
                    'samarindafile9' => 'mimes:pdf|max:1024' ,
                    'samarindafile10'=> 'mimes:pdf|max:1024' ,
                    'samarindafile11'=> 'mimes:pdf|max:1024' ,
                    'samarindafile12'=> 'mimes:pdf|max:1024' ,
                    'samarindafile13'=> 'mimes:pdf|max:1024' ,
                    'samarindafile14'=> 'mimes:pdf|max:1024' ,
                    'samarindafile15'=> 'mimes:pdf|max:1024' ,
                    'samarindafile16'=> 'mimes:pdf|max:1024' ,
                    'samarindafile17'=> 'mimes:pdf|max:1024' ,
                    'samarindafile18'=> 'mimes:pdf|max:1024' ,
                    'samarindafile19'=> 'mimes:pdf|max:1024' ,
                    'samarindafile20'=> 'mimes:pdf|max:1024' ,
                    'samarindafile21'=> 'mimes:pdf|max:1024' ,
                    'samarindafile22'=> 'mimes:pdf|max:1024' ,
                    'samarindafile23'=> 'mimes:pdf|max:1024' ,
                    'samarindafile24'=> 'mimes:pdf|max:1024' ,
                    'samarindafile25'=> 'mimes:pdf|max:1024' ,
                    'samarindafile26'=> 'mimes:pdf|max:1024' ,
                    'samarindafile27'=> 'mimes:pdf|max:1024' ,
                    'samarindafile28'=> 'mimes:pdf|max:1024' ,
                    'samarindafile29'=> 'mimes:pdf|max:1024' ,
                    'samarindafile30'=> 'mimes:pdf|max:1024' ,
                    'samarindafile31'=> 'mimes:pdf|max:1024' ,
                    'samarindafile32'=> 'mimes:pdf|max:1024' ,
                    'samarindafile33'=> 'mimes:pdf|max:1024' ,
                    'samarindafile34'=> 'mimes:pdf|max:1024' ,
                    'samarindafile35'=> 'mimes:pdf|max:1024' ,
                    'samarindafile36'=> 'mimes:pdf|max:1024' ,
                    'samarindafile37'=> 'mimes:pdf|max:1024' ,
                    'samarindafile38'=> 'mimes:pdf|max:1024' ,
                ]);
                if ($request->hasFile('samarindafile1')) {
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()) {
                        $file1 = $request->file('samarindafile1');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/sertifikat_keselamatan(perpanjangan)';
                        $request->file('samarindafile1')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda1 = Storage::path('samarinda/sertifikat_keselamatan(perpanjangan)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        documentsamarinda::where('cabang', 'Samarinda')->update([
                                'status1' => 'on review',
                                'time_upload1' => date("Y-m-d"),
                                'sertifikat_keselamatan(perpanjangan)' => $pathsamarinda1,]);
                    }else{
                        documentsamarinda::create([
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status1' => 'on review',
                                'time_upload1' => date("Y-m-d"),
                                'sertifikat_keselamatan(perpanjangan)' => $pathsamarinda1,]);
                    }
                }
                if ($request->hasFile('samarindafile2')) {
                $file1 = $request->file('samarindafile2');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'samarinda/perubahan_ok_13_ke_ok_1';
                $request->file('samarindafile2')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathsamarinda2 = Storage::path('samarinda/perubahan_ok_13_ke_ok_1/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
               if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                   documentsamarinda::where('cabang', 'Samarinda')->update([
                           'status2' => 'on review',
                           'time_upload2' => date("Y-m-d"),
                           'perubahan_ok_13_ke_ok_1' => $pathsamarinda2,]);
                }else{
                    documentsamarinda::create([
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                                
                        'status2' => 'on review',
                        'time_upload2' => date("Y-m-d"),
                        'perubahan_ok_13_ke_ok_1' => $pathsamarinda2,]);
                }
                }
                if ($request->hasFile('samarindafile3')) {
                    $file1 = $request->file('samarindafile3');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/keselamatan_(tahunan)';
                    $request->file('samarindafile3')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda3 = Storage::path('samarinda/keselamatan_(tahunan)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                   if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                       documentsamarinda::where('cabang', 'Samarinda')->update([  
                               'status3' => 'on review',
                               'time_upload3' => date("Y-m-d"),
                               'keselamatan_(tahunan)' => $pathsamarinda3,]);
                               
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,
                                
                            'status3' => 'on review',
                            'time_upload3' => date("Y-m-d"),
                            'keselamatan_(tahunan)' => $pathsamarinda3,]);
                    }
                }
                if ($request->hasFile('samarindafile4')) {
                    $file1 = $request->file('samarindafile4');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/keselamatan_(dok)';
                    $request->file('samarindafile4')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda4 = Storage::path('samarinda/keselamatan_(dok)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                   if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                       documentsamarinda::where('cabang', 'Samarinda')->update([  
                               'status4' => 'on review',
                               'time_upload4' => date("Y-m-d"),
                               'keselamatan_(dok)' => $pathsamarinda4,]);
                               
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,
                                
                            'status4' => 'on review',
                            'time_upload4' => date("Y-m-d"),
                            'keselamatan_(dok)' => $pathsamarinda4,]);
                    }
                }
                if ($request->hasFile('samarindafile5')) {
                    $file1 = $request->file('samarindafile5');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/keselamatan_(pengaturan_dok)';
                    $request->file('samarindafile5')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda5 = Storage::path('samarinda/keselamatan_(pengaturan_dok)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                   if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                       documentsamarinda::where('cabang', 'Samarinda')->update([  
                               'status5' => 'on review',
                               'time_upload5' => date("Y-m-d"),
                               'keselamatan_(pengaturan_dok)' => $pathsamarinda5,]);
                               
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,
                                
                            'status5' => 'on review',
                            'time_upload5' => date("Y-m-d"),
                            'keselamatan_(pengaturan_dok)' => $pathsamarinda5,]);
                    }
                }
                if ($request->hasFile('samarindafile6')) {
                    $file1 = $request->file('samarindafile6');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/keselamatan_(penundaan_dok)';
                    $request->file('samarindafile6')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda6 = Storage::path('samarinda/keselamatan_(penundaan_dok)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                   if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                       documentsamarinda::where('cabang', 'Samarinda')->update([  
                               'status6' => 'on review',
                               'time_upload6' => date("Y-m-d"),
                               'keselamatan_(penundaan_dok)' => $pathsamarinda6,]);
                               
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,
                                
                            'status6' => 'on review',
                            'time_upload6' => date("Y-m-d"),
                            'keselamatan_(penundaan_dok)' => $pathsamarinda6,]);
                    }
                }
                if ($request->hasFile('samarindafile7')) {
                    $file1 = $request->file('samarindafile7');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/sertifikat_garis_muat';
                    $request->file('samarindafile7')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda7 = Storage::path('samarinda/sertifikat_garis_muat/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                   if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                       documentsamarinda::where('cabang', 'Samarinda')->update([  
                               'status7' => 'on review',
                               'time_upload7' => date("Y-m-d"),
                               'sertifikat_garis_muat' => $pathsamarinda7,]);
                               
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,
                                
                            'status7' => 'on review',
                            'time_upload7' => date("Y-m-d"),
                            'sertifikat_garis_muat' => $pathsamarinda7,]);
                    }
                }
                if ($request->hasFile('samarindafile8')) {
                    $file1 = $request->file('samarindafile8');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/laporan_pemeriksaan_garis_muat';
                    $request->file('samarindafile8')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda8 = Storage::path('samarinda/laporan_pemeriksaan_garis_muat/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                   if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                       documentsamarinda::where('cabang', 'Samarinda')->update([  
                               'status8' => 'on review',
                               'time_upload8' => date("Y-m-d"),
                               'laporan_pemeriksaan_garis_muat' => $pathsamarinda8,]);
                               
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,
                                
                            'status8' => 'on review',
                            'time_upload8' => date("Y-m-d"),
                            'laporan_pemeriksaan_garis_muat' => $pathsamarinda8,]);
                    }
                }
                if ($request->hasFile('samarindafile9')) {
                        $file1 = $request->file('samarindafile9');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/sertifikat_anti_fauling';
                        $request->file('samarindafile9')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda9 = Storage::path('samarinda/sertifikat_anti_fauling/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                       if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                           documentsamarinda::where('cabang', 'Samarinda')->update([  
                                   'status9' => 'on review',
                                   'time_upload9' => date("Y-m-d"),
                                   'sertifikat_anti_fauling' => $pathsamarinda9,]);
                                   
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,
                                
                                'status9' => 'on review',
                                'time_upload9' => date("Y-m-d"),
                                'sertifikat_anti_fauling' => $pathsamarinda9,]);
                        }
                }
                if ($request->hasFile('samarindafile10')) {
                        $file1 = $request->file('samarindafile10');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/surat_laut_permanen';
                        $request->file('samarindafile10')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda10 = Storage::path('samarinda/surat_laut_permanen/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status10' => 'on review',
                                    'time_upload10' => date("Y-m-d"),
                                    'surat_laut_permanen' => $pathsamarinda10,]);
                                    
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status10' => 'on review',
                                'time_upload10' => date("Y-m-d"),
                                'surat_laut_permanen' => $pathsamarinda10,]);
                            }
                }
                if ($request->hasFile('samarindafile11')) {
                    $file1 = $request->file('samarindafile11');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/surat_laut_endorse';
                    $request->file('samarindafile11')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda11 = Storage::path('samarinda/surat_laut_endorse/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status11' => 'on review',
                                'time_upload11' => date("Y-m-d"),
                                'surat_laut_endorse' => $pathsamarinda11,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status11' => 'on review',
                            'time_upload11' => date("Y-m-d"),
                            'surat_laut_endorse' => $pathsamarinda11,]);
                        }
                }
                if ($request->hasFile('samarindafile12')) {
                        $file1 = $request->file('samarindafile12');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/call_sign';
                        $request->file('samarindafile12')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda12 = Storage::path('samarinda/call_sign/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status12' => 'on review',
                                    'time_upload12' => date("Y-m-d"),
                                    'call_sign' => $pathsamarinda12,]);
                                    
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,
                                'status12' => 'on review',

                                'time_upload12' => date("Y-m-d"),
                                'call_sign' => $pathsamarinda12,]);
                            }
                }
                if ($request->hasFile('samarindafile13')) {
                        $file1 = $request->file('samarindafile13');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/perubahan_sertifikat_keselamatan';
                        $request->file('samarindafile13')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda13 = Storage::path('samarinda/perubahan_sertifikat_keselamatan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status13' => 'on review',
                                    'time_upload13' => date("Y-m-d"),
                                    'perubahan_sertifikat_keselamatan' => $pathsamarinda13,]);
                                    
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status13' => 'on review',
                                'time_upload13' => date("Y-m-d"),
                                'perubahan_sertifikat_keselamatan' => $pathsamarinda13,]);
                            }
                }
                if ($request->hasFile('samarindafile14')) {
                        $file1 = $request->file('samarindafile14');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/perubahan_kawasan_tanpa_notadin';
                        $request->file('samarindafile14')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda14 = Storage::path('samarinda/perubahan_kawasan_tanpa_notadin/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status14' => 'on review',
                                    'time_upload14' => date("Y-m-d"),
                                    'perubahan_kawasan_tanpa_notadin' => $pathsamarinda14,]);
                                    
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status14' => 'on review',
                                'time_upload14' => date("Y-m-d"),
                                'perubahan_kawasan_tanpa_notadin' => $pathsamarinda14,]);
                            }
                }
                if ($request->hasFile('samarindafile15')) {
                        $file1 = $request->file('samarindafile15');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/snpp_permanen';
                        $request->file('samarindafile15')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda15 = Storage::path('samarinda/snpp_permanen/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status15' => 'on review',
                                    'time_upload15' => date("Y-m-d"),
                                    'snpp_permanen' => $pathsamarinda15,]);
                                    
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status15' => 'on review',
                                'time_upload15' => date("Y-m-d"),
                                'snpp_permanen' => $pathsamarinda15,]);
                            }
                }
                if ($request->hasFile('samarindafile16')) {
                        $file1 = $request->file('samarindafile16');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/snpp_endorse';
                        $request->file('samarindafile16')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda16 = Storage::path('samarinda/snpp_endorse/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status16' => 'on review',
                                    'time_upload16' => date("Y-m-d"),
                                    'snpp_endorse' => $pathsamarinda16,]);
                                    
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status16' => 'on review',
                                'time_upload16' => date("Y-m-d"),
                                'snpp_endorse' => $pathsamarinda16,]);
                            }
                }
                if ($request->hasFile('samarindafile17')) {
                        $file1 = $request->file('samarindafile17');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/laporan_pemeriksaan_snpp';
                        $request->file('samarindafile17')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda17 = Storage::path('samarinda/laporan_pemeriksaan_snpp/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status17' => 'on review',
                                    'time_upload17' => date("Y-m-d"),
                                    'laporan_pemeriksaan_snpp' => $pathsamarinda17,]);
                                    
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status17' => 'on review',
                                'time_upload17' => date("Y-m-d"),
                                'laporan_pemeriksaan_snpp' => $pathsamarinda17,]);
                            }
                }
                if ($request->hasFile('samarindafile18')) {
                        $file1 = $request->file('samarindafile18');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/laporan_pemeriksaan_keselamatan';
                        $request->file('samarindafile18')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda18 = Storage::path('samarinda/laporan_pemeriksaan_keselamatan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status18' => 'on review',
                                    'time_upload18' => date("Y-m-d"),
                                    'laporan_pemeriksaan_keselamatan' => $pathsamarinda18,]);
                                    
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status18' => 'on review',
                                'time_upload18' => date("Y-m-d"),
                                'laporan_pemeriksaan_keselamatan' => $pathsamarinda18,]);
                            }
                }
                if ($request->hasFile('samarindafile19')) {
                    $file1 = $request->file('samarindafile19');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/buku_kesehatan';
                    $request->file('samarindafile19')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda19 = Storage::path('samarinda/buku_kesehatan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status19' => 'on review',
                                'time_upload19' => date("Y-m-d"),
                                'buku_kesehatan' => $pathsamarinda19,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status19' => 'on review',
                            'time_upload19' => date("Y-m-d"),
                            'buku_kesehatan' => $pathsamarinda19,]);
                        }
                }
                if ($request->hasFile('samarindafile20')) {
                    $file1 = $request->file('samarindafile20');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/sertifikat_sanitasi_water&p3k';
                    $request->file('samarindafile20')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda20 = Storage::path('samarinda/sertifikat_sanitasi_water&p3k/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status20' => 'on review',
                                'time_upload20' => date("Y-m-d"),
                                'sertifikat_sanitasi_water&p3k' => $pathsamarinda20,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status20' => 'on review',
                            'time_upload20' => date("Y-m-d"),
                            'sertifikat_sanitasi_water&p3k' => $pathsamarinda20,]);
                        }
                }
                if ($request->hasFile('samarindafile21')) {
                $file1 = $request->file('samarindafile21');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'samarinda/pengaturan_non_ke_klas_bki';
                $request->file('samarindafile21')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathsamarinda21 = Storage::path('samarinda/pengaturan_non_ke_klas_bki/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                    documentsamarinda::where('cabang', 'Samarinda')->update([  
                            'status21' => 'on review',
                            'time_upload21' => date("Y-m-d"),
                            'pengaturan_non_ke_klas_bki' => $pathsamarinda21,]);
                            
                }else{
                    documentsamarinda::create([  
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,

                        'status21' => 'on review',
                        'time_upload21' => date("Y-m-d"),
                        'pengaturan_non_ke_klas_bki' => $pathsamarinda21,]);
                    }
                }
                if ($request->hasFile('samarindafile22')) {
                    $file1 = $request->file('samarindafile22');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/pengaturan_klas_bki_(dok_ss)';
                    $request->file('samarindafile22')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda22 = Storage::path('samarinda/pengaturan_klas_bki_(dok_ss)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status22' => 'on review',
                                'time_upload22' => date("Y-m-d"),
                                'pengaturan_klas_bki_(dok_ss)' => $pathsamarinda22,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status22' => 'on review',
                            'time_upload22' => date("Y-m-d"),
                            'pengaturan_klas_bki_(dok_ss)' => $pathsamarinda22,]);
                        }
                }
                if ($request->hasFile('samarindafile23')) {
                    $file1 = $request->file('samarindafile23');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/surveyor_endorse_tahunan_bki';
                    $request->file('samarindafile23')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda23 = Storage::path('samarinda/surveyor_endorse_tahunan_bki/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status23' => 'on review',
                                'time_upload23' => date("Y-m-d"),
                                'surveyor_endorse_tahunan_bki' => $pathsamarinda23,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status23' => 'on review',
                            'time_upload23' => date("Y-m-d"),
                            'surveyor_endorse_tahunan_bki' => $pathsamarinda23,]);
                        }
                }
                if ($request->hasFile('samarindafile24')) {
                    $file1 = $request->file('samarindafile24');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/pr_supplier_bki';
                    $request->file('samarindafile24')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda24 = Storage::path('samarinda/pr_supplier_bki/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status24' => 'on review',
                                'time_upload24' => date("Y-m-d"),
                                'pr_supplier_bki' => $pathsamarinda24,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status24' => 'on review',
                            'time_upload24' => date("Y-m-d"),
                            'pr_supplier_bki' => $pathsamarinda24,]);
                        }
                }
                if ($request->hasFile('samarindafile25')) {
                    $file1 = $request->file('samarindafile25');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/balik_nama_grosse';
                    $request->file('samarindafile25')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda25 = Storage::path('samarinda/balik_nama_grosse/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status25' => 'on review',
                                'time_upload25' => date("Y-m-d"),
                                'balik_nama_grosse' => $pathsamarinda25,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status25' => 'on review',
                            'time_upload25' => date("Y-m-d"),
                            'balik_nama_grosse' => $pathsamarinda25,]);
                        }
                }
                if ($request->hasFile('samarindafile26')) {
                    $file1 = $request->file('samarindafile26');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/kapal_baru_body_(set_dokumen)';
                    $request->file('samarindafile26')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda26 = Storage::path('samarinda/kapal_baru_body_(set_dokumen)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status26' => 'on review',
                                'time_upload26' => date("Y-m-d"),
                                'kapal_baru_body_(set_dokumen)' => $pathsamarinda26,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status26' => 'on review',
                            'time_upload26' => date("Y-m-d"),
                            'kapal_baru_body_(set_dokumen)' => $pathsamarinda26,]);
                        }
                }
                if ($request->hasFile('samarindafile27')) {
                    $file1 = $request->file('samarindafile27');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/halaman_tambahan_grosse';
                    $request->file('samarindafile27')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda27 = Storage::path('samarinda/halaman_tambahan_grosse/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status27' => 'on review',
                                'time_upload27' => date("Y-m-d"),
                                'halaman_tambahan_grosse' => $pathsamarinda27,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status27' => 'on review',
                            'time_upload27' => date("Y-m-d"),
                            'halaman_tambahan_grosse' => $pathsamarinda27,]);
                        }
                }
                if ($request->hasFile('samarindafile28')) {
                    $file1 = $request->file('samarindafile28');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/pnbp&pup';
                    $request->file('samarindafile28')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda28 = Storage::path('samarinda/pnbp&pup/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status28' => 'on review',
                                'time_upload28' => date("Y-m-d"),
                                'pnbp&pup' => $pathsamarinda28,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status28' => 'on review',
                            'time_upload28' => date("Y-m-d"),
                            'pnbp&pup' => $pathsamarinda28,]);
                        }
                }
                if ($request->hasFile('samarindafile29')) {
                    $file1 = $request->file('samarindafile29');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/laporan_pemeriksaan_anti_teriti';
                    $request->file('samarindafile29')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda29 = Storage::path('samarinda/laporan_pemeriksaan_anti_teriti/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status29' => 'on review',
                                'time_upload29' => date("Y-m-d"),
                                'laporan_pemeriksaan_anti_teriti' => $pathsamarinda29,]);
                                
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status29' => 'on review',
                            'time_upload29' => date("Y-m-d"),
                            'laporan_pemeriksaan_anti_teriti' => $pathsamarinda29,]);
                        }
                }
                if ($request->hasFile('samarindafile30')) {
                $file1 = $request->file('samarindafile30');
                $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'samarinda/surveyor_pengedokan';
                $request->file('samarindafile30')->storeAs($tujuan_upload, $name1).'.pdf';   
                $pathsamarinda30 = Storage::path('samarinda/surveyor_pengedokan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                    documentsamarinda::where('cabang', 'Samarinda')->update([  
                            'status30' => 'on review',
                            'time_upload30' => date("Y-m-d"),
                            'surveyor_pengedokan' => $pathsamarinda30,]);
                            
                }else{
                    documentsamarinda::create([  
                        'cabang' => Auth::user()->cabang ,
                        'due_time' => "28-" . date("m-Y") ,
                        'user_id' => Auth::user()->id,
                        
                        'status30' => 'on review',
                        'time_upload30' => date("Y-m-d"),
                        'surveyor_pengedokan' => $pathsamarinda30,]);
                    }
                }
                if ($request->hasFile('samarindafile31')) {
                    $file1 = $request->file('samarindafile31');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/surveyor_penerimaan_klas_bki';
                    $request->file('samarindafile31')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda31 = Storage::path('samarinda/surveyor_penerimaan_klas_bki/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status31' => 'on review',
                                'time_upload31' => date("Y-m-d"),
                                'surveyor_penerimaan_klas_bki' => $pathsamarinda31,]);   
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status31' => 'on review',
                            'time_upload31' => date("Y-m-d"),
                            'surveyor_penerimaan_klas_bki' => $pathsamarinda31,]);
                        }
                }
                if ($request->hasFile('samarindafile32')) {
                    $file1 = $request->file('samarindafile32');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/nota_tagihan_jasa_perkapalan';
                    $request->file('samarindafile32')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda32 = Storage::path('samarinda/nota_tagihan_jasa_perkapalan/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status32' => 'on review',
                                'time_upload32' => date("Y-m-d"),
                                'nota_tagihan_jasa_perkapalan' => $pathsamarinda32,]);
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status32' => 'on review',
                            'time_upload32' => date("Y-m-d"),
                            'nota_tagihan_jasa_perkapalan' => $pathsamarinda32,]);
                        }
            }
                if ($request->hasFile('samarindafile33')) {
                    $file1 = $request->file('samarindafile33');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/gambar_kapal_baru_(bki)';
                    $request->file('samarindafile33')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda33 = Storage::path('samarinda/gambar_kapal_baru_(bki)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status33' => 'on review',
                                'time_upload33' => date("Y-m-d"),
                                'gambar_kapal_baru_(bki)' => $pathsamarinda33,]);
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status33' => 'on review',
                            'time_upload33' => date("Y-m-d"),
                            'gambar_kapal_baru_(bki)' => $pathsamarinda33,]);
                        }
                }
                if ($request->hasFile('samarindafile34')) {
                    $file1 = $request->file('samarindafile34');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/samarinda_jam1nan_(clc)';
                    $request->file('samarindafile34')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda34 = Storage::path('samarinda/samarinda_jam1nan_(clc)/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status34' => 'on review',
                                'time_upload34' => date("Y-m-d"),
                                'samarinda_jam1nan_(clc)' => $pathsamarinda34,]);
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status34' => 'on review',
                            'time_upload34' => date("Y-m-d"),
                            'samarinda_jam1nan_(clc)' => $pathsamarinda34,]);
                        }
                }
                if ($request->hasFile('samarindafile35')) {
                        $file1 = $request->file('samarindafile35');
                        $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                        $tujuan_upload = 'samarinda/surat_ukur_dalam_negeri';
                        $request->file('samarindafile35')->storeAs($tujuan_upload, $name1).'.pdf';   
                        $pathsamarinda35 = Storage::path('samarinda/surat_ukur_dalam_negeri/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                        if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                            documentsamarinda::where('cabang', 'Samarinda')->update([  
                                    'status35' => 'on review',
                                    'time_upload35' => date("Y-m-d"),
                                    'surat_ukur_dalam_negeri' => $pathsamarinda35,]);
                        }else{
                            documentsamarinda::create([  
                                'cabang' => Auth::user()->cabang ,
                                'due_time' => "28-" . date("m-Y") ,
                                'user_id' => Auth::user()->id,

                                'status35' => 'on review',
                                'time_upload35' => date("Y-m-d"),
                                'surat_ukur_dalam_negeri' => $pathsamarinda35,]);
                            }
                }
                if ($request->hasFile('samarindafile36')) {
                    $file1 = $request->file('samarindafile36');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/penerbitan_sertifikat_kapal_baru';
                    $request->file('samarindafile36')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda36 = Storage::path('samarinda/penerbitan_sertifikat_kapal_baru/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status36' => 'on review',
                                'time_upload36' => date("Y-m-d"),
                                'penerbitan_sertifikat_kapal_baru' => $pathsamarinda36,]);
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status36' => 'on review',
                            'time_upload36' => date("Y-m-d"),
                            'penerbitan_sertifikat_kapal_baru' => $pathsamarinda36,]);
                        }
                }
                if ($request->hasFile('samarindafile37')) {
                    $file1 = $request->file('samarindafile37');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/buku_stabilitas';
                    $request->file('samarindafile37')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda37 = Storage::path('samarinda/buku_stabilitas/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status37' => 'on review',
                                'time_upload37' => date("Y-m-d"),
                                'buku_stabilitas' => $pathsamarinda37,]);
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status37' => 'on review',
                            'time_upload37' => date("Y-m-d"),
                            'buku_stabilitas' => $pathsamarinda37,]);
                        }
                }
                if ($request->hasFile('samarindafile38')) {
                    $file1 = $request->file('samarindafile38');
                    $name1 =  $file1->getClientOriginalName() . '-picsite-'. Auth::user()->cabang;
                    $tujuan_upload = 'samarinda/grosse_akta';
                    $request->file('samarindafile38')->storeAs($tujuan_upload, $name1).'.pdf';   
                    $pathsamarinda38 = Storage::path('samarinda/grosse_akta/'.$file1->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                    if(documentsamarinda::where('cabang', 'Samarinda')->exists()){
                        documentsamarinda::where('cabang', 'Samarinda')->update([  
                                'status38' => 'on review',
                                'time_upload38' => date("Y-m-d"),
                                'grosse_akta' => $pathsamarinda38,]);
                    }else{
                        documentsamarinda::create([  
                            'cabang' => Auth::user()->cabang ,
                            'due_time' => "28-" . date("m-Y") ,
                            'user_id' => Auth::user()->id,

                            'status38' => 'on review',
                            'time_upload38' => date("Y-m-d"),
                            'grosse_akta' => $pathsamarinda38,]);
                        }
                }
            return redirect('/picsite/upload')->with('message', 'Upload success!');
        }
        
//email to user
// $details = [
    //     'title' => 'Thank you for receiving this email', 
    //     'body' => 'you are a test subject for the project hehe'
    // ];
    
    // Mail::to(Auth::user()->email)->send(new Gmail($details));

        return view('picsite.upload',compact('document', 'documentberau' , 'documentsamarinda' , 'documentbanjarmasin'));
        // return redirect('picsite/upload')->with('message', 'Upload success!');
    }
    
    public function view(){
        $filename = Auth::user()->name .'-picsite-Babelan-1.pdf';
        $pathbabelan1 = storage::path('babelan/sertifikat_keselamatan/stenli-picsite-1.pdf');

        return Response::make(file_get_contents($pathbabelan1), 200,
         [
            'Content-Type' => 'application//pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"']);
    }
    
}
