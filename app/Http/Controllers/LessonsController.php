<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Term;
use App\Teacher;
use App\LessonUpdate;

class LessonsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $terms = Term::all();
        $term_id = $request->get('term_id');
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
        $lessons = Lesson::where('term_id',$term_id)->orderBy('lesson_name')->get();
        return view('lessons/index',compact('lessons','terms','term_id'));
    }

    public function destroy()
    {

    }

    public function edit(Request $request, $id)
    {
        $current_term_id = $request->input('term_id');
        $term = Term::find($current_term_id);
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->end_date)->get();
        if (stristr($term->term_name, 'Summer'))
        {
            $days = ['Mon', 'Wed', 'Fri'];
        }
        else
        {
            $days = ['Fri', 'Sat', 'Sun'];
        }
        $lesson = Lesson::find($id);
        return view('lessons/edit',compact('lesson','term','days','current_term_id','teachers'));
    }

    public function create(Request $request)
    {
        $current_term_id = $request->input('term_id');
        $term = Term::find($current_term_id);
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->end_date)->get();
        // $terms = Term::all();
        if (stristr($term->term_name, 'Summer'))
        {
            $days = ['Mon', 'Wed', 'Fri'];
        }
        else
        {
            $days = ['Fri', 'Sat', 'Sun'];
        }
        return view('lessons/create',compact('teachers','term','days','current_term_id'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'lesson_name'=>'required|max:50',
            'lesson_start_time'=>'required',
            'lesson_end_time' => 'required',
            'day' => 'required',
            'classroom'=>'required|integer|max:20',
            'term_id'=>'required',
            // 'teacher_id'=>'required'
        ]);
        $term = Term::find($request->get('term_id'));
        if (stristr($term->term_name,'Summer')) // 暑假一节课 一三五 排课一样
        {
            $summer_days = ['Mon', 'Wed', 'Fri'];
        }
        else
        {
            $summer_days = [$request->get('day')];
        }
        foreach ($summer_days as $d) {
            $lesson = new Lesson();
            $lesson->lesson_name = $request->get('lesson_name');
            $lesson->start_time = $request->get('lesson_start_time');
            $lesson->end_time = $request->get('lesson_end_time');
            $lesson->day = $d;
            $lesson->classroom = $request->get('classroom');
            $lesson->term_id = $request->get('term_id');
            $lesson->teacher_id = $request->get('teacher_id');

            $str_start = strtotime($lesson->start_time);
            $str_end = strtotime($lesson->end_time);

            $results = Lesson::where('term_id',$lesson->term_id)->where('day',$lesson->day)->where('classroom',$lesson->classroom)->get();
            if ($results != null)
            {
                foreach($results as $r)
                {
                    $str_old_start = strtotime($r->start_time);
                    $str_old_end = strtotime($r->end_time);
                    if (Lesson::isCrossing($str_start, $str_end, $str_old_start, $str_old_end))
                    {
                        session()->flash('danger','该时间段已排课！');
                        return redirect()->back()->withInput();
                    }
                }
            }
            // 判断起止时间是否填反了
            if (strtotime($lesson->start_time)>strtotime($lesson->end_time))
            {
                session()->flash('danger','开始时间晚于结束时间！');
                return redirect()->back()->withInput();
            }
            // 计算时长
            $lesson->duration = ($str_end-$str_start)/3600;

            $term_id = $lesson->term_id;
            if ($lesson->save())
            {

                $lesson_update = new LessonUpdate();
                $lesson_update->lesson_id = $lesson->id;
                $lesson_update->duration = $lesson->duration;
                $lesson_update->day = $lesson->day;
                $lesson_update->start_date = $lesson->term->start_date;
                $lesson_update->end_date = $lesson->term->end_date;
                $lesson_update->save();
                // 随后计算这个学期每月实际排课 (不考虑节假日调休情况)
                // $start_date = $lesson->term->start_date; // 学期开始日 计算第一个月实际排课要用
                // $end_date = $lesson->term->end_date; // 学期结束日 计算最后月实际排课要用

                $start_date = $lesson_update->start_date;
                $end_date = $lesson_update->end_date;
                Teacher::calTermDuration($start_date, $end_date, $lesson);
            }
        }
        session()->flash('success','新建课程成功！');
        return redirect()->route('lessons.index',compact('term_id'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'lesson_name'=>'required|max:50',
            'lesson_start_time'=>'required',
            'lesson_end_time' => 'required',
            'day' => 'required',
            'classroom'=>'required|integer|max:20',
            // 'term_id'=>'required',
            // 'teacher_id'=>'required'
            'effective_date'=>'required',
        ]);

        $lesson = Lesson::find($id);
        $term_id = $lesson->term_id;
        $origin_day = $lesson->day;
        $origin_duration = $lesson->duration;

        $lesson->lesson_name = $request->get('lesson_name');
        $lesson->start_time = $request->get('lesson_start_time');
        $lesson->end_time = $request->get('lesson_end_time');
        $lesson->day = $request->get('day');
        $lesson->classroom = $request->get('classroom');
        $lesson->teacher_id = $request->get('teacher_id');

        $str_start = strtotime($lesson->start_time);
        $str_end = strtotime($lesson->end_time);

        $results = Lesson::where('term_id',$lesson->term_id)->where('day',$lesson->day)->where('classroom',$lesson->classroom)->get();

        if ($results != null)
        {
            foreach($results as $r)
            {
                if ($r->id != $lesson->id) // 编辑之后，看是否和其他课重合
                {
                    $str_old_start = strtotime($r->start_time);
                    $str_old_end = strtotime($r->end_time);
                    if (Lesson::isCrossing($str_start, $str_end, $str_old_start, $str_old_end))
                    {
                        session()->flash('danger','该时间段已排课！');
                        return redirect()->back()->withInput();
                    }
                }
            }
        }

        // 判断起止时间是否填反了
        if (strtotime($lesson->start_time)>strtotime($lesson->end_time))
        {
            session()->flash('danger','开始时间晚于结束时间！');
            return redirect()->back()->withInput();
        }

        // 判断生效时间是否在学期内
        if (strtotime($request->get('effective_date'))>strtotime($lesson->term->end_date) || strtotime($request->get('effective_date'))<strtotime($lesson->term->start_date))
        {
            session()->flash('danger','生效时间超出范围！');
            return redirect()->back()->withInput();
        }


        // 计算时长
        // 要考虑课程编辑之后，更新的数据在什么时候生效的问题。
        // 如学期区间1.1~6.30，在3.25课程调整，1.1~3.25该课程仍然按照之前的时长，星期数据计算实际排班，3.25~6.30按照新的时长和星期进行计算。这样需要减去3.25之后的原数据，加上更改的新数据。

        $lesson->duration = ($str_end-$str_start)/3600;

        if ($lesson->save())
        {
            // 只有当星期变了或者时长变了时才需要记录这次更改的生效
            if ($lesson->duration != $origin_duration || $lesson->day != $origin_day)
            {
                $lesson_updates = $lesson->lessonUpdates;
                if (count($lesson_updates)==0) // 之前这门课从未改过,建立两个更新记录
                {
                    $lesson_update_1 = new LessonUpdate();
                    $lesson_update_1->lesson_id = $lesson->id;
                    $lesson_update_1->duration = $origin_duration;
                    $lesson_update_1->day = $origin_day;
                    $lesson_update_1->start_date = $lesson->term->start_date;
                    $lesson_update_1->end_date = $request->get('effective_date');
                    $lesson_update_1->save();

                    $lesson_update_2 = new LessonUpdate();
                    $lesson_update_2->lesson_id = $lesson->id;
                    $lesson_update_2->duration = $lesson->duration;
                    $lesson_update_2->day = $lesson->day;
                    $lesson_update_2->start_date = $request->get('effective_date');
                    $lesson_update_2->end_date = $lesson->term->end_date;
                    $lesson_update_2->save();
                }
                // 如果之前有记录那么，只修改最近一次的更新记录，并创建新的更新记录
            }

            // 重新计算
            $all_updates = $lesson->lessonUpdates;
            foreach ($all_updates as $au)
            {
                $start_date = $au->start_date;
                $end_date = $au->end_date;
                $start_year = date('Y',strtotime($start_date));
            }
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

        session()->flash('success','更新课程成功！');
        return redirect()->route('lessons.index',compact('term_id'));
    }
}
