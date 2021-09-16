<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderPage()
    {
        $items = Item::all();
        // dd($items);
        return view('crew.crewOrder', compact('items'));
    }

    public function taskPage()
    {
        return view('crew.crewTask');
    }

}
