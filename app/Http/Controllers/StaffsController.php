<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Staff;
use App\Department;
use App\Position;
use App\Staffworkday;

class StaffsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $staffs = Staff::paginate(10);
        // $departments = Department::all();
        return view('staffs/index',compact('staffs','departments'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        return view('staffs/create',compact('departments','positions'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'staff_id'=>'required|unique:staffs|max:10',
            'staffname'=>'required|max:50',
            'englishname'=>'max:50',
            'department'=>'max:50',
            'position'=>'max:50',
            'work_time'=>'required',
            'home_time'=>'required',
            'workdays'=>'required|max:100',
            'annual_holiday'=>'max:10',
        ]);

        $staff = new Staff();
        $staff->id = $request->get('staff_id');
        $staff->staffname = $request->get('staffname');
        $staff->englishname = $request->get('englishname');
        $staff->department_id = $request->get('department');
        $staff->position_id = $request->get('position');
        $staff->join_company = $request->get('join_company');
        $staff->join_work = $request->get('join_work');
        $staff->work_time = $request->get('work_time');
        $staff->home_time = $request->get('home_time');
        // $workdaysall = '';
        // $workdays_array = [0=>'Monday', 1=>'Tuesday'];
        $workdays_array = $request->input('workdays');
        $staff->workdays = $staff->getAllWorkdays($workdays_array);
        $staff->annual_holiday = $request->get('annual_holiday');
        if ($staff->save()) {
            session()->flash('success','保存成功！');
            return redirect('staffs'); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }

    }
}
