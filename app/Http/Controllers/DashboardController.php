<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()->hasRole('crew')){
            $orders = Order::all();

            return view('crew.crewDashboard', compact('orders'));
        }elseif(Auth::user()->hasRole('logistic')){
            return view('logistic.logisticDashboard');
        }elseif(Auth::user()->hasRole('purchasing')){
            return view('purchasing.purchasingDashboard');
        }
    }
}
