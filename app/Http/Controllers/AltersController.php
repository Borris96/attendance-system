<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Alter;
use App\Term;
use App\Lesson;
use App\MonthDuration;

class AltersController extends Controller
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
        $term = Term::find($term_id);
        $alters = Alter::where('term_id',$term_id)->orderBy('lesson_date')->get();
        return view('alters/index',compact('alters','terms','term_id'));
    }


    public function destroy($id) // 是新建的反向过程
    {
        $alter = Alter::find($id);
        $term = Term::find($alter->term_id);
        $month_duration = null;
        $alter_month_duration = null;

        // 换课原课时间在同一个月,实际排课不会变
        if (date('Y-m',strtotime($alter->lesson_date)) == date('Y-m',strtotime($alter->alter_date)))
        {
            if (date('w',strtotime($alter->lesson_date)) != date('w',strtotime($alter->alter_date)))
            // 换课原课星期不同
            {
                // 查找这个老师这个月的实际排课时长
                $month_duration_id = MonthDuration::where('term_id',$alter->term_id)->where('teacher_id',$alter->teacher_id)->where('month',date('m',strtotime($alter->lesson_date)))->value('id');
                $month_duration = MonthDuration::find($month_duration_id);
                // dump($month_duration);
                // exit();
                // 删除：原星期加，现星期减，月总时长不变
                if (stristr($term->term_name,'Summer'))
                {
                    // 原星期加
                    if (date('w',strtotime($alter->lesson_date)) == 1)
                    {
                        $month_duration->mon_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 5)
                    {
                        $month_duration->fri_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 3)
                    {
                        $month_duration->wed_duration += $alter->duration;
                    }

                    // 现星期减
                    if (date('w',strtotime($alter->alter_date)) == 1)
                    {
                        $month_duration->mon_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 5)
                    {
                        $month_duration->fri_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 3)
                    {
                        $month_duration->wed_duration -= $alter->duration;
                    }
                    else
                    {
                        $month_duration->other_duration -= $alter->duration;
                    }
                    $month_duration->save();
                }
                else
                {
                    // 原星期加
                    if (date('w',strtotime($alter->lesson_date)) == 0)
                    {
                        $month_duration->sun_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 5)
                    {
                        $month_duration->fri_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 6)
                    {
                        $month_duration->sat_duration += $alter->duration;
                    }
                    // dump($month_duration->sat_duration);
                    // exit();

                    if (date('w',strtotime($alter->alter_date)) == 0)
                    {
                        $month_duration->sun_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 5)
                    {
                        $month_duration->fri_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 6)
                    {
                        $month_duration->sat_duration -= $alter->duration;
                    }
                    else
                    {
                        $month_duration->other_duration -= $alter->duration;
                    }
                    $month_duration->save();
                    // dump($month_duration->sat_duration);
                    // exit();
                }
            }
            // 换课原课星期相同 $month_duration不用变
        }
        else // 换课原课时间不在同一个月
        {
            // 查找这个老师这个月的实际排课时长
            $month_duration_id = MonthDuration::where('term_id',$alter->term_id)->where('teacher_id',$alter->teacher_id)->where('month',date('m',strtotime($alter->lesson_date)))->value('id');
            $month_duration = MonthDuration::find($month_duration_id);
            // 查找这个老师换课月的实际排课时长
            $alter_month_duration_id = MonthDuration::where('term_id',$alter->term_id)->where('teacher_id',$alter->teacher_id)->where('month',date('m',strtotime($alter->alter_date)))->value('id');
            $alter_month_duration = MonthDuration::find($alter_month_duration_id);

            // 原月份，星期时长增，实际排课时长增
            // 换课月份，星期时长减，实际排课时长减
            if (stristr($term->term_name,'Summer'))
            {
                if (date('w',strtotime($alter->lesson_date)) == 1)
                {
                    $month_duration->mon_duration += $alter->duration;
                }
                elseif (date('w',strtotime($alter->lesson_date)) == 5)
                {
                    $month_duration->fri_duration += $alter->duration;
                }
                elseif (date('w',strtotime($alter->lesson_date)) == 3)
                {
                    $month_duration->wed_duration += $alter->duration;
                }

                if (date('w',strtotime($alter->alter_date)) == 1)
                {
                    $alter_month_duration->mon_duration -= $alter->duration;
                }
                elseif (date('w',strtotime($alter->alter_date)) == 5)
                {
                    $alter_month_duration->fri_duration -= $alter->duration;
                }
                elseif (date('w',strtotime($alter->alter_date)) == 3)
                {
                    $alter_month_duration->wed_duration -= $alter->duration;
                }
                else
                {
                    $alter_month_duration->other_duration -= $alter->duration;
                }

                $month_duration->actual_duration += $alter->duration;
                $alter_month_duration->actual_duration -= $alter->duration;
                $month_duration->save();
                $alter_month_duration->save();
            }
            else
            {
                if (date('w',strtotime($alter->lesson_date)) == 0)
                {
                    $month_duration->sun_duration += $alter->duration;
                }
                elseif (date('w',strtotime($alter->lesson_date)) == 5)
                {
                    $month_duration->fri_duration += $alter->duration;
                }
                elseif (date('w',strtotime($alter->lesson_date)) == 6)
                {
                    $month_duration->sat_duration += $alter->duration;
                }

                if (date('w',strtotime($alter->alter_date)) == 0)
                {
                    $alter_month_duration->sun_duration -= $alter->duration;
                }
                elseif (date('w',strtotime($alter->alter_date)) == 5)
                {
                    $alter_month_duration->fri_duration -= $alter->duration;
                }
                elseif (date('w',strtotime($alter->alter_date)) == 6)
                {
                    $alter_month_duration->sat_duration -= $alter->duration;
                }
                else
                {
                    $alter_month_duration->other_duration -= $alter->duration;
                }

                $month_duration->actual_duration += $alter->duration;
                $alter_month_duration->actual_duration -= $alter->duration;
                $month_duration->save();
                $alter_month_duration->save();
            }
        }

        $alter->delete();
        session()->flash('success','换课记录删除成功！');
        return redirect()->back()->withInput();
    }

    public function edit()
    {
        return view('alters/edit');
    }

    public function create(Request $request)
    {
        $current_term_id = $request->get('term_id');
        $term = Term::find($current_term_id);
        // 这个学期的课
        $lessons = Lesson::where('term_id',$current_term_id)->whereNotNull('teacher_id')->orderBy('lesson_name')->get();

        return view('alters/create',compact('lessons','current_term_id','term'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'lesson_id'=>'required',
            'lesson_date'=>'required',
            'alter_date'=>'required',
        ]);

        $lesson_day_array = ['Sun'=>0,'Fri'=>5,'Sat'=>6, 'Mon'=>1, 'Wed'=>3];
        $alter = new Alter();
        $lesson = Lesson::find($request->input('lesson_id'));
        $alter->teacher_id = $lesson->teacher->id;
        $alter->lesson_id = $request->input('lesson_id');
        $alter->lesson_date = $request->input('lesson_date');
        $alter->alter_date = $request->input('alter_date');
        $alter->term_id = $lesson->term_id;
        $alter->duration = $lesson->duration;

        // 该日这节课是否重复代课
        $find = Alter::where('lesson_id',$alter->lesson_id)->where('lesson_date',$alter->lesson_date)->get();
        if (count($find) != 0)
        {
            session()->flash('danger','代课记录重复！');
            return redirect()->back()->withInput();
        }
        // 该日是否在学期内
        $term = Term::find($alter->term_id);
        if (strtotime($alter->lesson_date)<strtotime($term->start_date) || strtotime($alter->lesson_date)>strtotime($term->end_date))
        {
            session()->flash('danger','原上课日期超出范围！');
            return redirect()->back()->withInput();
        }

        if (strtotime($alter->alter_date)<strtotime($term->start_date) || strtotime($alter->alter_date)>strtotime($term->end_date))
        {
            session()->flash('danger','换课日期超出范围！');
            return redirect()->back()->withInput();
        }

        // 该日是否有这节课
        $lesson_day = $lesson_day_array[$lesson->day];
        $this_day = date('w',strtotime($alter->lesson_date));

        if ($lesson_day != $this_day)
        {
            session()->flash('danger','该日期未安排课程！');
            return redirect()->back()->withInput();
        }

        $month_duration = null;
        $alter_month_duration = null;
        // 换课后，换课那个月实际排课增加，原来那个月实际排课减少；如星期不同，换课星期时长增加，原星期时长减少

        // 换课原课时间在同一个月
        if (date('Y-m',strtotime($alter->lesson_date)) == date('Y-m',strtotime($alter->alter_date)))
        {
            if (date('w',strtotime($alter->lesson_date)) != date('w',strtotime($alter->alter_date)))
            // 换课原课星期不同
            {
                // 查找这个老师这个月的实际排课时长
                $month_duration_id = MonthDuration::where('term_id',$alter->term_id)->where('teacher_id',$alter->teacher_id)->where('month',date('m',strtotime($alter->lesson_date)))->value('id');
                $month_duration = MonthDuration::find($month_duration_id);
                // 原星期减，现星期加，月总时长不变
                if (stristr($term->term_name,'Summer'))
                {
                    if (date('w',strtotime($alter->lesson_date)) == 1)
                    {
                        $month_duration->mon_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 5)
                    {
                        $month_duration->fri_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 3)
                    {
                        $month_duration->wed_duration -= $alter->duration;
                    }

                    if (date('w',strtotime($alter->alter_date)) == 1)
                    {
                        $month_duration->mon_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 5)
                    {
                        $month_duration->fri_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 3)
                    {
                        $month_duration->wed_duration += $alter->duration;
                    }
                    else
                    {
                        $month_duration->other_duration += $alter->duration;
                    }
                }
                else
                {
                    if (date('w',strtotime($alter->lesson_date)) == 0)
                    {
                        $month_duration->sun_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 5)
                    {
                        $month_duration->fri_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 6)
                    {
                        $month_duration->sat_duration -= $alter->duration;
                    }

                    if (date('w',strtotime($alter->alter_date)) == 0)
                    {
                        $month_duration->sun_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 5)
                    {
                        $month_duration->fri_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 6)
                    {
                        $month_duration->sat_duration += $alter->duration;
                    }
                    else
                    {
                        $month_duration->other_duration += $alter->duration;
                    }
                }
            }
            // 换课原课星期相同 $month_duration不用变
        }
        else // 换课原课时间不在同一个月
        {
                // 查找这个老师这个月的实际排课时长
                $month_duration_id = MonthDuration::where('term_id',$alter->term_id)->where('teacher_id',$alter->teacher_id)->where('month',date('m',strtotime($alter->lesson_date)))->value('id');
                $month_duration = MonthDuration::find($month_duration_id);
                // 查找这个老师换课月的实际排课时长
                $alter_month_duration_id = MonthDuration::where('term_id',$alter->term_id)->where('teacher_id',$alter->teacher_id)->where('month',date('m',strtotime($alter->alter_date)))->value('id');
                $alter_month_duration = MonthDuration::find($alter_month_duration_id);

                // 原月份，星期时长减，实际排课时长减
                // 换课月份，星期时长增，实际排课时长增
                if (stristr($term->term_name,'Summer'))
                {
                    if (date('w',strtotime($alter->lesson_date)) == 1)
                    {
                        $month_duration->mon_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 5)
                    {
                        $month_duration->fri_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 3)
                    {
                        $month_duration->wed_duration -= $alter->duration;
                    }

                    if (date('w',strtotime($alter->alter_date)) == 1)
                    {
                        $alter_month_duration->mon_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 5)
                    {
                        $alter_month_duration->fri_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 3)
                    {
                        $alter_month_duration->wed_duration += $alter->duration;
                    }
                    else
                    {
                        $alter_month_duration->other_duration += $alter->duration;
                    }

                    $month_duration->actual_duration -= $alter->duration;
                    $alter_month_duration->actual_duration += $alter->duration;
                }
                else
                {
                    if (date('w',strtotime($alter->lesson_date)) == 0)
                    {
                        $month_duration->sun_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 5)
                    {
                        $month_duration->fri_duration -= $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->lesson_date)) == 6)
                    {
                        $month_duration->sat_duration -= $alter->duration;
                    }

                    if (date('w',strtotime($alter->alter_date)) == 0)
                    {
                        $alter_month_duration->sun_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 5)
                    {
                        $alter_month_duration->fri_duration += $alter->duration;
                    }
                    elseif (date('w',strtotime($alter->alter_date)) == 6)
                    {
                        $alter_month_duration->sat_duration += $alter->duration;
                    }
                    else
                    {
                        $alter_month_duration->other_duration += $alter->duration;
                    }

                    $month_duration->actual_duration -= $alter->duration;
                    $alter_month_duration->actual_duration += $alter->duration;
                }
        }

        $term_id = $alter->term_id;
        if ($alter->save())
        {
            if ($month_duration != null)
            {
                $month_duration->save();
            }
            if ($alter_month_duration != null)
            {
                $alter_month_duration->save();
            }
            session()->flash('success','换课记录创建成功！');
            return redirect()->route('alters.index',compact('term_id'));
        }

    }

    public function update(Request $request, $id)
    {

    }
}
