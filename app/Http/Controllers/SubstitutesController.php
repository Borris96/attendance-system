<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Substitute;
use App\Term;
use App\Lesson;
use App\Teacher;
use App\TermTotal;

class SubstitutesController extends Controller
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
        $substitutes = Substitute::where('term_id',$term_id)->orderBy('lesson_date')->get();
        return view('substitutes/index',compact('substitutes','terms','term_id'));
    }

    public function destroy()
    {

    }

    public function edit()
    {
        return view('substitutes/edit');
    }

    public function create(Request $request)
    {
        $current_term_id = $request->get('term_id');
        $term = Term::find($current_term_id);
        // 这个学期的课
        $lessons = Lesson::where('term_id',$current_term_id)->whereNotNull('teacher_id')->orderBy('lesson_name')->get();
        // 寻找在这个学期上课的老师，即：入职比学期开始早，离职比学期开始晚
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->end_date)->get();
        return view('substitutes/create',compact('current_term_id','term','lessons','teachers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            // 'term_name'=>'',
            'lesson_id'=>'required',
            // 'substitute_teacher_id' => 'required',
            'lesson_date'=>'required',
        ]);

        $lesson_day_array = ['Sun'=>0,'Fri'=>5,'Sat'=>6, 'Mon'=>1, 'Wed'=>3];
        $substitute = new Substitute();

        $substitute->lesson_id = $request->get('lesson_id');
        $lesson = Lesson::find($substitute->lesson_id);
        $substitute->teacher_id = $lesson->teacher->id;
        $substitute->substitute_teacher_id = $request->get('substitute_teacher_id');
        $substitute->lesson_date = $request->get('lesson_date');
        $substitute->term_id = $request->get('current_term_id');

        // 该日这节课是否重复代课
        $find = Substitute::where('lesson_id',$substitute->lesson_id)->where('lesson_date',$substitute->lesson_date)->get();
        if (count($find) != 0)
        {
            session()->flash('danger','代课记录重复！');
            return redirect()->back()->withInput();
        }
        // 该日是否在学期内
        $term = Term::find($substitute->term_id);
        if (strtotime($substitute->lesson_date)<strtotime($term->start_date) || strtotime($substitute->lesson_date)>strtotime($term->end_date))
        {
            session()->flash('danger','日期超出范围！');
            return redirect()->back()->withInput();
        }

        // 该日是否有这节课

        $lesson_day = $lesson_day_array[$lesson->day];
        $this_day = date('w',strtotime($substitute->lesson_date));

        if ($lesson_day != $this_day)
        {
            session()->flash('danger','该日期未安排课程！');
            return redirect()->back()->withInput();
        }

        // 代课老师是否和原老师不同
        if ($substitute->teacher_id == $substitute->substitute_teacher_id)
        {
            session()->flash('danger','代课老师不能与原老师相同！');
            return redirect()->back()->withInput();
        }

        // 缺课老师这学期缺课记录更新
        $teacher = Teacher::find($substitute->teacher_id);
        // 查找是否有这个学期的总缺课记录
        $teacher_term_total = TermTotal::where('teacher_id',$substitute->teacher_id)->where('term_id',$substitute->term_id);

        if (count($teacher_term_total->get())!=0) // 这条记录存在
        {
            $teacher_term_total = TermTotal::find($teacher_term_total->value('id'));
            $teacher_term_total->total_missing_hours+= $substitute->lesson->duration;
        }
        else // 新建一条记录
        {
            $teacher_term_total = new TermTotal();
            $teacher_term_total->teacher_id = $substitute->teacher_id;
            $teacher_term_total->term_id = $substitute->term_id;
            $teacher_term_total->total_missing_hours += $substitute->lesson->duration;
        }

        // 代课老师这学期代课记录更新
        if ($substitute->substitute_teacher_id!=null)
        {
            $substitute_teacher = Teacher::find($substitute->substitute_teacher_id);

            // 查看这节课是否和这个老师的其他课程时间有冲突



            // 查找是否有这个学期的总代课缺课记录
            $substitute_teacher_term_total = TermTotal::where('teacher_id',$substitute->substitute_teacher_id)->where('term_id',$substitute->term_id);

            if (count($substitute_teacher_term_total->get())!=0) // 这条记录存在
            {
                $substitute_teacher_term_total = TermTotal::find($substitute_teacher_term_total->value('id'));
                $substitute_teacher_term_total->total_substitute_hours+= $substitute->lesson->duration;
            }
            else // 新建一条记录
            {
                $substitute_teacher_term_total = new TermTotal();
                $substitute_teacher_term_total->teacher_id = $substitute->substitute_teacher_id;
                $substitute_teacher_term_total->term_id = $substitute->term_id;
                $substitute_teacher_term_total->total_substitute_hours += $substitute->lesson->duration;
            }
        }

        $term_id = $substitute->term_id;
        if ($substitute->save())
        {
            $teacher_term_total->save();
            if ($substitute->substitute_teacher_id!=null)
            {
                $substitute_teacher_term_total->save();
            }
            session()->flash('success','代课缺课记录创建成功！');
            return redirect()->route('substitutes.index',compact('term_id'));
        }
    }

    public function update(Request $request, $id)
    {

    }
}
