<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Absence;
use App\Lieu;

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
            $absences = Absence::orderBy('updated_at','desc')->paginate(15);
        }
        else {
            $englishname = $request->get('englishname');
            // 只能返回第一个有类似英文名的员工id
            $staff_id = Staff::where('englishname','like',$englishname.'%')->value('id');
            $absences = Absence::where('staff_id',$staff_id)->orderBy('updated_at','desc')->paginate(15);
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
        return view('absences/create',compact('staffs'));
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
        if ($absence->absence_start_time>$absence->absence_end_time){
            session()->flash('danger','日期填写错误！');
            return redirect()->back()->withInput();
        }

        $absence_start_time = strtotime($absence->absence_start_time);
        $absence_end_time = strtotime($absence->absence_end_time);

        // 开始查找该员工的工作日上下班数据
        $weekarray=array("日","一","二","三","四","五","六");
        $absence_start_day = $weekarray[date('w', $absence_start_time)]; // 请假开始那天是周几
        $absence_end_day = $weekarray[date('w', $absence_end_time)]; // 请假结束那天是周几

        ////// 判断请假的天数中是否有该员工的假期


        $staff = Staff::find($absence->staff_id);
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

        $absence->approve = $request->get('approve');
        $absence->note = $request->get('note');
        // $absence->duration = 0.9;
        $absence->duration = $absence->calDuration($first_day_home_time, $last_day_work_time, $work_duration, $absence->absence_start_time, $absence->absence_end_time);

        // 只有年假，且被批准情况下计算剩余年假
        if ($absence->absence_type == "年假" && $absence->approve == true){
            $staff = Staff::find($absence->staff_id);
            $staff->remaining_annual_holiday -= $absence->duration;
            if ($staff->remaining_annual_holiday<0){
                session()->flash('danger','年假余额不足，不能请假！');
                return redirect()->back()->withInput();
            }

            if ($absence->save() && $staff->save()) {
                session()->flash('success','保存成功！');
                return redirect('absences'); //应导向列表
            } else {
                session()->flash('danger','年假更新失败！');
                return redirect()->back()->withInput();
            }
        } elseif ($absence->absence_type == "年假" && $absence->approve == false) {
            session()->flash('danger','年假需要批准！');
            return redirect()->back()->withInput();
        }

        if ($absence->absence_type == "调休" && $absence->approve == true){
            //把这个员工的那一条调休记录调出来！！！
            $this_lieu = $absence->staff->lieu;
            if ($this_lieu == null) {
                session()->flash('danger','调休剩余时间不足！');
                return redirect()->back()->withInput();
            }
            $this_lieu->remaining_time -= $absence->duration;
            if ($this_lieu->remaining_time<0){
                session()->flash('danger','调休剩余时间不足，不能请假！');
                return redirect()->back()->withInput();
            }

            if ($absence->save() && $this_lieu->save()) {
                session()->flash('success','保存成功！');
                return redirect('absences'); //应导向列表
            } else {
                session()->flash('danger','调休更新失败！');
                return redirect()->back()->withInput();
            }
        } elseif ($absence->absence_type == "调休" && $absence->approve == false) {
            session()->flash('danger','调休需要批准！');
            return redirect()->back()->withInput();
        }

        if ($absence->save()) {
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

        // 判断新的请假时间是否与该员工原来的某段请假时间重叠，如不重叠才能更新成功。
        $absence_start_time = strtotime($absence->absence_start_time);
        $absence_end_time = strtotime($absence->absence_end_time);


        // 开始查找该员工的工作日上下班数据
        $weekarray=array("日","一","二","三","四","五","六");
        $absence_start_day = $weekarray[date('w', $absence_start_time)]; // 请假开始那天是周几
        $absence_end_day = $weekarray[date('w', $absence_end_time)]; // 请假结束那天是周几

        $staff = Staff::find($absence->staff_id);
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

        $absences = $staff->absences->whereNotIn('id',[$id]); // 除去本条记录
        foreach ($absences as $ab) {
            $old_absence_start_time = strtotime($ab->absence_start_time);
            $old_absence_end_time = strtotime($ab->absence_end_time);
            if ($absence->isCrossing($absence_start_time, $absence_end_time, $old_absence_start_time, $old_absence_end_time) == true) {
                session()->flash('danger','请假时间重叠！');
                return redirect()->back()->withInput();
            }
        }


        $absence->approve = $request->get('approve');
        $absence->note = $request->get('note');

        $absence->duration = $absence->calDuration($first_day_home_time, $last_day_work_time, $work_duration, $absence->absence_start_time, $absence->absence_end_time);

        if ($absence->absence_type == "调休" && $absence->approve == false) {
            session()->flash('danger','调休需要批准！');
            return redirect()->back()->withInput();
        }

        if ($absence->absence_type == "年假" && $absence->approve == false) {
            session()->flash('danger','年假需要批准！');
            return redirect()->back()->withInput();
        }

        // 只有年假，且被批准情况下计算剩余年假
        if ($absence->absence_type == "年假" && $absence->approve == true){
            $staff = $absence->staff;
            $remaining = $staff->remaining_annual_holiday;
            // 新的剩余年假：把之前减去的时长加上，再减去新的时长
            $staff->remaining_annual_holiday = $remaining + $origin_duration - $absence->duration;

            if ($staff->remaining_annual_holiday<0){
                session()->flash('danger','年假余额不足，不能请假！');
                return redirect()->back()->withInput();
            }
            if ($absence->save() && $staff->save()) {
                session()->flash('success','年假更新成功！');
                return redirect('absences'); //应导向列表
            } else {
                session()->flash('danger','年假更新失败！');
                return redirect()->back()->withInput();
            }
        }

        // 计算修改过的调休
        if ($absence->absence_type == "调休" && $absence->approve == true){
            $lieu = $absence->staff->lieu;
            $remaining = $lieu->remaining_time;
            // 新的剩余调休：把之前减去的时长加上，再减去新的时长
            $lieu->remaining_time = $remaining + $origin_duration - $absence->duration;

            if ($lieu->remaining_time<0){
                session()->flash('danger','调休余额不足，不能请假！');
                return redirect()->back()->withInput();
            }
            if ($absence->save() && $lieu->save()) {
                session()->flash('success','调休更新成功！');
                return redirect('absences'); //应导向列表
            } else {
                session()->flash('danger','调休更新失败！');
                return redirect()->back()->withInput();
            }
        }

        if ($absence->save()) {
            session()->flash('success','更新成功！');
            return redirect('absences'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }
    }
}
