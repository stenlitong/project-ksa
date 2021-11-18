<?php

namespace App\Http\Controllers;
use App\Exports\PurchasingReportExport;
use App\Exports\POExport;
use Illuminate\Http\Request;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\User;
use App\Models\ApList;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Auth;
use Storage;

class PurchasingManagerController extends Controller
{
    public function branchDashboard($branch){
        // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
        $month_now = (int)(date('m'));
        
        if($month_now <= 6){
            $start_date = date('Y-01-01');
            $end_date = date('Y-06-30');
        }else{
            $start_date = date('Y-07-01');
            $end_date = date('Y-12-31');
        }

        $default_branch = $branch;

        // Find order from the logistic role, because purchasing role can only see the order from "logistic/admin logistic" role NOT from "crew" roles
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', $default_branch)->pluck('users.id');
        
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
            ->orWhere('status', 'like', 'Order Rejected By Purchasing Manager')
            ->orWhere('status', 'like', 'Order Rejected By Purchasing');
        })->where('cabang', 'like', $default_branch)->whereBetween('created_at', [$start_date, $end_date])->count();

        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order In Progress By Supervisor')
            ->orWhere('status', 'like', 'Order In Progress By Purchasing')
            ->orWhere('status', 'like', 'Order In Progress By Purchasing Manager')
            ->orWhere('status', 'like', 'Order Delivered By Supplier');
        })->where('cabang', 'like', $default_branch)->whereBetween('created_at', [$start_date, $end_date])->count();

        // Get all the suppliers
        $suppliers = Supplier::latest()->get();

        return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'completed', 'in_progress', 'default_branch'));
    }

    public function editSupplier(Request $request, Supplier $suppliers){
        // Find the supplier id, then edit the ratings
        Supplier::find($suppliers->id)->update([
            'quality' => $request -> quality,
            'top' => $request -> top,
            'price' => $request -> price,
            'deliveryTime' => $request -> deliveryTime,
            'availability' => $request -> availability,
        ]);

        return redirect('/dashboard')->with('statusA', 'Edited Successfully');
    }

    public function approveOrderPage(OrderHead $orderHeads){
        // Find the order detail of the following order
        $orderDetails = OrderDetail::with('item')->where('orders_id', $orderHeads->id)->get();

        return view('purchasingManager.purchasingManagerApprovedPage', compact('orderHeads', 'orderDetails'));
    }

    public function approveOrder(OrderHead $orderHeads){
        // We are not validating anything because we won't change/input anything to the database, Purchasing Manager only sees the order and decide if he/she approve it or not
        // Check if the order already been processed or not using order tracker
        if($orderHeads -> order_tracker == 5){
            return redirect('/purchasing-manager/order/' . $orderHeads -> id . '/approve')->with('error', 'Order Already Been Processed');
        }

        OrderHead::find($orderHeads->id)->update([
            'order_tracker' => 5,
            'status' => 'Item Delivered By Supplier'
        ]);

        return redirect('/dashboard')->with('statusB', 'Order Approved By Purchasing Manager');
    }

    public function rejectOrder (OrderHead $orderHeads){
        // We are not validating anything because we won't change/input anything to the database, Purchasing Manager only sees the order and decide if he/she approve it or not
        // Check if the order already been processed or not using order tracker
        if($orderHeads -> order_tracker == 5){
            return redirect('/purchasing-manager/order/' . $orderHeads -> id . '/approve')->with('error', 'Order Already Been Processed');
        }

        
    }
}
