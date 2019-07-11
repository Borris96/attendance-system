<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Substitute;

class SubstitutesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('substitutes/index');
    }

    public function destroy()
    {

    }

    public function edit()
    {
        return view('substitutes/edit');
    }

    public function create()
    {
        return view('substitutes/create');
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }
}
