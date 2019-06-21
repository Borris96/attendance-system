<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Staff;

class LeaveStaffsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $leave_staffs = Staff::where('status',false)->orderby('leave_company','desc')->paginate(50);
        return view('leave_staffs.index',compact('leave_staffs'));
    }

    public function show($id)
    {
        $staff = Staff::find($id);
        $staff_id = $staff->id;
        $staffworkdays = $staff->staffworkdays;
        $work_historys = $staff->workHistorys;
        return view('leave_staffs.show',compact('staff','staffworkdays','work_historys'));
    }
}
