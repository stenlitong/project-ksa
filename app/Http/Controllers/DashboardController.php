<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Supplier;
// Use \Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(){
        if(Auth::user()->hasRole('crew')){
            // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
            $month_now = (int)(date('m'));
            if($month_now <= 6){
                $start_date = date('Y-01-01');
                $end_date = date('Y-06-30');
            }else{
                $start_date = date('Y-07-01');
                $end_date = date('Y-12-31');
            }

            // Get all the order within the logged in user within 6 month
            $orderHeads = OrderHead::with('user')->where('user_id', 'like', Auth::user()->id)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(8);

            // Get the orderDetail from orders_id within the orderHead table 
            // $order_id = OrderHead::where('user_id', Auth::user()->id)->pluck('order_id');
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Request Completed (Crew)')
                ->orWhere('status', 'like', 'Request Rejected By Logistic');
            })->where('user_id', 'like', Auth::user()->id)->whereBetween('created_at', [$start_date, $end_date])->count();
            
            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', 'Request In Progress By Logistic')
                ->orWhere('status', 'like', 'Items Ready')
                ->orWhere('status', 'like', 'On Delivery');
            })->where('user_id', 'like', Auth::user()->id)->whereBetween('created_at', [$start_date, $end_date])->count();

            return view('crew.crewDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));

        }elseif(Auth::user()->hasRole('logistic')){
            // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
            $month_now = (int)(date('m'));
            if($month_now <= 6){
                $start_date = date('Y-01-01');
                $end_date = date('Y-06-30');
            }else{
                $start_date = date('Y-07-01');
                $end_date = date('Y-12-31');
            }

            // Search functonality
            if(request('search')){
                $orderHeads = OrderHead::with('user')->where(function($query){
                    $query->where('status', 'like', '%'. request('search') .'%')
                    ->orWhere( 'order_id', 'like', '%'. request('search') .'%');
                })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(7)->withQueryString();
                //->whereBetween('created_at', [$start_date, $end_date])
            }else{
                $orderHeads = OrderHead::with('user')->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(7)->withQueryString();
            }

            // Get all the order detail
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'Completed' . '%')
                ->orWhere('status', 'like', '%' . 'Rejected' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();
            
            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress' . '%')
                ->orWhere('status', 'like', 'Items Ready')
                ->orWhere('status', 'like', 'On Delivery')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));

        }elseif(Auth::user()->hasRole('supervisor') or Auth::user()->hasRole('supervisorMaster')){
            // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
            $month_now = (int)(date('m'));
            if($month_now <= 6){
                $start_date = date('Y-01-01');
                $end_date = date('Y-06-30');
            }else{
                $start_date = date('Y-07-01');
                $end_date = date('Y-12-31');
            }

            // Find order from logistic role, then they can approve and send it to the purchasing role
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');

            if(request('search')){
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                    $query->where('status', 'like', '%'. request('search') .'%')
                    ->orWhere('order_id', 'like', '%'. request('search') .'%');
                })->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(6);
            }else{
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(6)->withQueryString();
            }

            // Then find all the order details from the orderHeads
            // $order_id = OrderHead::whereIn('user_id', $users)->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            return view('supervisor.supervisorDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));

        }elseif(Auth::user()->hasRole('purchasing')){
            // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
            $month_now = (int)(date('m'));
            if($month_now <= 6){
                $start_date = date('Y-01-01');
                $end_date = date('Y-06-30');
            }else{
                $start_date = date('Y-07-01');
                $end_date = date('Y-12-31');
            }

            // Find order from the logistic role, because purchasing role can only see the order from "logistic/admin logistic" role NOT from "crew" roles
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
            
            if(request('search')){
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                    $query->where('status', 'like', '%'. request('search') .'%')
                    ->orWhere('order_id', 'like', '%'. request('search') .'%');
                })->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(6);
            }else{
                $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(6)->withQueryString();
            }

            // Then find all the order details from the orderHeads
            // $order_id = OrderHead::whereIn('user_id', $users)->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
            $order_id = $orderHeads->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            // Get all the suppliers
            $suppliers = Supplier::latest()->get();

            return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'completed', 'in_progress'));

        }elseif(Auth::user()->hasRole('adminPurchasing')){
            $suppliers = Supplier::latest()->get();

            return view('adminPurchasing.adminPurchasingDashboard', compact('suppliers'));
        }
    }
}
