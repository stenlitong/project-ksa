<?php

namespace App\Http\Controllers;

use App\Exports\PurchasingReportExport;
use App\Exports\POExport;
use Illuminate\Http\Request;
// Use \Carbon\Carbon;
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

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->whereBetween('created_at', [$start_date, $end_date])->pluck('order_id');
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
            })->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(6);
            
            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();
            
            // Get all the suppliers
            $suppliers = Supplier::latest()->get();

            return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'completed', 'in_progress'));
        }else{
            $orderHeads = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(10);
    
            $completed = $orderHeads->count();
    
            // Get all the suppliers
            $suppliers = Supplier::latest()->get();
    
            return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'suppliers'));
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

        // Find order from the logistic role, because purchasing role can only see the order from "logistic/admin logistic" role NOT from "crew" roles
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->whereBetween('created_at', [$start_date, $end_date])->pluck('order_id');
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
            })->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(6);

            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            // Get all the suppliers
            $suppliers = Supplier::latest()->get();

            return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'completed', 'in_progress'));
        }else{
            $orderHeads =  OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress By Supervisor' . '%')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(10);
    
            $in_progress = $orderHeads->count();
    
            // Get all the suppliers
            $suppliers = Supplier::latest()->get();
    
            return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'suppliers'));
        }
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
        $orderDetails = OrderDetail::with('item')->where('orders_id', $orderHeads->id)->get();

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
            // 'kurs' => 'required|in:Rp.,$',
            // 'price' => 'required|integer|min:1',
            'descriptions' => 'nullable'
        ]);

        // Check if already been processed or not
        if($orderHeads -> order_tracker == 4){
            return redirect('/dashboard')->with('errorB', 'Order Already Been Processed');
        }

        // Then update the following order
        OrderHead::find($orderHeads -> id)->update([
            'status' => 'Item Delivered By Supplier',
            'noPo' => $request->noPo,
            'invoiceAddress' => $request->invoiceAddress,
            'itemAddress' => $request->itemAddress,
            'supplier_id' => $request->supplier_id,
            'order_tracker' => 4,
            'descriptions' => $request->descriptions
        ]);
        return redirect('/dashboard')->with('statusB', 'Order Approved By Purchasing');
    }

    public function rejectOrder(Request $request, OrderHead $orderHeads){
        // Reject the order made from logistic
        $request->validate([
            'reason' => 'required'
        ]);

        // Check if already been processed or not
        if($orderHeads -> order_tracker == 4){
            return redirect('/dashboard')->with('errorB', 'Order Already Been Processed');
        }
        
        // Then update the status + reason
        OrderHead::where('id', $orderHeads->id)->update([
            'order_tracker' => 4,
            'status' => 'Order Rejected By Purchasing',
            'reason' => $request->reason
        ]);
        return redirect('/dashboard')->with('statusB', 'Order Rejected');
    }

    public function downloadPo(OrderHead $orderHeads){
        return (new POExport($orderHeads -> order_id))->download('PO-' . $orderHeads -> order_id . '_' .  date("d-m-Y") . '.xlsx');
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

    public function reportPage(){
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
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where(function($query){
            $query->where('role_user.role_id' , '=', '3')
            ->orWhere('role_user.role_id' , '=', '4');
        })->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
                
        // Find all the items that has been approved from the logistic | last 30 days only
        $orderHeads = OrderHead::with('supplier')->whereIn('user_id', $users)->where('status', 'like', 'Order Completed (Logistic)')->whereBetween('created_at', [$start_date, $end_date])->where('cabang', 'like', Auth::user()->cabang)->orderBy('order_heads.updated_at', 'desc')->get();

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
        // Validate if it's already been processed
        if($apList->tracker == 4){
            return redirect('/purchasing/form-ap')->with('error', 'Form Already Been Processed');
        }

        // Find the form, then update the status to approved
        ApList::find($apList -> id)->update([
            'tracker' => 4,
            'status' => 'Approved'
        ]);

        return redirect('/purchasing/form-ap')->with('status', 'Form Approved');
    }

    public function rejectAp(Request $request, ApList $apList){
        // User must input the reason, then validate the reason
        $request->validate([
            'description' => 'required'
        ]);
        
        // Validate if it's already been processed
        if($apList->tracker == 4){
            return redirect('/purchasing/form-ap')->with('error', 'Form Already Been Processed');
        }

        // Find the form, then update the status to denied
        ApList::find($apList -> id)->update([
            'tracker' => 4,
            'status' => 'Denied',
            'description' => $request -> description
        ]);

        return redirect('/purchasing/form-ap')->with('status', 'Form Denied');
    }
}
