<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Item;
use App\Models\Order;
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
        $items = Item::all();
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // dd($carts);
        return view('crew.crewOrder', compact('items', 'carts'));
    }

    public function taskPage()
    {
        return view('crew.crewTask');
    }

    // public function storeOrder(Request $request)
    // {
    //     $request->validate([
    //         'item_id' => 'required',
    //         'departmentName' => 'required',
    //         'quantity' => 'required|numeric',
    //         'satuan' => 'required',
    //         'reason' => 'nullable'
    //     ]);
        
    //     $new_qty = $request->quantity . " " . $request->satuan;
    //     // dd($order, Auth::user()->name);
    //     Order::create([
    //         'item_id' => $request->item_id,
    //         'crew_id' => Auth::user()->id,
    //         'department' => $request->departmentName,
    //         'quantity' => $new_qty
    //     ]);

    //     return redirect('crew/order')->with('status', 'Order Success');
    // }

    public function addItemToCart(Request $request){
        // dd($request);

        // Validate Cart Request
        $request->validate([
            'item_id' => 'required',
            'department' => 'required',
            'quantity' => 'required | numeric',
            'satuan' => 'required'
        ]);

        // Check if the cart within the user is already > 12 items, then return with message
        $counts = Cart::where('user_id', Auth::user()->id)->count();
        if($counts ==  12){
            return redirect('/crew/order')->with('error', 'Cart is Full');
        }

        // Else add item to the cart
        $new_qty = $request->quantity . " " . $request->satuan;

        Cart::create([
            'user_id' => Auth::user()->id,
            'item_id' => $request->item_id,
            'quantity' => $new_qty,
            'department' => $request->department
        ]);
        
        return redirect('/crew/order')->with('status', 'Add Item Success');
    }

    public function deleteItemFromCart(Cart $cart){
        // dd($cart->id);

        // Delete item from cart of the following user
        Cart::destroy($cart->id);

        return redirect('/crew/order')->with('status', 'Delete Item Success');
    }

    public function submitOrder(User $user){
        // Find the cart of the following user
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // dd(Item::where('id', 2)->value('itemName'));

        // Generate unique id for the order_id || Create the order from the cart
        do{
            $unique_id = Str::random(9);
        }while(OrderHead::where('order_id', $unique_id)->exists());

        // Create Order Head
        OrderHead::create([
            'user_id' => Auth::user()->id,
            'order_id' => $unique_id,
            'status' => 'In Progress (Logistic)'
        ]);
        
        // Then fill the Order Detail with the cart items
        foreach($carts as $c){
            OrderDetail::create([
                'orders_id' => $unique_id,
                'itemName' => Item::where('id', $c->item_id)->value('itemName'),
                'quantity' => $c->quantity,
                'department' => $c->department
            ]);
        }

        // Emptying the cart items
        Cart::where('user_id', Auth::user()->id)->delete();

        return redirect('/dashboard')->with('status', 'Submit Order Success');
    }
}
