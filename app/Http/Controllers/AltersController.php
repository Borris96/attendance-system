<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alter;

class AltersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('alters/index');
    }

    public function destroy()
    {

    }

    public function edit()
    {
        return view('alters/edit');
    }

    public function create()
    {
        return view('alters/create');
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }
}
