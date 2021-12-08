<?php

namespace App\Http\Controllers;

use App\Exports\PurchasingReportExport;
use App\Exports\POExport;
use App\Exports\ReportAPExport;
use Illuminate\Http\Request;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\User;
use App\Models\ApList;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Storage;

class PurchasingController extends Controller
{

    public function branchDashboard($branch){
        $default_branch = $branch;

        // Find order from the logistic role, because purchasing role can only see the order from "logistic/admin logistic" role NOT from "crew" roles
        // $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', $default_branch)->pluck('users.id');
        $users = User::whereHas('roles', function($query){
            $query->where('name', 'logistic');
        })->where('cabang', 'like', $default_branch)->pluck('users.id');
        
        
        if(request('search')){
            $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                $query->where('status', 'like', '%'. request('search') .'%')
                ->orWhere('order_id', 'like', '%'. request('search') .'%');
            })->whereYear('created_at', date('Y'))->latest()->paginate(6);
        }else{
            $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->whereYear('created_at', date('Y'))->latest()->paginate(6)->withQueryString();
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
        })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order In Progress By Supervisor')
            ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
            ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
            ->orWhere('status', 'like', 'Item Delivered By Supplier');
        })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

        // Get all the suppliers
        $suppliers = Supplier::latest()->get();

        return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'completed', 'in_progress', 'default_branch'));
    }

    public function completedOrder($branch){
        $default_branch = $branch;

        // Find order from the logistic role, because purchasing role can only see the order from "logistic/admin logistic" role NOT from "crew" roles
        $users = User::whereHas('roles', function($query){
            $query->where('name', 'logistic');
        })->where('cabang', 'like', $default_branch)->pluck('users.id');

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->whereYear('created_at', date('Y'))->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order In Progress By Supervisor')
            ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
            ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
            ->orWhere('status', 'like', 'Item Delivered By Supplier');
        })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

        if(request('search')){
            $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                $query->where('status', 'like', '%'. request('search') .'%')
                ->orWhere('order_id', 'like', '%'. request('search') .'%');
            })->whereYear('created_at', date('Y'))->latest()->paginate(6);
            
            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();
            
            // Get all the suppliers
            $suppliers = Supplier::latest()->get();

            return view('purchasingManager.purchasingManagerDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'completed', 'in_progress', 'default_branch'));
        }else{
            $orderHeads = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Order Rejected By Supervisor')
                ->orWhere('status', 'like', 'Order Rejected By Purchasing');
            })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->latest()->paginate(10);
    
            $completed = $orderHeads->count();
    
            // Get all the suppliers
            $suppliers = Supplier::latest()->get();
    
            return view('purchasingManager.purchasingManagerDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'suppliers', 'default_branch'));
        }
    }

    public function inProgressOrder($branch){
        $default_branch = $branch;

        // Find order from the logistic role, because purchasing role can only see the order from "logistic/admin logistic" role NOT from "crew" roles
        $users = User::whereHas('roles', function($query){
            $query->where('name', 'logistic');
        })->where('cabang', 'like', $default_branch)->pluck('users.id');

        // Then find all the order details from the orderHeads
        $order_id = OrderHead::whereIn('user_id', $users)->whereYear('created_at', date('Y'))->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        // Count the completed & in progress order
        $completed = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order Completed (Logistic)')
            ->orWhere('status', 'like', 'Order Rejected By Supervisor')
            ->orWhere('status', 'like', 'Order Rejected By Purchasing Manager')
            ->orWhere('status', 'like', 'Order Rejected By Purchasing');
        })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

        if(request('search')){
            $orderHeads = OrderHead::with('user')->whereIn('user_id', $users)->where(function($query){
                $query->where('status', 'like', '%'. request('search') .'%')
                ->orWhere('order_id', 'like', '%'. request('search') .'%');
            })->whereYear('created_at', date('Y'))->latest()->paginate(6);

            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', 'Order In Progress By Supervisor')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', 'Item Delivered By Supplier');
            })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->count();

            // Get all the suppliers
            $suppliers = Supplier::latest()->get();

            return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'suppliers', 'completed', 'in_progress', 'default_branch'));
        }else{
            $orderHeads =  OrderHead::where(function($query){
                $query->where('status', 'like', 'Order In Progress By Supervisor')
                ->orWhere('status', 'like', '%' . 'In Progress By Purchasing' . '%')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', 'Item Delivered By Supplier');
            })->where('cabang', 'like', $default_branch)->whereYear('created_at', date('Y'))->latest()->paginate(10);
    
            $in_progress = $orderHeads->count();
    
            // Get all the suppliers
            $suppliers = Supplier::latest()->get();
    
            return view('purchasing.purchasingDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'suppliers', 'default_branch'));
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

    public function editPriceOrderDetail(Request $request, OrderHead $orderHeads, OrderDetail $orderDetails){
        $request->validate([
            'itemPrice' => 'required|integer|min:1',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $subTotal = $orderDetails -> acceptedQuantity * $request -> itemPrice;

        OrderDetail::where('id', $orderDetails->id)->update([
            'itemPrice' => $request->itemPrice,
            'totalItemPrice' => $subTotal,
            'supplier_id' => $request->supplier_id,
        ]);
        
        $totalPrice = OrderDetail::where('orders_id', $orderHeads -> id)->sum('totalItemPrice');

        OrderHead::find($orderHeads -> id)->update([
            'totalPrice' => $totalPrice
        ]);

        return redirect('/purchasing/order/' . $orderHeads->id . '/approve')->with('status', 'Price Updated Successfully');
    }
    
    // ============================================ Fix this section ===========================================================
    public function approveOrder(Request $request, OrderHead $orderHeads){
        // Set default branch
        $default_branch = $orderHeads -> cabang;

        // Validate the request form
        $request -> validate([
            'boatName' => 'required',
            'noPr' => 'required',
            'noPo' => 'required',
            'invoiceAddress' => 'required',
            'itemAddress' => 'required',
            'ppn' => 'required|numeric|in:0,10',
            'discount' => 'nullable|numeric|min:0|max:100',
            'totalPrice' => 'required',
        ]);

        // calculate discount first, then PPN
        $updatedPriceAfterDiscount = $orderHeads -> totalPrice - ($orderHeads -> totalPrice * $request -> discount / 100);

        // calculate PPN
        $updatedPriceAfterPPN = $updatedPriceAfterDiscount + ($updatedPriceAfterDiscount * $request -> ppn / 100);

        // Check if already been processed or not
        if($orderHeads -> order_tracker == 4){
            // return redirect('/purchasing/dashboard/' . $default_branch)->with('errorB', 'Order Already Been Processed');
            return redirect()->back()->with('errorB', 'Order Already Been Processed');
        }

        $orderDetails = OrderDetail::where('orders_id', $orderHeads -> id)->get();

        foreach($orderDetails as $od){
            if($od -> totalItemPrice == 0){
                return redirect('/purchasing/order/' . $orderHeads -> id . '/approve')->with('error', 'Harga ' . $od -> item -> itemName . ' Invalid');
            }
            if(!$od -> supplier_id){
                return redirect('/purchasing/order/' . $orderHeads -> id . '/approve')->with('error', 'Supplier Invalid');
            }
        }
        
        if(!$request->discount){
            $updatedDiscount = 0;
        }else{
            $updatedDiscount = $request -> discount;
        }

        // Then update the following order
        OrderHead::find($orderHeads -> id)->update([
            'approvedBy' => Auth::user()->name,
            'status' => 'Order In Progress By Purchasing Manager',
            'poDate' => date('d/m/Y'),
            'noPo' => $request->noPo,
            'invoiceAddress' => $request->invoiceAddress,
            'itemAddress' => $request->itemAddress,
            'ppn' => $request->ppn,
            'discount' => $updatedDiscount,
            'totalPrice' => $updatedPriceAfterPPN,
            'order_tracker' => 4,
        ]);
        return redirect('/purchasing/dashboard/' . $default_branch)->with('statusB', 'Order Approved By Purchasing');
        // return redirect()->back()->with('statusB', 'Order Approved By Purchasing');
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
            'approvedBy' => Auth::user()->name,
            'status' => 'Order Rejected By Purchasing',
            'reason' => $request->reason
        ]);
        // return redirect('/dashboard')->with('statusB', 'Order Rejected');
        return redirect()->back()->with('statusB', 'Order Rejected');
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
        // Basically the report is created per 3 months, so we divide it into 4 reports
        // Base on current month, then we classified what period is the report
        $month_now = (int)(date('m'));

        if($month_now <= 3){
            $start_date = date('Y-01-01');
            $end_date = date('Y-03-31');
            $str_month = 'Jan - Mar';
        }elseif($month_now > 3 && $month_now <= 6){
            $start_date = date('Y-04-01');
            $end_date = date('Y-06-30');
            $str_month = 'Apr - Jun';
        }elseif($month_now > 6 && $month_now <= 9){
            $start_date = date('Y-07-01');
            $end_date = date('Y-09-30');
            $str_month = 'Jul - Sep';
        }else{
            $start_date = date('Y-10-01');
            $end_date = date('Y-12-31');
            $str_month = 'Okt - Des';
        }

        // Default branch is Jakarta
        $default_branch = 'Jakarta';

        // Find order from user/goods in
        // $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where(function($query){
        //     $query->where('role_user.role_id' , '=', '3')
        //     ->orWhere('role_user.role_id' , '=', '4');
        // })->where('cabang', 'like', $default_branch)->pluck('users.id');
        $users = User::whereHas('roles', function($query){
            $query->where('name', 'logistic');
        })->where('cabang', 'like', $default_branch)->pluck('users.id');
                
        // Find all the items that has been approved from the logistic | Per 3 months

        // $orderHeads = OrderHead::whereIn('user_id', $users)->where(function($query){
        //     $query->where('status', 'like', 'Order Completed (Logistic)')
        //         ->orWhere('status', 'like', 'Item Delivered By Supplier');
        // })->whereBetween('order_heads.created_at', [$start_date, $end_date])->where('cabang', 'like', $default_branch)->orderBy('order_heads.updated_at', 'desc')->get();

        $orders = OrderDetail::with(['item', 'supplier'])->join('order_heads', 'order_heads.id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->where(function($query){
            $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Item Delivered By Supplier');
        })->whereBetween('order_heads.created_at', [$start_date, $end_date])->where('cabang', 'like', $default_branch)->orderBy('order_heads.updated_at', 'desc')->get();

        return view('purchasing.purchasingReport', compact('orders', 'default_branch', 'str_month'));
    }

    public function reportPageBranch($cabang){
        // Basically the report is created per 3 months, so we divide it into 4 reports
        // Base on current month, then we classified what period is the report
        $month_now = (int)(date('m'));

        if($month_now <= 3){
            $start_date = date('Y-01-01');
            $end_date = date('Y-03-31');
            $str_month = 'Jan - Mar';
        }elseif($month_now > 3 && $month_now <= 6){
            $start_date = date('Y-04-01');
            $end_date = date('Y-06-30');
            $str_month = 'Apr - Jun';
        }elseif($month_now > 6 && $month_now <= 9){
            $start_date = date('Y-07-01');
            $end_date = date('Y-09-30');
            $str_month = 'Jul - Sep';
        }else{
            $start_date = date('Y-10-01');
            $end_date = date('Y-12-31');
            $str_month = 'Okt - Des';
        }

        $default_branch = $cabang;

        // Find order from user that created the order
        // $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where(function($query){
        //     $query->where('role_user.role_id' , '=', '3')
        //     ->orWhere('role_user.role_id' , '=', '4');
        // })->where('cabang', 'like', $default_branch)->pluck('users.id');
        $users = User::whereHas('roles', function($query){
            $query->where('name', 'logistic');
        })->where('cabang', 'like', $default_branch)->pluck('users.id');
                
        // Find all the items that has been approved from the logistic | Per 3 months

        // $orderHeads = OrderHead::whereIn('user_id', $users)->where('cabang', 'like', $default_branch)->where(function($query){
        //     $query->where('status', 'like', 'Order Completed (Logistic)')
        //         ->orWhere('status', 'like', 'Item Delivered By Supplier');
        // })->whereBetween('order_heads.created_at', [$start_date, $end_date])->orderBy('order_heads.updated_at', 'desc')->get();

        $orders = OrderDetail::with(['item', 'supplier'])->join('order_heads', 'order_heads.id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->where(function($query){
            $query->where('status', 'like', 'Order Completed (Logistic)')
                ->orWhere('status', 'like', 'Item Delivered By Supplier');
        })->whereBetween('order_heads.created_at', [$start_date, $end_date])->where('cabang', 'like', $default_branch)->orderBy('order_heads.updated_at', 'desc')->get();

        return view('purchasing.purchasingReport', compact('orders', 'default_branch', 'str_month'));
    }

    public function downloadReport(Excel $excel, $cabang){
        $default_branch = $cabang;
        return $excel -> download(new PurchasingReportExport($default_branch), 'Reports_'. date("d-m-Y") . '.xlsx');
    }

    public function reportApPage(){
        // Basically the report is created per 3 months, so we divide it into 4 reports
        // Base on current month, then we classified what period is the report
        $month_now = (int)(date('m'));

        if($month_now <= 3){
            $start_date = date('Y-01-01');
            $end_date = date('Y-03-31');
            $str_month = 'Jan - Mar';
        }elseif($month_now > 3 && $month_now <= 6){
            $start_date = date('Y-04-01');
            $end_date = date('Y-06-30');
            $str_month = 'Apr - Jun';
        }elseif($month_now > 6 && $month_now <= 9){
            $start_date = date('Y-07-01');
            $end_date = date('Y-09-30');
            $str_month = 'Jul - Sep';
        }else{
            $start_date = date('Y-10-01');
            $end_date = date('Y-12-31');
            $str_month = 'Okt - Des';
        }

        // Helper var
        $default_branch = 'Jakarta';

        // Find all the AP within the 3 months period
        $apList = ApList::with('orderHead')->where('cabang', 'like', $default_branch)->join('ap_list_details', 'ap_list_details.aplist_id', '=', 'ap_lists.id')->whereBetween('ap_lists.created_at', [$start_date, $end_date])->orderBy('ap_lists.created_at', 'desc')->get();

        return view('purchasing.purchasingReportApPage', compact('default_branch', 'str_month', 'apList'));
    }

    public function reportApPageBranch($branch){
        // Basically the report is created per 3 months, so we divide it into 4 reports
        // Base on current month, then we classified what period is the report
        $month_now = (int)(date('m'));

        if($month_now <= 3){
            $start_date = date('Y-01-01');
            $end_date = date('Y-03-31');
            $str_month = 'Jan - Mar';
        }elseif($month_now > 3 && $month_now <= 6){
            $start_date = date('Y-04-01');
            $end_date = date('Y-06-30');
            $str_month = 'Apr - Jun';
        }elseif($month_now > 6 && $month_now <= 9){
            $start_date = date('Y-07-01');
            $end_date = date('Y-09-30');
            $str_month = 'Jul - Sep';
        }else{
            $start_date = date('Y-10-01');
            $end_date = date('Y-12-31');
            $str_month = 'Okt - Des';
        }

        // Helper Var
        $default_branch = $branch;

        // Find all the AP within the 3 months period
        $apList = ApList::with('orderHead')->where('cabang', 'like', $default_branch)->join('ap_list_details', 'ap_list_details.aplist_id', '=', 'ap_lists.id')->whereBetween('ap_lists.created_at', [$start_date, $end_date])->orderBy('ap_lists.created_at', 'desc')->get();

        return view('purchasing.purchasingReportApPage', compact('default_branch', 'str_month', 'apList'));
    }

    public function exportReportAp(Excel $excel, $branch){

        // Export into excel
        return $excel -> download(new ReportAPExport($branch), 'Reports_AP('. $branch . ')_'. date("d-m-Y") . '.xlsx');
    }

    public function supplierPage(){
        // Get all supplier
        $suppliers = Supplier::latest()->get();

        return view('purchasing.purchasingSupplierPage', compact('suppliers'));
    }

    public function addSupplier(Request $request){
        // Validate request
        $validated = $request -> validate([
            'supplierName' => ['required', 'regex:/^[a-zA-Z\s-]*$/', 'unique:suppliers'],
            'supplierPic' => ['required', 'string'],
            'supplierEmail' => ['required', 'string', 'email:rfc,dns', 'unique:suppliers'],
            'supplierAddress' => ['required', 'string'],
            'supplierNoRek' => ['required', 'string'],
            'supplierNPWP' => ['required', 'string'],
            'supplierCode' => ['required', 'string'],
            'noTelpBks' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpSms' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpBer' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpBnt' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpBnj' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpJkt' => ['nullable', 'numeric', 'digits_between:8,11'],
        ]);
        
        // Then create the supplier
        Supplier::create($validated);

        // Redirect
        return redirect('/purchasing/supplier')->with('status', 'Added Successfully');
    }

    public function editSupplierDetail(Request $request){
        // Validate Request
        $validated = $request -> validate([
            'supplierEmail' => ['required', 'string', 'email:rfc,dns', Rule::unique('suppliers')->ignore($request->supplier_id)],
            'supplierAddress' => ['required', 'string'],
            'supplierNoRek' => ['required', 'string'],
            'supplierNPWP' => ['required', 'string'],
            'noTelpBks' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpSms' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpBer' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpBnt' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpBnj' => ['nullable', 'numeric', 'digits_between:8,11'],
            'noTelpJkt' => ['nullable', 'numeric', 'digits_between:8,11'],
        ]);

        // Then update the supplier
        Supplier::where('id', $request -> supplier_id)->update($validated);

        // Redirect
        return redirect('/purchasing/supplier')->with('status', 'Edit Successfully');
    }

    public function deleteSupplier(Request $request){
        // Find the supplier, then delete it
        Supplier::where('id', $request -> supplier_id)->delete();

        // Redirect
        return redirect('/purchasing/supplier')->with('status', 'Delete Successfully');
    }
}
