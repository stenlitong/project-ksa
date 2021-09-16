<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CrewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderPage()
    {
        return view('crew.crewOrder');
    }

    public function taskPage()
    {
        return view('crew.crewTask');
    }

}
