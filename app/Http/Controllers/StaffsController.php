<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffsController extends Controller
{
    public function index()
    {
        return view('staffs/index');
    }

    public function create()
    {
        return view('staffs/create');
    }
}
