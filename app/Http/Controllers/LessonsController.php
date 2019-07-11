<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;

class LessonsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('lessons/index');
    }

    public function destroy()
    {

    }

    public function edit()
    {
        return view('lessons/edit');
    }

    public function create()
    {
        return view('lessons/create');
    }

    public function store(Request $request)
    {

    }

    public function update(Request $request, $id)
    {

    }
}
