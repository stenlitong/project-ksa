<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Barge;
use App\Models\Tug;
use App\Models\User;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use Illuminate\Support\Str;


class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderPage()
    {
        // Select items to choose in the order page & carts according to the login user
        $items = Item::where('cabang', Auth::user()->cabang)->get();
        $barges = Barge::all();
        $tugs = Tug::all();
        $carts = Cart::where('user_id', Auth::user()->id)->join('items', 'items.id', '=', 'carts.item_id')->get();

        // dd($carts);
        return view('crew.crewOrder', compact('items', 'carts', 'tugs', 'barges'));
    }

    public function taskPage()
    {
        return view('crew.crewTask');
    }

    public function addItemToCart(Request $request){
        // dd($request);

        // Validate Cart Request
        $request->validate([
            'item_id' => 'required',
            'department' => 'required',
            'quantity' => 'required | numeric',
        ]);

        // Check if the cart within the user is already > 12 items, then return with message
        $counts = Cart::where('user_id', Auth::user()->id)->count();
        if($counts ==  12){
            return redirect('/crew/order')->with('error', 'Cart is Full');
        }

        // Else add item to the cart
        // $new_qty = $request->quantity . " " . $request->satuan;

        Cart::create([
            'user_id' => Auth::user()->id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'department' => $request->department
        ]);
        
        return redirect('/crew/order')->with('status', 'Add Item Success');
    }

    public function deleteItemFromCart(Cart $cart){
        // Delete item from cart of the following user
        Cart::destroy($cart->id);

        return redirect('/crew/order')->with('status', 'Delete Item Success');
    }

    public function submitOrder(Request $request){
        $request -> validate([
            'tugName' => 'required',
            'bargeName' => 'required'
        ]);

        // Find the cart of the following user
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // Validate cart size
        // dd(count($carts));
        if(count($carts) == 0){
            return redirect('/crew/order')->with('errorCart', 'Cart is Empty');
        }

        // Generate unique id for the order_id || Create the order from the cart
        do{
            $unique_id = Str::random(9);
        }while(OrderHead::where('order_id', $unique_id)->exists());

        // String formatting for boatName with tugName + bargeName
        $boatName = $request->tugName . '/' . $request->bargeName;

        // Create Order Head
        OrderHead::create([
            'user_id' => Auth::user()->id,
            'order_id' => $unique_id,
            'cabang' => Auth::user()->cabang,
            'boatName' => $boatName,
            'status' => 'In Progress (Logistic)'
        ]);
        
        // Then fill the Order Detail with the cart items
        foreach($carts as $c){
            $serialNo = Item::where('id', $c->item_id)->pluck('serialNo');
            $unit = Item::where('id', $c->item_id)->pluck('unit');
            OrderDetail::create([
                'orders_id' => $unique_id,
                'item_id' => $c->item_id,
                'quantity' => $c->quantity,
                'unit' => $unit[0],
                'serialNo' => $serialNo[0],
                'department' => $c->department,
            ]);
        }

        // Emptying the cart items
        Cart::where('user_id', Auth::user()->id)->delete();

        return redirect('/dashboard')->with('status', 'Submit Order Success');
    }

    public function acceptOrder(OrderHead $orderHeads){
        // dd($orderHeads->id);

        OrderHead::where('id', $orderHeads->id)->update([
            'status' => 'Completed'
        ]);

        return redirect('/dashboard')->with('status', 'Order Diterima');
    }
}
