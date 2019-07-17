<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lesson;
use App\Term;
use App\Teacher;

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

    public function edit($id)
    {
        $lesson = Lesson::find($id);
        return view('lessons/edit',compact('lesson'));
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
            // 'day' => 'required',
            'classroom'=>'required|integer|max:20',
            'term_id'=>'required',
            // 'teacher_id'=>'required'
        ]);
        $lesson = new Lesson();
        $lesson->lesson_name = $request->get('lesson_name');
        $lesson->start_time = $request->get('lesson_start_time');
        $lesson->end_time = $request->get('lesson_end_time');
        $lesson->day = $request->get('day');
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
        if ($lesson->start_time>$lesson->end_time)
        {
            session()->flash('danger','开始时间晚于结束时间！');
            return redirect()->back()->withInput();
        }
        // 计算时长
        $lesson->duration = ($str_end-$str_start)/3600;

        $term_id = $lesson->term_id;
        if ($lesson->save())
        {
            session()->flash('success','新建课程成功！');
            return redirect()->route('lessons.index',compact('term_id'));
        }
    }

    public function update(Request $request, $id)
    {

    }
}
