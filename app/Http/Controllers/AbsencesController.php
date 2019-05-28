<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsencesController extends Controller
{
    public function index()
    {
        return view('absences/index');
    }

    public function create()
    {
        return view('absences/create');
    }
}
