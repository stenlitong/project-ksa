<?php

namespace App\Http\Controllers;

use Storage;
use Response;
use validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use App\Mail\Gmail;
use App\Models\documentrpk;
use App\Models\User;

class PicRpkController extends Controller
{
    public function rpk(){
        $docrpk = documentrpk::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();
        return view('picsite.rpk' , compact('docrpk'));
    }

    public function downloadrpk(){
        $path = Storage::path('SuratBarang/stenli-picsiteRPK-1.pdf' );
        return Response::download($path, 'stenli-picsiteRPK-1.pdf');
    }

    public function uploadrpk(Request $request){

        $docrpk = documentrpk::with('user')->where('cabang',Auth::user()->cabang)->latest()->get();

        if(Auth::user()->cabang == 'Babelan'){ 
            if ($request->hasFile('rfile1')) {
                //dd($request);
                $file = $request->file('rfile1');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/surat_barang';
                $pathrpk1 = Storage::path('RPK/surat_barang/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                $request->file('rfile1')->storeAs($tujuan_upload, $name.'.pdf');             
                
                DB::table('rpkdocuments')->insert([
                    'user_id' => Auth::user()->id,
                    'cabang' => Auth::user()->cabang ,
                    'Due_time' => "28-" . date("m-Y"),
                    
                    'status1' => 'on review',
                    'surat_barang' => $pathrpk1,
                    'time_upload1' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('rfile2')){
                $file = $request->file('rfile2');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/cargo_manifest';
                $pathrpk2 = Storage::path('RPK/cargo_manifest/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('rfile2')->storeAs($tujuan_upload, $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Babelan' )->update([
                        
                    'status2' => 'on review',
                    'cargo_manifest'=> $pathrpk2 ,
                    'time_upload2' => date("Y-m-d"),
                ]);
            }      
            if ($request->hasFile('rfile3')){
                $file = $request->file('rfile3');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/voyage';
                $pathrpk3 = Storage::path('RPK/voyage/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('rfile3')->storeAs($tujuan_upload, $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Babelan' )->update([
                  
                    'status3' => 'on review',
                    'voyage' => $pathrpk3,
                    'time_upload3' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('rfile4')){
                $file = $request->file('rfile4');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/bill_lading';
                $pathrpk4 = Storage::path('RPK/bill_lading/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('rfile4')->storeAs($tujuan_upload , $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Babelan' )->update([
                                                             
                    'status4' => 'on review',
                    'bill_lading' => $pathrpk4,
                    'time_upload4' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('rfile5')){
                $file = $request->file('rfile5');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/gerak_kapal';
                $pathrpk5 = Storage::path('RPK/gerak_kapal/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('rfile5')->storeAs($tujuan_upload , $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Babelan' )->update([                   
                    
                    'status5' => 'on review',
                    'gerak_kapal'=> $pathrpk5 ,
                    'time_upload5' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('rfile6')){
                $file = $request->file('rfile6');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/docking';
                $pathrpk6 = Storage::path('RPK/docking/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('rfile6')->storeAs($tujuan_upload , $name.'.pdf');
                DB::table('rpkdocuments')->where('cabang', 'Babelan' )->update([                
                    
                    'status6' => 'on review',
                    'docking' => $pathrpk6,
                    'time_upload6' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('rfile7')){
                $file = $request->file('rfile7');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/surat_kapal';
                $pathrpk7 = Storage::path('RPK/surat_kapal/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('rfile7')->storeAs($tujuan_upload , $name.'.pdf');
                            
                DB::table('rpkdocuments')->where('cabang', 'Babelan' )->update([
                    //babelan                   
                    'status7' => 'on review',
                    'time_upload7' => date("Y-m-d"),
                    'surat_kapal' => $pathrpk7,
                ]);
            }
        }

        if(Auth::user()->cabang == 'Berau'){
            if ($request->hasFile('brfile1')) {
                //dd($request);
                $file = $request->file('brfile1');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/surat_barang';
                $pathrpk1 = Storage::path('RPK/surat_barang/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                $request->file('brfile1')->storeAs($tujuan_upload, $name.'.pdf');             
                
                DB::table('rpkdocuments')->insert([
                    'user_id' => Auth::user()->id,
                    'cabang' => Auth::user()->cabang ,
                    'Due_time' => "28-" . date("m-Y"),
                    
                    'status1' => 'on review',
                    'surat_barang' => $pathrpk1,
                    'time_upload1' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('brfile2')){
                $file = $request->file('brfile2');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/cargo_manifest';
                $pathrpk2 = Storage::path('RPK/cargo_manifest/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('brfile2')->storeAs($tujuan_upload, $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Berau' )->update([
                        
                    'status2' => 'on review',
                    'cargo_manifest'=> $pathrpk2 ,
                    'time_upload2' => date("Y-m-d"),
                ]);
            }      
            if ($request->hasFile('brfile3')){
                $file = $request->file('brfile3');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/voyage';
                $pathrpk3 = Storage::path('RPK/voyage/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('brfile3')->storeAs($tujuan_upload, $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Berau' )->update([
                  
                    'status3' => 'on review',
                    'voyage' => $pathrpk3,
                    'time_upload3' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('brfile4')){
                $file = $request->file('brfile4');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/bill_lading';
                $pathrpk4 = Storage::path('RPK/bill_lading/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('brfile4')->storeAs($tujuan_upload , $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Berau' )->update([
                                                            
                    'status4' => 'on review',
                    'bill_lading' => $pathrpk4,
                    'time_upload4' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('brfile5')){
                $file = $request->file('brfile5');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/gerak_kapal';
                $pathrpk5 = Storage::path('RPK/gerak_kapal/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('brfile5')->storeAs($tujuan_upload , $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Berau' )->update([                   
                    
                    'status5' => 'on review',
                    'gerak_kapal'=> $pathrpk5 ,
                    'time_upload5' => date("Y-m-d"),

                ]);
            }
            if ($request->hasFile('brfile6')){
                $file = $request->file('brfile6');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/docking';
                $pathrpk6 = Storage::path('RPK/docking/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('brfile6')->storeAs($tujuan_upload , $name.'.pdf');
                DB::table('rpkdocuments')->where('cabang', 'Berau' )->update([                
                    
                    'status6' => 'on review',
                    'docking' => $pathrpk6,
                    'time_upload6' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('brfile7')){
                $file = $request->file('brfile7');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/surat_kapal';
                $pathrpk7 = Storage::path('RPK/surat_kapal/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('brfile7')->storeAs($tujuan_upload , $name.'.pdf');
                            
                DB::table('rpkdocuments')->where('cabang', 'Berau' )->update([
                                      
                    'status7' => 'on review',
                    'time_upload7' => date("Y-m-d"),
                    'surat_kapal' => $pathrpk7,
                ]);
            }
        }

        if(Auth::user()->cabang == 'Banjarmasin'){
            if ($request->hasFile('bjrfile1')) {
                //dd($request);
                $file = $request->file('bjrfile1');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/surat_barang';
                $pathrpk1 = Storage::path('RPK/surat_barang/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                $request->file('bjrfile1')->storeAs($tujuan_upload, $name.'.pdf');             
                
                DB::table('rpkdocuments')->insert([
                    'user_id' => Auth::user()->id,
                    'cabang' => Auth::user()->cabang ,
                    'Due_time' => "28-" . date("m-Y"),
                    
                    'status1' => 'on review',
                    'surat_barang' => $pathrpk1,
                    'time_upload1' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('bjrfile2')){
                $file = $request->file('bjrfile2');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/cargo_manifest';
                $pathrpk2 = Storage::path('RPK/cargo_manifest/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('bjrfile2')->storeAs($tujuan_upload, $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Banjarmasin' )->update([
                        
                    'status2' => 'on review',
                    'cargo_manifest'=> $pathrpk2 ,
                    'time_upload2' => date("Y-m-d"),
                ]);
            }      
            if ($request->hasFile('bjrfile3')){
                $file = $request->file('bjrfile3');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/voyage';
                $pathrpk3 = Storage::path('RPK/voyage/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('bjrfile3')->storeAs($tujuan_upload, $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Banjarmasin' )->update([
                  
                    'status3' => 'on review',
                    'voyage' => $pathrpk3,
                    'time_upload3' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('bjrfile4')){
                $file = $request->file('bjrfile4');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/bill_lading';
                $pathrpk4 = Storage::path('RPK/bill_lading/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('bjrfile4')->storeAs($tujuan_upload , $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Banjarmasin' )->update([
                                                            
                    'status4' => 'on review',
                    'bill_lading' => $pathrpk4,
                    'time_upload4' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('bjrfile5')){
                $file = $request->file('bjrfile5');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/gerak_kapal';
                $pathrpk5 = Storage::path('RPK/gerak_kapal/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('bjrfile5')->storeAs($tujuan_upload , $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Banjarmasin' )->update([                   
                    
                    'status5' => 'on review',
                    'gerak_kapal'=> $pathrpk5 ,
                    'time_upload5' => date("Y-m-d"),

                ]);
            }
            if ($request->hasFile('bjrfile6')){
                $file = $request->file('bjrfile6');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/docking';
                $pathrpk6 = Storage::path('RPK/docking/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('bjrfile6')->storeAs($tujuan_upload , $name.'.pdf');
                DB::table('rpkdocuments')->where('cabang', 'Banjarmasin' )->update([                
                    
                    'status6' => 'on review',
                    'docking' => $pathrpk6,
                    'time_upload6' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('bjrfile7')){
                $file = $request->file('bjrfile7');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/surat_kapal';
                $pathrpk7 = Storage::path('RPK/surat_kapal/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('bjrfile7')->storeAs($tujuan_upload , $name.'.pdf');
                            
                DB::table('rpkdocuments')->where('cabang', 'Banjarmasin' )->update([
                                       
                    'status7' => 'on review',
                    'time_upload7' => date("Y-m-d"),
                    'surat_kapal' => $pathrpk7,
                ]);
            }
        }

        if(Auth::user()->cabang == 'Samarinda'){
            if ($request->hasFile('smrfile1')) {
                //dd($request);
                $file = $request->file('smrfile1');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/surat_barang';
                $pathrpk1 = Storage::path('RPK/surat_barang/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );
                $request->file('smrfile1')->storeAs($tujuan_upload, $name.'.pdf');             
                
                DB::table('rpkdocuments')->insert([
                    'user_id' => Auth::user()->id,
                    'cabang' => Auth::user()->cabang ,
                    'Due_time' => "28-" . date("m-Y"),
                    
                    'status1' => 'on review',
                    'surat_barang' => $pathrpk1,
                    'time_upload1' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('smrfile2')){
                $file = $request->file('smrfile2');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/cargo_manifest';
                $pathrpk2 = Storage::path('RPK/cargo_manifest/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('smrfile2')->storeAs($tujuan_upload, $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Samarinda' )->update([
                        
                    'status2' => 'on review',
                    'cargo_manifest'=> $pathrpk2 ,
                    'time_upload2' => date("Y-m-d"),
                ]);
            }      
            if ($request->hasFile('smrfile3')){
                $file = $request->file('smrfile3');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/voyage';
                $pathrpk3 = Storage::path('RPK/voyage/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('smrfile3')->storeAs($tujuan_upload, $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Samarinda' )->update([
                  
                    'status3' => 'on review',
                    'voyage' => $pathrpk3,
                    'time_upload3' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('smrfile4')){
                $file = $request->file('smrfile4');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/bill_lading';
                $pathrpk4 = Storage::path('RPK/bill_lading/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('smrfile4')->storeAs($tujuan_upload , $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Samarinda' )->update([
                                                            
                    'status4' => 'on review',
                    'bill_lading' => $pathrpk4,
                    'time_upload4' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('smrfile5')){
                $file = $request->file('smrfile5');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/gerak_kapal';
                $pathrpk5 = Storage::path('RPK/gerak_kapal/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('smrfile5')->storeAs($tujuan_upload , $name.'.pdf');
                
                DB::table('rpkdocuments')->where('cabang', 'Samarinda' )->update([                   
                    
                    'status5' => 'on review',
                    'gerak_kapal'=> $pathrpk5 ,
                    'time_upload5' => date("Y-m-d"),

                ]);
            }
            if ($request->hasFile('smrfile6')){
                $file = $request->file('smrfile6');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/docking';
                $pathrpk6 = Storage::path('RPK/docking/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('smrfile6')->storeAs($tujuan_upload , $name.'.pdf');
                DB::table('rpkdocuments')->where('cabang', 'Samarinda' )->update([                
                    
                    'status6' => 'on review',
                    'docking' => $pathrpk6,
                    'time_upload6' => date("Y-m-d"),
                ]);
            }
            if ($request->hasFile('smrfile7')){
                $file = $request->file('smrfile7');
                $name =  $file->getClientOriginalName(). '-picsite-'. Auth::user()->cabang;
                $tujuan_upload = 'RPK/surat_kapal';
                $pathrpk7 = Storage::path('RPK/surat_kapal/'.$file->getClientOriginalName() . '-picsite-' . Auth::user()->cabang );   
                $request->file('smrfile7')->storeAs($tujuan_upload , $name.'.pdf');
                            
                DB::table('rpkdocuments')->where('cabang', 'Samarinda' )->update([
                    //babelan                   
                    'status7' => 'on review',
                    'time_upload7' => date("Y-m-d"),
                    'surat_kapal' => $pathrpk7,
                ]);
            }
        }

        return redirect('picsite/rpk');
    }

    public function viewrpk(){
        $filename = 'stenli-picsite-1.pdf';
        $path = storage::path('Dana/stenli-picsite-1.pdf');

        return Response::make(file_get_contents($path), 200,
         [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"']);
    }
}
