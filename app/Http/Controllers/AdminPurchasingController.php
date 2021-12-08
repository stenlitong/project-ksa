<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\ApList;
use App\Models\ApListDetail;
use App\Models\OrderHead;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Excel;
use App\Exports\ReportAPExport;
use Storage;

class AdminPurchasingController extends Controller
{
    // public function addSupplier(Request $request){
    //     // Add supplier
    //     $validated = $request -> validate([
    //         'supplierName' => 'required',
    //         'noTelp' => 'required|numeric|digits_between:8,11',
    //         'supplierEmail' => 'required|email|unique:suppliers',
    //         'supplierAddress' => 'required',
    //         'supplierNPWP' => 'required',
    //         'supplierNoRek' => 'nullable'
    //     ]);
        
    //     Supplier::create($validated);

    //     return redirect('/dashboard')->with('status', 'Added Successfully');
    // }

    // public function editSupplier(Request $request, Supplier $suppliers){
    //     // Edit supplier
    //     $validated = $request -> validate([
    //         'supplierName' => 'required',
    //         'noTelp' => 'required|numeric|digits_between:8,11',
    //         'supplierEmail' => 'required|email',
    //         'supplierAddress' => 'required',
    //         'supplierNPWP' => 'required'
    //     ]);

    //     // Find the supplier's ID then update the value
    //     Supplier::find($suppliers->id)->update($validated);

    //     return redirect('/dashboard')->with('status', 'Edited Successfully');
    // }

    // public function deleteSupplier(Request $request, Supplier $suppliers){
    //     // Find the supplier by the id in the request params
    //     Supplier::destroy($suppliers->id);

    //     return redirect('/dashboard')->with('status', 'Deleted Successfully');
    // }

    public function formApPage(){
        // Show the form AP page
        $apList = ApList::with('orderHead')->where('cabang', Auth::user()->cabang)->whereYear('created_at', date('Y'))->latest()->paginate(7);
        
        // Get all the supplier
        $suppliers = Supplier::latest()->get();

        // Default branch is Jakarta
        $default_branch = 'Jakarta';

        return view('adminPurchasing.adminPurchasingFormAp', compact('apList', 'default_branch', 'suppliers'));
    }

    public function formApPageBranch($branch){
        // Show the form AP page
        $apList = ApList::with('orderHead')->where('cabang', $branch)->whereYear('created_at', date('Y'))->latest()->paginate(7);
        
        // Get all the supplier
        $suppliers = Supplier::latest()->get();

        // Get the branch from the parameter
        $default_branch = $branch;

        return view('adminPurchasing.adminPurchasingFormAp', compact('apList', 'default_branch', 'suppliers'));
    }

    public function uploadFile(Request $request){
        // Validate the file extension
        $request->validate([
            'doc_partial1' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial2' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial3' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial4' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial5' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',

            'doc_partial6' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial7' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial8' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial9' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial10' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            
            'doc_partial11' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial12' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial13' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial14' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial15' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',

            'doc_partial16' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial17' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial18' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial19' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
            'doc_partial20' => 'nullable|mimes:pdf,word,jpg,jpeg|max:5120',
        ]);

        // Helper var
        $filename = 'doc_partial';

        // Loop through all the inputs and check if they submit the file or not, if they submit a file then input into the database
        for($i = 1 ; $i <=20 ; $i++){
            if($request -> hasFile($filename . $i)){

                // Helper var
                $dynamic_file = $filename . $i;
                $dynamic_status = 'status_partial' . $i;
                $dynamic_uploadTime = 'uploadTime_partial' . $i;
                $dynamic_description = 'description_partial' . $i;
                $month = date('m');
                $year = date('Y');

                // ==================== Still in discussion if there will be 2 AP with the same file ===========================
                // Check if file already exists
                // $curr_file = ApList::find($request -> apListId)->pluck($dynamic_file)[0];

                // Then delete the file first, so it does not takes memory
                // if($curr_file && Storage::exists('APList/' . $curr_file)){
                //     unlink(storage_path('app/APList/' . $curr_file));
                // }

                // Get the path for the file
                $path = $year . '/' . $month . '/' . $request -> $dynamic_file -> getClientOriginalName();

                // Save all additional information to the database
                ApList::find($request -> apListId)->update([
                    $dynamic_file => $path,
                    $dynamic_status => 'On Review',
                    $dynamic_description => NULL,
                    $dynamic_uploadTime => date("d/m/Y")
                ]);

                // Store the file into storage folder, so it does not publicly accessible || the alternative way is store the files on public folder, but it is easier to access
                $request -> file($dynamic_file) -> storeAs('APList', $path);
            }
        };
        
        return redirect()->back()->with('openApListModalWithId', $request -> apListId);
        // return redirect()->back()->with('status', 'Saved Successfully');
    }

    public function saveApDetail(Request $request){
        // Validate the request
        $request -> validate([
            'noInvoice' => 'required',
            'nominalInvoice' => 'required|integer|min:1',
            'noFaktur' => 'required',
            'noDo' => 'required',
            'additionalInformation' => 'nullable'
        ]);

        ApListDetail::create([
            'aplist_id' => $request -> apListId,
            'supplierName' => $request -> supplierName,
            'noInvoice' => $request -> noInvoice,
            'noFaktur' => $request -> noFaktur,
            'noDo' => $request -> noDo,
            'nominalInvoice' => $request -> nominalInvoice,
            'additionalInformation' => $request -> additionalInformation
        ]);

        return redirect()->back()->with('openApListModalWithId', $request -> apListId);
        // return redirect()->back()->with('status', 'Saved Successfully');
    }

    public function closeAp(Request $request){
        // Find the AP
        $apList = ApList::find($request -> apListId);

        // Check if the AP is already processed Then redirect
        if($apList -> tracker == 6){
            return redirect()->back()->with('errorClosePo', $request -> apListId);
        }

        ApList::where('id', $request -> apListId)->update([
            'tracker' => 6,
            'status' => 'CLOSED'
        ]);

        return redirect()->back()->with('openApListModalWithId', $request -> apListId);
    }

    public function reportApPage(){
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

        // Helper var
        $default_branch = 'Jakarta';

        // Find all the AP within the 3 months period
        $apList = ApList::with('orderHead')->where('cabang', 'like', $default_branch)->join('ap_list_details', 'ap_list_details.aplist_id', '=', 'ap_lists.id')->whereBetween('ap_lists.created_at', [$start_date, $end_date])->orderBy('ap_lists.created_at', 'desc')->get();

        return view('adminPurchasing.adminPurchasingReportApPage', compact('default_branch', 'str_month', 'apList'));
    }

    public function reportApPageBranch($branch){
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

        // Helper Var
        $default_branch = $branch;

        // Find all the AP within the 3 months period
        $apList = ApList::with('orderHead')->where('cabang', 'like', $default_branch)->join('ap_list_details', 'ap_list_details.aplist_id', '=', 'ap_lists.id')->whereBetween('ap_lists.created_at', [$start_date, $end_date])->orderBy('ap_lists.created_at', 'desc')->get();

        return view('adminPurchasing.adminPurchasingReportApPage', compact('default_branch', 'str_month', 'apList'));
    }

    public function exportReportAp(Excel $excel, $branch){

        // Export into excel
        return $excel -> download(new ReportAPExport($branch), 'Reports_AP('. $branch . ')_'. date("d-m-Y") . '.xlsx');
    }
}
