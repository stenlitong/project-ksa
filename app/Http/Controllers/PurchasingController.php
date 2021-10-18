<?php

namespace App\Http\Controllers;

use App\Exports\PurchasingReportExport;
use Illuminate\Http\Request;
Use \Carbon\Carbon;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\User;
use App\Models\ApList;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Auth;
use Storage;

class PurchasingController extends Controller
{
    public function completedOrder(){
        // Find order from logistic role, then they can approve and send it to the purchasing role
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3', 'and', 'cabang', 'like', Auth::user()->cabang)->pluck('users.id');

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $orderHeads = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order Completed (Logistic)')
            ->orWhere('status', 'like', 'Order Rejected By Supervisor')
            ->orWhere('status', 'like', 'Order Rejected By Purchasing');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->latest()->paginate(10);

        // Count the completed & in progress order
        $completed = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order Completed (Logistic)')
            ->orWhere('status', 'like', 'Order Rejected By Supervisor')
            ->orWhere('status', 'like', 'Order Rejected By Purchasing');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();

        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
            ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
            ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();

        $show_search = false;

        // Get all the suppliers
        $suppliers = Supplier::latest()->get();

        return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'show_search', 'suppliers'));
    }

    public function inProgressOrder(){
        // Find order from logistic role, then they can approve and send it to the purchasing role
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3', 'and', 'cabang', 'like', Auth::user()->cabang)->pluck('users.id');

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $orderHeads =  OrderHead::where(function($query){
            $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
            ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
            ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->latest()->paginate(10);

        // Count the completed & in progress order
        $completed = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order Completed (Logistic)')
            ->orWhere('status', 'like', 'Order Rejected By Supervisor')
            ->orWhere('status', 'like', 'Order Rejected By Purchasing');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();

        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
            ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
            ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();

        $show_search = false;

        // Get all the suppliers
        $suppliers = Supplier::latest()->get();

        return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'show_search', 'suppliers'));
    }

    public function approveOrderPage(OrderHead $orderHeads){
        // Formatting the PO code
        $month_arr_in_roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        // Prepare all of the required resources
        $cabang_arr = [
            'Jakarta' => 'JKT',
            'Banjarmasin' => 'BNJ',
            'Samarinda' => 'SMD',
            'Bunati' => 'BNT',
            'Babelan' => 'BBL',
            'Berau' => 'BER'
        ];

        $po_id = $orderHeads -> id;
        $first_char_name = strtoupper(Auth::user()->name[0]);
        $location = $cabang_arr[Auth::user()->cabang];
        $month = date('n');
        $month_to_roman = $month_arr_in_roman[$month - 1];
        $year = date('Y');

        // Create the PO Number => 1251.P/PO-KSA-JKT/IX/2021
        $poNumber = $po_id . '.' . $first_char_name . '/' . 'PO-' . $orderHeads->company . '-' . $location . '/' . $month_to_roman . '/' . $year;

        // Get the order details join with the item
        $orderDetails = OrderDetail::with('item')->where('orders_id', $orderHeads->order_id)->get();

        $suppliers = Supplier::latest()->get();

        return view('purchasing.purchasingApprovedPage', compact('orderHeads', 'orderDetails', 'poNumber', 'suppliers'));
    }

    public function approveOrder(Request $request, OrderHead $orderHeads){
        // Validate the request form
        $request -> validate([
            'boatName' => 'required',
            'noPr' => 'required',
            'noPo' => 'required',
            'invoiceAddress' => 'required',
            'itemAddress' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
            'price' => 'required',
            'descriptions' => 'nullable'
        ]);

        // Then update the following order
        OrderHead::find($orderHeads -> id)->update([
            'status' => 'Item Delivered By Supplier',
            'noPo' => $request->noPo,
            'invoiceAddress' => $request->invoiceAddress,
            'itemAddress' => $request->itemAddress,
            'supplier_id' => $request->supplier_id,
            'price' => 'Rp.' . $request->price,
            'descriptions' => $request->descriptions
        ]);
        return redirect('/dashboard')->with('orderStatus', 'Order Approved By Purchasing');
    }

    public function rejectOrder(Request $request, OrderHead $orderHeads){
        // Reject the order made from logistic
        $request->validate([
            'reason' => 'required'
        ]);

        // Then update the status + reason
        OrderHead::where('id', $orderHeads->id)->update([
            'status' => 'Order Rejected By Purchasing',
            'reason' => $request->reason
        ]);
        return redirect('/dashboard');
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

        return redirect('/dashboard')->with('status', 'Edited Successfully');
    }

    public function reportPage(){
        // Find order from user/goods in
        // $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3', 'and', 'cabang', 'like', Auth::user()->cabang)->orWhere('role_user.role_id' , '=', '4', 'and', 'cabang', 'like', Auth::user()->cabang)->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where(function($query){
            $query->where('role_user.role_id' , '=', '3')
            ->orWhere('role_user.role_id' , '=', '4');
        })->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
                
        // Find all the items that has been approved from the logistic | last 30 days only
        $orderHeads = OrderHead::with('supplier')->whereIn('user_id', $users)->where('status', 'like', 'Order Completed (Logistic)', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->where('cabang', 'like', Auth::user()->cabang)->orderBy('order_heads.updated_at', 'desc')->get();

        return view('purchasing.purchasingReport', compact('orderHeads'));
    }

    public function downloadReport(Excel $excel){
        return $excel -> download(new PurchasingReportExport, 'Reports_'. date("d-m-Y") . '.xlsx');
    }

    public function formApPage(){
        // Find all of the documents for their respective branches
        $documents = ApList::where('cabang', Auth::user()->cabang)->latest()->get();

        return view('purchasing.purchasingFormAP', compact('documents'));
    }

    public function downloadFile(ApList $apList){
        // Find the document, then return download
        return Storage::download('/APList' . '/' . $apList->filename);
    }

    public function approveAp(ApList $apList){
        // Find the form, then update the status to approved
        ApList::find($apList -> id)->update([
            'status' => 'Approved'
        ]);

        return redirect('/purchasing/form-ap')->with('status', 'Form Approved');
    }

    public function rejectAp(Request $request, ApList $apList){
        // User must input the reason, then validate the reason
        $request->validate([
            'description' => 'required'
        ]);
        
        // Find the form, then update the status to denied
        ApList::find($apList -> id)->update([
            'status' => 'Denied',
            'description' => $request -> description
        ]);

        return redirect('/purchasing/form-ap')->with('status', 'Form Denied');
    }
}
