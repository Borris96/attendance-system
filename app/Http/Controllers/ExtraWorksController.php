<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Staff;
use App\Absence;
use App\ExtraWork;
use App\Lieu;

class ExtraWorksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        if ($request->get('englishname') == null)
        {
            $extra_works = ExtraWork::orderBy('updated_at','desc')->get();
        }
        else {
            $englishname = $request->get('englishname');
            // 只能返回第一个有类似英文名的员工id
            $staff_id = Staff::where('englishname','like',$englishname.'%')->value('id');
            $extra_works = ExtraWork::where('staff_id',$staff_id)->orderBy('updated_at','desc')->get();
            if (count($extra_works) == 0)
            {
                session()->flash('warning', '加班记录不存在！');
                return redirect()->back()->withInput();
            }
        }

        return view('extra_works/index', compact('extra_works'));
    }



    public function create()
    {
        $staffs = Staff::where('status',true)->get();
        return view('extra_works/create',compact('staffs'));
    }


    public function edit($id) {
        $extra_work = ExtraWork::find($id);
        $staff = $extra_work->staff; // 获取该请假的申请人
        return view('extra_works.edit',compact('extra_work','staff'));
    }

    /**
     * 删除请假时，因为请假相应减少的时间应该加回来
     *
     */
    public function destroy($id) {
        $extra_work = ExtraWork::find($id);
        //被批准的调休记录删除时，剩余应相应增加
        $approve = $extra_work->approve;
        if ($approve == true){
            $duration = $extra_work->duration;
            $lieu = $extra_work->staff->lieu;
            $lieu->total_time -= $duration;
            $lieu->save();
        }
        $extra_work->delete();
        session()->flash('success', '成功删除加班记录！');
        return back();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'staff_id'=>'required',
            'extra_work_type'=>'required|max:50',
            'extra_work_start_time'=>'required',
            'extra_work_end_time'=>'required',
            'approve' => 'required',
            'note'=>'max:140',
        ]);



        $extra_work = new ExtraWork();
        $extra_work->staff_id = $request->get('staff_id');
        $extra_work->extra_work_type = $request->get('extra_work_type');
        $extra_work->extra_work_start_time = $request->get('extra_work_start_time');
        $extra_work->extra_work_end_time = $request->get('extra_work_end_time');
        // 加班必须在同一天
        if (date("Y-m-d", strtotime($extra_work->extra_work_start_time)) !== date("Y-m-d", strtotime($extra_work->extra_work_end_time)))
        {
            session()->flash('danger','加班日期应在同一天！');
            return redirect()->back()->withInput();
        }

        // 开始不能晚于结束
        if ($extra_work->extra_work_start_time>$extra_work->extra_work_end_time){
            session()->flash('danger','日期填写错误！');
            return redirect()->back()->withInput();
        }

        // 判断新的请假时间是否与该员工原来的某段请假时间重叠，如不重叠才能创建成功。
        $ew_start_time = strtotime($extra_work->extra_work_start_time);
        $ew_end_time = strtotime($extra_work->extra_work_end_time);
        $staff = Staff::find($extra_work->staff_id);
        $extra_works = $staff->extraWorks;
        foreach ($extra_works as $ew) {
            $old_ew_start_time = strtotime($ew->extra_work_start_time);
            $old_ew_end_time = strtotime($ew->extra_work_end_time);
            if ($extra_work->isCrossing($ew_start_time, $ew_end_time, $old_ew_start_time, $old_ew_end_time) == true) {
                session()->flash('danger','请假时间重叠！');
                return redirect()->back()->withInput();
            }
        }

        $extra_work->approve = $request->get('approve');
        $extra_work->note = $request->get('note');
        // $extra_work->duration = 0.9;
        $extra_work->duration = $extra_work->calDuration($extra_work->extra_work_start_time, $extra_work->extra_work_end_time);

        // $now = $staff->extra_works()->value('duration'); //取在staffscontroller里duration的值，此处用不上。
        // dump($staff);
        // exit();

        //把被批准的调休类加班存进lieus表
        if ($extra_work->extra_work_type == "调休" && $extra_work->approve == true){
            $lieu = $extra_work->staff->lieu;

            if ($lieu != null) {
                $lieu->total_time += $extra_work->duration;
                $lieu->remaining_time += $extra_work->duration;
            } else {
                $lieu = new Lieu();
                $lieu->staff_id = $extra_work->staff_id;
                $lieu->total_time = $extra_work->duration;
                $lieu->remaining_time = $extra_work->duration;
            }

            if ($extra_work->save()) {
                $lieu->save();
                session()->flash('success','保存成功！');
                return redirect('extra_works'); //应导向列表
            } else {
                session()->flash('danger','保存失败！');
                return redirect()->back()->withInput();
            }
        } elseif ($extra_work->extra_work_type == "调休" && $extra_work->approve == false) {
            session()->flash('danger','调休需要批准！');
            return redirect()->back()->withInput();
        }

        if ($extra_work->save()) {
            session()->flash('success','保存成功！');
            return redirect('extra_works'); //应导向列表
        } else {
            session()->flash('danger','保存失败！');
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'extra_work_start_time'=>'required',
            'extra_work_end_time'=>'required',
            'approve' => 'required',
            'note'=>'required|max:140',
        ]);

        $extra_work = ExtraWork::find($id);
        $origin_duration = $extra_work->duration; // 获取之前时长，重新计算调休时要用
        $origin_approve = $extra_work->approve; // 获取是否批准，重新计算调休时要用
        $extra_work->extra_work_start_time = $request->get('extra_work_start_time');
        $extra_work->extra_work_end_time = $request->get('extra_work_end_time');

        // 加班必须在同一天
        if (date("Y-m-d", strtotime($extra_work->extra_work_start_time)) !== date("Y-m-d", strtotime($extra_work->extra_work_end_time)))
        {
            session()->flash('danger','加班日期应在同一天！');
            return redirect()->back()->withInput();
        }

        if ($extra_work->extra_work_start_time>$extra_work->extra_work_end_time){ //开始时间不能比结束时间早
            session()->flash('danger','日期填写错误！');
            return redirect()->back()->withInput();
        }

        // 判断编辑的请假时间是否与该员工原来的某段请假时间重叠，如不重叠才能创建成功。
        $ew_start_time = strtotime($extra_work->extra_work_start_time);
        $ew_end_time = strtotime($extra_work->extra_work_end_time);
        $staff = Staff::find($extra_work->staff_id);
        $extra_works = $staff->extraWorks->whereNotIn('id',[$id]);
        foreach ($extra_works as $ew) {
            $old_ew_start_time = strtotime($ew->extra_work_start_time);
            $old_ew_end_time = strtotime($ew->extra_work_end_time);
            if ($extra_work->isCrossing($ew_start_time, $ew_end_time, $old_ew_start_time, $old_ew_end_time) == true) {
                session()->flash('danger','请假时间重叠！');
                return redirect()->back()->withInput();
            }
        }

        $extra_work->approve = $request->get('approve');
        $extra_work->note = $request->get('note');
        // $extra_work->duration = 9;
        $extra_work->duration = $extra_work->calDuration($extra_work->extra_work_start_time, $extra_work->extra_work_end_time);

        // 只有调休，且被批准情况下计算剩余调休
        // if ($extra_work->extra_work_type == "调休" && $extra_work->approve == true){
        //     $lieu = $extra_work->staff->lieu;

        //     if ($origin_approve == true) { //之前批准了，那么减去之前的调休时间，加上新的调休时间
        //         $lieu->total_time = $lieu->total_time - $origin_duration + $extra_work->duration;
        //         $lieu->remaining_time = $lieu->remaining_time - $origin_duration + $extra_work->duration;
        //     } else { //之前未批准，要分类讨论
        //         // 对于已创建的用户：直接把新批准的时间加上
        //         if ($staff->lieu != null)
        //         {
        //             $lieu->total_time = $lieu->total_time + $extra_work->duration;
        //             $lieu->remaining_time = $lieu->remaining_time + $extra_work->duration;
        //         } else {
        //             // 对于新建用户：赋值新批准的时间
        //             $lieu = new Lieu();
        //             $lieu->staff_id = $extra_work->staff_id;
        //             $lieu->total_time = $extra_work->duration;
        //             $lieu->remaining_time = $extra_work->duration;
        //         }
        //     }

        //     if ($lieu->remaining_time<0){
        //         session()->flash('danger','剩余调休时间不足，请增加加班时间！');
        //         return redirect()->back()->withInput();
        //     }
        //     if ($extra_work->save() && $lieu->save()) {
        //         session()->flash('success','调休加班更新成功！');
        //         return redirect('extra_works'); //应导向列表
        //     } else {
        //         session()->flash('danger','调休加班更新失败！');
        //         return redirect()->back()->withInput();
        //     }
        // } else
        if ($extra_work->extra_work_type == "调休" && $extra_work->approve == false) {
            session()->flash('danger','调休需要批准！');
            return redirect()->back()->withInput();
        }

        // 重新计算总调休时间和总调休剩余
        $extra_work->staff->lieu->remaining_time = $extra_work->staff->lieu->remaining_time-$extra_work->staff->lieu->total_time+$extra_work->duration;
        $extra_work->staff->lieu->total_time = $extra_work->duration;


        if ($extra_work->save()) {
            $extra_work->staff->lieu->save();
            session()->flash('success','更新成功！');
            return redirect('extra_works'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }
    }
}
