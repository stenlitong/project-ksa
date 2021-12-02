<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Storage;
use Response;
use validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\Gmail;
use App\Models\documents;
use App\Models\documentberau;
use App\Models\documentbanjarmasin;
use App\Models\documentrpk;
use App\Models\documentsamarinda;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()->hasRole('crew')){
            // $orders = Order::latest()->Paginate(10);

            // Get all the order within the logged in user
            $orderHeads = OrderHead::where('user_id', Auth::user()->id)->paginate(10);

            // Get the orderDetail from orders_id within the orderHead table 
            $order_id = OrderHead::select('order_id')->where('user_id', Auth::user()->id)->pluck('order_id');
            $orderDetails = OrderDetail::whereIn('orders_id', $order_id)->join('items', 'items.itemName', '=', 'order_details.itemName')->get();

            return view('crew.crewDashboard', compact('orderHeads', 'orderDetails'));
        }elseif(Auth::user()->hasRole('logistic')){
            $orders = Order::where('in_progress', 'not like', '%in_progress(Purchasing)%')->latest()->Paginate(10);
            // $orders = Order::latest()->Paginate(10);
            return view('logistic.logisticDashboard', compact('orders'));
        }elseif(Auth::user()->hasRole('purchasing')){
            return view('purchasing.purchasingDashboard');
        }
        elseif(Auth::user()->hasRole('picSite')){
            return view('picsite.picDashboard');
        }
        elseif(Auth::user()->hasRole('picAdmin')){
            $document = DB::table('documents')->latest()->get();
            return view('picadmin.picAdminDashboard' , compact('document'));
        }
        elseif(Auth::user()->hasRole('picIncident')){
            
            return view('picincident.dashboardincident' );
        }
        elseif(Auth::user()->hasRole('insurance')){
            return view('insurance.insuranceDashboard');
        }
    }
}
