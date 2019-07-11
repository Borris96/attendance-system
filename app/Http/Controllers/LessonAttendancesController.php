<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LessonAttendance;

class LessonAttendancesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('lesson_attendances/index');
    }
}
