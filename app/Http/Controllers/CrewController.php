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
use App\Models\OperationalBoatData;
use DateTime;

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
            'tugName' => 'required|exists:tugs,tugName',
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

    // ============================ Task Section =========================

    public function taskPage()
    {   
        // Find There Is An Ongoing Task Running
        $ongoingTask = OperationalBoatData::where('user_id', Auth::user()->id)->where('status', 'On Going')->get();

        // Get all the tugs and barge
        $tugs = Tug::all();
        $barges = Barge::all();

        // Check If There Is No Task Running, Redirect To Create Task Page
        if(count($ongoingTask) == 0){
            return view('crew.crewCreateTask', compact('tugs', 'barges'));

        // Check If There Is Task Running, Then User Can Access The Ongoing Task Page
        }elseif(count($ongoingTask) == 1){

            return redirect('/crew/ongoing-task')->with('error', 'There Is Still An Ongoing Task');
        }
    }

    public function createTaskPost(Request $request){
        // Validate Request
        $validated = $request -> validate([
            'tugName' => 'required|exists:tugs,tugName',
            'bargeName' => 'required|exists:barges,bargeName',
            'jetty' => 'required|string',
            'cargoAmountStart' => 'required|numeric|min:1',
            // 'customer' => 'required|alpha',
            'taskType' => 'required|in:Operational Shipment,Operational Transhipment'
        ]);

        $validated['jetty'] = strtoupper($request -> jetty);

        // Add User Id To The Collection
        $validated['user_id'] = Auth::user()->id;
        
        // Create The Data
        OperationalBoatData::create($validated);

        // Then Redirect
        return redirect('/crew/ongoing-task')->with('status', 'Task Created Successfully');
    }

    public function ongoingTaskPage(){
        // Find There Is An Ongoing Task Running
        $ongoingTask = OperationalBoatData::where('user_id', Auth::user()->id)->where('status', 'On Going')->get();

        // Check If There Is No Task Running, Redirect To Create Task Page
        if(count($ongoingTask) == 0){
            return redirect('/crew/create-task')->with('failed', 'No Ongoing Task Is Running');
        // Check If There Is Task Running, Then User Can Access The Ongoing Task Page
        }elseif(count($ongoingTask) == 1){
            // Get all the tugs and barge
            $tugs = Tug::all();
            $barges = Barge::all();

            return view('crew.crewCreateTaskDetail', compact('tugs', 'barges', 'ongoingTask'));
        }
    }

    public function updateOngoingTask(Request $request){
        $operationalData = OperationalBoatData::where('id', $request -> taskId)->first();

        if($operationalData -> taskType == 'Return Cargo'){
            // Validate All The Return Cargo Fields
            $validated = $request -> validate([
                'condition' => 'required|alpha',
                'estimatedTime' => 'nullable|regex:/^[a-zA-Z0-9\s]+$/',
                'cargoAmountEndCargo' => 'nullable|numeric|min:1',
                'description' => 'nullable',

                // Return Cargo
                'arrivalPODCargo' => 'nullable|date',
                'startAsideMVCargo' => 'nullable|date',
                'asideMVCargo' => 'nullable|date',
                'commMVCargo' => 'nullable|date',
                'compMVCargo' => 'nullable|date',
                'cOffMVCargo' => 'nullable|date',

            ]);
        }else{
            // Validate All The Fields
            $validated = $request -> validate([
                'from' => 'required|string',
                'to' => 'required|string',
                'condition' => 'required|string',
                'customer' => 'nullable',
                'estimatedTime' => 'nullable|regex:/^[a-zA-Z0-9\s]+$/',
                'cargoAmountEnd' => 'nullable|numeric|min:1',
                'description' => 'nullable',
    
                // Operational Shipment
                'arrivalTime' => 'nullable|date',
                'departureTime' => 'nullable|date',
                'startAsideL' => 'nullable|date',
                'asideL' => 'nullable|date',
                'commenceLoadL' => 'nullable|date',
                'completedLoadingL' => 'nullable|date',
                'cOffL' => 'nullable|date',
                'DOH' => 'nullable|date',
                'DOB' => 'nullable|date',
                'departurePOD' => 'nullable|date',
                'arrivalPODGeneral' => 'nullable|date',
                'startAsidePOD' => 'nullable|date',
                'asidePOD' => 'nullable|date',
                'commenceLoadPOD' => 'nullable|date',
                'completedLoadingPOD' => 'nullable|date',
                'cOffPOD' => 'nullable|date',
                'DOBPOD' => 'nullable|date',
    
                // Operational Transhipment
                'faVessel' => 'nullable|date',
                'arrivalPOL' => 'nullable|date',
                'startAsideMVTranshipment' => 'nullable|date',
                'asideMVTranshipment' => 'nullable|date',
                'commMVTranshipment' => 'nullable|date',
                'compMVTranshipment' => 'nullable|date',
                'cOffMVTranshipment' => 'nullable|date',
                'departureJetty' => 'nullable|date',
            ]);
        }

        // Validation Of CargoAmountEnd, Max 2 Updates => Crew can only update twice of cargo amount, Operational Transhipment and Return Cargo does not share the updates
        if($operationalData -> taskType == 'Return Cargo'){
            if($operationalData -> cargo2ChangeTracker < 2 && $operationalData -> cargoAmountEnd != $request -> cargoAmountEnd){
                $validated['cargo2ChangeTracker'] = $operationalData -> cargo2ChangeTracker + 1;
            }elseif($operationalData -> cargo2ChangeTracker == 2 && $operationalData -> cargoAmountEnd != $request -> cargoAmountEnd){
                return redirect()->back()->with('error', 'Has Reached The Maximum Amount Of Jumlah Kargo Update');
            }
        }else{
            if($operationalData -> cargoChangeTracker < 2 && $operationalData -> cargoAmountEnd != $request -> cargoAmountEnd){
                $validated['cargoChangeTracker'] = $operationalData -> cargoChangeTracker + 1;
            }elseif($operationalData -> cargoChangeTracker == 2 && $operationalData -> cargoAmountEnd != $request -> cargoAmountEnd){
                return redirect()->back()->with('error', 'Has Reached The Maximum Amount Of Jumlah Kargo Update');
            }
        }

        $validated['from'] = strtolower($request -> from);
        $validated['to'] = strtolower($request -> to);

        // Update The Following Task Id
        OperationalBoatData::where('id', $request -> taskId)->update($validated);

        $operationalData = OperationalBoatData::where('id', $request -> taskId)->first();

        // Data Calculation

        // Document = Departure POD - D.O.B
        $document = !empty($operationalData -> departurePOD) && !empty($operationalData -> DOB) ? date_diff(new DateTime($operationalData -> departurePOD), new DateTime($operationalData -> DOB))->format('%h.%i') : 0;

        $calculation['document'] = (double) $document;

        if($operationalData -> taskType == 'Operational Shipment'){
            // Total Time = Arrival Time - Departure time
            $totalTime = !empty($operationalData -> arrivalTime) && !empty($operationalData -> departureTime) ? date_diff(new DateTime($operationalData -> arrivalTime), new DateTime($operationalData -> departureTime))->format('%D Days %H Hours') : 'n/a';

            $calculation['totalTime'] = $totalTime;
        }elseif($operationalData -> taskType == 'Operational Transhipment'){
            // Sailing To Jetty = (Arrival POL - F/A Vessel)
            $sailingToJetty = !empty($operationalData -> arrivalPOL) && !empty($operationalData -> faVessel) ? date_diff(new DateTime($operationalData -> arrivalPOL), new DateTime($operationalData -> faVessel))->format('%h.%i') : 0;

            // Prepare Ldg = (Commence Load (L) -Aside (L))
            $prepareLdg = !empty($operationalData -> commenceLoadL) && !empty($operationalData -> asideL) ? date_diff(new DateTime($operationalData -> commenceLoadL), new DateTime($operationalData -> asideL))->format('%h.%i') : 0;

            // Ldg Time = (C/Off (L) - Commence Load (L))
            $ldgTime = !empty($operationalData -> cOffL) && !empty($operationalData -> commenceLoadL) ? date_diff(new DateTime($operationalData -> cOffL), new DateTime($operationalData -> commenceLoadL))->format('%h.%i') : 0;

            // Ldg Rate = Quantity : Actual Ldg Time
            $ldgRate = $operationalData -> cargoAmountEnd != 0 && (double) $ldgTime > 0 ? (double) $operationalData -> cargoAmountEnd / (double) $ldgTime : 0;

            // Berthing = (Aside (L) - Start Aside (L))
            $berthing = !empty($operationalData -> asideL) && !empty($operationalData -> startAsideL) ? date_diff(new DateTime($operationalData -> asideL), new DateTime($operationalData -> startAsideL))->format('%h.%i') : 0;

            // Unberthing = DOH - C/OFF (L)
            $unberthing = !empty($operationalData -> DOH) && !empty($operationalData -> cOffL) ? date_diff(new DateTime($operationalData -> DOH), new DateTime($operationalData -> cOffL))->format('%h.%i') : 0;

            // Sailing to MV = (Arrival POD - Departure POD)
            $sailingToMV = !empty($operationalData -> arrivalPODGeneral) && !empty($operationalData -> departurePOD) ? date_diff(new DateTime($operationalData -> arrivalPODGeneral), new DateTime($operationalData -> departurePOD))->format('%h.%i') : 0;

            // Disch Time = (Comp (MV) - Comm (MV))
            $dischTime = !empty($operationalData -> compMVTranshipment) && !empty($operationalData -> commMVTranshipment) ? date_diff(new DateTime($operationalData -> compMVTranshipment), new DateTime($operationalData -> commMVTranshipment))->format('%h.%i') : 0;

            // Disch Rate / day = (Quantity - Actual Disch Time)
            $dischRate = $operationalData -> cargoAmountEnd != 0 && !empty($dischTime) ? (double) $operationalData -> cargoAmountEnd - (double) $dischTime : 0;

            // Manuever = (Aside (MV) - Start Aside (MV))
            $maneuver = !empty($operationalData -> asideMVTranshipment) && !empty($operationalData -> startAsideMVTranshipment) ? date_diff(new DateTime($operationalData -> asideMVTranshipment), new DateTime($operationalData -> startAsideMVTranshipment))->format('%h.%i') : 0;

            // Cycle Time = Disch Time + Manuever + Sailing to MV + Unberthing + Ldg Time + Prepare Ldg + Berthing + Sailing to Jetty
            $cycleTime = !empty($dischTime) && !empty($maneuver) && !empty($sailingToMV) && !empty($unberthing) && !empty($ldgTime) && !empty($prepareLdg) && !empty($berthing) && !empty($sailingToJetty) ? 
            (double) $dischTime + (double) $maneuver + (double) $sailingToMV + (double) $unberthing + (double) $ldgTime + (double) $prepareLdg + (double) $berthing + (double) $sailingToJetty : 
            0;

            $calculation['sailingToJetty'] = (double) $sailingToJetty;
            $calculation['prepareLdg'] = (double) $prepareLdg;
            $calculation['ldgTime'] = (double) $ldgTime;
            $calculation['ldgRate'] = $ldgRate;
            $calculation['berthing'] = (double) $berthing;
            $calculation['unberthing'] = (double) $unberthing;
            $calculation['sailingToMV'] = (double) $sailingToMV;
            $calculation['dischTime'] = (double) $dischTime;
            $calculation['dischRate'] = $dischRate;
            $calculation['maneuver'] = (double) $maneuver;
            $calculation['cycleTime'] = (double) $cycleTime;
        }elseif($operationalData -> taskType == 'Return Cargo'){
            // Sailing to MV = (Arrival POD - Departure POD)
            $sailingToMVCargo = !empty($operationalData -> arrivalPODCargo) && !empty($operationalData -> departurePOD) ? date_diff(new DateTime($operationalData -> arrivalPODCargo), new DateTime($operationalData -> departurePOD))->format('%h.%i') : 0;

            // Manuever = (Aside (MV) - Start Aside (MV))
            $maneuverCargo = !empty($operationalData -> asideMVCargo) && !empty($operationalData -> startAsideMVCargo) ? date_diff(new DateTime($operationalData -> asideMVCargo), new DateTime($operationalData -> startAsideMVCargo))->format('%h.%i') : 0;

            // Disch Time = (Comp (MV) - Comm (MV))
            $dischTimeCargo = !empty($operationalData -> compMVCargo) && !empty($operationalData -> commMVCargo) ? date_diff(new DateTime($operationalData -> compMVCargo), new DateTime($operationalData -> commMVCargo))->format('%h.%i') : 0;

            // Disch Rate / day = (Quantity - Actual Disch Time)
            $dischRateCargo = $operationalData -> cargoAmountEndCargo != 0 && !empty($dischTime) ? (double) $operationalData -> cargoAmountEndCargo - (double) $dischTime : 0;

            // Unberthing = DOH - C/OFF (L)
            $unberthing = !empty($operationalData -> DOH) && !empty($operationalData -> cOffL) ? date_diff(new DateTime($operationalData -> DOH), new DateTime($operationalData -> cOffL))->format('%h.%i') : 0;

            // Berthing = (Aside (L) - Start Aside (L))
            $berthing = !empty($operationalData -> asideL) && !empty($operationalData -> startAsideL) ? date_diff(new DateTime($operationalData -> asideL), new DateTime($operationalData -> startAsideL))->format('%h.%i') : 0;

            // Ldg Time = (C/Off (L) - Commence Load (L))
            $ldgTime = !empty($operationalData -> cOffL) && !empty($operationalData -> commenceLoadL) ? date_diff(new DateTime($operationalData -> cOffL), new DateTime($operationalData -> commenceLoadL))->format('%h.%i') : 0;

            // Prepare Ldg = (Commence Load (L) -Aside (L))
            $prepareLdg = !empty($operationalData -> commenceLoadL) && !empty($operationalData -> asideL) ? date_diff(new DateTime($operationalData -> commenceLoadL), new DateTime($operationalData -> asideL))->format('%h.%i') : 0;

            // Sailing To Jetty = (Arrival POL - F/A Vessel)
            $sailingToJetty = !empty($operationalData -> arrivalPOL) && !empty($operationalData -> faVessel) ? date_diff(new DateTime($operationalData -> arrivalPOL), new DateTime($operationalData -> faVessel))->format('%h.%i') : 0;

            // Cycle Time = Disch Time + Manuever + Sailing to MV + Unberthing + Ldg Time + Prepare Ldg + Berthing + Sailing to Jetty
            $cycleTimeCargo = !empty($dischTimeCargo) && !empty($maneuverCargo) && !empty($sailingToMVCargo) && !empty($unberthing) && !empty($ldgTime) && !empty($prepareLdg) && !empty($berthing) && !empty($sailingToJetty) ? 
            (double) $dischTimeCargo + (double) $maneuverCargo + (double) $sailingToMVCargo + (double) $unberthing + (double) $ldgTime + (double) $prepareLdg + (double) $berthing + (double) $sailingToJetty : 
            0;

            $calculation['sailingToJetty'] = (double) $sailingToJetty;
            $calculation['prepareLdg'] = (double) $prepareLdg;
            $calculation['ldgTime'] = (double) $ldgTime;
            $calculation['berthing'] = (double) $berthing;
            $calculation['unberthing'] = (double) $unberthing;
            $calculation['sailingToMVCargo'] = (double) $sailingToMVCargo;
            $calculation['dischTimeCargo'] = (double) $dischTimeCargo;
            $calculation['dischRateCargo'] = $dischRateCargo;
            $calculation['maneuverCargo'] = (double) $maneuverCargo;
            $calculation['cycleTimeCargo'] = (double) $cycleTimeCargo;
        }
        // Update The Following Task Id
        OperationalBoatData::where('id', $request -> taskId)->update($calculation);

        // Then Redirect
        return redirect('/crew/ongoing-task')->with('status', 'Task Updated Successfully');
    }

    public function finalizeOngoingTask(Request $request){
        // Find The Task Using Task ID
        $data = OperationalBoatData::where('id', $request -> taskId)->first();

        // Helper Var
        $operationShipment_loops = ['from', 'to', 'condition', 'estimatedTime', 'cargoAmountEnd', 'customer', 'arrivalTime', 'departureTime', 'startAsideL', 'asideL', 'commenceLoadL', 'completedLoadingL', 'cOffL', 'DOH', 'DOB', 'departurePOD', 'arrivalPODGeneral', 'startAsidePOD', 'asidePOD', 'commenceLoadPOD', 'completedLoadingPOD', 'cOffPOD', 'DOBPOD'];

        $operationTranshipment_loops = ['from', 'to', 'condition', 'estimatedTime', 'cargoAmountEnd', 'faVessel', 'arrivalPOL', 'startAsideL', 'asideL', 'commenceLoadL', 'completedLoadingL', 'cOffL', 'DOH', 'DOB', 'departurePOD', 'arrivalPODGeneral', 'startAsideMVTranshipment', 'asideMVTranshipment', 'commMVTranshipment', 'compMVTranshipment', 'cOffMVTranshipment', 'departureJetty'];

        $returnCargo_loops = ['from', 'to', 'condition', 'estimatedTime', 'cargoAmountEnd', 'cargoAmountEndCargo', 'arrivalPODCargo', 'startAsideMVCargo', 'asideMVCargo', 'commMVCargo', 'compMVCargo', 'cOffMVCargo', 'departureTime'];

        if($data -> taskType == 'Operational Shipment'){
            foreach($operationShipment_loops as $os){
                if($data -> $os == NULL){
                    return redirect()->back()->with('error', 'Input Field Must Not Be Empty');
                }
            }
        }elseif($data -> taskType == 'Operational Transhipment'){
            foreach($operationTranshipment_loops as $ot){
                if($data -> $ot == NULL){
                    return redirect()->back()->with('error', 'Input Field Must Not Be Empty');
                }
            }
        }else{
            foreach($returnCargo_loops as $ot){
                if($data -> $ot == NULL){
                    return redirect()->back()->with('error', 'Input Field Must Not Be Empty');
                }
            }
        }

        // Find The Task, Then Update The Status
        if($data -> task_tracker == 1){
            return redirect('/crew/create-task')->with('failed', 'Task Already Been Processed');
        }else{
            OperationalBoatData::where('id', $request -> taskId)->update([
                'status' => 'Finalized',
                'task_tracker' => 1
            ]);
        }

        // Then Redirect To Create Task Page
        return redirect('/crew/create-task')->with('status', 'Task Finalized Successfully');
    }

    public function continueReturnCargo(Request $request){
        // Validate The Task Type
        if($request -> taskType != 'Operational Transhipment') {
            return redirect()->back()->with('error', 'Wrong Task');
        }

        // Helper Var
        $operationTranshipment_loops = ['from', 'to', 'condition', 'estimatedTime', 'cargoAmountEnd', 'faVessel', 'arrivalPOL', 'startAsideL', 'asideL', 'commenceLoadL', 'completedLoadingL', 'cOffL', 'DOH', 'DOB', 'departurePOD', 'arrivalPODGeneral', 'startAsideMVTranshipment', 'asideMVTranshipment', 'commMVTranshipment', 'compMVTranshipment', 'cOffMVTranshipment', 'departureJetty'];
        
        $data = OperationalBoatData::where('id', $request -> taskId)->first();

        // Then Validate Empty Fields
        foreach($operationTranshipment_loops as $ot){
            if($data -> $ot == NULL){
                return redirect()->back()->with('error', 'Input Field Must Not Be Empty');
            }
        };

        // Then Change The Task Type Into Return Cargo
        $data->update([
            'taskType' => 'Return Cargo'
        ]);

        return redirect()->back()->with('status', 'Task Updated Successfully');
    }

    public function cancelOngoingTask(Request $request){
        // Find The Task, Then Delete It
        OperationalBoatData::where('id', $request -> taskId)->delete();

        // Then Redirect To Create Task Page
        return redirect('/crew/create-task')->with('status', 'Task Deleted Successfully');
    }
}
