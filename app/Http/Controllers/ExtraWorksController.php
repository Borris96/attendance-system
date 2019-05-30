<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExtraWorksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('extra_works/index');
    }

    public function create()
    {
        return view('extra_works/create');
    }
}
