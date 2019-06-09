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

    public function index()
    {
        $absences = Absence::orderBy('updated_at','desc')->paginate(10);
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

        if (strtotime($absence->absence_start_time)>strtotime(date("Y-m-d", strtotime($absence->absence_start_time)).' 18:00') || strtotime($absence->absence_end_time)<strtotime(date("Y-m-d", strtotime($absence->absence_end_time)).' 9:00')){
            session()->flash('danger','日期超出范围！');
            return redirect()->back()->withInput();
        }

        // 判断新的请假时间是否与该员工原来的某段请假时间重叠，如不重叠才能创建成功。
        $absence_start_time = strtotime($absence->absence_start_time);
        $absence_end_time = strtotime($absence->absence_end_time);
        $staff = Staff::find($absence->staff_id);
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
        $absence->duration = $absence->calDuration($absence->absence_start_time, $absence->absence_end_time);

        // $now = $staff->absences()->value('duration'); //取在staffscontroller里duration的值，此处用不上。
        // dump($staff);
        // exit();

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
        $origin_approve = $absence->approve; // 获取是否批准，重新计算年假时要用
        $absence->absence_start_time = $request->get('absence_start_time');
        $absence->absence_end_time = $request->get('absence_end_time');
        if ($absence->absence_start_time>$absence->absence_end_time){ //开始时间不能比结束时间早
            session()->flash('danger','日期填写错误！');
            return redirect()->back()->withInput();
        }

        if (strtotime($absence->absence_start_time)>strtotime(date("Y-m-d", strtotime($absence->absence_start_time)).' 18:00') || strtotime($absence->absence_end_time)<strtotime(date("Y-m-d", strtotime($absence->absence_end_time)).' 9:00')){
        // 开始时间不能是18点以后，结束时间不能是9点以前
            session()->flash('danger','日期超出范围！');
            return redirect()->back()->withInput();
        }

        // 判断新的请假时间是否与该员工原来的某段请假时间重叠，如不重叠才能更新成功。
        $absence_start_time = strtotime($absence->absence_start_time);
        $absence_end_time = strtotime($absence->absence_end_time);
        $staff = Staff::find($absence->staff_id);
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
        // $absence->duration = 9;
        $absence->duration = $absence->calDuration($absence->absence_start_time, $absence->absence_end_time);

        // 只有年假，且被批准情况下计算剩余年假
        // if ($absence->absence_type == "年假" && $absence->approve == true){
        //     $staff = $absence->staff;
        //     $remaining = $staff->remaining_annual_holiday;
        //     if ($origin_approve == true) { //之前批准了，那么恢复之前剩余年假，减去新的时长
        //         $staff->remaining_annual_holiday = $remaining + $origin_duration - $absence->duration;
        //     } else { //之前未批准，那么直接减去新的时长
        //         $staff->remaining_annual_holiday = $remaining - $absence->duration;
        //     }

        //     if ($staff->remaining_annual_holiday<0){
        //         session()->flash('danger','年假余额不足，不能请假！');
        //         return redirect()->back()->withInput();
        //     }
        //     if ($absence->save() && $staff->save()) {
        //         session()->flash('success','年假更新成功！');
        //         return redirect('absences'); //应导向列表
        //     } else {
        //         session()->flash('danger','年假更新失败！');
        //         return redirect()->back()->withInput();
        //     }
        // }
        if ($absence->absence_type == "调休" && $absence->approve == false) {
            session()->flash('danger','调休需要批准！');
            return redirect()->back()->withInput();
        }

        if ($absence->absence_type == "年假" && $absence->approve == false) {
            session()->flash('danger','年假需要批准！');
            return redirect()->back()->withInput();
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
