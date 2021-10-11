<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Tug;
use App\Models\Barge;
use App\Models\Cart;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Exports\OrderOutExport;
use App\Exports\OrderInExport;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Str;
Use \Carbon\Carbon;

class AdminLogisticController extends Controller
{
    public function addItem(Request $request){
         // Storing the item to the stock
         $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric',
            'umur' => 'required',
            'itemStock' => 'required|numeric',
            'unit' => 'required',
            'serialNo' => 'nullable',
            'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
            'cabang' => 'required',
            'description' => 'nullable'
        ]);

        // Formatting the item age
        $new_itemAge = $request->itemAge . ' ' . $request->umur;
        
        // Create the item
        Item::create([
            'itemName' => $request -> itemName,
            'itemAge' => $new_itemAge,
            'itemStock' => $request -> itemStock,
            'unit' => $request -> unit,
            'serialNo' => $request -> serialNo,
            'codeMasterItem' => $request -> codeMasterItem,
            'cabang' => $request->cabang,
            'description' => $request -> description
        ]);

        return redirect('/dashboard')->with('status', 'Added Successfully');
    }

    public function editItem(Request $request, Item $item){
        // Edit the requested item
        $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric',
            'umur' => 'required',
            'itemStock' => 'required|numeric',
            'unit' => 'required',
            'serialNo' => 'nullable',
            'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
            'description' => 'nullable'
        ]);

        // Formatting the item age
        $new_itemAge = $request->itemAge . ' ' . $request->umur;
        
        // Update the item
        Item::where('id', $item->id)->update([
            'itemName' => $request -> itemName,
            'itemAge' => $new_itemAge,
            'itemStock' => $request -> itemStock,
            'unit' => $request -> unit,
            'serialNo' => $request -> serialNo,
            'codeMasterItem' => $request -> codeMasterItem,
            'description' => $request -> description
        ]);
        return redirect('/dashboard')->with('status', 'Edit Successfully');
    }

    public function preMakeOrderPage(){
        // Get the page to select which branches to order
        return view('adminLogistic.adminLogisticPreMakeOrderPage');
    }

    public function submittedCabangPage(Request $request){
        // Get the selected branches, then pass it to the form

        return redirect('/admin-logistic/create-order/'.$request->cabang);
    }

    public function createOrderFormPage($request){
        // Get the item on the selected branch
        $cabang = $request;
        $items = Item::where('cabang', $request)->get();

        // Get all the tugs, barges, and cart of the following user
        $barges = Barge::all();
        $tugs = Tug::all();
        $carts = Cart::with('item')->where('user_id', Auth::user()->id)->get();

        return view('adminLogistic.adminLogisticCreateOrderForm', compact('cabang', 'items', 'carts', 'tugs', 'barges'));
    }

    public function addItemToCart(Request $request, $cabang){
        // Validate Cart Request
        $validated = $request->validate([
            'item_id' => 'required',
            'quantity' => 'required | numeric',
            'department' => 'nullable',
            'golongan' => 'required',
            'note' => 'nullable'
        ]);

        // Check if the cart within the user is already > 12 items, then cart is full & return with message
        $counts = Cart::where('user_id', Auth::user()->id)->count();
        if($counts ==  12){
            return redirect('/admin-logistic/create-order/'. $cabang)->with('error', 'Cart is Full');
        }
        
        // Else add item to the cart
        $validated['user_id'] = Auth::user()->id;
        Cart::create($validated);
        
        return redirect('/admin-logistic/create-order/'. $cabang)->with('status', 'Add Item Success');
    }

    public function deleteItemFromCart($cabang, Cart $cart){
        // Delete item from cart of the following user
        Cart::destroy($cart->id);

        return redirect('/admin-logistic/create-order/'. $cabang)->with('status', 'Delete Item Success');
    }

    public function submitOrder(Request $request, $cabang, User $user){
        $request -> validate([
            'tugName' => 'required',
            'bargeName' => 'nullable',
            'company' => 'required'
        ]);

        // Find the cart of the following user
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // Validate cart size, if it is empty then redirect
        if(count($carts) == 0){
            return redirect('/logistic/make-order')->with('errorCart', 'Cart is Empty');
        }

        // Generate unique id for the order_id || Create the order from the cart
        do{
            $unique_id = Str::random(8);
        }while(OrderHead::where('order_id', $unique_id)->exists());

        // String formatting for boatName with tugName + bargeName
        $boatName = $request->tugName . '/' . $request->bargeName;

        // Create Order Head
        $orderHead = OrderHead::create([
            'user_id' => Auth::user()->id,
            'order_id' => $unique_id,
            'cabang' => $cabang,
            'boatName' => $boatName,
            'status' => 'Order In Progress (Purchasing)'
        ]);

        // Formatting the PR format requirements
        $month_arr_in_roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        $cabang_arr = [
            'Jakarta' => 'JKT',
            'Banjarmasin' => 'BNJ',
            'Samarinda' => 'SMD',
            'Bunati' => 'BNT',
            'Babelan' => 'BBL',
            'Berau' => 'BER'
        ];

        $pr_id = $orderHead -> id;
        $first_char_name = strtoupper(Auth::user()->name[0]);
        $location = $cabang_arr[Auth::user()->cabang];
        $month = date('n');
        $month_to_roman = $month_arr_in_roman[$month - 1];
        $year = date('Y');

        // Create the PR Number => 001.A/PR-ISA-SMD/IX/2021
        $pr_number = $pr_id . '.' . $first_char_name . '/' . 'PR-' . $request->company . '-' . $location . '/' . $month_to_roman . '/' . $year;

        OrderHead::where('id', $pr_id)->update([
            'noPr' => $pr_number,
            'company' => $request->company,
            'prDate' => now()
        ]);

        // Then fill the Order Detail with the cart items
        foreach($carts as $c){
            OrderDetail::create([
                'orders_id' => $unique_id,
                'item_id' => $c->item_id,
                'quantity' => $c->quantity,
                'unit' => $c->item->unit,
                'golongan' => $c->golongan,
                'serialNo' => $c->item->serialNo,
                'department' => $c->department,
                'note' => $c->note
            ]);
        }

        // Emptying the cart items
        Cart::where('user_id', Auth::user()->id)->delete();

        return redirect('/admin-logistic/create-order')->with('status', 'Order Success');
    }

    public function historyOutPage(){
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '2')->pluck('users.id');

        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->where('status', 'like', 'Completed', 'and', 'created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_details.created_at', 'desc')->get();

        return view('adminLogistic.adminLogisticHistoryOut', compact('orderHeads'));
    }

    public function historyInPage(){
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '4')->orWhere('role_user.role_id' , '=', '3')->pluck('user_id');

        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->join('suppliers', 'suppliers.id', '=', 'order_heads.supplier_id')->whereIn('user_id', $users)->where('status', 'like', '%' . 'Completed'. '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.updated_at', 'desc')->get();

        return view('adminLogistic.adminLogisticHistoryIn', compact('orderHeads'));
    }

    public function downloadOut(Excel $excel){
        // Exporting the data into excel => command : composer require maatwebsite/excel || php artisan make:export TransactionExport --model=Transaction 
        // Export the data of history goods out
        return $excel -> download(new OrderOutExport, 'OrderGoodsOut_'. date("d-m-Y") . '.xlsx');
    }

    public function downloadIn(Excel $excel){
        // Exporting the data into excel => command : composer require maatwebsite/excel || php artisan make:export TransactionExport --model=Transaction 
        // Export the data of history goods out
        return $excel -> download(new OrderInExport, 'OrderGoodsIn_'. date("d-m-Y") . '.xlsx');
    }
}
