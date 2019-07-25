<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Teacher;
use App\Staff;
use App\Lesson;
use App\Term;
use App\MonthDuration;
use App\Holiday;
use App\TermTotal;
use App\LessonAttendance;
use App\LessonUpdate;
use App\WorkHistory;

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
            // $today = '2019-05-05';
            $today = date('Y-m-d'); // 等投入使用之后再改过来
            foreach ($terms as $t) {
                if ($today <= $t->end_date && $today >= $t->start_date)
                {
                    $term_id = $t->id;
                }
            }
        }
        $term = Term::find($term_id);
        $flag = stristr($term->term_name, 'Summer');
        // 寻找在这个学期上课的老师，即：入职比学期开始早，离职比学期开始晚
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->start_date)->get();
        return view('teachers/index',compact('staffs','teachers','terms','term_id','flag'));
    }

    public function show(Request $request, $id)
    {
        // 显示这个学期的排课。 目前还没有从用户处获取当前学期的方法。
        $current_term_id = $request->input('term_id');
        $term = Term::find($current_term_id);
        $teacher = Teacher::find($id);
        $holidays = Holiday::all(); // 在计算实际上课时需要考虑到
        // 计算每个月应排课（目前默认为一整月时间，不考虑学期具体几号开始）
        // 需要改。
        $start_date = $term->start_date;
        $end_date = $term->end_date;
        $start_year = date('Y',strtotime($start_date));

        $term_months = Teacher::getTermMonths($start_date, $end_date);
        $month_should_durations = [];
        $year = $start_year;
        foreach ($term_months as $key => $m)
        {
            $term = Term::find($current_term_id);
            $s_m_y = $year.'-'.$term_months[$key];
            $month_f_l = LessonAttendance::decideMonthFirstLast($term->start_date, $term->end_date, $s_m_y); // 计算学期内的一个月的首末日期
            // $month_first_day = date('Y-m-01',strtotime($year.'-'.$term_months[$key]));
            // $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
            $month_first_day = $month_f_l[0];
            $month_last_day = $month_f_l[1];

            // 学期首月首周和末月末周的应上班: 由于学期开始不一定在周一，结束不一定在周日，所以要把首末月起止时间往前往后推移。
            if ($key == 0) // 首月补满首周
            {
                while (date('w',strtotime($month_first_day)) != 1)
                {
                    $month_first_day = date('Y-m-d', strtotime("$month_first_day -1 day"));
                }
            }
            elseif ($key == count($term_months)-1) // 末月补满末周
            {
                while (date('w',strtotime($month_last_day)) != 0)
                {
                    $month_last_day = date('Y-m-d', strtotime("$month_last_day +1 day"));
                }
                // dump($month_last_day);
                // exit();
            }

            $month_should_durations[$m] = Teacher::calShouldMonthDuration($teacher, $month_first_day,$month_last_day);
            if ($term_months[$key] == 12) // 到12月了那么年数加一
            {
                $year+=1;
            }
        }
        // dump($month_should_durations);
        // exit();
        $lesson_updates = LessonUpdate::where('teacher_id',$id)->orderBy('lesson_id')->get();
        $lessons = Lesson::where('teacher_id',$id)->where('term_id',$current_term_id)->orderBy('lesson_name','asc')->get();
        // 本学期每月实际排课
        $month_durations = MonthDuration::where('teacher_id',$id)->where('term_id',$current_term_id)->orderBy('year','asc')->get();
        $term_totals = TermTotal::where('teacher_id',$id)->where('term_id',$current_term_id)->get();

        $flag = stristr($term->term_name, 'Summer');
        return view('teachers/show',compact('teacher','lessons','lesson_updates','term','term_totals','current_term_id','month_durations', 'month_should_durations','flag'));
    }

    public function edit(Request $request, $id)
    {
        $current_term_id = $request->get('term_id');
        $term = Term::find($current_term_id);
        $teacher = Teacher::find($id);
        if (stristr($term->term_name, 'Summer'))
        {
            $lessons = Lesson::where('term_id',$current_term_id)->where('day','Mon')->whereNull('teacher_id')->whereNotNull('day')->get();
        }
        else
        {
            $lessons = Lesson::where('term_id',$current_term_id)->whereNull('teacher_id')->whereNotNull('day')->get();
        }

        return view('teachers/edit',compact('term','lessons','teacher','current_term_id'));
    }

    // 新增老师(从员工中选择)
    public function role(Request $request)
    {
        // $this->validate($request, [
        //     'id'=>'integer|required|unique:staffs',
        // ]);
        $staff_ids = $request->input('staff_ids');
        if ($staff_ids != null)
        {
            foreach($staff_ids as $staff_id)
            {
                $staff = Staff::find($staff_id);
                // 如果之前没当过老师，新建；如果之前当过，找到之前的老师id，重新建立关联
                if ($staff->teacher == null)
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

        $term_id = $request->input('term_id');
        $term = Term::find($term_id);

        $teacher_id = $request->get('teacher_id');


        $lesson_ids = $request->input('lesson_id');
        foreach($lesson_ids as $id)
        {

            if (stristr($term->term_name, 'Summer'))
            {
                Teacher::linkLessons($id,$teacher_id); // $id 是 lesson_id, 暑期默认是Mon的id
                Teacher::linkLessons($id+1,$teacher_id); // Wed
                Teacher::linkLessons($id+2,$teacher_id); // Fri
            }
            else
            {
                Teacher::linkLessons($id,$teacher_id); // $id 是 lesson_id
            }

        }
        session()->flash('success','关联课程成功！');
        return redirect()->route('teachers.index',compact('term_id'));
    }

    // 新建学期（不可和之前学期重合）
    // 学期名称中必须包括 summer spring 或者 fall
    public function createTerm(Request $request)
    {
        $term_id = $request->get('term_id');
        return view('teachers/create_term',compact('term_id'));
    }

    public function storeTerm(Request $request)
    {
        $this->validate($request, [
            'term_name'=>'required|max:50',
            'start_date'=>'required',
            'end_date'=>'required',
        ]);
        $term = new Term();
        $term->term_name = $request->input('term_name');
        $term->start_date = $request->input('start_date');
        $term->end_date = $request->input('end_date');

        if (stristr($term->term_name,'Summer')==false && stristr($term->term_name,'Spring')==false && stristr($term->term_name,'Fall')==false)
        {
            session()->flash('danger','学期名称必须包括 Spring, Summer 或 Fall！');
            return redirect()->back()->withInput();
        }
        // 需要判断的是起止日是否填反，新建学期是否和已存在学期重合
        if ($term->start_date>=$term->end_date)
        {
            session()->flash('danger','起止日期填反！');
            return redirect()->back()->withInput();
        }
        $terms = Term::all();
        foreach($terms as $t)
        {
            if (WorkHistory::isCrossing($term->start_date,$term->end_date,$t->start_date,$t->end_date))
            {
                session()->flash('danger','起止日期与之前学期重合！');
                return redirect()->back()->withInput();
            }
        }
        $term_id = $request->input('term_id');

        if ($term->save())
        {
            session()->flash('success','新建学期成功！');
            return redirect()->route('teachers.index',compact('term_id'));
        }
    }

    // 更新学期（不可和之前学期重合，学期内课程有效期后延，老师实际排课更新）
    public function editTerm(Request $request)
    {
        $term_id = $request->input('term_id');
        $term = Term::find($term_id);
        return view('teachers/edit_term',compact('term'));
    }

    public function updateTerm(Request $request)
    {
        $this->validate($request, [
            'term_name'=>'required|max:50',
            'start_date'=>'required',
            'end_date'=>'required',
        ]);
        $term = Term::find($request->input('term_id'));
        $term->term_name = $request->input('term_name');
        $term->start_date = $request->input('start_date');
        $term->end_date = $request->input('end_date');

        if (stristr($term->term_name,'Summer')==false && stristr($term->term_name,'Spring')==false && stristr($term->term_name,'Fall')==false)
        {
            session()->flash('danger','学期名称必须包括 Spring, Summer 或 Fall！');
            return redirect()->back()->withInput();
        }
        // 需要判断的是起止日是否填反，新建学期是否和已存在学期重合
        if ($term->start_date>=$term->end_date)
        {
            session()->flash('danger','起止日期填反！');
            return redirect()->back()->withInput();
        }
        $terms = Term::where('term_id','<>',$term->id)->get();
        foreach($terms as $t)
        {
            if (WorkHistory::isCrossing($term->start_date,$term->end_date,$t->start_date,$t->end_date))
            {
                session()->flash('danger','起止日期与之前学期重合！');
                return redirect()->back()->withInput();
            }
        }

        // 需要修改课程的前后有效时间，并且需要修改老师学期首末月实际排课 -- 最后做吧，毕竟不是常用功能

        // 开始时间延后
        // 开始时间提前

        // 结束时间提前
        // 结束时间延后

        $term_id = $request->input('term_id');
        if ($term->save())
        {
            session()->flash('success','修改学期成功！');
            return redirect()->route('teachers.index',compact('term_id'));
        }
    }
}
