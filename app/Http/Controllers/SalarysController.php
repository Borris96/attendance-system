<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalarysController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('salarys/index');
    }

    public function create()
    {
        return view('salarys/create');
    }
}
