<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Absence;

class AbsencesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $absences = Absence::orderBy('id','desc')->paginate(10);
        return view('absences/index',compact('absences'));
    }

    public function create()
    {
        $staffs = Staff::all();
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
        //被批准的年假记录删除时，年假应相应增加
        $approve = $absence->approve;
        if ($approve == true){
            $duration = $absence->duration;
            $staff_id = $absence->staff->id;
            $staff = Staff::find($staff_id);
            $staff->remaining_annual_holiday += $duration;
            $staff->save();
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

        /** 请假时间不能重复
         * 首先调出该员工请假记录，
         * 再查询他是否有重复请假的情况
         * 新的开始时间不能在原来表中找到
         * 旧的开始时间也不能在原来表中找到
         * 有的话，创建失败
         */

        $this_absences = Absence::where('staff_id',$absence->staff_id);
        $result_start = $this_absences->where('absence_start_time', $absence->absence_start_time);
        $result_end = $this_absences->where('absence_end_time', $absence->absence_end_time);

        if ($result_start == true || $result_end == true) {
            session()->flash('danger', '请假时间重复');
            return redirect()->back()->withInput();
        }

        $absence->approve = $request->get('approve');
        $absence->note = $request->get('note');
        // $absence->duration = 0.9;
        $absence->duration = $absence->calDuration($absence->absence_start_time, $absence->absence_end_time);

        // $now = $staff->absences()->value('duration'); //取在staffscontroller里duration的值，此处用不上。
        // dump($staff);
        // exit();

        // 只有年假情况remaining
        if ($absence->absence_type == "年假" && $absence->approve == true){
            $staff = Staff::find($absence->staff_id);
            $remaining = $staff->remaining_annual_holiday;
            $staff->remaining_annual_holiday = $remaining - $absence->duration;
            if ($staff->remaining_annual_holiday<0){
                session()->flash('danger','年假余额不足，不能请假！');
                return redirect()->back()->withInput();
            }
            if (!($staff->save())){
                session()->flash('danger','年假更新失败！');
                return redirect()->back()->withInput();
            }
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

        /** 请假时间不能重复
         * 首先调出该员工请假记录，
         * 再查询他是否有重复请假的情况
         * 新的开始时间不能在原来表中找到
         * 旧的开始时间也不能在原来表中找到
         * 有的话，修改失败
         */

        $this_absences = Absence::where('staff_id',$absence->staff_id)->get();
        // whereNotIn() 排除正在修改的记录
        $result_start = $this_absences->whereNotIn('id',[$id])->where('absence_start_time', $absence->absence_start_time);
        $result_end = $this_absences->whereNotIn('id',[$id])->where('absence_end_time', $absence->absence_end_time);

        if (count($result_start) != 0 || count($result_end) != 0) {
            session()->flash('danger', '请假时间重复');
            return redirect()->back()->withInput();
        }

        $absence->approve = $request->get('approve');
        $absence->note = $request->get('note');
        // $absence->duration = 9;
        $absence->duration = $absence->calDuration($absence->absence_start_time, $absence->absence_end_time);

        // 只有年假，且被批准情况下计算剩余年假
        if ($absence->absence_type == "年假" && $absence->approve == true){
            $staff = $absence->staff;
            $remaining = $staff->remaining_annual_holiday;
            if ($origin_approve == true) { //之前批准了，那么恢复之前剩余年假，减去新的时长
                $staff->remaining_annual_holiday = $remaining + $origin_duration - $absence->duration;
            } else { //之前未批准，那么直接减去新的时长
                $staff->remaining_annual_holiday = $remaining - $absence->duration;
            }

            if ($staff->remaining_annual_holiday<0){
                session()->flash('danger','年假余额不足，不能请假！');
                return redirect()->back()->withInput();
            }
            if (!($staff->save())){
                session()->flash('danger','年假更新失败！');
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
