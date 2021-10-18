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
use Illuminate\Support\Str;
use App\Exports\OrderOutExport;
use App\Exports\OrderInExport;
use App\Exports\PRExport;
use App\Exports\PurchasingReportExport;
use Maatwebsite\Excel\Excel;
Use \Carbon\Carbon;
use Storage;

use function PHPSTORM_META\map;

class LogisticController extends Controller
{
    public function inProgressOrder(){
        // Find all of the order that is "in progress" state
        $orderHeads = OrderHead::with('user')->where('status', 'like', '%' . 'In Progress' . '%')->orWhere('status', 'like', 'Items Ready')->orWhere('status', 'like', 'On Delivery')->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->latest()->paginate(10);

        // Then get all the order detail
        $order_id = OrderHead::where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        // Get the count number of the completed and in progress order to show it on the view
        $completed = OrderHead::where('status', 'like', '%' . 'Completed' . '%')->orWhere('status', 'like', '%' . 'Rejected' . '%')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();
        $in_progress = OrderHead::where('status', 'like', '%' . 'In Progress' . '%')->orWhere('status', 'like', 'Items Ready')->orWhere('status', 'like', 'On Delivery')->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->count();

        // If they access it from the button, then remove search functionality
        $show_search = false;

        return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'show_search'));
    }

    public function completedOrder(){
        $orderHeads = OrderHead::with('user')->where('status', 'like', '%' . 'Completed' . '%')->orWhere('status', 'like', '%' . 'Rejected' . '%')->where('cabang', 'like', Auth::user()->cabang, 'and','order_heads.created_at', '>=', Carbon::now()->subDays(30))->latest()->paginate(10);

        // Get all the order detail
        $order_id = OrderHead::where('created_at', '>=', Carbon::now()->subDays(30))->pluck('order_id');
        $orderDetails = OrderDetail::with('item')->whereIn('orders_id', $order_id)->get();

        $completed = OrderHead::where('status', 'like', '%' . 'Completed' . '%')->orWhere('status', 'like', '%' . 'Rejected' . '%')->count();
        $in_progress = OrderHead::where('status', 'like', '%' . 'In Progress' . '%')->orWhere('status', 'like', 'Items Ready')->orWhere('status', 'like', 'On Delivery')->orWhere('status', 'like', '%' . 'Delivered By Supplier' . '%')->count();
        $show_search = false;

        return view('logistic.logisticDashboard', compact('orderHeads', 'orderDetails', 'completed', 'in_progress', 'show_search'));
    }

    public function stocksPage(){
        // Logistic can see the stocks of all branches
        if(request('search')){
            $items = Item::where('itemName', 'like', '%' . request('search') . '%')->orWhere('cabang', 'like', '%' . request('search') . '%')->orWhere('codeMasterItem', 'like', '%' . request('search') . '%')->Paginate(10)->withQueryString();
            return view('logistic.stocksPage', compact('items'));
        }else{
            $items = Item::latest()->Paginate(10)->withQueryString();
            return view('logistic.stocksPage', compact('items'));
        }
    }

    public function requestStock(Request $request, Item $items){
        // Request stock validation
        $request->validate([
           'itemName' => 'required',
           'cabang' => 'required',
           'quantity' => 'required|numeric',
           'description' => 'nullable'
        ]);

        // Find the item on the respective branches
        $itemToFound = Item::where('itemName', $items->itemName)->where('cabang', Auth::user()->cabang)->first();

        // Check if the same item is exist OR if the requested quantity is more than the available stock, then return error
        // case #1 => item request from Jakarta -> Bahan Bakar, then the item from requested branch Banjarmasin -> Bahan Bakarr, return error cause it is not exist/not the same
        // case #2 => CASE SENSITIVE MATTER, Bahan Bakar !== BAHAN Bakar, they need to make sure their naming correct
        if($itemToFound === null || ($request -> quantity > $items -> itemStock)){
            return redirect('/logistic/stocks')->with('itemInvalid', 'Barang Tidak Tersedia Dalam Cabang/Stok Invalid');
        }else{
            // Else, create a DO request
            OrderDo::create([
                'user_id' => Auth::user()->id,
                'item_id' => $itemToFound -> id,
                'quantity' => $request -> quantity,
                'status' => 'In Progress By Supervisor Cabang ' . Auth::user()->cabang,
                'fromCabang' => Auth::user()->cabang,
                'toCabang' => $request -> cabang,
                'description' => $request -> description
            ]); 

            return redirect('/logistic/stocks')->with('success', 'Request Successfully');
        }
    }

    public function requestDoPage(){
        $ongoingOrders = OrderDo::with(['item', 'user'])->where('fromCabang', Auth::user()->cabang)->where('order_dos.created_at', '>=', Carbon::now()->subDays(30))->latest()->get();

        return view('logistic.logisticOngoingDO', compact('ongoingOrders'));
    }

    // ============================================= soon to be deleted, just for references ==============================================================
    // public function storeItem(Request $request){
    //     // Storing the item to the stock
    //     $request->validate([
    //         'itemName' => 'required',
    //         'itemAge' => 'required|numeric',
    //         'umur' => 'required',
    //         'itemStock' => 'required|numeric',
    //         'unit' => 'required',
    //         'serialNo' => 'nullable',
    //         'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
    //         'cabang' => 'required',
    //         'description' => 'nullable'
    //     ]);

    //     // Formatting the item age
    //     $new_itemAge = $request->itemAge . ' ' . $request->umur;
        
    //     // Create the item
    //     Item::create([
    //         'itemName' => $request -> itemName,
    //         'itemAge' => $new_itemAge,
    //         'itemStock' => $request -> itemStock,
    //         'unit' => $request -> unit,
    //         'serialNo' => $request -> serialNo,
    //         'codeMasterItem' => $request -> codeMasterItem,
    //         'cabang' => $request->cabang,
    //         'description' => $request -> description
    //     ]);

    //     return redirect('logistic/stocks')->with('status', 'Added Successfully');
    // }

    // public function editItem(Request $request, Item $item){
    //     // Edit the requested item
    //      $request->validate([
    //         'itemName' => 'required',
    //         'itemAge' => 'required|numeric',
    //         'umur' => 'required',
    //         'itemStock' => 'required|numeric',
    //         'unit' => 'required',
    //         'serialNo' => 'nullable',
    //         'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
    //         'description' => 'nullable'
    //     ]);

    //     // Formatting the item age
    //     $new_itemAge = $request->itemAge . ' ' . $request->umur;

    //     // Update the item
    //     Item::where('id', $item->id)->update([
    //         'itemName' => $request -> itemName,
    //         'itemAge' => $new_itemAge,
    //         'itemStock' => $request -> itemStock,
    //         'unit' => $request -> unit,
    //         'serialNo' => $request -> serialNo,
    //         'codeMasterItem' => $request -> codeMasterItem,
    //         'description' => $request -> description
    //     ]);
    //     return redirect('logistic/stocks')->with('status', 'Edit Successfully');
    // }

    // public function deleteItem(Item $item){
    //     // Find the selected item by id
    //     Item::find($item->id)->delete();
        
    //     return redirect('logistic/stocks')->with('status', 'Delete Successfully');
    // }
    // ============================================= soon to be deleted, just for references ==============================================================
    

    public function rejectOrder(Request $request, OrderHead $orderHeads){
        // Reject the order made from crew
        $request->validate([
            'reason' => 'reason'
        ]);

        OrderHead::where('id', $orderHeads->id)->update([
            'status' => 'Rejected By Logistic',
            'reason' => $request->reason
        ]);
        return redirect('/dashboard');
    }

    public function approveOrderPage(OrderHead $orderHeads){
        // Get the order details join with the item
        $orderDetails = OrderDetail::with('item')->where('orders_id', $orderHeads->order_id)->get();

        return view('logistic.logisticApprovedOrder', compact('orderDetails', 'orderHeads'));
    }

    public function approveOrder(Request $request, OrderHead $orderHeads){
        // Validate
        $request -> validate([
            'boatName' => 'required',
            'sender' => 'required',
            'receiver' => 'required',
            'expedition' => 'required',
            'noResi' => 'nullable',
            'description' => 'nullable',
        ]);

        // Get the order details of the following order
        $orderDetails = OrderDetail::where('orders_id', $orderHeads->order_id)->get();

        //If the stock is not enough then redirect to dashboard with error
        foreach($orderDetails as $od){
            // Pluck return an array
            if(Item::where('id', $od -> item -> id)->pluck('itemStock')[0] < $od -> quantity){
                return redirect('/dashboard')->with('error', 'Stok Tidak Mencukupi, Silahkan Periksa Stok Kembali');
            }
        }

        // Else stock is enough, then update the stock
        foreach($orderDetails as $od){
            Item::where('id', $od -> item -> id)->decrement('itemStock', $od -> quantity);
        }

        if($request->expedition == 'onsite'){
            $status = 'Items Ready';
        }else{
            $status = 'On Delivery';
        }

        // Update the status of the following order
        OrderHead::where('id', $orderHeads -> id)->update([
            'status' => $status,
            'sender' => $request -> sender,
            'receiver' => $request -> receiver,
            'expedition' => $request -> expedition,
            'noResi' => $request -> noResi,
            'descriptions' => $request -> description,
            'approved_at' => date("d/m/Y")
        ]);

        return redirect('/dashboard')->with('status', 'Order Approved');
    }

    public function historyOutPage(){
        // Find order from crew role/goods out
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '2')->pluck('users.id');
        
        // Find all the items that has been approved/completed from the user feedback | last 30 days only
        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->where('cabang', 'like', Auth::user()->cabang,)->where('status', 'like', '%' . 'Completed' . '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_details.created_at', 'desc')->get();

        return view('logistic.logisticHistory', compact('orderHeads'));
    }

    public function historyInPage(){
        // Find order from logistic role/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->pluck('users.id');
        
        // Find all the items that has been approved from the user | last 30 days only
        $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->join('suppliers', 'suppliers.id', '=', 'order_heads.supplier_id')->whereIn('user_id', $users)->where('cabang', 'like', Auth::user()->cabang,)->where('status', 'like', '%' . 'Completed'. '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.updated_at', 'desc')->get();

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
        $items = Item::where('cabang', Auth::user()->cabang)->get();

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
            'quantity' => 'required | numeric',
            'department' => 'nullable',
            'golongan' => 'required',
            'note' => 'nullable'
        ]);

        // Check if the cart within the user is already > 12 items, then cart is full & return with message
        $counts = Cart::where('user_id', Auth::user()->id)->count();
        if($counts ==  12){
            return redirect('/logistic/make-order')->with('error', 'Cart is Full');
        }
        
        // Else add item to the cart
        $validated['user_id'] = Auth::user()->id;
        Cart::create($validated);
        
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
            'company' => 'required'
        ]);

        // Find the cart of the following user
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        // Validate cart size
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
            'cabang' => Auth::user()->cabang,
            'boatName' => $boatName,
            'status' => 'Order In Progress By Supervisor'
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
            // 'prDate' => date("d-m-Y")
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
            ]);
        }

        // Emptying the cart items
        Cart::where('user_id', Auth::user()->id)->delete();

        return redirect('/dashboard')->with('status', 'Submit Order Success');
    }

    public function acceptStockOrder(OrderHead $orderHeads){
        
        // Get the order details of the following order
        $orderDetails = OrderDetail::where('orders_id', $orderHeads->order_id)->get();

        // Update the stock by adding the amount of the ordered items
        foreach($orderDetails as $od){
            Item::where('id', $od -> item -> id)->increment('itemStock', $od -> quantity);
        }

        OrderHead::find($orderHeads->id)->update([
            'status' => 'Order Completed (Logistic)',
            'approved_at' => date("d/m/Y")
        ]);

        return redirect('/dashboard')->with('status', 'Order Accepted');
    }

    public function downloadPr(OrderHead $orderHeads){
        // dd($orderHeads->id);

        return (new PRExport($orderHeads -> order_id))->download('PR-' . $orderHeads -> order_id . '_' .  date("d-m-Y") . '.xlsx');
    }

    public function reportPage(){
        // Find order from user/goods in
        $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3', 'and', 'cabang', 'like', Auth::user()->cabang)->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
        
        // Find all the items that has been approved from the logistic | last 30 days only
        $orderHeads = OrderHead::with('supplier')->whereIn('user_id', $users)->where('status', 'like', 'Order Completed (Logistic)', 'and', 'order_heads.created_at', '>=', Carbon::now()->subDays(30))->where('cabang', 'like', Auth::user()->cabang)->orderBy('order_heads.updated_at', 'desc')->get();

        return view('logistic.logisticReport', compact('orderHeads'));
    }

    public function downloadReport(Excel $excel){

        return $excel -> download(new PurchasingReportExport, 'Reports_'. date("d-m-Y") . '.xlsx');
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
