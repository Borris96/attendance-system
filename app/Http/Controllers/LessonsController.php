<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Term;
use App\Teacher;
use App\LessonUpdate;
use App\MonthDuration;

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
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->start_date)->get();
        if (stristr($term->term_name,'Summer'))
        {
            $lessons = Lesson::where('term_id',$term_id)->where('day','Mon')->orderBy('lesson_name')->get();
        }
        else
        {
            $lessons = Lesson::where('term_id',$term_id)->orderBy('lesson_name')->get();
        }
        return view('lessons/index',compact('lessons','terms','term_id','teachers','term'));
    }

    public function destroy(Request $request, $id)
    {
        $term_id = $request->input('term_id');
        $term = Term::find($term_id);
        if (stristr($term->term_name, 'Summer'))
        {
            $count = 2;
        }
        else
        {
            $count = 0;
        }

        for($i = 0; $i<=$count; $i++)
        {
            $lesson = Lesson::find($id+$i);
            $lesson_updates = $lesson->lessonUpdates;
            // 不仅要删除课程本身，还要将其关联的所有老师的实际排课时长删除
            foreach ($lesson_updates as $lu) {
                $start_date = $lu->start_date;
                $end_date = $lu->end_date;
                if ($lu->teacher_id != null)
                {
                    Teacher::calTermDuration($start_date, $end_date, $lu, $option = 'substract');
                }
                $lu->delete();
            }
            $lesson->delete();
        }
        session()->flash('success','删除课程成功！');
        return redirect()->back()->withInput();
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

    public function editTeacher(Request $request, $id)
    {
        $current_term_id = $request->input('term_id');
        $term = Term::find($current_term_id);
        $lesson = Lesson::find($id);
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->end_date)->get();
        return view('lessons/edit_teacher',compact('lesson','term','current_term_id','teachers'));
    }

    public function updateTeacher(Request $request, $id) // 更换老师
    {
        $this->validate($request, [
        'effective_date'=>'required',
        ]);
        $current_term_id = $request->input('term_id');
        $lesson = Lesson::find($id);
        $teacher = $lesson->teacher;
        $current_teacher_id = $request->input('current_teacher_id');
        if ($current_teacher_id == $teacher->id)
        {
            session()->flash('danger','上课老师未更改！');
            return redirect()->back()->withInput();
        }

        // 判断生效时间是否在学期内
        if (strtotime($request->get('effective_date'))>strtotime($lesson->term->end_date) || strtotime($request->get('effective_date'))<strtotime($lesson->term->start_date))
        {
            session()->flash('danger','生效时间超出范围！');
            return redirect()->back()->withInput();
        }

        // 如果之前有过更新记录，那么这次的生效时间不能比之前的生效时间早
        foreach ($lesson->lessonUpdates as $r)
        {
            if (strtotime($request->get('effective_date'))<strtotime($r->start_date))
            {
                session()->flash('danger','生效时间早于上次更新！');
                return redirect()->back()->withInput();
            }
        }

        $lesson->teacher_id = $current_teacher_id;
        if ($lesson->save())
        {
            $lesson_updates = $lesson->lessonUpdates;
            // 修改最后一次更新记录的end_date为新记录的effective_date, 新记录的end_date为term->end_date
            $count = count($lesson_updates);

            foreach ($lesson_updates as $key => $lu) {
                if ($key == ($count-1))
                {
                    $lu->end_date = $request->get('effective_date');
                    $lu->save();
                    $new_update = new LessonUpdate();
                    $new_update->lesson_id = $lesson->id;
                    $new_update->duration = $lesson->duration;
                    $new_update->teacher_id = $lesson->teacher_id;
                    $new_update->day = $lesson->day;
                    $new_update->start_date = $request->get('effective_date');
                    $new_update->end_date = $lesson->term->end_date;
                    $new_update->save();
                }
            }

            // 还要重新计算实际排课。原老师减去时长，现老师加上时长。
            // 这个时间段，就是新老师代课的时间段。也就是说之前的时间，原老师的实际排课时长不变，而在这个时间段里，原老师上这节课的时长要减去。
            // 这个时间段就是刚才的 $new_update

            $start_date = $new_update->start_date;
            $end_date = $new_update->end_date;
            // 由于$new_update的老师id是新老师的，所以要在调用调用下面函数时添加一个原老师id的参数。
            // 原老师时长减
            $teacher_id = $teacher->id;
            Teacher::calTermDuration($start_date, $end_date, $new_update, $option = 'substract', $teacher_id);
            // 新老师时长加
            Teacher::calTermDuration($start_date, $end_date, $new_update, $option = 'add');

            session()->flash('success','更换老师成功！');
            return redirect()->route('lessons.index',compact('current_term_id'));
        }
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
                $lesson_update->teacher_id = $lesson->teacher_id;
                $lesson_update->save();
                // 随后计算这个学期每月实际排课 (不考虑节假日调休情况)
                // $start_date = $lesson->term->start_date; // 学期开始日 计算第一个月实际排课要用
                // $end_date = $lesson->term->end_date; // 学期结束日 计算最后月实际排课要用

                $start_date = $lesson_update->start_date;
                $end_date = $lesson_update->end_date;
                if ($lesson_update->teacher_id != null) // 如果已经分配老师
                {
                    Teacher::calTermDuration($start_date, $end_date, $lesson_update);
                }
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
        $origin_teacher_id = $lesson->teacher_id;

        $lesson->lesson_name = $request->get('lesson_name');
        $lesson->start_time = $request->get('lesson_start_time');
        $lesson->end_time = $request->get('lesson_end_time');
        $lesson->day = $request->get('day');
        $lesson->classroom = $request->get('classroom');

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
                        session()->flash('danger','该时间段已排课！'.$r->lesson_name.' '.$r->day.'-'.date('H:i',strtotime($r->start_time)).'-'.date('H:i',strtotime($r->end_time)).'-'.$r->classroom);
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

        // 如果之前有过更新记录，那么这次的生效时间不能比之前的生效时间早
        foreach ($lesson->lessonUpdates as $r)
        {
            if (strtotime($request->get('effective_date'))<strtotime($r->start_date))
            {
                session()->flash('danger','生效时间早于上次更新！');
                return redirect()->back()->withInput();
            }
        }


        // 计算时长
        // 要考虑课程编辑之后，更新的数据在什么时候生效的问题。
        // 如学期区间1.1~6.30，在3.25课程调整，1.1~3.25该课程仍然按照之前的时长，星期数据计算实际排班，3.25~6.30按照新的时长和星期进行计算。这样需要减去3.25之后的原数据，加上更改的新数据。

        $lesson->duration = ($str_end-$str_start)/3600;

        if ($lesson->save())
        {

            // 当星期变了或者时长变了时需要记录这次更改的生效时间, 需要重新计算被关联老师（或者原老师）的实际排课
            if ($lesson->duration != $origin_duration || $lesson->day != $origin_day)
            {
                // 未分配老师的，直接在原来的更新记录上修改
                // 分配过老师的，新建一个更新记录
                $lesson_updates = $lesson->lessonUpdates;
                // 修改最后一次更新记录的end_date为新记录的effective_date, 新记录的end_date为term->end_date
                $count = count($lesson_updates);

                foreach ($lesson_updates as $key => $lu) {
                    if ($key == ($count-1))
                    {
                        if ($lesson->teacher_id != null)
                        {
                            $lu->end_date = $request->get('effective_date');
                            $lu->save();
                            $new_update = new LessonUpdate();
                            $new_update->lesson_id = $lesson->id;
                            $new_update->duration = $lesson->duration;
                            $new_update->day = $lesson->day;
                            $new_update->teacher_id = $lesson->teacher_id;
                            $new_update->start_date = $request->get('effective_date');
                            $new_update->end_date = $lesson->term->end_date;
                            $new_update->save();
                        }
                        else
                        {
                            $lu->start_date = $request->get('effective_date');
                            $lu->duration = $lesson->duration;
                            $lu->day = $lesson->day;
                            $lu->save();
                        }
                    }
                }
            }

                // 如果课程已经被关联了，重新计算每月实际排课 -> 这个稍后做吧
                // 即：学期开始后，课程的时长或者哪一天上课发生变动。这种情况下需要重新计算老师的应排课。
                if ($lesson->teacher_id != null)
                {
                    // 生效日期之前的实际排课课时不变，生效日期之后实际排课课时发生调整：
                        // 原来的减去，现在的加上。 - 依据是 $new_update
                    $start_date = $new_update->start_date;
                    $end_date = $new_update->end_date;
                    // 由于$new_update的老师id是新老师的，所以要在调用调用下面函数时添加一个原老师id的参数。
                    // 这个老师的这门课原时长减去 （星期或者时长的变动，所以需要增加原时长和原星期数据）
                    Teacher::calTermDuration($start_date, $end_date, $new_update, $option = 'substract','', $origin_duration, $origin_day);
                    // 这个老师的这门课新时长加上
                    Teacher::calTermDuration($start_date, $end_date, $new_update, $option = 'add');

                }
        }

        session()->flash('success','更新'.$lesson->lesson_name.'课程成功！');
        return redirect()->route('lessons.index',compact('term_id'));
    }
}
