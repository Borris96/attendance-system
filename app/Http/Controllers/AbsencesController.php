<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Absence;
use App\Attendance;
use App\Lieu;
use App\SeparateAbsence;
use App\TotalAttendance;

class AbsencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->get('englishname') == null)
        {
            $absences = Absence::orderBy('updated_at','desc')->get();
        }
        else {
            $englishname = $request->get('englishname');
            // 只能返回第一个有类似英文名的员工id
            $staff_id = Staff::where('englishname','like',$englishname.'%')->value('id');
            $absences = Absence::where('staff_id',$staff_id)->orderBy('updated_at','desc')->get();
            if (count($absences) == 0)
            {
                session()->flash('warning', '加班记录不存在！');
                return redirect()->back()->withInput();
            }
        }
        return view('absences/index',compact('absences'));
    }

    public function create()
    {
        $staffs = Staff::where('status',true)->get();
        $attendance = null;
        return view('absences/create',compact('staffs','attendance'));
    }

    public function edit($id) {
        $absence = Absence::find($id);
        $staff = $absence->staff; // 获取该请假的申请人
        return view('absences.edit',compact('absence','staff'));
    }

    /**
     * 删除请假时，因为请假相应减少的时间应该加回来
     *
     */
    public function destroy($id) {
        $absence = Absence::find($id);
        if ($absence->absence_type == '年假') {
            //被批准的年假记录删除时，被扣除的年假应增加
            $approve = $absence->approve;
            if ($approve == true){
                $duration = $absence->duration;
                $staff = $absence->staff;
                $staff->remaining_annual_holiday += $duration;
                $staff->save();
            }
        }

        // 此处调休假删除，调休剩余时间要增加。
        if ($absence->absence_type == '调休') {
            $absence->staff->lieu->remaining_time += $absence->duration;
            $absence->staff->lieu->save();
        }

        if ($absence->separateAbsences != null)
        {
            foreach ($absence->separateAbsences as $s_a) {
                $s_a->delete();
            }
        }

        $absence->delete();
        session()->flash('success', '成功删除请假记录！');
        return back();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'staff_id'=>'required',
            'absence_type'=>'required|max:50',
            'absence_start_time'=>'required',
            'absence_end_time'=>'required',
            'approve' => 'required',
            'note'=>'max:140',
        ]);

        $absence = new Absence();
        $absence->staff_id = $request->get('staff_id');
        $absence->absence_type = $request->get('absence_type');
        $absence->absence_start_time = $request->get('absence_start_time');
        $absence->absence_end_time = $request->get('absence_end_time');
        $absence->approve = $request->get('approve');
        $absence->note = $request->get('note');

        $staff = Staff::find($absence->staff_id);

        if ($absence->absence_start_time>$absence->absence_end_time){
            session()->flash('danger','日期填写错误！');
            return redirect()->back()->withInput();
        }

        if ($absence->absence_type == "调休" && $absence->approve == false) {
            session()->flash('danger','调休需要批准！');
            return redirect()->back()->withInput();
        }

        if ($absence->absence_type == "年假" && $absence->approve == false) {
            session()->flash('danger','年假需要批准！');
            return redirect()->back()->withInput();
        }

        $absence_start_time = strtotime($absence->absence_start_time);
        $absence_end_time = strtotime($absence->absence_end_time);

        // 计算请假天数的差值
        $days_difference = (strtotime(date('Y-m-d',$absence_end_time))-strtotime(date('Y-m-d',$absence_start_time)))/(60*60*24);

        // 判断请假的起止日期中是否有该员工的假期
        $weekarray=array("日","一","二","三","四","五","六");
        $first_day = $weekarray[date('w', $absence_start_time)];
        $last_day = $weekarray[date('w', $absence_start_time+60*60*24*$days_difference)];
        $first_workdays = $staff->staffworkdays->where('workday_name',$first_day);
        foreach ($first_workdays as $fwd) { // 其实只有一个值
            $is_work_first = $fwd->is_work;
        }
        $last_workdays = $staff->staffworkdays->where('workday_name',$last_day);
        foreach ($last_workdays as $lwd) { // 其实只有一个值
            $is_work_last = $lwd->is_work;
        }
        if ($is_work_first == false || $is_work_last == false)
        {
            session()->flash('danger','起止时间包含非工作日！');
            return redirect()->back()->withInput();
        }

        // 开始查找该员工的工作日上下班数据
        $absence_start_day = $weekarray[date('w', $absence_start_time)]; // 请假开始那天是周几
        $absence_end_day = $weekarray[date('w', $absence_end_time)]; // 请假结束那天是周几

        // 获取请假开始日下班时间
        $workdays = $staff->staffworkdays->where('workday_name',$absence_start_day);
        foreach ($workdays as $wd) { // 其实只有一个值
            $first_day_home_time = $wd->home_time;
            $first_day_work_time = $wd->work_time;
        }

        if (date('H:i:s',$absence_start_time)>$first_day_home_time)
        {
            session()->flash('danger','请假开始时间晚于下班时间！');
            return redirect()->back()->withInput();
        }

        if (date('H:i:s',$absence_start_time)<$first_day_work_time)
        {
            session()->flash('danger','请假开始时间早于上班时间！');
            return redirect()->back()->withInput();
        }

        // 获取请假结束日上班时间
        $workdays = $staff->staffworkdays->where('workday_name',$absence_end_day);
        foreach ($workdays as $wd) { // 其实只有一个值
            $last_day_home_time = $wd->home_time;
            $last_day_work_time = $wd->work_time;
            // $work_duration = $wd->duration; // 此处有问题
        }

        if (date('H:i:s',$absence_end_time)<$last_day_work_time)
        {
            session()->flash('danger','请假结束时间早于上班时间！');
            return redirect()->back()->withInput();
        }

        if (date('H:i:s',$absence_end_time)>$last_day_home_time)
        {
            session()->flash('danger','请假结束时间晚于下班时间！');
            return redirect()->back()->withInput();
        }

        // 判断新的请假时间是否与该员工原来的某段请假时间重叠，如不重叠才能创建成功。
        $absences = $staff->absences;
        foreach ($absences as $ab) {
            $old_absence_start_time = strtotime($ab->absence_start_time);
            $old_absence_end_time = strtotime($ab->absence_end_time);
            if ($absence->isCrossing($absence_start_time, $absence_end_time, $old_absence_start_time, $old_absence_end_time) == true) {
                session()->flash('danger','请假时间重叠！');
                return redirect()->back()->withInput();
            }
        }
        // $absence->duration = 0.9;
        // $absence->duration = $absence->calDuration($first_day_home_time, $last_day_work_time, $work_duration, $absence->absence_start_time, $absence->absence_end_time);

        $duration_array = [];
        // 获取请假起止日的时长
        $duration_array = $absence->separateDuration($first_day_home_time, $last_day_work_time, $absence->absence_start_time, $absence->absence_end_time, $duration_array);

        // 请假只有一天时
        if (count($duration_array) == 1)
        {   // 将请假日的年-月-日分开


            $y_m_d = explode('-',date("Y-m-d", strtotime($absence->absence_start_time)));
            $attendance = Attendance::where('staff_id',$staff->id)->where('year',$y_m_d[0])->where('month',$y_m_d[1])->where('date',$y_m_d[2])->get();
            if ($attendance != null)
            {
                foreach($attendance as $at) // 其实只有一条
                {
                    if ($at->actual_duration != null) // 如果实际时间非空，看是不是和实际上班时间有交集
                    {
                        $start = $at->actual_work_time; // H:i
                        $end = $at->actual_home_time; // H:i
                        $str_start = strtotime(date("Y-m-d", strtotime($absence->absence_start_time)).' '.$start);
                        $str_end = strtotime(date("Y-m-d", strtotime($absence->absence_start_time)).' '.$end);
                        // 工作日，已经打卡，请假时间段必须在打卡时间外
                        if($absence->isCrossing($absence_start_time, $absence_end_time, $str_start, $str_end))
                        {
                            session()->flash('danger','请假时间需要在打卡时间外！');
                            return redirect()->back()->withInput();
                        }
                    }
                }
            }



            $separate_absence = new SeparateAbsence();
            $y_m_d = explode('-', date('Y-m-d',$absence_start_time));
            $separate_absence->year = $y_m_d[0];
            $separate_absence->month = $y_m_d[1];
            $separate_absence->date = $y_m_d[2];
            $separate_absence->duration = array_sum($duration_array);
            $absence->duration = $separate_absence->duration;
            $separate_absence->save();
        }
        // 不止一天时
        elseif (count($duration_array) == 2)
        {
            $middle_duration = 0;
            // 将每一天分开，请假除去收尾的天按当日工作时长计算请假时长
            $date_day = [];
            $date_day = Attendance::separateAbsence($absence->absence_start_time, $absence->absence_end_time, $date_day);

            // 录入第一天
            $separate_absence_start = new SeparateAbsence();
            $y_m_d = explode('-', date('Y-m-d',$absence_start_time));
            $separate_absence_start->year = $y_m_d[0];
            $separate_absence_start->month = $y_m_d[1];
            $separate_absence_start->date = $y_m_d[2];
            $separate_absence_start->duration = $duration_array[0];
            $separate_absence_start->save();
            // 录入最后一天
            $separate_absence_end = new SeparateAbsence();
            $y_m_d = explode('-', date('Y-m-d',$absence_end_time));
            $separate_absence_end->year = $y_m_d[0];
            $separate_absence_end->month = $y_m_d[1];
            $separate_absence_end->date = $y_m_d[2];
            $separate_absence_end->duration = $duration_array[1];
            $separate_absence_end->save();

            // 计算中间日期的请假总时长
            $count = count($date_day)-2; // 减去了起止日期
            $middle_absence_array = [];
            for ($j=1; $j<=$count; $j++)
            {
                $workday_name = $weekarray[date('w',strtotime($date_day[$j]))];
                // 寻找这一天（星期）的该员工工作时长
                $this_workday = $staff->staffworkdays->where('workday_name',$workday_name);
                foreach ($this_workday as $twd) { // 其实只有一个 workday
                    if ($twd->duration != null)
                    {
                        if ($twd->duration != 0)
                        {
                            $middle_absence_array[$j] = new SeparateAbsence();
                            $y_m_d = explode('-', date('Y-m-d',strtotime($date_day[$j])));
                            $middle_absence_array[$j]->year = $y_m_d[0];
                            $middle_absence_array[$j]->month = $y_m_d[1];
                            $middle_absence_array[$j]->date = $y_m_d[2];
                            $middle_absence_array[$j]->duration = $twd->duration;
                            $middle_absence_array[$j]->save();
                            $middle_duration += $middle_absence_array[$j]->duration;
                        }
                    }
                }
            }
            $absence->duration = array_sum($duration_array) + $middle_duration;
        }
        else
        {
            session()->flash('danger','请假时长计算失败！');
            return redirect()->back()->withInput();
        }

        // 只有年假，且被批准情况下计算剩余年假

        if ($absence->absence_type == "年假"){
            $staff = Staff::find($absence->staff_id);
            $staff->remaining_annual_holiday -= $absence->duration;
            if ($staff->remaining_annual_holiday<0){
                session()->flash('danger','年假余额不足，不能请假！');
                return redirect()->back()->withInput();
            }
            else
            {
                $staff->save();
            }
        }

        // 只有调休，且被批准情况下计算剩余调休
        if ($absence->absence_type == "调休"){
            //把这个员工的那一条调休记录调出来！！！
            $this_lieu = $absence->staff->lieu;
            if ($this_lieu == null) {
                session()->flash('danger','调休剩余时间不足，不能请假！！');
                return redirect()->back()->withInput();
            }
            $this_lieu->remaining_time -= $absence->duration;
            if ($this_lieu->remaining_time<0){
                session()->flash('danger','调休剩余时间不足，不能请假！');
                return redirect()->back()->withInput();
            }
            else
            {
                $this_lieu->save();
            }
        }

        if ($absence->save()) {
            // 把请假申请的id录入分日请假--此时这些请假分日才被关联
            if (count($duration_array) == 1)
            {
                $separate_absence->absence_id = $absence->id;
                $separate_absence->staff_id = $absence->staff_id;
                $separate_absence->save();
            }
            if ($attendance!=null)
            {
                foreach ($attendance as $at) {
                    $at->absence_id = $absence->id;
                    $at->absence_duration = $absence->duration;
                    $at->absence_type = $absence->absence_type;
                    Attendance::isAbnormal($at);
                    $this_month_attendances = $at->totalAttendance->attendances;
                    TotalAttendance::updateTotal($this_month_attendances, $at, $type='absence');
                    return redirect()->route('attendances.show',$at->totalAttendance->id); //应导向列表
                }
            }
            elseif (count($duration_array) ==2)
            {
                $separate_absence_start->absence_id = $absence->id;
                $separate_absence_start->staff_id = $absence->staff_id;
                $separate_absence_start->save();
                $separate_absence_end->absence_id = $absence->id;
                $separate_absence_end->staff_id = $absence->staff_id;
                $separate_absence_end->save();
                foreach ($middle_absence_array as $maa) {
                    $maa->absence_id = $absence->id;
                    $maa->staff_id = $absence->staff_id;
                    $maa->save();
                }
            }

            // 如果attendnce查到了，还需要把attendance和absence_id关联
            session()->flash('success','保存成功！');
            return redirect('absences'); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'absence_start_time'=>'required',
            'absence_end_time'=>'required',
            'approve' => 'required',
            'note'=>'required|max:140',
        ]);

        $absence = Absence::find($id);

        $origin_duration = $absence->duration; // 获取之前时长，重新计算年假时要用
        $absence->absence_start_time = $request->get('absence_start_time');
        $absence->absence_end_time = $request->get('absence_end_time');
        if ($absence->absence_start_time>$absence->absence_end_time){ //开始时间不能比结束时间早
            session()->flash('danger','日期填写错误！');
            return redirect()->back()->withInput();
        }

        $absence->approve = $request->get('approve');
        $absence->note = $request->get('note');
        if ($absence->absence_type == "调休" && $absence->approve == false) {
            session()->flash('danger','调休需要批准！');
            return redirect()->back()->withInput();
        }

        if ($absence->absence_type == "年假" && $absence->approve == false) {
            session()->flash('danger','年假需要批准！');
            return redirect()->back()->withInput();
        }

        $staff = Staff::find($absence->staff_id);
        $absence_start_time = strtotime($absence->absence_start_time);
        $absence_end_time = strtotime($absence->absence_end_time);


        // 开始查找该员工的工作日上下班数据
        $weekarray=array("日","一","二","三","四","五","六");
        // 计算请假天数的差值
        $days_difference = (strtotime(date('Y-m-d',$absence_end_time))-strtotime(date('Y-m-d',$absence_start_time)))/(60*60*24);

        // 判断请假的起止日中是否有该员工的假期
        $first_day = $weekarray[date('w', $absence_start_time)];
        $last_day = $weekarray[date('w', $absence_start_time+60*60*24*$days_difference)];
        $first_workdays = $staff->staffworkdays->where('workday_name',$first_day);
        foreach ($first_workdays as $fwd) { // 其实只有一个值
            $is_work_first = $fwd->is_work;
        }
        $last_workdays = $staff->staffworkdays->where('workday_name',$last_day);
        foreach ($last_workdays as $lwd) { // 其实只有一个值
            $is_work_last = $lwd->is_work;
        }
        if ($is_work_first == false || $is_work_last == false)
        {
            session()->flash('danger','起止时间包含非工作日！');
            return redirect()->back()->withInput();
        }
        // for ($i=0; $i<=$days_difference; $i++)
        // {
        //     $this_day = $weekarray[date('w', $absence_start_time+60*60*24*$i)];
        //     $workdays = $staff->staffworkdays->where('workday_name',$this_day);
        //     foreach ($workdays as $wd) { // 其实只有一个值
        //         $is_work = $wd->is_work;
        //     }
        //     if ($is_work == false)
        //     {
        //         session()->flash('danger','请假时间包含非工作日！');
        //         return redirect()->back()->withInput();
        //     }
        // }

        $absence_start_day = $weekarray[date('w', $absence_start_time)]; // 请假开始那天是周几
        $absence_end_day = $weekarray[date('w', $absence_end_time)]; // 请假结束那天是周几

        // 获取请假开始日下班时间
        $workdays = $staff->staffworkdays->where('workday_name',$absence_start_day);
        foreach ($workdays as $wd) { // 其实只有一个值
            $first_day_home_time = $wd->home_time;
            $first_day_work_time = $wd->work_time;
        }

        if (date('H:i:s',$absence_start_time)>$first_day_home_time)
        {
            session()->flash('danger','请假开始时间晚于下班时间！');
            return redirect()->back()->withInput();
        }

        if (date('H:i:s',$absence_start_time)<$first_day_work_time)
        {
            session()->flash('danger','请假开始时间早于上班时间！');
            return redirect()->back()->withInput();
        }

        // 获取请假结束日上班时间
        $workdays = $staff->staffworkdays->where('workday_name',$absence_end_day);
        foreach ($workdays as $wd) { // 其实只有一个值
            $last_day_home_time = $wd->home_time;
            $last_day_work_time = $wd->work_time;
            $work_duration = $wd->duration;
        }

        if (date('H:i:s',$absence_end_time)<$last_day_work_time)
        {
            session()->flash('danger','请假结束时间早于上班时间！');
            return redirect()->back()->withInput();
        }

        if (date('H:i:s',$absence_end_time)>$last_day_home_time)
        {
            session()->flash('danger','请假结束时间晚于下班时间！');
            return redirect()->back()->withInput();
        }

        // 判断新的请假时间是否与该员工原来的某段请假时间重叠，如不重叠才能更新成功。
        $absences = $staff->absences->whereNotIn('id',[$id]); // 除去本条记录
        foreach ($absences as $ab) {
            $old_absence_start_time = strtotime($ab->absence_start_time);
            $old_absence_end_time = strtotime($ab->absence_end_time);
            if ($absence->isCrossing($absence_start_time, $absence_end_time, $old_absence_start_time, $old_absence_end_time) == true) {
                session()->flash('danger','请假时间重叠！');
                return redirect()->back()->withInput();
            }
        }

        // $absence->duration = $absence->calDuration($first_day_home_time, $last_day_work_time, $work_duration, $absence->absence_start_time, $absence->absence_end_time);
        // 先把之前存过的Separate Absence删掉，后面重新计算，这样就和新建一样了。
        if ($absence->separateAbsences != null)
        {
            foreach ($absence->separateAbsences as $s_a) {
                $s_a->delete();
            }
        }
        $duration_array = [];
        $duration_array = $absence->separateDuration($first_day_home_time, $last_day_work_time, $absence->absence_start_time, $absence->absence_end_time, $duration_array);

        // 请假只有一天时
        if (count($duration_array) == 1)
        {   // 将请假日的年-月-日分开
            $separate_absence = new SeparateAbsence();
            $y_m_d = explode('-', date('Y-m-d',$absence_start_time));
            $separate_absence->year = $y_m_d[0];
            $separate_absence->month = $y_m_d[1];
            $separate_absence->date = $y_m_d[2];
            $separate_absence->duration = array_sum($duration_array);
            $absence->duration = $separate_absence->duration;
            $separate_absence->save();
        }
        elseif (count($duration_array) == 2)
        {
            $middle_duration = 0;
            // 将每一天分开，请假除去收尾的天按当日工作时长计算请假时长
            $date_day = [];
            $date_day = Attendance::separateAbsence($absence->absence_start_time, $absence->absence_end_time, $date_day);

            // 新代码
            // 录入第一天
            $separate_absence_start = new SeparateAbsence();
            $y_m_d = explode('-', date('Y-m-d',$absence_start_time));
            $separate_absence_start->year = $y_m_d[0];
            $separate_absence_start->month = $y_m_d[1];
            $separate_absence_start->date = $y_m_d[2];
            $separate_absence_start->duration = $duration_array[0];
            $separate_absence_start->save();
            // 录入最后一天
            $separate_absence_end = new SeparateAbsence();
            $y_m_d = explode('-', date('Y-m-d',$absence_end_time));
            $separate_absence_end->year = $y_m_d[0];
            $separate_absence_end->month = $y_m_d[1];
            $separate_absence_end->date = $y_m_d[2];
            $separate_absence_end->duration = $duration_array[1];
            $separate_absence_end->save();

            // 计算中间日期的请假总时长
            $count = count($date_day)-2; // 减去了起止日期
            $middle_absence_array = [];
            for ($j=1; $j<=$count; $j++)
            {
                $workday_name = $weekarray[date('w',strtotime($date_day[$j]))];
                // 寻找这一天（星期）的该员工工作时长
                $this_workday = $staff->staffworkdays->where('workday_name',$workday_name);
                foreach ($this_workday as $twd) { // 其实只有一个 workday
                    if ($twd->duration != null)
                    {
                        if ($twd->duration != 0)
                        {
                            $middle_absence_array[$j] = new SeparateAbsence();
                            $y_m_d = explode('-', date('Y-m-d',strtotime($date_day[$j])));
                            $middle_absence_array[$j]->year = $y_m_d[0];
                            $middle_absence_array[$j]->month = $y_m_d[1];
                            $middle_absence_array[$j]->date = $y_m_d[2];
                            $middle_absence_array[$j]->duration = $twd->duration;
                            $middle_absence_array[$j]->save();
                            $middle_duration += $middle_absence_array[$j]->duration;
                        }
                    }
                }
            }
            $absence->duration = array_sum($duration_array) + $middle_duration;
        }
        else
        {
            session()->flash('danger','请假时长计算失败！');
            return redirect()->back()->withInput();
        }

        // 只有年假，且被批准情况下计算剩余年假
        if ($absence->absence_type == "年假"){
            $staff = $absence->staff;
            $remaining = $staff->remaining_annual_holiday;
            // 新的剩余年假：把之前减去的时长加上，再减去新的时长
            $staff->remaining_annual_holiday = $remaining + $origin_duration - $absence->duration;

            if ($staff->remaining_annual_holiday<0){
                session()->flash('danger','年假余额不足，不能请假！');
                return redirect()->back()->withInput();
            }
            else
            {
                $staff->save();
            }
        }

        // 计算修改过的调休
        if ($absence->absence_type == "调休"){
            $lieu = $absence->staff->lieu;
            $remaining = $lieu->remaining_time;
            // 新的剩余调休：把之前减去的时长加上，再减去新的时长
            $lieu->remaining_time = $remaining + $origin_duration - $absence->duration;
            if ($lieu->remaining_time<0){
                session()->flash('danger','调休余额不足，不能请假！');
                return redirect()->back()->withInput();
            }
            else
            {
                $lieu->save();
            }
        }

        if ($absence->save()) {
            // 把请假申请的id录入分日请假
            if (count($duration_array) == 1)
            {
                $separate_absence->absence_id = $absence->id;
                $separate_absence->staff_id = $absence->staff_id;
                $separate_absence->save();
            }
            elseif (count($duration_array) ==2)
            {
                $separate_absence_start->absence_id = $absence->id;
                $separate_absence_start->staff_id = $absence->staff_id;
                $separate_absence_start->save();
                $separate_absence_end->absence_id = $absence->id;
                $separate_absence_end->staff_id = $absence->staff_id;
                $separate_absence_end->save();
                foreach ($middle_absence_array as $maa) {
                    $maa->absence_id = $absence->id;
                    $maa->staff_id = $absence->staff_id;
                    $maa->save();
                }
            }
            session()->flash('success','更新成功！');
            return redirect('absences'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }
    }
}
