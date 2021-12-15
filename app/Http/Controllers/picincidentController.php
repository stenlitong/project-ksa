<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Storage;
use Response;
use validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Gmail;
use App\Models\User;
use App\Models\spgrfile;
use App\Models\formclaims;


class picincidentController extends Controller
{
    public function formclaim(){
        $claims = formclaims::with('user')->where('id', '>=' , 1)->latest()->get();
        return view('picincident.formclaim' , compact('claims'));
    }

    public function submitformclaim(Request $request){

        $request->validate([
            'reasonbox' => 'required|max:255',
        ]);

        formclaims::add([
            'user_id' => Auth::user()->id,
            'tgl_insiden' => $request->dateincident ,
            'tgl_formclaim' => $request->dateclaim , 
            'name'=> $request->name ,
            'jenis_incident'=> $request->jenisincident ,
            'item' => $request->Item_name ,
            'no_FormClaim'=> $request->FormClaim , 
            'TOW'=> $request->TOW ,
            'total_sum_insurade'=> $request->TotalSumInsurade,
            'deductible'=>$request->Deductible ,
            'amount'=> $request->Amount,
            'surveyor'=> $request->Surveyor,
            'tugBoat'=> $request->TugBoat,
            'incident'=> $request->Incident ,
            'description'=> $request->reasonbox ,
        ]);
        
        return redirect('/picincident/formclaim');
    }

// public function addToCart(Request $request)
    // {
    //     Cart::add([
    //         'id' => $request->id,
    //         'name' => $request->name,
    //         'price' => $request->price,
    //         'quantity' => $request->quantity,
    //         'attributes' => array(
    //             'image' => $request->image,
    //         )
    //     ]);
    //     session()->flash('success', 'Product is Added to Cart Successfully !');

    //     return redirect()->route('cart.list');
    // }

// public function updateCart(Request $request)
    // {
    //     Cart::update(
    //         $request->id,
    //         [
    //             'quantity' => [
    //                 'relative' => false,
    //                 'value' => $request->quantity
    //             ],
    //         ]
    //     );

    //     session()->flash('success', 'Item Cart is Updated Successfully !');

    //     return redirect()->route('cart.list');
    // }

// public function removeCart(Request $request)
    // {
    //     Cart::remove($request->id);
    //     session()->flash('success', 'Item Cart Remove Successfully !');

    //     return redirect()->route('cart.list');
    // }

// public function clearAllCart()
    // {
    //     Cart::clear();

    //     session()->flash('success', 'All Item Cart Clear Successfully !');

    //     return redirect()->route('cart.list');
    // }


    public function formclaimhistory() {

        return view('picincident.historyFCI');
    }

    public function destroy(formclaims $claim){
        formclaims::destroy($claim->id); 
        return redirect('/picincident/formclaim')->with('success', 'post telah dihapus.'); 
    }

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
                    'lot_line' => basename($path),]);
                }else{
                spgrfile::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,
                    'status6' => 'on review',
                    'time_upload6' => date("Y-m-d"),
                    'lot_line' => basename($path),
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
