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
use Maatwebsite\Excel\Excel;

class SupervisorController extends Controller
{
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
        return (new PRExport($orderHeads -> order_id))->download('PR-' . $orderHeads -> order_id . '-' .  date("d-m-Y") . '.xlsx');
    }
}
