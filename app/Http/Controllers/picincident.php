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

class picincident extends Controller
{
    public function formclaim(Request $request){

        return view('picincident.formclaim');
    }

    public function spgr(Request $request){
        return view('picincident.spgr');
    }


}
