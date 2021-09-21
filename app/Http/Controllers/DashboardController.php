<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderHead;
use App\Models\OrderDetail;
Use \Carbon\Carbon;

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
            // $orders = Order::where('in_progress', 'not like', '%in_progress(Purchasing)%')->latest()->Paginate(10);
            // $orders = Order::latest()->Paginate(10);

            // Get the latest 30 days using Carbon package => still in testing
            // Get all the order
            $orderHeads = OrderHead::where('created_at', '>=', Carbon::now()->subDays(30))->paginate(10);
            
            // Get all the order detail

            return view('logistic.logisticDashboard', compact('orderHeads'));
        }elseif(Auth::user()->hasRole('purchasing')){
            return view('purchasing.purchasingDashboard');
        }
    }
}
