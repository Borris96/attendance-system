<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalarysController extends Controller
{
    public function index()
    {
        return view('salarys/index');
    }

    public function create()
    {
        return view('salarys/create');
    }
}
