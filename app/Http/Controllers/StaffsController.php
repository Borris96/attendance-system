<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Staff;

class StaffsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('staffs/index')->withStaffs(Staff::all());
    }

    public function create()
    {
        return view('staffs/create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id'=>'required|unique:staffs|max:10',
            'staffname'=>'required|max:50',
            'englishname'=>'max:50',
            'work_time'=>'required',
            'home_time'=>'required',
            'workdays'=>'required|max:100',
            'annual_holiday'=>'max:10',
        ]);

        $staff = new Staff();
        $staff->id = $request->get('id');
        $staff->staffname = $request->get('staffname');
        $staff->englishname = $request->get('englishname');
        // $staff->department_name = $request->get('department_name');
        // $staff->position_name = $request->get('position_name');
        $staff->join_company = $request->get('join_company');
        $staff->join_work = $request->get('join_work');
        $staff->work_time = $request->get('work_time');
        $staff->home_time = $request->get('home_time');
        // $workdays_array = $request->input('workdays[]');
        // var_dump($workdays_array);
        // exit();
        $workdaysall = '';
        $workdays_array = [0=>'Monday', 1=>'Tuesday'];
        foreach($workdays_array as $wd){

            $workdaysall.=$wd.',';

        }
        $workdaysall = rtrim($workdaysall,',');
        $staff->workdays = $workdaysall;
        $staff->annual_holiday = $request->get('annual_holiday');

        if ($staff->save()) {
            return redirect()->back(); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }

    }
}
