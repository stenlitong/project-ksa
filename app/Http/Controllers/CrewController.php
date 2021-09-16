<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Order;
use App\Models\User;

class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderPage()
    {
        $items = Item::all();
        // dd($items);
        return view('crew.crewOrder', compact('items'));
    }

    public function taskPage()
    {
        return view('crew.crewTask');
    }

    public function storeOrder(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'departmentName' => 'required',
            'quantity' => 'required',
            'satuan' => 'required'
        ]);
        
        $new_qty = $request->quantity . " " . $request->satuan;
        // dd($order, Auth::user()->name);
        Order::create([
            'item_id' => $request->item_id,
            'crew_id' => Auth::user()->id,
            'department' => $request->departmentName,
            'quantity' => $new_qty
        ]);

        return redirect('crew/order')->with('status', 'Order Success');
    }

}
