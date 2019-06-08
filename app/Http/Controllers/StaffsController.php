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
            //每年更新一次
            $updated_at = $staff->updated_at; //获取年份，以便更新年假时到新一年再更新
            //以每天时间为准，更新参加工作年数
            if ($updated_at->isLastYear())
            {
                $annual_holiday = $staff->annual_holiday; //原来的年假
                $remaining_annual_holiday = $staff->remaining_annual_holiday;
                $join_year = (strtotime(date('Y').'-01-01')-strtotime($staff->join_company))/(365*24*3600); // 加入公司的年数
                $staff->work_year = $staff->origin_work_year + round($join_year,2); // 目前工作年数 = 加入公司前+加入公司后
                $staff->annual_holiday = $staff->updateAnnualHolidays($updated_at, $annual_holiday, $staff->work_year); // 根据工作年数更新年假
                $staff->remaining_annual_holiday = $staff->updateAnnualHolidays($updated_at, $remaining_annual_holiday, $staff->work_year); // 根据工作年数更新剩余年假
                $staff->save();
            }
        }
        return view('staffs/index',compact('staffs'));
    }

    public function show($id)
    {
        $staff = Staff::find($id);
        $staff_id = $staff->id;
        $staffworkdays = $staff->staffworkdays;
        $work_historys = $staff->workHistorys;
        return view('staffs.show',compact('staff','staffworkdays','work_historys'));
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
            session()->flash('warning','员工离职成功。');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id'=>'required|unique:staffs|max:10',
            'staffname'=>'required|max:50',
            'englishname'=>'required|max:50',
            'department'=>'max:50',
            // 'work_year' => 'required|max:2',
            'join_company' => 'required',
            'position'=>'max:50',
            // 'work_time'=>'required',
            // 'home_time'=>'required',
            // 'workdays'=>'required|max:100',
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

        $work_times = $request->input('work_time');
        $home_times = $request->input('home_time');

        $work_experiences_array = $request->input('work_experiences');
        $leave_experiences_array = $request->input('leave_experiences');
        // dump($work_experiences_array);
        // dump($leave_experiences_array);
        // exit();

        // 判断填写格式是否正确

        for ($i=0; $i<=6; $i++){
            if (($work_times[$i]!=null && $home_times[$i]==null) || ($work_times[$i]==null && $home_times[$i]!=null))
            {
                session()->flash('danger','时间填写不完整！');
                return redirect()->back()->withInput();
            }

            if (strtotime($work_times[$i])>strtotime($home_times[$i]))
            {
                session()->flash('danger','上班时间晚于下班时间！');
                return redirect()->back()->withInput();
            }
        }

        for ($i=0; $i<=9; $i++){
            if (($work_experiences_array[$i]!=null && $leave_experiences_array[$i]==null) || ($work_experiences_array[$i]==null && $leave_experiences_array[$i]!=null))
            {
                session()->flash('danger','日期填写不完整！');
                return redirect()->back()->withInput();
            }

            if (strtotime($work_experiences_array[$i])>strtotime($leave_experiences_array[$i]))
            {
                session()->flash('danger','入职日期晚于离职日期！');
                return redirect()->back()->withInput();
            }

            // 还要判断前一段时间是否和后一段时间重叠
                // 判断工作经历是否填写
            if ($work_experiences_array[$i] != null && $leave_experiences_array[$i]!=null) {
                if ($i>0) {
                    $old_start_time = strtotime($work_experiences_array[$i-1]);
                    $old_end_time = strtotime($leave_experiences_array[$i-1]);
                } else {
                    $old_start_time = strtotime('1970-01-02'); //这两个时间要设置得足够早
                    $old_end_time = strtotime('1970-01-03');
                }
                $start_time = strtotime($work_experiences_array[$i]);
                $end_time = strtotime($leave_experiences_array[$i]);

                if ($end_time<$old_start_time) {
                    session()->flash('warning','请按照顺序填写工作经历');
                    return redirect()->back()->withInput();
                }
                if (WorkHistory::isCrossing($start_time, $end_time, $old_start_time, $old_end_time) == true)
                {
                    session()->flash('danger','工作经历重合');
                    return redirect()->back()->withInput();
                }
            }
        }
        // 等所有条件都满足才进行录入
        // Insert work historys into work_historys table
        $total_work_year = 0;
        for ($i=0; $i<=9; $i++){
            if ($work_experiences_array[$i]!=null && $leave_experiences_array[$i]!=null){ //填写了才录入
                $work_history = new WorkHistory();
                $work_history->staff_id = $staff->id;
                $work_history->work_experience = $work_experiences_array[$i];
                $work_history->leave_experience = $leave_experiences_array[$i];
                $work_history->save();
                $total_work_year += strtotime($leave_experiences_array[$i])-strtotime($work_experiences_array[$i]);
            }
        }
        $total_work_year = $total_work_year/(31536000); //转换成年
        $staff->work_year = $total_work_year;
        $staff->origin_work_year = $total_work_year;


        // 录入staffworkdays表
        for ($i=0; $i<=6; $i++){
            $staffworkday = new Staffworkday();
            $staffworkday->staff_id = $staff->id;
            $staffworkday->workday_name = $staffworkday->getWorkdayName($i);
            $staffworkday->work_time = $work_times[$i];
            $staffworkday->home_time = $home_times[$i];
            $staffworkday->save();
            // dump($staffworkday);
        }

        if ($request->get('annual_holiday')!==null){
            $staff->annual_holiday = $request->get('annual_holiday');
        } else {
            $staff->annual_holiday = $staff->getAnnualHolidays($staff->origin_work_year, $staff->join_company);
        }
        $staff->remaining_annual_holiday = $staff->annual_holiday;

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
        $workdays = $staff->staffworkdays;
        $departments = Department::all();
        $positions = Position::all();
        return view('staffs.edit',compact('staff','workdays','departments','positions'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'department'=>'max:50',
            'position'=>'max:50',
            // 'work_time'=>'required',
            // 'home_time'=>'required',
            // 'workdays'=>'required|max:100',
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

        // 更新工作日
        $work_times = $request->input('work_time');
        $home_times = $request->input('home_time');

        // 判断填写格式是否正确
        for ($i=0; $i<=6; $i++){
            if (($work_times[$i]!=null && $home_times[$i]==null) || ($work_times[$i]==null && $home_times[$i]!=null))
            {
                session()->flash('danger','时间填写不完整！');
                return redirect()->back()->withInput();
            }

            if (strtotime($work_times[0])>strtotime($home_times[0]))
            {
                session()->flash('danger','上班时间晚于下班时间！');
                return redirect()->back()->withInput();
            }
        }

        // 录入表
        $origin_workdays = $staff->staffworkdays;
        for ($i=0; $i<=6; $i++){
            $origin_workdays[$i]->work_time = $work_times[$i];
            $origin_workdays[$i]->home_time = $home_times[$i];
            $origin_workdays[$i]->save();
        }

        // if ($request->get('annual_holiday')!=null){
        //     $staff->annual_holiday = $request->get('annual_holiday');
        // } else {
        //     $staff->annual_holiday = $staff->getAnnualHolidays($staff->work_year, $staff->join_company);
        // }

        if ($staff->save()) {
            session()->flash('success','更新成功！');
            return redirect('staffs'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }
    }
}
