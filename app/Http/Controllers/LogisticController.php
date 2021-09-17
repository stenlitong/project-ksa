<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    public function index(){
        return view('logistic.logisticDashboard');
    }
    public function stocksPage(){
        if(request('search')){
            $items = Item::where('itemName', 'like', '%' . request('search') . '%')->Paginate(5)->withQueryString();
            return view('logistic.stocksPage', [
                'items' => $items
            ]);
        }else{
            $items = Item::orderBy('created_at', 'desc')->Paginate(5)->withQueryString();
            return view('logistic.stocksPage', compact('items'));
        }
    }

    public function storeItem(Request $request){
        $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric',
            'itemStock' => 'required|numeric',
            'satuan' => 'required',
        ]);
        $new_qty = $request->itemStock . " " . $request->satuan;

        Item::create([
            'itemName' => $request->itemName,
            'itemStock' => $new_qty,
            'itemAge' => $request->itemAge,
            'description' => $request->description
        ]);
        return redirect('logistic/stocks')->with('status', 'Added Successfully');
    }

    public function editItemPage(Item $item){
        $items = Item::find($item->id);
        // dd($items);
        return view('logistic.logisticEditItem', compact('items'));
    }

    public function editItem(Request $request, Item $item){
        // dd($item->id);
        $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric',
            'itemStock' => 'required|numeric',
            'satuan' => 'required',
        ]);
        $new_qty = $request->itemStock . " " . $request->satuan;

        Item::where('id', $item->id)->update([
            'itemName' => $request->itemName,
            'itemStock' => $new_qty,
            'itemAge' => $request->itemAge,
            'description' => $request->description
        ]);
        return redirect('logistic/stocks')->with('status', 'Edit Successfully');
    }

    public function rejectOrderPage(Order $order){
        // dd($order->id);
        return view('logistic.logisticRejectOrder', compact('order'));
    }

    public function rejectOrder(Request $request, Order $order){
        // dd($request->reason);
        $request->validate([
            'reason' => 'required'
        ]);
        Order::where('id', $order->id)->update([
            'in_progress' => 'rejected(Logistic)',
            'reason' => $request->reason
        ]);
        return redirect('/dashboard');
    }

    public function approveOrderPage(Order $order){
        return view('logistic.logisticApproveOrder', compact('order'));
    }
}
