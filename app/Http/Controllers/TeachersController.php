<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;

class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('teachers/index');
    }

    public function show()
    {
        return view('teachers/show');
    }

    public function edit()
    {
        return view('teachers/edit');
    }

    public function update(Request $request)
    {

    }
}
