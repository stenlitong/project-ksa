<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()->hasRole('crew')){
            // $orders = Order::all();
            $orders = Order::orderBy('created_at', 'desc')->get();
            return view('crew.crewDashboard', compact('orders'));
        }elseif(Auth::user()->hasRole('logistic')){
            // $orders = Order::all();
            $orders = Order::orderBy('created_at', 'desc')->get();
            return view('logistic.logisticDashboard', compact('orders'));
        }elseif(Auth::user()->hasRole('purchasing')){
            return view('purchasing.purchasingDashboard');
        }
    }
}
