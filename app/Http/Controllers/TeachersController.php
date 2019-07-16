<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;
use App\Staff;
use App\Lesson;
use App\Term;
use App\MonthDuration;
use App\Holiday;

class TeachersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $terms = Term::all();
        $term_id = $request->get('term_id');
        // 添加老师时使用
        $staffs = Staff::where('status',true)->where('teacher_id',null)->orderBy('id','asc')->get();
        if ($term_id == null) // 如果没有输入要使用的学期，默认是当日所在的学期
        {
            $today = '2019-05-05';
            // $today = date('Y-m-d'); // 等投入使用之后再改过来
            foreach ($terms as $t) {
                if ($today <= $t->end_date && $today >= $t->start_date)
                {
                    $term_id = $t->id;
                }
            }
        }
        $term = Term::find($term_id);
        // 寻找在这个学期上课的老师，即：入职比学期开始早，离职比学期开始晚
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->end_date)->get();
        return view('teachers/index',compact('staffs','teachers','terms','term_id'));
    }

    public function show(Request $request, $id)
    {
        // 显示这个学期的排课。 目前还没有从用户处获取当前学期的方法。
        $current_term_id = $request->input('term_id');
        $term = Term::find($current_term_id);
        $teacher = Teacher::find($id);
        $holidays = Holiday::all(); // 在计算实际上课时需要考虑到
        // 计算每个月应排课（目前默认为一整月时间，不考虑学期具体几号开始）
        $start_date = $term->start_date;
        $end_date = $term->end_date;
        $start_year = date('Y',strtotime($start_date));

        $term_months = Teacher::getTermMonths($start_date, $end_date);
        $month_should_durations = [];
        $year = $start_year;
        foreach ($term_months as $key => $m)
        {
            $month_first_day = date('Y-m-01',strtotime($year.'-'.$term_months[$key]));
            $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
            $month_should_durations[$m] = Teacher::calShouldMonthDuration($teacher, $month_first_day,$month_last_day);
            if ($term_months[$key] == 12) // 到12月了那么年数加一
            {
                $year+=1;
            }
        }

        $lessons = Lesson::where('teacher_id',$id)->where('term_id',$current_term_id)->orderBy('lesson_name','asc')->get();
        // 本学期每月实际排课
        $month_durations = MonthDuration::where('teacher_id',$id)->where('term_id',$current_term_id)->orderBy('year','asc')->get();

        return view('teachers/show',compact('teacher','lessons','term','current_term_id','month_durations', 'month_should_durations'));
    }

    public function edit(Request $request, $id)
    {
        $current_term_id = $request->get('term_id');
        $term = Term::find($current_term_id);
        $teacher = Teacher::find($id);
        $lessons = Lesson::where('term_id',$current_term_id)->whereNull('teacher_id')->whereNotNull('day')->get();
        return view('teachers/edit',compact('term','lessons','teacher','current_term_id'));
    }

    // 新增老师(从员工中选择)
    public function role(Request $request)
    {
        // $this->validate($request, [
        //     'id'=>'integer|required|unique:staffs',
        // ]);
        $staff_ids = $request->input('staff_ids');
        foreach($staff_ids as $staff_id)
        {
            $staff = Staff::find($staff_id);
            // 如果之前没当过老师，新建；如果之前当过，找到之前的老师id，重新建立关联
            if ($staff->teacher==null)
            {
                $teacher = new Teacher();
            }
            else // 虽然下面代码写了，但是暂时不考虑重新回来当老师的情况
            {
                $teacher = Teacher::find($staff->teacher_id);
            }
            // 目前新增老师的操作是将老师id和员工id建立关联
            $teacher->staff_id = $staff_id;
            $teacher->status = true;
            // 方便查询离职老师
            $teacher->join_date = $teacher->staff->join_company;
            $teacher->leave_date = $teacher->staff->leave_company;
            if ($teacher->save())
            {
                $staff->teacher_id = $teacher->id;
                $staff->save();
                session()->flash('success','添加老师成功！');
            }
        }
        return redirect()->back();
    }

    public function remove($id)
    {
        $teacher = Teacher::find($id);
        // 移除老师操作，目前是移除老师id和员工id的关联
        $teacher->status = false;
        $teacher->leave_date = date('Y-m-d');
        // $teacher->staff->teacher_id = null;
        $teacher->save();
        session()->flash('warning','老师离职成功！');
        return redirect()->back();
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'lesson_id'=>'required',
        ]);
        $term_id = $request->get('term_id');
        $lesson_ids = $request->input('lesson_id');
        foreach($lesson_ids as $id)
        {
            $lesson = Lesson::find($id);
            $lesson->teacher_id = $request->get('teacher_id');
            $lesson->save();

            // 随后计算这个学期每月实际排课 (不考虑节假日调休情况)
            $start_date = $lesson->term->start_date; // 学期开始日 计算第一个月实际排课要用
            $end_date = $lesson->term->end_date; // 学期结束日 计算最后月实际排课要用
            $start_year = date('Y',strtotime($start_date));

            $term_months = Teacher::getTermMonths($start_date, $end_date);
            // 录入该学期每个月的实际排课课时
            foreach ($term_months as $key=>$m)
            {
                if ($key == 0) // 第一个月
                {
                    $year = $start_year;
                    $first_month_first_day = date('Y-m-01',strtotime($year.'-'.$term_months[$key]));
                    $month_last_day = date('Y-m-d', strtotime("$first_month_first_day +1 month -1 day"));
                    $month_first_day = $start_date;
                }
                elseif ($key == count($term_months)-1) //最后一个月
                {
                    $month_first_day = date('Y-m-01',strtotime($year.'-'.$term_months[$key])); // 最后一月的第一天
                    $month_last_day = $end_date;
                }
                else // 中间月份
                {
                    $month_first_day = date('Y-m-01',strtotime($year.'-'.$term_months[$key]));
                    $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
                }
                Teacher::calMonthDuration($month_first_day,$month_last_day,$lesson,$term_months[$key],$year);
                if ($term_months[$key] == 12) // 到12月了那么年数加一
                {
                    $year+=1;
                }
            }
        }
        session()->flash('success','关联课程成功！');
        return redirect()->route('teachers.index',compact('term_id'));
    }
}
