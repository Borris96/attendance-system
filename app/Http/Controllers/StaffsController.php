<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Staff;
use App\Department;
use App\Position;
use App\Staffworkday;
use App\WorkHistory;
use App\Absence;

class StaffsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $staffs = Staff::paginate(10);
        foreach ($staffs as $staff) {
            //主要是为了自动更新年假。如果下一年到了，自动加上年假天数。
            $work_year = $staff->work_year;
            $updated_at = $staff->updated_at;
            $annual_holiday = $staff->annual_holiday;
            $remaining_annual_holiday = $staff->remaining_annual_holiday;
            $staff->work_year = $staff->updateWorkYears($updated_at, $work_year);
            $staff->annual_holiday = $staff->updateAnnualHolidays($updated_at, $annual_holiday, $staff->work_year);
            $staff->remaining_annual_holiday = $staff->updateAnnualHolidays($updated_at, $remaining_annual_holiday, $staff->work_year);
            if ($staff->workyear!=$work_year && $staff->annual_holiday!=$annual_holiday){
                $staff->save();
            }
        }
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
// 但后期此功能需要修改成“离职”，因为员工数据不可以轻易删除
    public function leave($id)
    {
        $staff = Staff::find($id);
        $staff->leave_company = now();
        if ($staff->save()){
            session()->flash('warning','员工已离职。');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id'=>'required|unique:staffs|max:10',
            'staffname'=>'required|max:50',
            'englishname'=>'max:50',
            'department'=>'max:50',
            'work_year' => 'required|max:2',
            'join_company' => 'required',
            'position'=>'max:50',
            'work_time'=>'required',
            'home_time'=>'required',
            'workdays'=>'required|max:100',
            'annual_holiday'=>'max:2',
        ]);

        $staff = new Staff();
        $staff->id = $request->get('id');
        $staff->staffname = $request->get('staffname');
        $staff->englishname = $request->get('englishname');
        $staff->department_id = $request->get('departments');
        // 根据id获取员工所属部门和职位
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

        //Insert work historys into work_historys table
        $work_experiences_array = $request->get('work_experiences');
        $leave_experiences_array = $request->get('leave_experiences');

        // $staff->insertWH($work_experiences_array, $leave_experiences_array, $staff->id);

        if ($request->get('annual_holiday')!==null){
            $staff->annual_holiday = $request->get('annual_holiday');
        } else {
            $staff->annual_holiday = $staff->getAnnualHolidays($staff->work_year, $staff->join_company);
        }
        $staff->remaining_annual_holiday = $staff->annual_holiday;
        $staff->insertWorkDays($workdays_array,$staff->id);
        if ($staff->save()) {
            // $staff->insertWH($work_experiences_array, $leave_experiences_array, $staff->id);
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
            // 'annual_holiday'=>'max:10',
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
        // $staff->join_company = $request->get('join_company');

        $workdays_array = $request->input('workdays');
        $staff->workdays = $staff->getAllWorkdays($workdays_array);

        // if ($request->get('annual_holiday')!=null){
        //     $staff->annual_holiday = $request->get('annual_holiday');
        // } else {
        //     $staff->annual_holiday = $staff->getAnnualHolidays($staff->work_year, $staff->join_company);
        // }

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
