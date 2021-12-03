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

        
    public function destroy(formclaim $claim){
        
        formclaims::destroy($claim->id); 
        return redirect('/picincident/formclaim')->with('success', 'post telah dihapus.'); 
    }


    public function spgr(){
        return view('picincident.spgr');
    }


}
