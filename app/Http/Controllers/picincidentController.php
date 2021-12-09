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

        formclaims::create([
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
            $year = date('Y');
            $month = date('m');

            $path = $request->file('banjarmasinfile1')->storeas('banjarmasin/'. $year . "/". $month , $name1, 's3');
            if (documentbanjarmasin::where('cabang', 'Banjarmasin')->whereMonth('created_at', date('m'))->exists()){
                    Storage::disk('s3')->delete($path."/".$name1);
                    documentbanjarmasin::where('cabang', 'Banjarmasin' )->update([
                    'status1' => 'on review',
                    'time_upload1' => date("Y-m-d"),
                    'perjalanan' => basename($path),]);
                }else{
                    documentbanjarmasin::create([
                    'cabang' => Auth::user()->cabang ,
                    'user_id' => Auth::user()->id,

                    'status1' => 'on review',
                    'time_upload1' => date("Y-m-d"),
                    'perjalanan' => basename($path),
                ]);
            }
        return view('picincident.spgr');
    }


}
