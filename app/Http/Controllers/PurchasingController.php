<?php

namespace App\Http\Controllers;

use App\Exports\PurchasingReportExport;
use Illuminate\Http\Request;
Use \Carbon\Carbon;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\User;
use App\Exports\PRExport;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Excel;

class PurchasingController extends Controller
{
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
            'status' => 'Order Approved By Purchasing',
            'noPo' => $request->noPo,
            'invoiceAddress' => $request->invoiceAddress,
            'itemAddress' => $request->itemAddress,
            'supplier_id' => $request->supplier_id,
            'price' => $request->price,
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

        // Purchasing role can see all of the order of their respective branches
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '4')->orWhere('role_user.role_id' , '=', '3')->pluck('user_id');

        $orderHeads = OrderHead::with('supplier')->whereIn('user_id', $users)->where('status', 'like', '%' . 'Order Completed' . '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->where('cabang', 'like', Auth::user()->cabang)->orderBy('order_heads.updated_at', 'desc')->get();

        return view('purchasing.purchasingReport', compact('orderHeads'));
    }

    public function downloadPo(OrderHead $orderHeads){
        dd($orderHeads->id);

        return (new PRExport($orderHeads -> order_id))->download('PR-' . $orderHeads -> order_id . '-' .  date("d-m-Y") . '.xlsx');
    }

    public function downloadReport(Excel $excel){
        return $excel -> download(new PurchasingReportExport, 'PurchasingReport-'. date("d-m-Y") . '.xlsx');
    }
}
