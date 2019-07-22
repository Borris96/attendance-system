<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;

class LeaveTeachersController extends Controller
{
    public function index()
    {
        $teachers = Teacher::where('status',false)->orderby('updated_at','desc')->get();
        return view('leave_teachers.index',compact('teachers'));
    }
}
