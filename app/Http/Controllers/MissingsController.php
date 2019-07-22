<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Missing;

class MissingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('missings/index');
    }

    public function destroy()
    {

    }

    public function edit()
    {
        return view('missings/edit');
    }

    public function create()
    {
        return view('missings/create');
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }
}
