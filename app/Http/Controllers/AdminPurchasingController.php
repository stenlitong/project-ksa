<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class AdminPurchasingController extends Controller
{
    public function addSupplier(Request $request){
        // Add supplier
        $validated = $request -> validate([
            'supplierName' => 'required',
            'noTelp' => 'required|max:15|min:8',
            'supplierEmail' => 'required|email',
            'supplierAddress' => 'required',
            'supplierNPWP' => 'required'
        ]);
        
        Supplier::create($validated);

        return redirect('/dashboard')->with('status', 'Added Successfully');
    }

    public function editSupplier(Request $request, Supplier $suppliers){
        // Edit supplier
        $validated = $request -> validate([
            'supplierName' => 'required',
            'noTelp' => 'required|max:15|min:8',
            'supplierEmail' => 'required|email',
            'supplierAddress' => 'required',
            'supplierNPWP' => 'required'
        ]);

        // Find the supplier's ID then update the value
        Supplier::find($suppliers->id)->update($validated);

        return redirect('/dashboard')->with('status', 'Edited Successfully');
    }
}
