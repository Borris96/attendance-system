<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('absences/index');
    }

    public function create()
    {
        return view('absences/create');
    }
}
