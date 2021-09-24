<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderHead;
use App\Models\OrderDetail;
Use \Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()->hasRole('crew')){
            // $orders = Order::latest()->Paginate(10);

            // Get all the order within the logged in user within 30 days from date now
            $orderHeads = OrderHead::with('user')->where('user_id', 'like', Auth::user()->id, 'and', 'created_at', '>=', Carbon::now()->subDays(30))->paginate(10);

            // Get the orderDetail from orders_id within the orderHead table 
            $order_id = OrderHead::select('order_id')->where('user_id', Auth::user()->id)->pluck('order_id');
            $orderDetails = OrderDetail::whereIn('orders_id', $order_id)->join('items', 'items.itemName', '=', 'order_details.itemName')->get();

            return view('crew.crewDashboard', compact('orderHeads', 'orderDetails'));
        }elseif(Auth::user()->hasRole('logistic')){

            // Get the latest 30 days using Carbon package => still in testing
            if(request('search')){
                $orderHeads = OrderHead::with('user')->where('status', 'like', '%'. request('search') .'%', 'and', 'created_at', '>=', Carbon::now()->subDays(30))->orWhere('order_id', 'like', '%'. request('search') .'%', 'and', 'created_at', '>=', Carbon::now()->subDays(30))->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

            }else{
                $orderHeads = OrderHead::with('user')->where('created_at', '>=', Carbon::now()->subDays(30))->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            }
            // $orderHeads = OrderHead::where('status', 'like', '%In Progress%', 'or','created_at', '>=', Carbon::now()->subDays(30))->orderBy('created_at', 'desc')->paginate(10);

            // Get all the order detail
            $order_id = OrderHead::select('order_id')->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
            $orderDetails = OrderDetail::whereIn('orders_id', $order_id)->join('items', 'items.itemName', '=', 'order_details.itemName')->get();

            return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails'));
        }elseif(Auth::user()->hasRole('purchasing')){
            return view('purchasing.purchasingDashboard');
        }
    }
}
