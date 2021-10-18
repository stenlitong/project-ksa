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
Use \Carbon\Carbon;

class CrewController extends Controller
{
    
    public function completedOrder(){
        // Get all the order within the logged in user within 30 days from date now
        $orderHeads = OrderHead::with('user')->where(function($query){
            $query->where('status', 'like', 'Order Completed (Crew)')
            ->orWhere('status', 'like', 'Rejected By Logistic');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->paginate(10);

        // Get the orderDetail from orders_id within the orderHead table 
        $order_id = OrderHead::where('user_id', Auth::user()->id)->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $completed = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order Completed (Crew)')
            ->orWhere('status', 'like', 'Rejected By Logistic');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();
        
        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', 'In Progress By Logistic')
            ->orWhere('status', 'like', 'Items Ready')
            ->orWhere('status', 'like', 'On Delivery');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();

        return view('crew.crewDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
    }

    public function inProgressOrder(){
        // Get all the order within the logged in user within 30 days from date now
        $orderHeads = OrderHead::with('user')->where(function($query){
            $query->where('status', 'like', 'In Progress By Logistic')
            ->orWhere('status', 'like', 'Items Ready')
            ->orWhere('status', 'like', 'On Delivery');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->paginate(10);

        // Get the orderDetail from orders_id within the orderHead table 
        $order_id = OrderHead::where('user_id', Auth::user()->id)->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $completed = OrderHead::where(function($query){
            $query->where('status', 'like', 'Order Completed (Crew)')
            ->orWhere('status', 'like', 'Rejected By Logistic');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();
        
        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', 'In Progress By Logistic')
            ->orWhere('status', 'like', 'Items Ready')
            ->orWhere('status', 'like', 'On Delivery');
        })->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();

        return view('crew.crewDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
    }

    public function orderPage()
    {
        // Select items to choose in the order page & carts according to the login user
        $items = Item::where('cabang', Auth::user()->cabang)->get();
        $barges = Barge::all();
        $tugs = Tug::all();
        $carts = Cart::with('item')->where('user_id', Auth::user()->id)->get();

        return view('crew.crewOrder', compact('items', 'carts', 'tugs', 'barges'));
    }

    public function taskPage()
    {
        // ==== In Progress ====

        return view('crew.crewTask');
    }

    public function addItemToCart(Request $request){
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
            'bargeName' => 'nullable'
        ]);

        // Find the cart of the following user
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // Validate cart size, if the cart size is zero then return error message
        if(count($carts) == 0){
            return redirect('/crew/order')->with('errorCart', 'Cart is Empty');
        }

        // Else, generate unique id for the order_id and checks the order_id is already exist || Create the order from the cart
        do{
            $unique_id = Str::random(8);
        }while(OrderHead::where('order_id', $unique_id)->exists());

        // String formatting for boatName with tugName + bargeName
        $boatName = $request->tugName . '/' . $request->bargeName;

        // Create Order Head firstly
        OrderHead::create([
            'user_id' => Auth::user()->id,
            'order_id' => $unique_id,
            'cabang' => Auth::user()->cabang,
            'boatName' => $boatName,
            'status' => 'Request In Progress By Logistic'
        ]);
        
        // Then fill the Order Detail with the cart items of the following Order Head
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

        // After all of that, emptying the cart items to reset the cart
        Cart::where('user_id', Auth::user()->id)->delete();

        return redirect('/dashboard')->with('status', 'Submit Order Success');
    }

    public function acceptOrder(OrderHead $orderHeads){
        // Crew accept the order, then the status will be completed
        $order_heads = OrderHead::where('id', $orderHeads->id)->update([
            'status' => 'Order Completed (Crew)',
        ]);

        // Get the order details of the following order
        $orderDetails = OrderDetail::where('orders_id', $orderHeads->order_id)->get();

        foreach($orderDetails as $od){
            Item::where('id', $od -> item -> id)->update([
                'lastGiven' => date("d/m/Y")
            ]);
            Item::where('id', $od -> item -> id)->decrement('itemStock', $od -> quantity);
        }

        return redirect('/dashboard')->with('status', 'Order Accepted');
    }
}
