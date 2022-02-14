<?php

namespace App\Http\Controllers;

// use Illuminate\Validation\Validator;
use Validator;
use Carbon\Carbon;
use App\Models\Tug;
use App\Models\Cart;
use App\Models\Item;
use App\Models\User;
use App\Models\Barge;
use App\Models\JobHead;
use App\Models\cartJasa;
use App\Models\OrderHead;
use App\Models\JobDetails;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;


class CrewController extends Controller
{
    
    public function changeBranch(Request $request){
        User::find(Auth::user()->id)->update([
            'cabang' => $request->cabang
        ]);      

        return redirect('/dashboard')->with('status', 'Change Branch Successfully');
    }

    public function completedOrder(){
        // Get all the order within the logged in user within 6 month
        $orderHeads = OrderHead::with('user')->where(function($query){
            $query->where('status', 'like', 'Request Completed (Crew)')
            ->orWhere('status', 'like', 'Request Rejected By Logistic');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->latest()->paginate(10);

        // Get the orderDetail from orders_id within the orderHead table 
        $order_id = OrderHead::where('user_id', Auth::user()->id)->pluck('id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();
        
        $in_progress = OrderHead::where(function($query){
            $query->where('status', 'like', 'Request In Progress By Logistic')
            ->orWhere('status', 'like', 'Items Ready')
            ->orWhere('status', 'like', 'On Delivery');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->count();
        
        $completed = $orderHeads->count();

        return view('crew.crewDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
    }

    public function inProgressOrder(){
        // Get all the order within the logged in user within 6 month
        $orderHeads = OrderHead::with('user')->where(function($query){
            $query->where('status', 'like', 'Request In Progress By Logistic')
            ->orWhere('status', 'like', 'Items Ready')
            ->orWhere('status', 'like', 'On Delivery');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->paginate(10);

        // Get the orderDetail from orders_id within the orderHead table 
        $order_id = OrderHead::where('user_id', Auth::user()->id)->pluck('id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $completed = OrderHead::where(function($query){
            $query->where('status', 'like', 'Request Completed (Crew)')
            ->orWhere('status', 'like', 'Request Rejected By Logistic');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->count();
        
        $in_progress = $orderHeads->count();

        return view('crew.crewDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress'));
    }

    // order_tracker is a validation mechanism (somewhat) to validate if the order is already being processed or not,
    // scenario : 2 people open the page at the same time (A & B), then A processed the order, while B has not refreshed the same page (even tho we already create auto
    // refresh the pages every 60 seconds), then there will be a case where the order in guy B where it has not been processed still shown it can be processed, 
    // while it has already been processed by guy A, 
    // this mechanism will check if the order is already processed or not by checking the number => crew(1), logistic(2), supervisor A(3), supervisor B(4),
    // purchasing (5), if the tracker number is different then it will return the "error" message.

    public function orderPage(){
        // Select items to choose in the order page & carts according to the login user
        $items = Item::where('cabang', Auth::user()->cabang)->where('itemState', 'like', 'Available')->get();
        $barges = Barge::all();
        $tugs = Tug::all();
        $carts = Cart::with('item')->where('cabang', Auth::user()->cabang)->where('user_id', Auth::user()->id)->get();

        return view('crew.crewOrder', compact('items', 'carts', 'tugs', 'barges'));
    }

    public function addItemToCart(Request $request){
        // Validate Cart Request
        $request->validate([
            'item_id' => 'required',
            'department' => 'required',
            'quantity' => 'required|numeric|min:1',
        ]);

        // Check if the item state is on hold, then return error
        $check_item_state = Item::where('id', $request -> item_id)->pluck('itemState')[0];
        if($check_item_state == 'Hold'){
            return redirect('/crew/order')->with('error', 'Item is Unavailable');
        }

        // Check if the cart within the user is already > 12 items, then return with message
        $counts = Cart::where('user_id', Auth::user()->id)->count();
        if($counts ==  12){
            return redirect('/crew/order')->with('error', 'Cart is Full');
        }

         // Find if the same configuration of item is already exist in cart or no
         $itemExistInCart = Cart::where('user_id', Auth::user()->id)->where('item_id', $request->item_id)->where('department', $request->department)->first();

         if($itemExistInCart){
            Cart::find($itemExistInCart->id)->increment('quantity', $request->quantity);
         }else{
            // Else add item to the cart
            Cart::create([
                'user_id' => Auth::user()->id,
                'item_id' => $request->item_id,
                'quantity' => $request->quantity,
                'cabang' => Auth::user()->cabang,
                'department' => $request->department
            ]);  
         }
        
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
        $carts = Cart::where('user_id', Auth::user()->id)->where('cabang', Auth::user()->cabang)->get();

        // Validate cart size, if the cart size is zero then return error message
        if(count($carts) == 0){
            return redirect('/crew/order')->with('errorCart', 'Cart is Empty');
        }

        // Double check the item state, if there are items that is on 'Hold' status, then return error
        foreach($carts as $c){
            if($c -> item -> itemState == 'Hold'){
                return redirect('/crew/order')->with('errorCart', $c -> item -> itemName . ' is Currently Unavailable, Kindly Remove it From the Cart');
            }
        }

        // String formatting for boatName with tugName + bargeName
        $boatName = $request->tugName . '/' . $request->bargeName;

        // Create Order Head firstly
        $o_id = OrderHead::create([
            'user_id' => Auth::user()->id,
            'cabang' => Auth::user()->cabang,
            'boatName' => $boatName,
            'order_tracker' => 1,
            'status' => 'Request In Progress By Logistic'
        ]);

        // Formatted branch for SBK
        $cabang_arr = [
            'Jakarta' => 'JKT',
            'Banjarmasin' => 'BNJ',
            'Samarinda' => 'SMD',
            'Bunati' => 'BNT',
            'Babelan' => 'BBL',
            'Berau' => 'BER'
        ];

        // Update the order id and SBK
        OrderHead::find($o_id->id)->update([
            'order_id' => 'COID#' . $o_id->id,
            'noSbk' => 'SBK/' . $o_id->id . '/' . $cabang_arr[Auth::user()->cabang]
        ]);

        // Then fill the Order Detail with the cart items of the following Order Head
        foreach($carts as $c){
            $serialNo = Item::where('id', $c->item_id)->pluck('serialNo');
            $unit = Item::where('id', $c->item_id)->pluck('unit');
            OrderDetail::create([
                'orders_id' => $o_id -> id,
                'item_id' => $c -> item_id,
                'quantity' => $c -> quantity,
                'acceptedQuantity' => $c -> quantity,
                'unit' => $unit[0],
                'serialNo' => $serialNo[0],
                'department' => $c->department,
            ]);
        }

        // After all of that, emptying the cart items to reset the cart
        Cart::where('user_id', Auth::user()->id)->where('cabang', Auth::user()->cabang)->delete();

        return redirect('/dashboard')->with('status', 'Submit Request Order Success');
    }

    public function acceptOrder(OrderHead $orderHeads){
        // Get the order details of the following order
        // $orderDetails = OrderDetail::where('orders_id', $orderHeads->order_id)->get();

        if($orderHeads -> order_tracker == 1){
            return redirect('/dashboard')->with('error', 'Request Order Already Accepted');
        }

        OrderHead::find($orderHeads -> id)->update([
            'order_tracker' => 1
        ]);

        // Crew accept the order, then the status will be completed
        OrderHead::where('id', $orderHeads->id)->update([
            'status' => 'Request Completed (Crew)',
        ]);

        // foreach($orderDetails as $od){
        //     Item::where('id', $od -> item -> id)->update([
        //         'lastGiven' => date("d/m/Y")
        //     ]);
        //     Item::where('id', $od -> item -> id)->decrement('itemStock', $od -> quantity);
        // }

        return redirect('/dashboard')->with('status', 'Request Order Accepted');
    }

    public function taskPage(){
        // Get all the tugs and barge
        $tugs = Tug::all();
        $barges = Barge::all();

        return view('crew.crewCreateTask', compact('tugs', 'barges'));
    }

    public function createTaskDetailPage(){

        // Get all the tugs and barge
        $tugs = Tug::all();
        $barges = Barge::all();

        return view('crew.crewCreateTaskDetail', compact('tugs', 'barges'));
    }

    //job request
    public function completedJobRequest(){
        // Get all the job request within the logged in user within 6 month
        $JobRequestHeads = JobHead::with('user')->where(function($query){
            $query->where('status', 'like', 'Job Request Completed (Crew)')
            ->orWhere('status', 'like', 'Job Request Rejected By Logistic');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->latest()->paginate(10);

         // Get the jobDetail from jasa_id within the orderHead table 
        $job_id = JobHead::where('user_id', Auth::user()->id)->pluck('id');
        $jobDetails = JobDetails::whereIn('jasa_id', $job_id)->get();
        // Count the completed & in progress job Requests
        
        $job_in_progress = JobHead::where(function($query){
            $query->where('status', 'like', 'Job Request In Progress By Logistic');           
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->count();
        
        $completedJR = $JobRequestHeads->count();
        return view('crew.crewDashboard', compact('job_in_progress','JobRequestHeads' , 'jobDetails', 'completedJR'));
    }

    public function inProgressJobRequest(){
        // Get all the order within the logged in user within 6 month
        $JobRequestHeads = JobHead::with('user')->where(function($query){
            $query->where('status', 'like', 'Job Request In Progress By Logistic');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->paginate(10);

        // Get the orderDetail from orders_id within the orderHead table 
        $job_id = $JobRequestHeads->pluck('id');
        $jobDetails = JobDetails::whereIn('jasa_id', $job_id)->get();

        $job_completed = JobHead::where(function($query){
            $query->where('status', 'like', 'Job Request Completed (Crew)')
            ->orWhere('status', 'like', 'Job Request Rejected By Logistic');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->count();
        
        $JR_in_progress = $JobRequestHeads->count();

        return view('crew.crewDashboard', compact('JR_in_progress' ,'jobDetails' ,'JobRequestHeads','job_completed'));
    }

    public function makeJobPage() {
        // Get all the tugs, barges, and cart of the following user
        $barges = Barge::all();
        $tugs = Tug::all();
        $carts = cartJasa::where('user_id', Auth::user()->id)->get();

        return view('crew.crewMakejob', compact('carts', 'tugs', 'barges'));
    }

    public function ViewJobPage() {
        // Get all the job request within the logged in user within 6 month
        $JobRequestHeads = JobHead::with('user')->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->paginate(7); 
        $job_id = $JobRequestHeads->pluck('id');
        $jobDetails = JobDetails::whereIn('jasa_id', $job_id)->get();

        // Count the completed & in progress job Requests
        $job_completed = JobHead::where(function($query){
            $query->where('status', 'like', 'Job Request Approved By Logistics')
            ->orWhere('status', 'like', 'Job Request Rejected By Logistic');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->count();
        
        $job_in_progress = JobHead::where(function($query){
            $query->where('status', 'like', 'Job Request In Progress By Logistics');
        })->where('user_id', 'like', Auth::user()->id)->whereYear('created_at', date('Y'))->count();


        return view('crew.crewListJobOrder', compact('job_completed','job_in_progress','jobDetails','JobRequestHeads'));
    }

    public function addjasaToCart(Request $request){
        // Validate Cart Request
        $checkinput = $request->validate([
            'tugName' => ['nullable','regex:/^[A-Za-z_-][A-Za-z0-9_-]*$/'] ,
            'bargeName' => ['nullable','regex:/^[A-Za-z_-][A-Za-z0-9_-]*$/'] ,
            'quantity' => ['required' , 'numeric'],
            'note' => ['required' , 'string']
        ]);

        // Check if the cart within the user is already > 12 items, then cart is full & return with message
        $counts = cartJasa::where('user_id', Auth::user()->id)->count();
        if($counts ==  12){
            return redirect('/crew/make-Job')->with('error', 'Cart is Full');
        }

        

        // Find if the same configuration of item is already exist in cart or no
        $itemExistInCart = cartJasa::where('user_id', Auth::user()->id)->whereRaw('LOWER(`note`) LIKE ? ',strtolower($request->note))->where('lokasi', $request->lokasi)->first();
        if($itemExistInCart){
            cartJasa::find($itemExistInCart->id)->increment('quantity', $request->quantity);
        }else{
        // Else add item to the cart
            cartJasa::create([
                'tugName' => $request->tugName ,
                'bargeName' => $request->bargeName ,
                'lokasi' => $request->lokasi ,
                'quantity' => $request->quantity ,
                'note' => $request->note,
                // Add cabang & user id to the cart
                'cabang' => Auth::user()->cabang,
                'user_id'=> Auth::user()->id
            ]);
        }
 
        return Redirect::back()->withInput()->with('success', 'Add Item Success');
    }

    public function deleteJasaFromCart(cartJasa $cart){
        // Delete item from cart of the following user
        cartJasa::destroy($cart->id);

        return redirect('/crew/make-Job')->with('status', 'Delete Item Success');
    }

    public function submitJasa(){
        // Find the cart of the following user
        $carts = cartJasa::where('user_id', Auth::user()->id)->get();

        // Validate cart size
        if(count($carts) == 0){
            return redirect('/crew/make-Job')->with('errorCart', 'Cart is Empty');
        }
        
            // Formatting the PR format requirements
            $month_arr_in_roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

            $cabang_arr = [
                'Jakarta' => 'JKT',
                'Banjarmasin' => 'BNJ',
                'Samarinda' => 'SMD',
                'Bunati' => 'BNT',
                'Babelan' => 'BBL',
                'Berau' => 'BER',
                "Kendari" => 'KDR',
                "Morosi" => "MRS"
            ];
         
            
            // Create job request Head
            $JobHead = JobHead::create([
                'user_id' => Auth::user()->id,
                'created_by' => Auth::user()->name,
                'cabang' => Auth::user()->cabang,
                'status' => 'Job Request In Progress By Logistics',
                'jrDate' => date("Y/m/d"),
                'Headjasa_tracker_id' => 1 ,
            ]);

            $Jr_id = $JobHead -> id;
            $headID = 'JRID#' . $JobHead -> id;
            $first_char_name = strtoupper(Auth::user()->name[0]);
            $location = $cabang_arr[Auth::user()->cabang];
            $month = date('n');
            $month_to_roman = $month_arr_in_roman[$month - 1];
            $year = date('Y');

            // Create the JR Number => 001.A/JR-SMD/IX/2021
            $Jr_number = $Jr_id . '.' . $first_char_name . '/' . 'JR-'. $location . '/' . $month_to_roman . '/' . $year;

            JobHead::find($JobHead->id)->update([
                'noJr' => $Jr_number,
                'Headjasa_id' => $headID,
            ]);

            // Then fill the job Detail with the cart items
            foreach($carts as $c){
                JobDetails::create([
                    'user_id' => Auth::user()->id,
                    'jasa_id' => $JobHead -> id,
                    'cabang' => $c->cabang,
                    'tugName' => $c ->tugName,
                    'bargeName' => $c ->bargeName,
                    'quantity' => $c->quantity ,
                    'lokasi' => $c ->lokasi ,
                    'note' => $c ->note,
                ]);
            }
    
            // Emptying the cart items
            cartJasa::where('user_id', Auth::user()->id)->delete();
        return redirect('/dashboard')->with('status', 'Submit Order Success');
    }
}
