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
    public function index()
    {
        $extra_works = ExtraWork::orderBy('updated_at','desc')->paginate(10);
        return view('extra_works/index', compact('extra_works'));
    }



    public function create()
    {
        $staffs = Staff::all();
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
            $lieu->remaining_time += $duration;
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

        /** 加班时间不能重复
         * 首先调出该员工加班记录，
         * 再查询他是否有重复加班的情况
         * 新的开始时间或新的结束时间不能在原来表中找到
         * 有的话，创建失败
         */

        $this_extra_works = ExtraWork::where('staff_id',$extra_work->staff_id);
        $result_start = $this_extra_works->where('extra_work_start_time', $extra_work->extra_work_start_time)->get()->toArray();
        $result_end = $this_extra_works->where('extra_work_end_time', $extra_work->extra_work_end_time)->get()->toArray();

        if (count($result_start) != 0 || count($result_end) != 0) {
            session()->flash('danger', '加班时间重复');
            return redirect()->back()->withInput();
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

            if ($extra_work->save() && $lieu->save()) {
                session()->flash('success','保存成功！');
                return redirect('extra_works'); //应导向列表
            } else {
                session()->flash('danger','保存失败！');
                return redirect()->back()->withInput();
            }
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

        /** 请假时间不能重复
         * 首先调出该员工请假记录，
         * 再查询他是否有重复请假的情况
         * 新的开始时间不能在原来表中找到
         * 旧的开始时间也不能在原来表中找到
         * 有的话，修改失败
         */

//         $this_extra_works = ExtraWork::where('staff_id',$extra_work->staff_id)->get();
//         // whereNotIn() 排除正在修改的记录
//         $result_start = $this_extra_works->whereNotIn('id',[$id])->where('extra_work_start_time', $extra_work->extra_work_start_time)->get()->toArray();
//         $result_end = $this_extra_works->whereNotIn('id',[$id])->where('extra_work_end_time', $extra_work->extra_work_end_time)->get()->toArray();

// // ************* 这个不对，应该判定范围


//         if (count($result_start) != 0 || count($result_end) != 0) {
//             session()->flash('danger', '请假时间重复');
//             return redirect()->back()->withInput();
//         }

        $extra_work->approve = $request->get('approve');
        $extra_work->note = $request->get('note');
        // $extra_work->duration = 9;
        $extra_work->duration = $extra_work->calDuration($extra_work->extra_work_start_time, $extra_work->extra_work_end_time);

        // 只有年假，且被批准情况下计算剩余年假
        if ($extra_work->extra_work_type == "调休" && $extra_work->approve == true){
            $lieu = $extra_work->staff->lieu;

            if ($origin_approve == true) { //之前批准了，那么减去之前的调休时间，加上新的调休时间
                $lieu->total_time = $lieu->total_time - $origin_duration + $extra_work->duration;
                $lieu->remaining_time = $lieu->remaining_time - $origin_duration + $extra_work->duration;
            } else { //之前未批准，那么直接减去新
                $lieu->total_time = $lieu->total_time + $extra_work->duration;
                $lieu->remaining_time = $lieu->remaining_time + $extra_work->duration;
            }

            if ($lieu->remaining_time<0){
                session()->flash('danger','剩余调休时间不足，请增加加班时间！');
                return redirect()->back()->withInput();
            }
            if ($extra_work->save() && $lieu->save()) {
                session()->flash('success','调休加班更新成功！');
                return redirect('extra_works'); //应导向列表
            } else {
                session()->flash('danger','调休加班更新失败！');
                return redirect()->back()->withInput();
            }
        }

        if ($extra_work->save()) {
            session()->flash('success','更新成功！');
            return redirect('extra_works'); //应导向列表
        } else {
            session()->flash('danger','更新失败！');
            return redirect()->back()->withInput();
        }
    }
}
