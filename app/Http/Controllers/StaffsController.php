<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return view('staffs/index',compact('staffs'));
    }

    public function show($id)
    {
        $staff = Staff::find($id);
        $staff_id = $staff->id;
        $workdays = Staffworkday::where('staff_id', $staff_id)->get('workday_name');
        return view('staffs.show',compact('staff','workdays'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        return view('staffs/create',compact('departments','positions'));
    }

// 任何和该员工有关联的数据都应该删除 （员工ID不是unique的）
    public function destroy($id)
    {
        Staff::find($id)->delete();
        Staffworkday::where('staff_id',$id)->delete();
        session()->flash('warning','删除成功！');
        return redirect()->back()->withInput();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id'=>'required|unique:staffs|max:10',
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
        $staff->id = $request->get('id');
        $staff->staffname = $request->get('staffname');
        $staff->englishname = $request->get('englishname');
        $staff->department_id = $request->get('departments');
        if ($staff->department_id!==null){
            $staff->department_name = Department::find($staff->department_id)->department_name;
        }
        $staff->position_id = $request->get('positions');
        if ($staff->position_id!==null){
            $staff->position_name = Position::find($staff->position_id)->position_name;
        }
        $staff->join_company = $request->get('join_company');
        $staff->work_year = $request->get('work_year');
        $staff->work_time = $request->get('work_time');
        $staff->home_time = $request->get('home_time');
        // $workdaysall = '';
        // $workdays_array = [0=>'Monday', 1=>'Tuesday'];
        $workdays_array = $request->input('workdays');
        $staff->workdays = $staff->getAllWorkdays($workdays_array);

        if ($request->get('annual_holiday')!==null){
            $staff->annual_holiday = $request->get('annual_holiday');
        } else {
            $staff->annual_holiday = $staff->getAnnualHolidays($staff->work_year, $staff->join_company);
        }
        $staff->insertWorkDays($workdays_array,$staff->id);

        if ($staff->save()) {
            session()->flash('success','保存成功！');
            return redirect('staffs'); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }

    }

    public function edit($id) {
        $staff = Staff::find($id);
        $staff_id = $staff->id;
        $workdays = Staffworkday::where('staff_id', $staff_id)->get('workday_name');
        $departments = Department::all();
        $positions = Position::all();
        return view('staffs.edit',compact('staff','workdays','departments','positions'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'department'=>'max:50',
            'position'=>'max:50',
            'work_time'=>'required',
            'home_time'=>'required',
            'workdays'=>'required|max:100',
            'annual_holiday'=>'max:10',
        ]);

        $staff = Staff::find($id);
        // $staff->staffname = $request->get('staffname');
        // $staff->englishname = $request->get('englishname');
        $staff->department_id = $request->get('departments');
        if ($staff->department_id!==null){
            $staff->department_name = Department::find($staff->department_id)->department_name;
        }
        $staff->position_id = $request->get('positions');
        if ($staff->position_id!==null){
            $staff->position_name = Position::find($staff->position_id)->position_name;
        }
        // $staff->work_year = $request->get('work_year');
        $staff->work_time = $request->get('work_time');
        $staff->home_time = $request->get('home_time');

        $workdays_array = $request->input('workdays');
        $staff->workdays = $staff->getAllWorkdays($workdays_array);

        if ($request->get('annual_holiday')!==null){
            $staff->annual_holiday = $request->get('annual_holiday');
        } else {
            $staff->annual_holiday = $staff->getAnnualHolidays($staff->work_year, $staff->join_company);
        }

        $staff->updateWorkDays($workdays_array,$staff->id);

        if ($staff->save()) {
            session()->flash('success','更新成功！');
            return redirect('staffs'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }
    }
}
