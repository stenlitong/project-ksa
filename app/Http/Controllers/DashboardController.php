<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Role;
Use \Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()->hasRole('crew')){
            // Get all the order within the logged in user within 30 days from date now
            $orderHeads = OrderHead::with('user')->where('user_id', 'like', Auth::user()->id, 'and', 'created_at', '>=', Carbon::now()->subDays(30))->paginate(10);

            // Get the orderDetail from orders_id within the orderHead table 
            $order_id = OrderHead::select('order_id')->where('user_id', Auth::user()->id)->pluck('order_id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            return view('crew.crewDashboard', compact('orderHeads', 'orderDetails'));
        }elseif(Auth::user()->hasRole('logistic') or Auth::user()->hasRole('adminLogistic')){

            // Get the latest 30 days using Carbon package => still in testing
            if(Auth::user()->hasRole('adminLogistic')){
                if(request('search')){
                    $orderHeads = OrderHead::with('user')->where('status', 'like', '%'. request('search') .'%', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->orWhere( 'order_id', 'like', '%'. request('search') .'%', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->orWhere( 'cabang', 'like', '%'. request('search') .'%', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.created_at', 'desc')->paginate(10)->withQueryString();
                }else{
                    $orderHeads = OrderHead::with('user')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.created_at', 'desc')->paginate(10)->withQueryString();
                }
            }else{
                if(request('search')){
                    $orderHeads = OrderHead::with('user')->where('status', 'like', '%'. request('search') .'%', 'and', 'cabang', 'like', Auth::user()->cabang, 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->orWhere( 'order_id', 'like', '%'. request('search') .'%', 'and', 'cabang', 'like', Auth::user()->cabang, 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.created_at', 'desc')->paginate(10)->withQueryString();
                }else{
                    $orderHeads = OrderHead::with('user')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.created_at', 'desc')->paginate(10)->withQueryString();
                }
            }

            // Get all the order detail
            $order_id = OrderHead::select('order_id')->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Get user's role
            // $user_id = OrderHead::select('user_id')->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('user_id');
            // $roles = Role::join('role_user', 'role_user.role_id' , '=', 'roles.id')->whereIn('role_user.user_id', $user_id)->get();

            return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails'));
        }elseif(Auth::user()->hasRole('purchasing')){

            return view('purchasing.purchasingDashboard');
        }
    }
}
