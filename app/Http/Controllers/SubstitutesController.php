<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Substitute;
use App\Term;
use App\Lesson;
use App\Teacher;
use App\TermTotal;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class SubstitutesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function exportSub(Request $request)
    {
        if ($request->get('start_date') == null || $request->get('end_date') == null) // 有一个为空说明查询不成功
        {
            $terms = Term::all();
            $term_id = $request->get('term_id');
            $substitutes = Substitute::where('term_id',$term_id)->orderBy('lesson_date')->get();
            return view('substitutes/index',compact('substitutes','terms','term_id'));
        }
        else
        {
            $start_date = $request->get('start_date');
            $end_date = date('Y-m-d',strtotime($request->get('end_date'))+24*3600);
            // 查询开始时间在此区间的加班记录
            $substitutes = Substitute::where('lesson_date','>=',$start_date)->where('lesson_date','<=',$end_date)->orderBy('lesson_date','asc')->get();

            if (count($substitutes)!=0)
            {
                $spreadsheet = new Spreadsheet();
                Substitute::export($request->get('start_date'), $request->get('end_date'), $substitutes, $spreadsheet);
            }
            else
            {
                session()->flash('warning','记录不存在！');
                return redirect()->back()->withInput();
            }


        }
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
        $substitutes = Substitute::where('term_id',$term_id)->orderBy('lesson_date')->get();
        return view('substitutes/index',compact('substitutes','terms','term_id'));
    }

    public function destroy($id)
    {
        // 删除条目的同时，减去缺课老师缺课时长，减去代课老师代课时长
        $substitute = Substitute::find($id);
        // 代课老师代课时长减
        $substitute_teacher_term_total_id = TermTotal::where('teacher_id',$substitute->substitute_teacher_id)->where('term_id',$substitute->term_id)->value('id');
        if ($substitute_teacher_term_total_id != null)
        {
            $substitute_teacher_term_total = TermTotal::find($substitute_teacher_term_total_id);
            $substitute_teacher_term_total->total_substitute_hours -= $substitute->duration;
            $substitute_teacher_term_total->save();
        }
        // 缺课老师缺课时长减
        $missing_teacher_term_total_id = TermTotal::where('teacher_id',$substitute->teacher_id)->where('term_id',$substitute->term_id)->value('id');
        $missing_teacher_term_total = TermTotal::find($missing_teacher_term_total_id);
        $missing_teacher_term_total->total_missing_hours -= $substitute->duration;
        $missing_teacher_term_total->save();

        $substitute->delete();
        session()->flash('success','删除代课缺课记录成功！');
        return redirect()->back()->withInput();

    }

    public function edit(Request $request, $id)
    {
        $current_term_id = $request->input('term_id');
        $term = Term::find($current_term_id);
        $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->end_date)->get();
        $substitute = Substitute::find($id);
        return view('substitutes/edit',compact('current_term_id','term','teachers','substitute'));
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
        $substitute->duration = $lesson->duration;

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
        $this->validate($request, [
            // 'term_name'=>'',
            // 'lesson_id'=>'required',
            // 'substitute_teacher_id' => 'required',
            'lesson_date'=>'required',
        ]);

        $substitute = Substitute::find($id);
        $origin_substitute_teacher_id = $substitute->substitute_teacher_id;
        $origin_lesson_date = $substitute->lesson_date;
        $substitute->substitute_teacher_id = $request->input('substitute_teacher_id');
        $substitute->lesson_date = $request->input('lesson_date');
        $substitute_teacher_term_total = null;
        $teacher_term_total = null;
        $origin_substitute_teacher_term_total = null;
        if ($origin_substitute_teacher_id == null) // 1. 没有代课老师
        {
            if ($substitute->substitute_teacher_id != null) // 1.1 代课老师修改
            {
                if ($substitute->lesson_date != $origin_lesson_date) // 1.1.1 日期修改
                    // 用那个日期的时长，缺课老师数据增减，代课老师增加
                {
                    $teacher_term_total = TermTotal::where('teacher_id',$substitute->teacher_id)->where('term_id',$substitute->term_id);
                    $teacher_term_total = TermTotal::find($teacher_term_total->value('id'));
                    $teacher_term_total->total_missing_hours -= $substitute->duration; // 先把之前的时长减去

                    // 获取这个日期这门课的时长
                    $lesson = Lesson::find($substitute->lesson_id);
                    $lesson_updates = $lesson->lessonUpdates;
                    foreach ($lesson_updates as $lu) {
                        // 上课日期在这个区间范围里，并且这门课仍然是这个老师上的情况下
                        if (strtotime($substitute->lesson_date) >= strtotime($lu->start_date) && strtotime($substitute->lesson_date) < strtotime($lu->end_date))
                        {
                            if ($lu->teacher_id == $substitute->teacher_id)
                            {
                                $substitute->duration = $lu->duration;
                                $teacher_term_total->total_missing_hours += $lu->duration; // 为缺课老师加上新的时长
                                $current_duration = $lu->duration;
                            }
                            else
                            {
                                session()->flash('danger','该日期原上课老师已变更！');
                                return redirect()->back()->withInput();
                            }
                        }
                    }

                    $substitute_teacher_term_total = TermTotal::where('teacher_id',$substitute->substitute_teacher_id)->where('term_id',$substitute->term_id);

                    if (count($substitute_teacher_term_total->get())!=0) // 这条记录存在
                    {
                        $substitute_teacher_term_total = TermTotal::find($substitute_teacher_term_total->value('id'));
                        $substitute_teacher_term_total->total_substitute_hours += $current_duration;
                    }
                    else // 新建一条记录
                    {
                        $substitute_teacher_term_total = new TermTotal();
                        $substitute_teacher_term_total->teacher_id = $substitute->substitute_teacher_id;
                        $substitute_teacher_term_total->term_id = $substitute->term_id;
                        $substitute_teacher_term_total->total_substitute_hours = $current_duration;
                    }
                }
                else // 1.1.2 日期未修改
                    // 增加代课老师代课时间
                {
                    $substitute_teacher_term_total = TermTotal::where('teacher_id',$substitute->substitute_teacher_id)->where('term_id',$substitute->term_id);

                    if (count($substitute_teacher_term_total->get())!=0) // 这条记录存在
                    {
                        $substitute_teacher_term_total = TermTotal::find($substitute_teacher_term_total->value('id'));
                        $substitute_teacher_term_total->total_substitute_hours += $substitute->duration;
                    }
                    else // 新建一条记录
                    {
                        $substitute_teacher_term_total = new TermTotal();
                        $substitute_teacher_term_total->teacher_id = $substitute->substitute_teacher_id;
                        $substitute_teacher_term_total->term_id = $substitute->term_id;
                        $substitute_teacher_term_total->total_substitute_hours = $substitute->duration;
                    }
                }
            }
            else // 1.2 代课老师未修改
            {
                if ($substitute->lesson_date != $origin_lesson_date) // 1.2.1 日期修改
                    // 用那个日期的时长，缺课老师数据增减
                {
                    $teacher_term_total = TermTotal::where('teacher_id',$substitute->teacher_id)->where('term_id',$substitute->term_id);
                    $teacher_term_total = TermTotal::find($teacher_term_total->value('id'));
                    $teacher_term_total->total_missing_hours -= $substitute->duration;

                    // 获取这个日期这门课的时长
                    $lesson = Lesson::find($substitute->lesson_id);
                    $lesson_updates = $lesson->lessonUpdates;
                    foreach ($lesson_updates as $lu) {
                        // 上课日期在这个区间范围里，并且这门课仍然是这个老师上的情况下
                        if (strtotime($substitute->lesson_date) >= strtotime($lu->start_date) && strtotime($substitute->lesson_date) < strtotime($lu->end_date))
                        {
                            if ($lu->teacher_id == $substitute->teacher_id)
                            {
                                $substitute->duration = $lu->duration;
                                $teacher_term_total->total_missing_hours += $lu->duration;
                            }
                            else
                            {
                                session()->flash('danger','该日期原上课老师已变更！');
                                return redirect()->back()->withInput();
                            }
                        }
                    }
                }
            }
        }
        else // 2. 有代课老师
        {
            if ($substitute->substitute_teacher_id != $origin_substitute_teacher_id) // 2.1 代课老师修改
            {
                if ($substitute->lesson_date != $origin_lesson_date) // 2.1.1 日期修改
                    // 缺课老师数据增减，原代课老师数据减(按之前日期时长)，新代课老师数据增(按现在日期时长)
                {
                    $teacher_term_total = TermTotal::where('teacher_id',$substitute->teacher_id)->where('term_id',$substitute->term_id);
                    $teacher_term_total = TermTotal::find($teacher_term_total->value('id'));
                    $teacher_term_total->total_missing_hours -= $substitute->duration; // 先把之前的时长减去
                    // 新代课老师
                    $substitute_teacher_term_total = TermTotal::where('teacher_id',$substitute->substitute_teacher_id)->where('term_id',$substitute->term_id);
                    $substitute_teacher_term_total = TermTotal::find($substitute_teacher_term_total->value('id'));

                    // 原代课老师
                    $origin_substitute_teacher_term_total = TermTotal::where('teacher_id',$origin_substitute_teacher_id)->where('term_id',$substitute->term_id);
                    $origin_substitute_teacher_term_total = TermTotal::find($origin_substitute_teacher_term_total->value('id'));
                    // 先把之前的时长减去
                    $origin_substitute_teacher_term_total->total_substitute_hours -= $substitute->duration;

                    // 获取这个日期这门课的时长
                    $lesson = Lesson::find($substitute->lesson_id);
                    $lesson_updates = $lesson->lessonUpdates;
                    foreach ($lesson_updates as $lu) {
                        // 上课日期在这个区间范围里，并且这门课仍然是这个老师上的情况下
                        if (strtotime($substitute->lesson_date) >= strtotime($lu->start_date) && strtotime($substitute->lesson_date) < strtotime($lu->end_date))
                        {
                            if ($lu->teacher_id == $substitute->teacher_id)
                            {
                                $substitute->duration = $lu->duration;
                                $teacher_term_total->total_missing_hours += $lu->duration; // 为缺课老师加上新的时长
                                $current_duration = $lu->duration;
                            }
                            else
                            {
                                session()->flash('danger','该日期原上课老师已变更！');
                                return redirect()->back()->withInput();
                            }
                        }
                    }
                    // 代课老师加上新时长
                        if (count($substitute_teacher_term_total->get())!=0) // 这条记录存在
                        {
                            $substitute_teacher_term_total = TermTotal::find($substitute_teacher_term_total->value('id'));
                            $substitute_teacher_term_total->total_substitute_hours += $current_duration;
                        }
                        else // 新建一条记录
                        {
                            $substitute_teacher_term_total = new TermTotal();
                            $substitute_teacher_term_total->teacher_id = $substitute->substitute_teacher_id;
                            $substitute_teacher_term_total->term_id = $substitute->term_id;
                            $substitute_teacher_term_total->total_substitute_hours = $substitute->duration;
                        }
                }
                else // 2.1.2 日期未修改
                    // 原代课老师数据减，新代课老师数据增
                {
                    // 原代课老师
                    $origin_substitute_teacher_term_total = TermTotal::where('teacher_id',$origin_substitute_teacher_id)->where('term_id',$substitute->term_id);
                    $origin_substitute_teacher_term_total = TermTotal::find($origin_substitute_teacher_term_total->value('id'));
                    // 先把之前的时长减去
                    $origin_substitute_teacher_term_total->total_substitute_hours -= $substitute->duration;

                    // 现代课老师
                    if ($substitute->substitute_teacher_id!=null) // 有现代课老师就加代课时长，没有就不操作
                    {
                        $substitute_teacher_term_total = TermTotal::where('teacher_id',$substitute->substitute_teacher_id)->where('term_id',$substitute->term_id);
                        if (count($substitute_teacher_term_total->get())!=0) // 这条记录存在
                        {
                            $substitute_teacher_term_total = TermTotal::find($substitute_teacher_term_total->value('id'));
                            $substitute_teacher_term_total->total_substitute_hours += $substitute->duration;
                        }
                        else // 新建一条记录
                        {
                            $substitute_teacher_term_total = new TermTotal();
                            $substitute_teacher_term_total->teacher_id = $substitute->substitute_teacher_id;
                            $substitute_teacher_term_total->term_id = $substitute->term_id;
                            $substitute_teacher_term_total->total_substitute_hours = $substitute->duration;
                        }
                    }

                }
            }
            else // 2.2 代课老师未修改
            {
                if ($substitute->lesson_date != $origin_lesson_date) // 2.2.1 日期修改
                    // 代课，缺课老师数据相应增减
                {
                    $teacher_term_total = TermTotal::where('teacher_id',$substitute->teacher_id)->where('term_id',$substitute->term_id);
                    $teacher_term_total = TermTotal::find($teacher_term_total->value('id'));
                    $teacher_term_total->total_missing_hours -= $substitute->duration; // 先把之前的时长减去
                    $substitute_teacher_term_total = TermTotal::where('teacher_id',$substitute->substitute_teacher_id)->where('term_id',$substitute->term_id);
                    $substitute_teacher_term_total = TermTotal::find($substitute_teacher_term_total->value('id'));
                    $substitute_teacher_term_total->total_substitute_hours -= $substitute->duration;

                    // 获取这个日期这门课的时长
                    $lesson = Lesson::find($substitute->lesson_id);
                    $lesson_updates = $lesson->lessonUpdates;
                    foreach ($lesson_updates as $lu) {
                        // 上课日期在这个区间范围里，并且这门课仍然是这个老师上的情况下
                        if (strtotime($substitute->lesson_date) >= strtotime($lu->start_date) && strtotime($substitute->lesson_date) < strtotime($lu->end_date))
                        {
                            if ($lu->teacher_id == $substitute->teacher_id)
                            {
                                $substitute->duration = $lu->duration;
                                $teacher_term_total->total_missing_hours += $lu->duration; // 为缺课老师加上新的时长
                                $current_duration = $lu->duration;
                            }
                            else
                            {
                                session()->flash('danger','该日期原上课老师已变更！');
                                return redirect()->back()->withInput();
                            }
                        }
                    }
                    // 代课老师加上新时长
                    $substitute_teacher_term_total->total_substitute_hours += $current_duration;
                }
            }
        }

        $term_id = $substitute->term_id;
        if ($substitute->save())
        {
            if ($substitute_teacher_term_total != null)
            {
                $substitute_teacher_term_total->save();
            }
            if ($teacher_term_total != null)
            {
                $teacher_term_total->save();
            }
            if ($origin_substitute_teacher_term_total != null)
            {
                $origin_substitute_teacher_term_total->save();
            }

            session()->flash('success','代课缺课记录更新成功！');
            return redirect()->route('substitutes.index',compact('term_id'));
        }
    }
}
