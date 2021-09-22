<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\OrderHead;
use App\Models\OrderDetail;
use App\Models\Cart;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Support\Facades\Auth;
use App\Exports\TransactionExport;
use Maatwebsite\Excel\Facades\Excel;

class LogisticController extends Controller
{
    public function index(){
        return view('logistic.logisticDashboard');
    }
    public function stocksPage(){
        // Search function || if there is 2 page or more, it will also include the query string
        if(request('search')){
            $items = Item::where('itemName', 'like', '%' . request('search') . '%')->Paginate(5)->withQueryString();
            return view('logistic.stocksPage', [
                'items' => $items
            ]);
        }else{
            $items = Item::latest()->Paginate(5)->withQueryString();
            return view('logistic.stocksPage', compact('items'));
        }
    }

    public function storeItem(Request $request){
        // Storing the item to the stock
        $validated = $request->validate([
            'itemName' => 'required',
            'itemAge' => 'required|numeric',
            'umur' => 'required',
            'itemStock' => 'required|numeric',
            'unit' => 'required',
            'serialNo' => 'nullable',
            'codeMasterItem' => 'required|regex:/^[0-9]{2}-[0-9]{4}-[0-9]/',
        ]);

        // Formatting the item age
        $new_itemAge = $request->itemAge . ' ' . $request->umur;
        $validated['itemAge'] = $new_itemAge;

        Item::create($validated);

        return redirect('logistic/stocks')->with('status', 'Added Successfully');
    }

    public function editItem(Request $request, Item $item){
        // Edit the requested item
        $validated = $request->validate([
            'itemStock' => 'required|numeric',
            'unit' => 'required',
            'description' => 'nullable'
        ]);

        Item::where('id', $item->id)->update($validated);
        return redirect('logistic/stocks')->with('status', 'Edit Successfully');
    }

    public function rejectOrder(Request $request, OrderHead $orderHeads){
        // dd($request->reason);
        // Reject the order made from crew
        $request->validate([
            'reason' => 'required'
        ]);
        OrderHead::where('id', $orderHeads->id)->update([
            'status' => 'Rejected (Logistic)',
            'reason' => $request->reason
        ]);
        return redirect('/dashboard');
    }

    public function approveOrder(OrderHead $orderHeads){
        dd($orderHeads -> id);
        // Approve the order
        $orderDetails = OrderDetail::where('orders_id', $order->order_id)->get();

        dd($order->id);

        return view('logistic.logisticApproveOrder', compact('order'));
    }

    public function makeOrderPage(){
        // Select items to choose in the order page & carts according to the login user
        $items = Item::all();
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        return view('logistic.logisticMakeOrder', compact('items', 'carts'));
    }

    public function addItemToCart(Request $request){
        dd($request);

    }

    public function reportPage(){
        return view('logistic.logisticReport');
    }

    // ============================ Testing Playgrounds ===================================

    // public function createTransaction(Request $request){
        
        // $validated = $request->validate([
        //     'boatName' => 'required',
        //     'department' => 'required',
        //     'company' => 'required',
        //     'location' => 'required',
        //     'itemName' => 'required',
        //     'prDate' => 'required',
        //     'serialNo' => 'required',
        //     'quantity' => 'required',
        //     'codeMasterItem' => 'required',
        //     'note' => 'nullable'
        // ]);

        // Formatting the PR requirements
        // $month_arr_in_roman = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        // if(Auth::user()->id < 10){
        //     $formatted_id = '00' . Auth::user()->id;
        // }else if(Auth::user()->id < 100){
        //     $formatted_id = '0' . Auth::user()->id;
        // }else{
        //     $formatted_id = Auth::user()->id;
        // }
        
        // $first_char_name = strtoupper(Auth::user()->name[0]);
        // $formatted_company = strtoupper(str_replace(' ', '-' , $request->company));
        // $month = date('n', strtotime($request->prDate));
        // $month_to_roman = $month_arr_in_roman[$month - 1];
        // $year = date('Y', strtotime($request->prDate));

        // Create the PR Number => 001.A/PR-ISA-SMD/IX/2021
        // $pr_number = $formatted_id . '.' . $first_char_name . '/' . 'PR-' . $formatted_company . '-' . $request->location . '/' . $month_to_roman . '/' . $year;
        
        // Adding columns to the validated arr before inserting the data into transaction table
        // $validated['noPr'] = $pr_number;
        // $validated['order_id'] = $order->id;
        // $validated['crew_id'] = Auth::user()->id;
        // $validated['status'] = 'Awaiting Approval';

        // Transaction::create($validated);

        // dd($validated);
        
        // Changing the status in orders table
        // Order::where('id', $order->id)->update([
        //     'in_progress' => 'in_progress(Purchasing)'
        // ]);

        // Then Exporting the data into excel => command : composer require maatwebsite/excel || php artisan make:export TransactionExport --model=Transaction 
        // $t_id = Transaction::where('order_id', $order->id)->value('id');
        // return (new TransactionExport($t_id))->download('Transaction-'. $t_id . '-' . $formatted_company . '.xlsx');

    //     return redirect('/logistic/ongoing-order');
    // }

    // public function downloadOrder(Transaction $transaction){
        // Testing export into excel function

        // Exporting the data into excel => command : composer require maatwebsite/excel || php artisan make:export TransactionExport --model=Transaction 
    //     return (new TransactionExport($transaction->id))->download('Transaction-'. $transaction->id . '.xlsx');
    // }

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
