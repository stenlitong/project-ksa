<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\User;
use App\Models\Tug;
use App\Models\Barge;
use App\Models\OrderDo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Str;
use App\Exports\OrderOutExport;
use App\Exports\OrderInExport;
use App\Exports\PRExport;
use App\Exports\DOExport;
use App\Exports\PurchasingReportExport;
use Maatwebsite\Excel\Excel;
// Use \Carbon\Carbon;
use Storage;

class LogisticController extends Controller
{
    public function inProgressOrder(){
        // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
        $month_now = (int)(date('m'));
        if($month_now <= 6){
            $start_date = date('Y-01-01');
            $end_date = date('Y-06-30');
        }else{
            $start_date = date('Y-07-01');
            $end_date = date('Y-12-31');
        }

        if(request('search')){
             // Search functonality
             $orderHeads = OrderHead::with('user')->where(function($query){
                $query->where('status', 'like', '%'. request('search') .'%')
                ->orWhere( 'order_id', 'like', '%'. request('search') .'%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(7)->withQueryString();
            
            // Get all the order detail
            $order_id = OrderHead::whereBetween('created_at', [$start_date, $end_date])->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'Completed' . '%')
                ->orWhere('status', 'like', '%' . 'Rejected' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();
            
            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress' . '%')
                ->orWhere('status', 'like', 'Items Ready')
                ->orWhere('status', 'like', 'On Delivery')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
        }else{
            // Find all of the order that is "in progress" state
            $orderHeads = OrderHead::with('user')->where(function($query){
                $query->where('status', 'like', '%' . 'In Progress' . '%')
                ->orWhere('status', 'like', 'Items Ready')
                ->orWhere('status', 'like', 'On Delivery')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(7);

            // Then get all the order detail
            $order_id = OrderHead::whereBetween('created_at', [$start_date, $end_date])->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Get the count number of the completed and in progress order to show it on the view
            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'Completed' . '%')
                ->orWhere('status', 'like', '%' . 'Rejected' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            $in_progress = $orderHeads->count();

            return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
        }
    }

    public function completedOrder(){
        // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
        $month_now = (int)(date('m'));
        if($month_now <= 6){
            $start_date = date('Y-01-01');
            $end_date = date('Y-06-30');
        }else{
            $start_date = date('Y-07-01');
            $end_date = date('Y-12-31');
        }

        if(request('search')){
            // Search functonality
            $orderHeads = OrderHead::with('user')->where(function($query){
                $query->where('status', 'like', '%'. request('search') .'%')
                ->orWhere( 'order_id', 'like', '%'. request('search') .'%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(7)->withQueryString();
            
            // Get all the order detail
            $order_id = OrderHead::whereBetween('created_at', [$start_date, $end_date])->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

            // Count the completed & in progress order
            $completed = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'Completed' . '%')
                ->orWhere('status', 'like', '%' . 'Rejected' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();
            
            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress' . '%')
                ->orWhere('status', 'like', 'Items Ready')
                ->orWhere('status', 'like', 'On Delivery')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();

            return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
        }else{
            $orderHeads = OrderHead::with('user')->where(function($query){
                $query->where('status', 'like', '%' . 'Completed' . '%')
                ->orWhere('status', 'like', '%' . 'Rejected' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->paginate(7);
    
            // Get all the order detail
            $order_id = OrderHead::whereBetween('created_at', [$start_date, $end_date])->pluck('id');
            $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();
    
             // Count the completed & in progress order
            $in_progress = OrderHead::where(function($query){
                $query->where('status', 'like', '%' . 'In Progress' . '%')
                ->orWhere('status', 'like', 'Items Ready')
                ->orWhere('status', 'like', 'On Delivery')
                ->orWhere('status', 'like', '%' . 'Rechecked' . '%')
                ->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%');
            })->where('cabang', 'like', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->count();
    
            $completed = $orderHeads->count();
    
            return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
        }
    }

    public function stocksPage(){
        // Logistic can see the stocks of all branches
        if(request('search')){
            if(request('search') == 'All'){
                $items = Item::orderBy('cabang')->Paginate(7)->withQueryString();
            }else{
                $items = Item::where(function($query){
                    $query->where('itemName', 'like', '%' . request('search') . '%')
                    ->orWhere('cabang', 'like', '%' . request('search') . '%')
                    ->orWhere('codeMasterItem', 'like', '%' . request('search') . '%');
                })->Paginate(7)->withQueryString();
            }
            return view('logistic.stocksPage', compact('items'));
        }else{
            // $branch_items = Item::where('cabang', Auth::user()->cabang)->get();

            // $other_branch_items = Item::where('cabang', 'not like', Auth::user()->cabang)->orderBy('cabang', 'asc')->get();

            // $items = collect();

            // $items = $items->merge($branch_items)->merge($other_branch_items)->paginate(7)->withQueryString();

            $items = Item::where('cabang', Auth::user()->cabang)->latest()->Paginate(7)->withQueryString();
            return view('logistic.stocksPage', compact('items'));
        }
    }

    // order_tracker is a validation mechanism (somewhat) to validate if the order is already being processed or not,
    // scenario : 2 people open the page at the same time (A & B), then A processed the order, while B has not refreshed the same page (even tho we already create auto
    // refresh the pages every 60 seconds), then there will be a case where the order in B where it has not been processed STILL shown it can be processed, 
    // while it has already been processed by A, 
    // this mechanism will check if the order is already processed or not by checking the number => crew(1), logistic(2), supervisor A(3), supervisor B(4),
    // purchasing (5), if the tracker number is same that means it already been processed, then it will return the "error" message.

    public function requestStock(Request $request, Item $items){
        // Request stock validation
        $request->validate([
           'itemName' => 'required',
           'cabang' => 'required',
           'quantity' => 'required|numeric|min:1',
           'description' => 'nullable'
        ]);

        // Find the item on the respective branches
        $itemToFound = Item::where('itemName', $items->itemName)->where('cabang', Auth::user()->cabang)->first();

        // Check if the same item is exist OR if the requested quantity is more than the available stock, then return error
        // case #1 => item request from Jakarta -> Bahan Bakar, then the item from requested branch Banjarmasin -> Bahan Bakarr, return error cause it is not exist/not the same
        // case #2 => CASE SENSITIVE MATTER, Bahan Bakar !== BAHAN Bakar, they need to make sure their naming correct
        if($itemToFound === null || ($request -> quantity > $items -> itemStock)){
            return redirect('/logistic/stocks')->with('itemInvalid', 'Invalid Item/Stock');
        }else{
            // Else, create a DO request
            OrderDo::create([
                'user_id' => Auth::user()->id,
                'item_requested_id' => $itemToFound -> id,
                'item_requested_from_id' => $items -> id,
                'quantity' => $request -> quantity,
                'status' => 'In Progress By Supervisor Cabang ' . Auth::user()->cabang,
                'fromCabang' => Auth::user()->cabang,
                'toCabang' => $request -> cabang,
                'order_tracker' => 2,
                'description' => $request -> description
            ]); 

            return redirect('/logistic/request-do')->with('success', 'Request Successfully');
        }
    }

    public function requestDoPage(){
        // Find the current month, display the transaction per 6 month => Jan - Jun || Jul - Dec
        $month_now = (int)(date('m'));
        if($month_now <= 6){
            $start_date = date('Y-01-01');
            $end_date = date('Y-06-30');
        }else{
            $start_date = date('Y-07-01');
            $end_date = date('Y-12-31');
        }

        // Get all the DO from the last 6 month
        $ongoingOrders = OrderDo::with(['item_requested', 'user'])->where('fromCabang', Auth::user()->cabang)->whereBetween('created_at', [$start_date, $end_date])->latest()->get();

        return view('logistic.logisticOngoingDO', compact('ongoingOrders'));
    }

    public function acceptDo(OrderDo $orderDos){
        // Validate if the order already been processed
        if($orderDos -> order_tracker == 2){
            return redirect('/logistic/request-do')->with('error', 'Order Already Been Accepted');
        }

        // Increment the stock for the requester branch
        Item::where('id', $orderDos -> item_requested_id)->increment('itemStock', $orderDos -> quantity);

        // Update the status of the DO
        OrderDo::where('id', $orderDos -> id)->update([
            'order_tracker' => 2,
            'status' => 'Accepted'
        ]);

        // Then redirect/refresh page
        return redirect('/logistic/request-do')->with('success', 'Order Accepted Successfully');
    }

    public function downloadDo(OrderDo $orderDos){
        // Find the specific DO, then download it
        return (new DOExport($orderDos -> id))->download('DO-' . $orderDos -> id . '_' .  date("d-m-Y") . '.xlsx');
    }

    public function rejectOrder(Request $request, OrderHead $orderHeads){
        // Reject the order made from crew
        $request->validate([
            'reason' => 'required'
        ]);

        // Check if the order already been processed or not
        if($orderHeads -> order_tracker == 2){
            return redirect('/dashboard')->with('error', 'Order Already Been Processed');
        }

        // If not, then proceed
        OrderHead::where('id', $orderHeads->id)->update([
            'status' => 'Request Rejected By Logistic',
            'order_tracker' => 2,
            'reason' => $request->reason
        ]);
        return redirect('/dashboard')->with('status', 'Order Rejected Successfully');
    }

    public function approveOrderPage(OrderHead $orderHeads){
        // Get the order details join with the item
        $orderDetails = OrderDetail::with('item')->where('orders_id', $orderHeads->id)->get();

        return view('logistic.logisticApprovedOrder', compact('orderDetails', 'orderHeads'));
    }

    public function editAcceptedQuantity(Request $request, OrderHead $orderHeads, OrderDetail $orderDetails){
        $request -> validate([
            'acceptedQuantity' => 'required|numeric|min:1'
        ]);

        if($request -> acceptedQuantity > $orderDetails -> item -> itemStock){
            return redirect('/logistic/order/' . $orderHeads -> id . '/approve')->with('error', 'Stocks Insufficient, Kindly Re-Check the Stocks');
        }

        OrderDetail::find($orderDetails -> id)->update([
            'acceptedQuantity' => $request -> acceptedQuantity
        ]);
        return redirect('/logistic/order/' . $orderHeads -> id . '/approve')->with('status', 'Stocks request updated successfully');
    }

    public function approveOrder(Request $request, OrderHead $orderHeads){
        // Validate
        $request -> validate([
            'boatName' => 'required',
            'sender' => 'required',
            'receiver' => 'required',
            'expedition' => 'required',
            'company' => 'required',
            'noResi' => 'nullable',
            'description' => 'nullable',
        ]);

        // Get the order details for the following order
        $orderDetails = OrderDetail::where('orders_id', $orderHeads->id)->get();

        //If the stock is not enough then redirect to dashboard with error
        foreach($orderDetails as $od){
            $itemSum = $orderDetails->where('item_id', $od -> item_id)->sum('acceptedQuantity');

            // Pluck return an array
            if(Item::where('id', $od -> item -> id)->pluck('itemStock')[0] < $itemSum){
                return redirect('/dashboard')->with('error', 'Stocks Insufficient, Kindly Re-Check the Stocks');
            }
        }

        // Check the order tracker, if it already been processed then return
        if($orderHeads -> order_tracker == 2){
            return redirect('/dashboard')->with('error', 'Order Already Been Processed');
        }

        // Determine the status
        if($request->expedition == 'onsite'){
            $status = 'Items Ready';
        }else{
            $status = 'On Delivery';
        }

        // If the stock checker passed, then decrement each item for the following order
        foreach($orderDetails as $od){
            Item::where('id', $od -> item -> id)->update([
                'lastGiven' => date("d/m/Y")
            ]);
            Item::where('id', $od -> item -> id)->decrement('itemStock', $od -> acceptedQuantity);
            // $sumPrice += $od -> acceptedQuantity * filter_var($od -> item -> itemPrice, FILTER_SANITIZE_NUMBER_INT);
        }

        // Update the status of the following order
        OrderHead::where('id', $orderHeads -> id)->update([
            'status' => $status,
            'sender' => $request -> sender,
            'receiver' => $request -> receiver,
            'expedition' => $request -> expedition,
            'noResi' => $request -> noResi,
            'order_tracker' => 2,
            'descriptions' => $request -> description,
            'approved_at' => date("d/m/Y")
        ]);

        //============ After approving, then automatically create new PR ======================
        // Create Order Head
        $orderHead = OrderHead::create([
            'user_id' => Auth::user()->id,
            'cabang' => Auth::user()->cabang,
            'boatName' => $request->boatName,
            'created_by' => Auth::user()->name,
            'order_tracker' => 2,
            'status' => 'Order In Progress By Supervisor'
        ]);

        // Formatting the PR format requirements
        $month_arr_in_roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        // Cabang object
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

        OrderHead::find($orderHead->id)->update([
            'order_id' => 'ROID#' . $orderHeads->id,
            'noPr' => $pr_number,
            'company' => $request->company,
            'prDate' => date("d/m/Y")
        ]);

        foreach($orderDetails as $od){
            OrderDetail::create([
                'orders_id' => $orderHead->id,
                'item_id' => $od->item_id,
                'quantity' => $od->acceptedQuantity,
                'unit' => $od->item->unit,
                'golongan' => $od->golongan,
                'serialNo' => $od->item->serialNo,
                'department' => $od->department,
            ]);
        }

        return redirect('/dashboard')->with('status', 'Order Approved');
    }

    public function historyOutPage(){
        // Find order from crew role/goods out
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '2')->pluck('users.id');
        
        // Find all the items that has been approved/completed from the user feedback | last 6 month
        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->where('cabang', 'like', Auth::user()->cabang)->where('status', 'like', '%' . 'Request Completed' . '%')->whereMonth('order_heads.created_at', date('m'))->whereYear('order_heads.created_at', date('Y'))->orderBy('order_details.created_at', 'desc')->get();

        return view('logistic.logisticHistory', compact('orderHeads'));
    }

    public function historyInPage(){
        // Find order from logistic role/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->pluck('users.id');
        
        // Find all the items that has been approved from the user | last 6 month
        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.id', '=', 'order_details.orders_id')->join('suppliers', 'suppliers.id', '=', 'order_details.supplier_id')->whereIn('user_id', $users)->where('cabang', 'like', Auth::user()->cabang)->where('status', 'like', '%' . 'Order Completed'. '%')->whereMonth('order_heads.created_at', date('m'))->whereYear('order_heads.created_at', date('Y'))->orderBy('order_heads.updated_at', 'desc')->get();

        return view('logistic.logisticHistoryIn', compact('orderHeads'));
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

    public function makeOrderPage(){
        // logistic role can only select the items that are only available to their branches & carts according to the login user
        $items = Item::where('cabang', Auth::user()->cabang)->where('itemState', 'like', 'Available')->get();

        // Get all the tugs, barges, and cart of the following user
        $barges = Barge::all();
        $tugs = Tug::all();
        $carts = Cart::with('item')->where('user_id', Auth::user()->id)->get();

        return view('logistic.logisticMakeOrder', compact('items', 'carts', 'tugs', 'barges'));
    }

    public function addItemToCart(Request $request){
        // Validate Cart Request
        $validated = $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'department' => 'nullable',
            'note' => 'nullable'
        ]);

        // Check if the item state is on hold, then return error
        $check_item_state = Item::where('id', $request -> item_id)->pluck('itemState')[0];
        if($check_item_state == 'Hold'){
            return redirect('/crew/order')->with('error', 'Item is Unavailable');
        }

        // Check if the cart within the user is already > 12 items, then cart is full & return with message
        $counts = Cart::where('user_id', Auth::user()->id)->count();
        if($counts ==  12){
            return redirect('/logistic/make-order')->with('error', 'Cart is Full');
        }

        // Find if the same configuration of item is already exist in cart or no
        $itemExistInCart = Cart::where('user_id', Auth::user()->id)->where('item_id', $request->item_id)->where('department', $request->department)->where('golongan', $request->golongan)->first();

        if($itemExistInCart){
            Cart::find($itemExistInCart->id)->increment('quantity', $request->quantity);
            Cart::find($itemExistInCart->id)->update([
                'note' => $request -> note
            ]);
        }else{
            // Add cabang to the cart
            $validated['cabang'] = Auth::user()->cabang;
            // Then add item to the cart
            $validated['user_id'] = Auth::user()->id;
            Cart::create($validated);
        }

        return redirect('/logistic/make-order')->with('status', 'Add Item Success');
    }

    public function deleteItemFromCart(Cart $cart){
        // Delete item from cart of the following user
        Cart::destroy($cart->id);

        return redirect('/logistic/make-order')->with('status', 'Delete Item Success');
    }

    public function submitOrder(Request $request){
        $request -> validate([
            'tugName' => 'required',
            'bargeName' => 'nullable',
            'company' => 'required',
            'orderType' => 'required|in:Susulan,Real Time',
            'descriptions' => 'nullable'
        ]);

        // Find the cart of the following user
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // Double check the item state, if there are items that is on 'Hold' status, then return error
        foreach($carts as $c){
            if($c -> item -> itemState == 'Hold'){
                return redirect('/crew/order')->with('errorCart', $c -> item -> itemName . ' is Currently Unavailable, Kindly Remove it From the Cart');
            }
        }

        // Validate cart size
        if(count($carts) == 0){
            return redirect('/logistic/make-order')->with('errorCart', 'Cart is Empty');
        }

        // String formatting for boatName with tugName + bargeName
        $boatName = $request->tugName . '/' . $request->bargeName;
        
        // Create Order Head
        $orderHead = OrderHead::create([
            'created_by' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'cabang' => Auth::user()->cabang,
            'boatName' => $boatName,
            'orderType' => $request -> orderType,
            'order_tracker' => 2,
            'status' => 'Order In Progress By Supervisor',
            'descriptions' => $request -> descriptions
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

        OrderHead::find($orderHead->id)->update([
            'order_id' => 'LOID#' . $orderHead->id,
            'noPr' => $pr_number,
            'company' => $request->company,
            'prDate' => date("d/m/Y")
        ]);

        // Then fill the Order Detail with the cart items
        foreach($carts as $c){
            OrderDetail::create([
                'orders_id' => $orderHead -> id,
                'item_id' => $c -> item_id,
                'quantity' => $c -> quantity,
                'acceptedQuantity' => $c -> quantity,
                'unit' => $c -> item -> unit,
                'serialNo' => $c -> item->serialNo,
                'department' => $c -> department,
                'note' => $c -> note
            ]);
        }

        // Emptying the cart items
        Cart::where('user_id', Auth::user()->id)->delete();

        return redirect('/dashboard')->with('status', 'Submit Order Success');
    }

    public function acceptStockOrder(OrderHead $orderHeads){
        // Check if the order is already been processed or not
        if($orderHeads -> order_tracker == 2){
            return redirect('/dashboard')->with('error', 'Order Already Been Processed');
        }
        
        // Get the order details of the following order
        $orderDetails = OrderDetail::where('orders_id', $orderHeads->id)->get();
        
        // Update the stock by adding the amount of the ordered items
        foreach($orderDetails as $od){
            Item::where('id', $od -> item -> id)->increment('itemStock', $od -> quantity);
        }

        OrderHead::find($orderHeads->id)->update([
            'status' => 'Order Completed (Logistic)',
            'order_tracker' => 2,
            'approved_at' => date("d/m/Y")
        ]);

        return redirect('/dashboard')->with('status', 'Order Completed');
    }

    public function downloadPr(OrderHead $orderHeads){
        // dd($orderHeads->id);

        return (new PRExport($orderHeads -> order_id))->download('PR-' . $orderHeads -> order_id . '_' .  date("d-m-Y") . '.pdf', Excel::DOMPDF);
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

        // Find order from user/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->where('cabang', 'like', Auth::user()->cabang)->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
        
        // Find all the items that has been approved from the logistic | last 6 month
        // $orderHeads = OrderHead::whereIn('user_id', $users)->where('status', 'like', 'Order Completed (Logistic)')->whereBetween('order_heads.created_at', [$start_date, $end_date])->where('cabang', 'like', Auth::user()->cabang)->orderBy('order_heads.updated_at', 'desc')->get();

        $orders = OrderDetail::with(['item', 'supplier'])->join('order_heads', 'order_heads.id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->where('status', 'like', 'Order Completed (Logistic)')->whereBetween('order_heads.created_at', [$start_date, $end_date])->where('cabang', 'like', Auth::user()->cabang)->orderBy('order_heads.updated_at', 'desc')->get();

        return view('logistic.logisticReport', compact('orders'));
    }

    public function downloadReport(Excel $excel){

        return $excel -> download(new PurchasingReportExport(Auth::user()->cabang), 'Reports_'. date("d-m-Y") . '.xlsx');
    }

    // ============================ Testing Playgrounds ===================================

    public function uploadItem(Request $request){
        // Testing upload to S3 function

        $path = "storage/files";
        $filename = "file_pdf.".$request->fileInput->getClientOriginalExtension();
        $file = $request->file('fileInput');

        $url = Storage::disk('s3')->url($path."/".$filename);
        dd($url);

        Storage::disk('s3')->delete($path."/".$filename);

        $file->storeAs(
            $path,
            $filename,
            's3'
        );
        
        // $url = Storage::disk('s3')->temporaryUrl(
        //     $path
        // )
        // return redirect('/dashboard');
    }
}
