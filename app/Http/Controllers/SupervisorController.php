<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\User;
use App\Exports\OrderOutExport;
use App\Exports\OrderInExport;
use App\Exports\PRExport;
use App\Exports\DOExport;
use App\Exports\PurchasingReportExport;
use App\Models\OrderDo;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Auth;
// Use \Carbon\Carbon;

class SupervisorController extends Controller
{
    public function completedOrder(){
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

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->whereBetween('created_at', [$start_date, $end_date])->pluck('id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
            ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
            ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
        })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

        if(request('search')){
            $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                $query->where('status', 'like', '%'. request('search') .'%')
                ->orWhere('order_id', 'like', '%'. request('search') .'%');
            })->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(8);

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            return view('supervisor.supervisorDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
        }else{
            $orderHeads = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(8);
    
            $completed = $orderHeads->count();
    
            return view('supervisor.supervisorDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
        }
    }

    public function inProgressOrder(){
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

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->whereBetween('created_at', [$start_date, $end_date])->pluck('id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        // Count the completed & in progress order
        $completed = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order Completed (Logistic)')
            ->orWhere('status', 'like', 'Order Rejected By Supervisor')
            ->orWhere('status', 'like', 'Order Rejected By Purchasing');
        })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

        if(request('search')){
            $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                $query->where('status', 'like', '%'. request('search') .'%')
                ->orWhere('order_id', 'like', '%'. request('search') .'%');
            })->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(8);

            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            return view('supervisor.supervisorDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
        }else{
            $orderHeads =  OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(10);
    
            $in_progress = $orderHeads->count();
    
            return view('supervisor.supervisorDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
        }
    }

    public function approveOrder(OrderHead $orderHeads){
        // Check if already been processed or not
        if($orderHeads -> order_tracker == 3){
            return redirect('/dashboard')->with('error', 'Order Already Been Processed');
        }

        // If they approve the order, then change the status of the order into purchasing
        OrderHead::find($orderHeads->id)->update([
            'order_tracker' => 3,
            'status' => 'Order In Progress By Purchasing'
        ]);

        return redirect('/dashboard')->with('status', 'Order Approved');
    }

    public function rejectOrder(Request $request, OrderHead $orderHeads){
        // Reject the order, required valid reason
        $request->validate([
            'reason' => 'required'
        ]);

        if($orderHeads -> order_tracker == 3){
            return redirect('/dashboard')->with('error', 'Order Already Been Processed');
        }

        // Then update the status as well as the reason
        OrderHead::find($orderHeads->id)->update([
            'status' => 'Order Rejected By Supervisor',
            'order_tracker' => 3,
            'reason' => $request -> reason
        ]);

        return redirect('/dashboard')->with('status', 'Order Rejected');
    }

    public function downloadPr(OrderHead $orderHeads){
        // Find the order id then, return download        
        return (new PRExport($orderHeads -> order_id))->download('PR-' . $orderHeads -> order_id . '_' .  date("d-m-Y") . '.pdf');
    }

    public function reportsPage(){
        // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
        $month_now = (int)(date('m'));
        if($month_now <= 6){
            $start_date = date('Y-01-01');
            $end_date = date('Y-06-30');
        }else{
            $start_date = date('Y-07-01');
            $end_date = date('Y-12-31');
        }

        // Find order from user/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
        
        // Find all the items that has been approved from the logistic | last 6 month
        $orderHeads = OrderHead::with('supplier')->whereIn('user_id', $users)->where('status', 'like', 'Order Completed (Logistic)')->whereBetween('created_at', [$start_date, $end_date])->where('cabang', 'like', Auth::user()->cabang)->orderBy('order_heads.updated_at', 'desc')->get();

        return view('supervisor.supervisorReport', compact('orderHeads'));
    }

    public function downloadReport(Excel $excel){
        return $excel -> download(new PurchasingReportExport, 'Reports_'. date("d-m-Y") . '.xlsx');
    }

    public function historyOut(){
        // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
        $month_now = (int)(date('m'));
        if($month_now <= 6){
            $start_date = date('Y-01-01');
            $end_date = date('Y-06-30');
        }else{
            $start_date = date('Y-07-01');
            $end_date = date('Y-12-31');
        }

        // Find order from crew role/goods out
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '2')->pluck('users.id');
        
        // Find all the items that has been approved/completed from the user feedback | last 6 month
        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->where('cabang', 'like', Auth::user()->cabang,)->where('status', 'like', '%' . 'Completed' . '%')->whereBetween('order_heads.created_at', [$start_date, $end_date])->orderBy('order_details.created_at', 'desc')->get();

        return view('supervisor.supervisorHistoryOut', compact('orderHeads'));
    }

    public function historyIn(){
        // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
        $month_now = (int)(date('m'));
        if($month_now <= 6){
            $start_date = date('Y-01-01');
            $end_date = date('Y-06-30');
        }else{
            $start_date = date('Y-07-01');
            $end_date = date('Y-12-31');
        }

        // Find order from logistic role/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->pluck('users.id');
        
        // Find all the items that has been approved from the user | last 6 month
        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.id', '=', 'order_details.orders_id')->join('suppliers', 'suppliers.id', '=', 'order_heads.supplier_id')->whereIn('user_id', $users)->where('cabang', 'like', Auth::user()->cabang,)->where('status', 'like', '%' . 'Completed'. '%')->whereBetween('order_heads.created_at', [$start_date, $end_date])->orderBy('order_heads.updated_at', 'desc')->get();

        return view('supervisor.supervisorHistoryIn', compact('orderHeads'));
    }

    public function downloadOut(Excel $excel){
        // Export the data of history goods out
        return $excel -> download(new OrderOutExport, 'OrderGoodsOut_'. date("d-m-Y") . '.xlsx');
    }

    public function downloadIn(Excel $excel){
        // Export the data of history goods in
        return $excel -> download(new OrderInExport, 'OrderGoodsIn_'. date("d-m-Y") . '.xlsx');
    }

    public function itemStock(){
        // Check the stocks of all branches
        if(request('search')){
            $items = Item::where(function($query){
                $query->where('itemName', 'like', '%' . request('search') . '%')
                ->orWhere('cabang', 'like', '%' . request('search') . '%')
                ->orWhere('codeMasterItem', 'like', '%' . request('search') . '%');
            })->Paginate(10)->withQueryString();
            return view('supervisor.supervisorItemStock', compact('items'));
        }else{
            $items = Item::orderBy('cabang')->Paginate(7)->withQueryString();
            // $items = Item::latest()->Paginate(10)->withQueryString();
            return view('supervisor.supervisorItemStock', compact('items'));
        }
    }

    public function addItemStock(Request $request){
         // Storing the item to the stock
         $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric|min:1',
            'umur' => 'required',
            'itemStock' => 'required|numeric|min:1',
            'unit' => 'required',
            'itemPrice' => 'required|min:1|numeric',
            'golongan' => 'required',
            'serialNo' => 'nullable',
            'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
            'cabang' => 'required',
            'description' => 'nullable'
        ]);

        // Formatting the item age
        $new_itemAge = $request->itemAge . ' ' . $request->umur;
        
        // Formatting the item price
        $new_itemPrice = 'Rp. ' . $request->itemPrice;

        // Create the item
        Item::create([
            'itemName' => $request -> itemName,
            'itemAge' => $new_itemAge,
            'itemStock' => $request -> itemStock,
            'unit' => $request -> unit,
            'itemPrice' => $new_itemPrice,
            'golongan' => $request -> golongan,
            'serialNo' => $request -> serialNo,
            'codeMasterItem' => $request -> codeMasterItem,
            'cabang' => $request->cabang,
            'description' => $request -> description
        ]);

        return redirect('/supervisor/item-stocks')->with('status', 'Added Successfully');
    }

    public function deleteItemStock(Item $item){
        Item::destroy($item->id);

        return redirect('/supervisor/item-stocks')->with('status', 'Deleted Successfully');
    }

    public function editItemStock(Request $request, Item $item){
        // Edit the requested item
        $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric|min:1',
            'umur' => 'required',
            'itemStock' => 'required|numeric|min:1',
            'unit' => 'required',
            'itemPrice' => 'required|min:1|numeric',
            'golongan' => 'required',
            'serialNo' => 'nullable',
            'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
            'description' => 'nullable'
        ]);

        // Formatting the item age
        $new_itemAge = $request->itemAge . ' ' . $request->umur;

        // Formatting the item price
        $new_itemPrice = 'Rp. ' . $request->itemPrice;

        // Update the item
        Item::where('id', $item->id)->update([
            'itemName' => $request -> itemName,
            'itemAge' => $new_itemAge,
            'itemStock' => $request->itemStock,
            'unit' => $request -> unit,
            'itemPrice' => $new_itemPrice,
            'golongan' => $request -> golongan,
            'serialNo' => $request -> serialNo,
            'codeMasterItem' => $request -> codeMasterItem,
            'description' => $request -> description
        ]);

        return redirect('/supervisor/item-stocks')->with('status', 'Edit Successfully');
    }

    public function approvalDoPage(){
        // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
        $month_now = (int)(date('m'));
        if($month_now <= 6){
            $start_date = date('Y-01-01');
            $end_date = date('Y-06-30');
        }else{
            $start_date = date('Y-07-01');
            $end_date = date('Y-12-31');
        }

        // Find all of the ongoing DO from the requested branch OR the destination branch
        $ongoingOrders = OrderDo::with(['item_requested', 'user'])->where(function($query){
            $query->where('fromCabang', Auth::user()->cabang)
            ->orWhere('toCabang', Auth::user()->cabang);
        })->whereBetween('created_at', [$start_date, $end_date])->latest()->get();

        return view('supervisor.supervisorApprovalDO', compact('ongoingOrders'));
    }

    public function forwardDo(OrderDo $orderDos){
        // Validate the stock first
        if($orderDos->quantity > $orderDos->item_requested_from -> itemStock){
            return redirect('/supervisor/approval-do')->with('error', 'Stocks Insufficient, Kindly Re-Check the Stocks');
        }else{
            // Then validate order_tracker
            if($orderDos -> order_tracker == 3){
                return redirect('/supervisor/approval-do')->with('error', 'Order Already Been Processed');
            }

            // Forward the DO to the requested branches, then update the order_tracker to validate
            OrderDo::where('id', $orderDos -> id)->update([
                'order_tracker' => 3,
                'status' => 'Waiting Approval By Supervisor Cabang ' . $orderDos->toCabang
            ]);
    
            return redirect('/supervisor/approval-do')->with('status', 'Approved Successfully');
        }
    }
    
    public function denyDo(OrderDo $orderDos){
        // Validate if the order already been processed
        if($orderDos -> order_tracker == 3){
            return redirect('/supervisor/approval-do')->with('error', 'Order Already Been Processed');
        }

        // Else, update the status
        OrderDo::where('id', $orderDos -> id)->update([
            'order_tracker' => 3,
            'status' => 'Rejected By Supervisor Cabang ' . $orderDos->fromCabang
        ]);

        return redirect('/supervisor/approval-do')->with('status', 'Request Rejected');
    }

    public function approveDo(OrderDo $orderDos){
        // Re-validate quantity, even we already put the validation from previous supervisor just to be sure the data is valid
        if($orderDos->quantity > $orderDos->item_requested_from -> itemStock){
            return redirect('/supervisor/approval-do')->with('error', 'Stocks Insufficient, Kindly Re-Check the Stocks');
        }else{
            // Validate if the order already been processed
            if($orderDos -> order_tracker == 4){
                return redirect('/supervisor/approval-do')->with('error', 'Order Already Been Processed');
            }
            
            OrderDo::where('id', $orderDos -> id)->update([
                'order_tracker' => 4,
                'status' => 'On Delivery'
            ]);
            
            // Decrement the stock for the requested branch
            Item::where('id', $orderDos -> item_requested_from_id)->decrement('itemStock', $orderDos -> quantity);

            return redirect('/supervisor/approval-do')->with('status', 'Approved Successfully');
        }
    }

    public function rejectDo(OrderDo $orderDos){
        // Validate if the order already been processed
        if($orderDos -> order_tracker == 4){
            return redirect('/supervisor/approval-do')->with('error', 'Order Already Been Processed');
        }

        OrderDo::where('id', $orderDos -> id)->update([
            'order_tracker' => 4,
            'status' => 'Rejected By Supervisor Cabang ' . $orderDos->toCabang
        ]);

        return redirect('/supervisor/approval-do')->with('status', 'Request Rejected');
    }

    public function downloadDo(OrderDo $orderDos){
        // Find the specific DO, then download it
        return (new DOExport($orderDos -> id))->download('DO-' . $orderDos -> id . '_' .  date("d-m-Y") . '.xlsx');
    }
}
