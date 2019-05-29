<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Staff;

class StaticPagesController extends Controller
{
    public function index()
    {
        return route('home');
    }
}
