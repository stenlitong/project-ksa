<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Cart;
use App\Models\User;
use App\Models\Tug;
use App\Models\Barge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Exports\OrderOutExport;
use App\Exports\OrderInExport;
use App\Exports\PRExport;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Excel;
Use \Carbon\Carbon;
use Storage;

class LogisticController extends Controller
{
    public function index(){
        // Dummy routes for testing purpose
        return view('logistic.logisticDashboard');
    }
    public function stocksPage(){
        // Check if the role is admin logistic, then he can see the stocks of all branches, else only can see the stocks of each branches
        // if(Auth::user()->hasRole('adminLogistic')){
            // Search function || if there is 2 page or more, it will also include the query string
            if(request('search')){
                $items = Item::where('itemName', 'like', '%' . request('search') . '%')->orWhere('cabang', 'like', '%' . request('search') . '%')->orWhere('codeMasterItem', 'like', '%' . request('search') . '%')->Paginate(5)->withQueryString();
                return view('logistic.stocksPage', compact('items'));
            }else{
                $items = Item::latest()->Paginate(5)->withQueryString();
                return view('logistic.stocksPage', compact('items'));
            }
        // }else{
            // Search function || if there is 2 page or more, it will also include the query string
            // if(request('search')){
            //     $items = Item::where('itemName', 'like', '%' . request('search') . '%', 'and', 'cabang', 'like', Auth::user()->cabang)->orWhere('codeMasterItem', 'like', '%' . request('search') . '%', 'and', 'cabang', 'like', Auth::user()->cabang)->Paginate(5)->withQueryString();
            //     return view('logistic.stocksPage', compact('items'));
            // }else{
            //     $items = Item::latest()->where('cabang', 'like', Auth::user()->cabang)->Paginate(5)->withQueryString();
            //     return view('logistic.stocksPage', compact('items'));
            // }
        // }
    }

    public function storeItem(Request $request){
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

        return redirect('logistic/stocks')->with('status', 'Added Successfully');
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
        return redirect('logistic/stocks')->with('status', 'Edit Successfully');
    }

    public function rejectOrder(Request $request, OrderHead $orderHeads){
        // Reject the order made from crew
        $request->validate([
            'reason' => 'required'
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
            'cabang' => 'required',
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

        // Update the status of the following order
        OrderHead::where('id', $orderHeads -> id)->update([
            'status' => 'On Delivery',
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
        // Chech if the role is admin logistic, then he can see all of the order, else logistic role can see their respectable order
        if(Auth::user()->hasRole('adminLogistic')){
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '2')->pluck('users.id');

            $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->select('order_id', 'approved_at', 'item_id', 'serialNo', 'quantity', 'unit', 'noResi', 'descriptions', 'cabang')->where('status', 'like', 'Completed', 'and', 'created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_details.created_at', 'desc')->get();
        }else{
            // Find order from user/goods out
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '2')->pluck('users.id');
            
            // Find all the items that has been approved from the user | last 30 days only
            $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->whereIn('user_id', $users)->select('order_id', 'approved_at', 'item_id', 'serialNo', 'quantity', 'unit', 'noResi', 'descriptions')->where('cabang', 'like', Auth::user()->cabang,)->where('status', 'like', 'Completed')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_details.created_at', 'desc')->get();
        }

        return view('logistic.logisticHistory', compact('orderHeads'));
    }

    public function historyInPage(){
        // Chech if the role is admin logistic, then he can see all of the order, else logistic role can see their respectable order
        if(Auth::user()->hasRole('adminLogistic')){
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '4')->orWhere('role_user.role_id' , '=', '3')->pluck('user_id');

            $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->join('suppliers', 'suppliers.id', '=', 'order_heads.supplier_id')->whereIn('user_id', $users)->select('order_id', 'approved_at', 'item_id', 'serialNo', 'quantity', 'unit', 'supplierName', 'descriptions')->where('status', 'like', '%' . 'Completed'. '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.updated_at', 'desc')->get();
        }else{
            // Find order from user/goods out
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3')->orWhere('role_user.role_id' , '=', '4')->pluck('users.id');
            
            // Find all the items that has been approved from the user | last 30 days only
            $orderHeads = OrderDetail::with('item')->join('order_heads', 'order_heads.order_id', '=', 'order_details.orders_id')->join('suppliers', 'suppliers.id', '=', 'order_heads.supplier_id')->whereIn('user_id', $users)->select('order_id', 'approved_at', 'item_id', 'serialNo', 'quantity', 'unit', 'supplierName', 'descriptions')->where('cabang', 'like', Auth::user()->cabang,)->where('status', 'like', '%' . 'Completed'. '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.updated_at', 'desc')->get();
        }

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
        // Check if the role is admin logistic, then he can see all of the items and order it for the stock
        if(Auth::user()->hasRole('adminLogistic')){
            $items = Item::latest()->get();
            // $items = $itemsUnique->unique('itemName');
        }else{
            // Else, logistic role can only select the items that are only available to their branches & carts according to the login user
            $items = Item::where('cabang', Auth::user()->cabang)->get();
        }

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
            'bargeName' => 'required',
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
            $unique_id = Str::random(9);
        }while(OrderHead::where('order_id', $unique_id)->exists());

        // String formatting for boatName with tugName + bargeName
        $boatName = $request->tugName . '/' . $request->bargeName;
        
        // Create Order Head
        $orderHead = OrderHead::create([
            'user_id' => Auth::user()->id,
            'order_id' => $unique_id,
            'cabang' => Auth::user()->cabang,
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

        return (new PRExport($orderHeads -> order_id))->download('test.xlsx');
    }

    public function reportPage(){

        // Chech if the role is admin logistic, then he can see all of the order, else logistic role can see their respectable order
        if(Auth::user()->hasRole('adminLogistic')){
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '4')->orWhere('role_user.role_id' , '=', '3')->pluck('user_id');

            $orderHeads = OrderHead::with('supplier')->whereIn('user_id', $users)->where('status', 'like', '%' . 'Order Completed' . '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.updated_at', 'desc')->get();
        }else{
            // Find order from user/goods out
            $users = User::join('role_user', 'role_user.user_id', '=', 'users.id')->where('role_user.role_id' , '=', '3', 'and', 'cabang', 'like', Auth::user()->cabang)->orWhere('role_user.role_id' , '=', '4', 'and', 'cabang', 'like', Auth::user()->cabang)->where('cabang', 'like', Auth::user()->cabang)->pluck('users.id');
            
            // Find all the items that has been approved from the user | last 30 days only
            $orderHeads = OrderHead::with('supplier')->whereIn('user_id', $users)->where('status', 'like', '%' . 'Order Completed' . '%')->where('order_heads.created_at', '>=', Carbon::now()->subDays(30))->orderBy('order_heads.updated_at', 'desc')->get();
        }

        return view('logistic.logisticReport', compact('orderHeads'));
    }

    public function downloadReport(Excel $excel){

        return $excel -> download(new ReportExport, 'LogisticReport-'. date("d-m-Y") . '.xlsx');
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
