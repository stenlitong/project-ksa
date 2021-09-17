<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    public function index(){
        return view('logistic.logisticDashboard');
    }

    public function stocksPage(){
        $items = Item::all();
        return view('logistic.stocksPage', compact('items'));
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

    public function editItem(Request $request, Item $item){
        // dd($request);
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
}
