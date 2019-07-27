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
use App\LeaveStaff;
use App\Card;
use App\StaffworkdayUpdate;

class StaffsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->get('englishname') == null)
        {

            $staffs = Staff::where('status',true)->where('position_id','<>','8')->where('position_id','<>','9')->where('position_id','<>','10')->where('position_id','<>','11')->orderBy('id','asc')->get(); // 除了外教,实习生,兼职之外的员工更新年假
            foreach ($staffs as $staff) {
                //每年更新一次
                $updated_at = $staff->updated_at; //获取年份，以便更新年假时到新一年再更新
                //以每天时间为准，更新参加工作年数
                if ($updated_at->isLastYear())
                {
                    if ($staff->position_name != '全职教师' && $staff->position_name != '兼职教师' && $staff->position_name != '实习生' && !stristr($staff->position_name, '兼职'))
                    {
                        $annual_holiday = $staff->annual_holiday; //原来的年假
                        $remaining_annual_holiday = $staff->remaining_annual_holiday;
                        $join_year = (strtotime(date('Y').'-01-01')-strtotime($staff->join_company))/(365*24*3600); // 加入公司的年数
                        $staff->work_year = $staff->origin_work_year + round($join_year,2); // 目前工作年数 = 加入公司前+加入公司后
                        $staff->annual_holiday = $staff->updateAnnualHolidays($updated_at, $annual_holiday, $staff->work_year); // 根据工作年数更新年假
                        $staff->remaining_annual_holiday = $staff->updateAnnualHolidays($updated_at, $remaining_annual_holiday, $staff->work_year); // 根据工作年数更新剩余年假
                        $staff->save();
                    }
                    elseif ($staff->position_name == '全职教师')
                    {
                        if (date('m-d')==date('m-d',strtotime($staff->join_company)) && date('Y')!=date('Y',strtotime($staff->join_company))) // 如果今天是外教的入职日并且不是他入职那一年，更新年假
                        {
                            $staff->annual_holiday += 8*10; // 全职外教年假80小时
                            $staff->remaining_annual_holiday += 8*10;
                            $staff->save();
                        }
                    }
                }
            }
        }
        else {
            $englishname = $request->get('englishname');
            //查询这个员工
            $staffs = Staff::where('status',true)->where('englishname','like',$englishname.'%')->get();
            if (count($staffs) == 0)
            {
                session()->flash('warning', '员工不存在！');
                return redirect()->back()->withInput();
            }
        }
        return view('staffs/index',compact('staffs'));
    }

    public function partTimeIndex()
    {
        $staffs = Staff::where('status',true)->where('position_id','>','7')->where('id','<>','12')->orderBy('id','asc')->get();
        return view('staffs/part_time_index',compact('staffs'));
    }

    public function show($id)
    {
        $staff = Staff::find($id);
        $staff_id = $staff->id;
        $staffworkdays = Staffworkday::where('staff_id',$staff_id)->orderBy('id','asc')->get();
        // 计算一周总工作时长
        $total_duration = 0;
        foreach ($staffworkdays as $workday) {
            $total_duration += $workday->duration;
        }
        $work_historys = $staff->workHistorys;
        return view('staffs.show',compact('staff','staffworkdays','work_historys','total_duration'));
    }

    public function showPartTime(Request $request)
    {
        $id = $request->input('id');
        $staff = Staff::find($id);
        $staff_id = $staff->id;
        $staffworkdays = Staffworkday::where('staff_id',$staff_id)->orderBy('id','asc')->get();
        // 计算一周总工作时长
        $total_duration = 0;
        foreach ($staffworkdays as $workday) {
            $total_duration += $workday->duration;
        }
        return view('staffs.show_part_time',compact('staff','staffworkdays','total_duration'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::where('id','<>','8')->where('id','<>','9')->where('id','<>','10')->where('id','<>','11')->get();
        $days = ['一','二','三','四','五','六','日'];
        return view('staffs/create',compact('departments','positions','days'));
    }

    public function createPartTime()
    {
        $departments = Department::all();
        $positions = Position::where('id','>','7')->where('id','<>','12')->get();
        $days = ['一','二','三','四','五','六','日'];
        return view('staffs/create_part_time',compact('departments','positions','days'));
    }

    public function edit($id) {
        $staff = Staff::find($id);
        $workdays = $staff->staffworkdays;
        $work_historys = $staff->workHistorys;
        $count = count($work_historys);
        $departments = Department::all();
        $positions = Position::where('id','<>','8')->where('id','<>','9')->where('id','<>','10')->where('id','<>','11')->get();
        $days = ['一','二','三','四','五','六','日'];
        return view('staffs.edit',compact('staff','workdays','departments','positions','work_historys','count','days'));
    }

    public function editPartTime(Request $request) {
        $id = $request->input('id');
        $staff = Staff::find($id);
        $workdays = $staff->staffworkdays;
        $departments = Department::all();
        $positions = Position::where('id','>','7')->where('id','<>','12')->get();
        $days = ['一','二','三','四','五','六','日'];
        return view('staffs.edit_part_time',compact('staff','workdays','departments','positions','days'));
    }

    public function editWorkTime($id)
    {
        $staff = Staff::find($id);
        $days = ['一','二','三','四','五','六','日'];
        $workdays = $staff->staffworkdays;
        return view('staffs/edit_work_time',compact('staff','days','workdays'));
    }

// 任何和该员工有关联的数据都应该删除 （员工ID不是unique的）
// 但后期此功能需要修改成“离职”，因为员工数据不可以轻易删除
    public function leave($id)
    {
        $staff = Staff::find($id);
        $staff->leave_company = now();
        $staff->status = false;
        if ($staff->teacher != null)
        {
            $staff->teacher->status = false; // 同时在老师名单中移除
            $staff->teacher->save();
        }

        if ($staff->save()){
            session()->flash('warning','员工离职成功。');
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'id'=>'integer|required|unique:staffs',
            'staffname'=>'required|max:50',
            'englishname'=>'required|max:50|unique:staffs',
            'join_company' => 'required',
            'positions'=>'required',
            // 'annual_holiday'=>'numeric',
            'card_number'=>'max:23',
            'bank'=>'max:50',
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
        // 为了在考勤中方便查询在该月离职以及还未离职的员工
        $staff->leave_company = '2038-01-01';
        $staff->status = true;

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
                session()->flash('warning','时间填写不完整！');
                return redirect()->back()->withInput();
            }

            if (strtotime($work_times[$i])>strtotime($home_times[$i]))
            {
                session()->flash('warning','上班时间晚于下班时间！');
                return redirect()->back()->withInput();
            }
        }

        for ($i=0; $i<=9; $i++){
            // 两个时间都非空时
            if ($work_experiences_array[$i] != null && $leave_experiences_array[$i]!=null) {
                // 入职不能晚于离职
                if ($work_experiences_array[$i]>$leave_experiences_array[$i])
                {
                    session()->flash('warning','日期顺序错误！');
                    return redirect()->back()->withInput();
                }
                else {
                    // 要求工作经历按从远到近的时间顺序填写
                    if ($i>0) {
                        $old_start_time = $work_experiences_array[$i-1];
                        $old_end_time = $leave_experiences_array[$i-1];
                    } else {
                        $old_start_time = '1970-01-02'; //这两个时间要设置得足够早
                        $old_end_time = '1970-01-03';
                    }
                    $start_time = $work_experiences_array[$i];
                    $end_time = $leave_experiences_array[$i];

                    if ($end_time<$old_start_time) {
                        session()->flash('warning','请按照顺序填写工作经历');
                        return redirect()->back()->withInput();
                    } elseif (WorkHistory::isCrossing($start_time, $end_time, $old_start_time, $old_end_time))
                    {
                        session()->flash('warning','工作经历重合');
                        return redirect()->back()->withInput();
                    }
                }
            }
            // 有一个空时，报错
            elseif (($work_experiences_array[$i]!=null && $leave_experiences_array[$i]==null) || ($work_experiences_array[$i]==null && $leave_experiences_array[$i]!=null))
            {
                session()->flash('warning','日期填写不完整！');
                return redirect()->back()->withInput();
            } // 全空就不用管了
        }
        // 判断最后一个离职日期是否早于本公司入职日期

        if (max($leave_experiences_array)>$staff->join_company)
        {
            session()->flash('warning','最后一个离职日期晚于入职公司日期');
            return redirect()->back()->withInput();
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
            if ($work_times[$i] != null && $home_times[$i] != null){
                $staffworkday->is_work = true;
            }
            else {
                $staffworkday->is_work = false;
            }
            $staffworkday->duration = Staffworkday::calDuration($work_times[$i],$home_times[$i]);
            if ($staffworkday->save())
            {
                // 新建排班更新记录
                $staffworkday_update = new StaffworkdayUpdate();
                $staffworkday_update->staffworkday_id = $staffworkday->id;
                $staffworkday_update->staff_id = $staffworkday->staff_id;
                $staffworkday_update->workday_name = $staffworkday->workday_name;
                $staffworkday_update->work_time = $staffworkday->work_time;
                $staffworkday_update->home_time = $staffworkday->home_time;
                $staffworkday_update->is_work = $staffworkday->is_work;
                $staffworkday_update->duration = $staffworkday->duration;
                $staffworkday_update->start_date = $staff->join_company;
                $staffworkday_update->end_date = $staff->leave_company;
                $staffworkday_update->save();
            }
            // dump($staffworkday);
        }

        if ($request->get('annual_holiday')!=null){
            $staff->annual_holiday = $request->get('annual_holiday');
        } else {
            $staff->annual_holiday = $staff->getAnnualHolidays($staff->origin_work_year, $staff->join_company, $staff->position_name);
        }
        $staff->remaining_annual_holiday = $staff->annual_holiday;

        $card_info = new Card();
        $card_info->card_number = $request->get('card_number');
        $card_info->bank = $request->get('bank');
        $card_info->staff_id = $staff->id;

        if ($staff->save()) {
            $card_info->save();
            session()->flash('success','保存成功！');
            return redirect('staffs'); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }

    }

    public function storePartTime(Request $request)
    {
        $this->validate($request, [
            // 'id'=>'integer|required|unique:staffs',
            'staffname'=>'required|max:50',
            'englishname'=>'required|max:50|unique:staffs',
            'join_company' => 'required',
            'positions'=>'required',
            // 'annual_holiday'=>'numeric',
            'card_number'=>'max:23',
            'bank'=>'max:50',
        ]);

        $staff = new Staff();
        // 根据当日日期随机生成id
        $staff_id = strtotime(date('Y-m-d H:i:s')); // 使用时间戳作为编号
        $staff->id = $staff_id;
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
        // 为了在考勤中方便查询在该月离职以及还未离职的员工
        $staff->leave_company = '2038-01-01';
        $staff->status = true;

        $work_times = $request->input('work_time');
        $home_times = $request->input('home_time');

        // 判断填写格式是否正确

        for ($i=0; $i<=6; $i++){
            if (($work_times[$i]!=null && $home_times[$i]==null) || ($work_times[$i]==null && $home_times[$i]!=null))
            {
                session()->flash('warning','时间填写不完整！');
                return redirect()->back()->withInput();
            }

            if (strtotime($work_times[$i])>strtotime($home_times[$i]))
            {
                session()->flash('warning','上班时间晚于下班时间！');
                return redirect()->back()->withInput();
            }
        }

        $staff->work_year = 0;
        $staff->origin_work_year = 0;

        // 录入staffworkdays表
        for ($i=0; $i<=6; $i++){
            $staffworkday = new Staffworkday();
            $staffworkday->staff_id = $staff->id;
            $staffworkday->workday_name = $staffworkday->getWorkdayName($i);
            $staffworkday->work_time = $work_times[$i];
            $staffworkday->home_time = $home_times[$i];
            if ($work_times[$i] != null && $home_times[$i] != null){
                $staffworkday->is_work = true;
            }
            else {
                $staffworkday->is_work = false;
            }
            $staffworkday->duration = Staffworkday::calDuration($work_times[$i],$home_times[$i]);
            if ($staffworkday->save())
            {
                // 新建排班更新记录
                $staffworkday_update = new StaffworkdayUpdate();
                $staffworkday_update->staffworkday_id = $staffworkday->id;
                $staffworkday_update->staff_id = $staffworkday->staff_id;
                $staffworkday_update->workday_name = $staffworkday->workday_name;
                $staffworkday_update->work_time = $staffworkday->work_time;
                $staffworkday_update->home_time = $staffworkday->home_time;
                $staffworkday_update->is_work = $staffworkday->is_work;
                $staffworkday_update->duration = $staffworkday->duration;
                $staffworkday_update->start_date = $staff->join_company;
                $staffworkday_update->end_date = $staff->leave_company;
                $staffworkday_update->save();
            }
            // dump($staffworkday);
        }
        $staff->annual_holiday = 0;
        $staff->remaining_annual_holiday = 0;

        $card_info = new Card();
        $card_info->card_number = $request->get('card_number');
        $card_info->bank = $request->get('bank');
        $card_info->staff_id = $staff->id;

        if ($staff->save()) {
            $card_info->save();
            session()->flash('success','保存成功！');
            $staffs = Staff::where('status',true)->where('position_id','>','7')->where('id','<>','12')->orderBy('id','asc')->get();
            return view('staffs.part_time_index',compact('staffs')); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'positions'=>'required',
            'card_number'=>'max:23',
            'bank'=>'max:50',
            'annual_holiday'=>'numeric',
            'remaining_annual_holiday'=>'numeric',
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

        // 获取工作日，工作经历

        $work_experiences_array = $request->input('work_experiences');
        $leave_experiences_array = $request->input('leave_experiences');


        for ($i=0; $i<=9; $i++){
            // 两个时间都非空时
            if ($work_experiences_array[$i] != null && $leave_experiences_array[$i]!=null) {
                // 入职不能晚于离职
                if ($work_experiences_array[$i]>$leave_experiences_array[$i])
                {
                    session()->flash('warning','日期顺序错误！');
                    return redirect()->back()->withInput();
                }
                else {
                    // 要求工作经历按从远到近的时间顺序填写
                    if ($i>0) {
                        $old_start_time = $work_experiences_array[$i-1];
                        $old_end_time = $leave_experiences_array[$i-1];
                    } else {
                        $old_start_time = '1970-01-02'; //这两个时间要设置得足够早
                        $old_end_time = '1970-01-03';
                    }
                    $start_time = $work_experiences_array[$i];
                    $end_time = $leave_experiences_array[$i];

                    if (strtotime($end_time)<=strtotime($old_start_time)) {
                        session()->flash('warning','请按照由近至远的顺序填写工作经历');
                        return redirect()->back()->withInput();
                    } elseif (WorkHistory::isCrossing($start_time, $end_time, $old_start_time, $old_end_time))
                    {
                        session()->flash('warning','工作经历重合');
                        return redirect()->back()->withInput();
                    }
                }
            }
            // 有一个空时，报错
            elseif (($work_experiences_array[$i]!=null && $leave_experiences_array[$i]==null) || ($work_experiences_array[$i]==null && $leave_experiences_array[$i]!=null))
            {
                session()->flash('warning','日期填写不完整！');
                return redirect()->back()->withInput();
            } // 全空就不用管了
        }
        // 判断最后一个离职日期是否早于本公司入职日期
        if (max($leave_experiences_array)>$staff->join_company)
        {
            session()->flash('warning','最后一个离职日期晚于入职公司日期');
            return redirect()->back()->withInput();
        }


        // 之前存在的 work history，更新
        $origin_work_historys = $staff->workHistorys;
        $count = count($origin_work_historys);
        // dump($count);
        // exit();
        $total_work_year = 0;
        for ($i=0; $i<$count; $i++){
            // if ($work_experiences_array[$i]!=null && $leave_experiences_array[$i]!=null){ //填写了才录入
                $origin_work_historys[$i]->work_experience = $work_experiences_array[$i];
                $origin_work_historys[$i]->leave_experience = $leave_experiences_array[$i];
                $origin_work_historys[$i]->save();
                $total_work_year += strtotime($leave_experiences_array[$i])-strtotime($work_experiences_array[$i]);
            // }
        }

        // 新增的，则新增记录
        for ($i=$count; $i<=9; $i++){
            if ($work_experiences_array[$i]!=null && $leave_experiences_array[$i]!=null){ //填写了才录入
                $work_history = new WorkHistory();
                $work_history->staff_id = $staff->id;
                $work_history->work_experience = $work_experiences_array[$i];
                $work_history->leave_experience = $leave_experiences_array[$i];
                $work_history->save();
                $total_work_year += strtotime($leave_experiences_array[$i])-strtotime($work_experiences_array[$i]);
            }
        }

        $old_work_year = $staff->work_year; // 原来的工作年数
        $total_work_year = $total_work_year/(31536000); // 现在的工作年数，转换成年
        $staff->work_year = $total_work_year;
        $staff->origin_work_year = $total_work_year;
        $old_annual_holiday = $staff->annual_holiday;
        $old_remaining_annual_holiday = $staff->remaining_annual_holiday;
        $new_annual_holiday = $request->get('annual_holiday');
        $new_remaining_annual_holiday = $request->get('remaining_annual_holiday');
        // 计算年假。如果工作经历改动,则按改动的数据计算;如果未改动,则按填写的数据计算。
        // 计算出的工作年数变了就是工作经历变了！

        if ($old_work_year != round($total_work_year,2)) // 改动过工作经历按经历计算
        {
            if ($new_annual_holiday >= $new_remaining_annual_holiday)
            {
                if ($new_annual_holiday == $old_annual_holiday)
                {
                    if ($new_remaining_annual_holiday == $old_remaining_annual_holiday)
                    {
                        // 如果总的年假和剩余年假没有改动,自动计算年假和剩余年假
                        $staff->annual_holiday = $staff->getAnnualHolidays($staff->origin_work_year, $staff->join_company, $staff->position_name);
                        $staff->remaining_annual_holiday = $old_remaining_annual_holiday-$old_annual_holiday+$staff->annual_holiday;
                    }
                    else
                    {
                        // 剩余改了，总的没改，总的计算，剩余的按改的
                        $staff->annual_holiday = $staff->getAnnualHolidays($staff->origin_work_year, $staff->join_company, $staff->position_name);
                        $staff->remaining_annual_holiday = $new_remaining_annual_holiday;
                    }
                }
                else
                {
                    $staff->annual_holiday = $new_annual_holiday;
                    $staff->remaining_annual_holiday = $new_remaining_annual_holiday;
                }
            }
            else
            {
                session()->flash('warning','剩余年假大于总年假！');
                return redirect()->back()->withInput();
            }
        }
        else // 否则直接读取填写的值
        {
            if ($new_annual_holiday >= $new_remaining_annual_holiday)
            {
                $staff->annual_holiday = $new_annual_holiday;
                $staff->remaining_annual_holiday = $new_remaining_annual_holiday;
            }
            else
            {
                session()->flash('warning','剩余年假大于总年假！');
                return redirect()->back()->withInput();
            }
        }

        // if ($request->get('annual_holiday')!=null){
        //     $staff->annual_holiday = $request->get('annual_holiday');
        // } else {
        //     $staff->annual_holiday = $staff->getAnnualHolidays($staff->work_year, $staff->join_company);
        // }



        if ($staff->card == null){
            $card_info = new Card();
            $card_info->card_number = $request->get('card_number');
            $card_info->bank = $request->get('bank');
            $card_info->staff_id = $staff->id;
        }
        else
        {
            $card_info = $staff->card;
            $card_info->card_number = $request->get('card_number');
            $card_info->bank = $request->get('bank');
        }

        if ($staff->save()) {
            $card_info->save();
            session()->flash('success','更新成功！');
            return redirect('staffs'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }
    }

    public function updatePartTime(Request $request)
    {
        $this->validate($request, [
            'positions'=>'required',
            'card_number'=>'max:23',
            'bank'=>'max:50',
        ]);
        $id = $request->input('id');
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
        if ($staff->card == null){
            $card_info = new Card();
            $card_info->card_number = $request->get('card_number');
            $card_info->bank = $request->get('bank');
            $card_info->staff_id = $staff->id;
        }
        else
        {
            $card_info = $staff->card;
            $card_info->card_number = $request->get('card_number');
            $card_info->bank = $request->get('bank');
        }

        if ($staff->save()) {
            $card_info->save();
            session()->flash('success','更新成功！');
            $staffs = Staff::where('status',true)->where('position_id','>','7')->where('id','<>','12')->orderBy('id','asc')->get();
            return view('staffs.part_time_index',compact('staffs')); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }

    }

    public function updateWorkTime(Request $request, $id)
    {
        $this->validate($request, [
            // 'work_times'=>'required',
            // 'home_times'=>'required',
            'effective_date'=>'required',
        ]);
        $staff = Staff::find($id);
        $work_times = $request->input('work_time');
        $home_times = $request->input('home_time');
        $effective_date = $request->input('effective_date');
        // 判断填写格式是否正确
        for ($i=0; $i<=6; $i++){
            if (($work_times[$i]!=null && $home_times[$i]==null) || ($work_times[$i]==null && $home_times[$i]!=null))
            {
                session()->flash('warning','时间填写不完整！');
                return redirect()->back()->withInput();
            }

            if (strtotime($work_times[0])>strtotime($home_times[0]))
            {
                session()->flash('warning','上班时间晚于下班时间！');
                return redirect()->back()->withInput();
            }
        }
        // 录入表
        $origin_workdays = $staff->staffworkdays;
        for ($i=0; $i<=6; $i++){
            $origin_workdays[$i]->work_time = $work_times[$i];
            $origin_workdays[$i]->home_time = $home_times[$i];
            if ($work_times[$i] != null && $home_times[$i] != null){
                $origin_workdays[$i]->is_work = true;
            }
            else {
                $origin_workdays[$i]->is_work = false;
            }
            $origin_workdays[$i]->duration = Staffworkday::calDuration($work_times[$i],$home_times[$i]);
            $origin_workdays[$i]->save();
        }


        session()->flash('success','更新成功！');
        if ($staff->position_id > 7 && $staff->position_id != 12)
        {
            $staffs = Staff::where('status',true)->where('position_id','>','7')->where('id','<>','12')->orderBy('id','asc')->get();
            return view('staffs.part_time_index',compact('staffs')); //应导向列表
        }
        else
        {
            return redirect('staffs'); //应导向列表
        }

    }
}
