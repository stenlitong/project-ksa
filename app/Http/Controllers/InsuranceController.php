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
use App\Models\NoteSpgr;

class InsuranceController extends Controller
{
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
    
    public function historynotespgr(){
        $UploadNotes = NoteSpgr::whereMonth('created_at', date('m'))->latest()->get();
        return view('insurance.insuranceHistoryNotes', compact('UploadNotes'));
    }
}
