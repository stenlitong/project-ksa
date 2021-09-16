<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()->hasRole('crew')){
            return view('crew.crewDashboard');
        }elseif(Auth::user()->hasRole('logistic')){
            return view('logistic.logisticDashboard');
        }elseif(Auth::user()->hasRole('purchasing')){
            return view('purchasing.purchasingDashboard');
        }
    }
}
