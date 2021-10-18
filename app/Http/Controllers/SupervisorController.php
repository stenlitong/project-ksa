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
use App\Exports\PurchasingReportExport;
use App\Models\OrderDo;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Auth;
Use \Carbon\Carbon;

class SupervisorController extends Controller
{
    public function completedOrder(){
        // Find order from logistic role, then they can approve and send it to the purchasing role
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3', 'and', 'cabang', 'like', Auth::user()->cabang)->pluck('users.id');

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $orderHeads = OrderHead::where('status', 'like', 'Order Completed (Logistic)')->orWhere('status', 'like', 'Order Rejected By Supervisor')->orWhere('status', 'like', 'Order Rejected By Purchasing')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->latest()->paginate(10);

        // Count the completed & in progress order
        $completed = OrderHead::where('status', 'like', 'Order Completed (Logistic)')->orWhere('status', 'like', 'Order Rejected By Supervisor')->orWhere('status', 'like', 'Order Rejected By Purchasing')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();
        $in_progress = OrderHead::where('status', 'like', '%' . 'In Progress By Supervisor' . '%')->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();
        $show_search = false;

        return view('supervisor.supervisorDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'show_search'));
    }

    public function inProgressOrder(){
        // Find order from logistic role, then they can approve and send it to the purchasing role
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3', 'and', 'cabang', 'like', Auth::user()->cabang)->pluck('users.id');

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $orderHeads =  OrderHead::where('status', 'like', '%' . 'In Progress By Supervisor' . '%')->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->latest()->paginate(10);

        // Count the completed & in progress order
        $completed = OrderHead::where('status', 'like', 'Order Completed (Logistic)')->orWhere('status', 'like', 'Order Rejected By Supervisor')->orWhere('status', 'like', 'Order Rejected By Purchasing')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();
        $in_progress = OrderHead::where('status', 'like', '%' . 'In Progress By Supervisor' . '%')->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();
        $show_search = false;

        return view('supervisor.supervisorDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'show_search'));
    }

    public function approveOrder(OrderHead $orderHeads){
        // If they approve the order, then change the status of the order into purchasing
        OrderHead::find($orderHeads->id)->update([
            'status' => 'Order In Progress By Purchasing'
        ]);

        return redirect('/dashboard')->with('status', 'Order Approved');
    }

    public function rejectOrder(Request $request, OrderHead $orderHeads){
        // Reject the order, required valid reason
        $request->validate([
            'reason' => 'required'
        ]);

        // Then update the status as well as the reason
        OrderHead::find($orderHeads->id)->update([
            'status' => 'Order Rejected By Supervisor',
            'reason' => $request -> reason
        ]);

        return redirect('/dashboard')->with('status', 'Order Rejected');
    }

    public function downloadPr(OrderHead $orderHeads){
        // Find the order id then, return download        
        return (new PRExport($orderHeads -> order_id))->download('PR-' . $orderHeads -> order_id . '_' .  date("d-m-Y") . '.xlsx');
    }

    public function reportsPage(){
        // Find order from user/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3', 'and', 'cabang', 'like', Auth::user()->cabang)->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
        
        // Find all the items that has been approved from the logistic | last 30 days only
        $orderHeads = OrderHead::with('supplier')->whereIn('user_id', $users)->where('status', 'like', 'Order Completed (Logistic)', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->where('cabang', 'like', Auth::user()->cabang)->orderBy('order_heads.updated_at', 'desc')->get();

        return view('supervisor.supervisorReport', compact('orderHeads'));
    }

    public function downloadReport(Excel $excel){
        return $excel -> download(new PurchasingReportExport, 'Reports_'. date("d-m-Y") . '.xlsx');
    }

    public function historyOut(){
        // Find order from crew role/goods out
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '2')->pluck('users.id');
        
        // Find all the items that has been approved/completed from the user feedback | last 30 days only
        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->where('cabang', 'like', Auth::user()->cabang,)->where('status', 'like', '%' . 'Completed' . '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_details.created_at', 'desc')->get();

        return view('supervisor.supervisorHistoryOut', compact('orderHeads'));
    }

    public function historyIn(){
        // Find order from logistic role/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->pluck('users.id');
        
        // Find all the items that has been approved from the user | last 30 days only
        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->join('suppliers', 'suppliers.id', '=', 'order_heads.supplier_id')->whereIn('user_id', $users)->where('cabang', 'like', Auth::user()->cabang,)->where('status', 'like', '%' . 'Completed'. '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.updated_at', 'desc')->get();

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
            $items = Item::where('itemName', 'like', '%' . request('search') . '%')->orWhere('cabang', 'like', '%' . request('search') . '%')->orWhere('codeMasterItem', 'like', '%' . request('search') . '%')->Paginate(10)->withQueryString();
            return view('supervisor.supervisorItemStock', compact('items'));
        }else{
            $items = Item::latest()->Paginate(10)->withQueryString();
            return view('supervisor.supervisorItemStock', compact('items'));
        }
    }

    public function addItemStock(Request $request){
         // Storing the item to the stock
         $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric',
            'umur' => 'required',
            'itemStock' => 'required|numeric',
            'unit' => 'required',
            'serialNo' => 'nullable',
            'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
            'cabang' => 'required',
            'description' => 'nullable'
        ]);

        // Formatting the item age
        $new_itemAge = $request->itemAge . ' ' . $request->umur;
        
        // Create the item
        Item::create([
            'itemName' => $request -> itemName,
            'itemAge' => $new_itemAge,
            'itemStock' => $request -> itemStock,
            'unit' => $request -> unit,
            'serialNo' => $request -> serialNo,
            'codeMasterItem' => $request -> codeMasterItem,
            'cabang' => $request->cabang,
            'description' => $request -> description
        ]);

        return redirect('/supervisor/item-stocks')->with('status', 'Added Successfully');
    }

    public function editItemStock(Request $request, Item $item){
        // Edit the requested item
        $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric',
            'umur' => 'required',
            'itemStock' => 'required|numeric',
            'unit' => 'required',
            'serialNo' => 'nullable',
            'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
            'description' => 'nullable'
        ]);

        // Formatting the item age
        $new_itemAge = $request->itemAge . ' ' . $request->umur;

        // Update the item
        Item::where('id', $item->id)->update([
            'itemName' => $request -> itemName,
            'itemAge' => $new_itemAge,
            'itemStock' => $request->itemStock,
            'unit' => $request -> unit,
            'serialNo' => $request -> serialNo,
            'codeMasterItem' => $request -> codeMasterItem,
            'description' => $request -> description
        ]);

        return redirect('/supervisor/item-stocks')->with('status', 'Edit Successfully');
    }

    public function approvalDoPage(){
        // Find all of the ongoing DO from the requested branch OR the destination branch
        $ongoingOrders = OrderDo::with(['user', 'item'])->where('fromCabang', Auth::user()->cabang)->orWhere('toCabang', Auth::user()->cabang)->where('order_dos.created_at', '>=', Carbon::now()->subDays(30))->latest()->get();

        return view('supervisor.supervisorApprovalDO', compact('ongoingOrders'));
    }
}
