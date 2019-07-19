<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LessonAttendance;
use App\Term;
use App\MonthDuration;
use App\Teacher;
use App\Substitute;

class LessonAttendancesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 查询页面，目前只要这个月有排课记录，就可以查询，无论那个月是否到了
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
        $start_year = date('Y',strtotime($term->start_date));
        $end_year = date('Y',strtotime($term->end_date));
        $term_first_month = $start_year.'-'.date('m',strtotime($term->start_date));
        $term_last_month = $end_year.'-'.date('m',strtotime($term->end_date));
        // 学期的开始结束年份
        if ($request->get('start_month') != null)
        {
            if ($request->get('end_month') == null) // 说明只查询单月
            {
                // 查询的开始年月
                $search_start_year = date('Y',strtotime($term->start_date));
                $search_start_month = $request->get('start_month');
                // 查询的 年-月
                $s_m_y = $search_start_year.'-'.$search_start_month;
                if (strtotime($s_m_y)<strtotime($term_first_month)) // 如果不在范围内，说明年数少一年
                {
                    $search_start_year += 1;
                    // 新的查询 年-月
                    $s_m_y = $search_start_year.'-'.$search_start_month;
                }

                $month_f_l = LessonAttendance::decideMonthFirstLast($term->start_date, $term->end_date, $s_m_y);
                $month_first_day = $month_f_l[0];
                $month_last_day = $month_f_l[1];

                // 查询学期中这个月所有老师实际排课
                $this_month_durations = MonthDuration::where('term_id',$term_id)->where('month',$search_start_month)->get();
                if (count($this_month_durations) == 0) // 学期外
                {
                    session()->flash('warning','未查询到记录！');
                    return view('lesson_attendances/index',compact('terms','term_id'));
                }
                else // 学期中
                {
                    $teacher_ids = [];
                    $actual_durations = []; // 实际排课时长
                    $teachers = []; // 老师
                    $actual_goes = []; // 实际上课时长

                    foreach ($this_month_durations as $d) {
                        $teacher_ids[] = $d->teacher_id;
                        $actual_durations[] = $d->actual_duration;
                        $actual_goes[] = LessonAttendance::monthActualGo($month_first_day, $month_last_day, $term_id, $d->actual_duration, $d->teacher_id); // 实际上课时长
                    }
                    // 存取老师数据
                    foreach ($teacher_ids as $id) {
                        $teachers[] = Teacher::find($id);
                    }

                    $month = $search_start_month;
                    return view('lesson_attendances/teacher_results',compact('teachers','actual_durations','actual_goes','month','term'));
                }

            }
            else // 多个月一起查询
            {
                // 判断这两个月是否都包含在学期中
                // 查询的开始年月
                $search_start_year = $start_year;
                $search_start_month = $request->get('start_month');
                // 查询的 年-月
                $s_m_y = $search_start_year.'-'.$search_start_month;
                if (strtotime($s_m_y)<strtotime($term_first_month)) // 如果不在范围内，说明年数少一年
                {
                    $search_start_year += 1;
                    // 新的查询 年-月
                    $s_m_y = $search_start_year.'-'.$search_start_month;
                }

                // 查询的结束年月
                $search_end_year = $end_year;
                $search_end_month = $request->get('end_month');
                // 查询的 年-月
                $e_m_y = $search_end_year.'-'.$search_end_month;

                if (strtotime($e_m_y)>strtotime($term_last_month)) // 如果不在范围内，说明年数多一年
                {
                    $search_end_year -= 1;
                    // 新的查询 年-月
                    $e_m_y = $search_end_year.'-'.$search_end_month;
                }

                if (strtotime($s_m_y) == strtotime($e_m_y))
                {
                    session()->flash('warning','查询起止月重复！');
                    return view('lesson_attendances/index',compact('terms','term_id'));
                }
                elseif(strtotime($s_m_y) > strtotime($e_m_y)) // 开始晚于结束
                {
                    session()->flash('warning','查询时间段错误！');
                    return view('lesson_attendances/index',compact('terms','term_id'));
                }
                else // 正常区间
                {
                    // 按每个老师查询
                    $teachers = Teacher::where('join_date','<=',$term->start_date)->where('leave_date','>=',$term->start_date)->get();

                    $all_teacher_durations = []; // 储存所有老师每个月的课时
                    $all_teacher_total_durations = []; // 储存所有老师这个学期合计的课时
                    foreach ($teachers as $t)
                    {
                        // 从查询开始月遍历到结束月,如果到12月了年份+1
                        $search_year = $search_start_year;
                        $search_month = $search_start_month;
                        $search_m_y = $search_year.'-'.$search_month;
                        $teacher_durations[] = array();
                        // $teacher_durations[][0]: 应排课
                        // $teacher_durations[][1]: 实际排课
                        // $teacher_durations[][2]: 实际上课
                        // $teacher_durations[][3]: 缺少课时
                        $actual_goes = []; //'3'=>'71.5'


                        // 只要遍历的 年-月 还没有到查询终止月，就一直录数据
                        while (strtotime($search_m_y)<=strtotime($e_m_y))
                        {
                            $month_f_l = LessonAttendance::decideMonthFirstLast($term->start_date, $term->end_date, $search_m_y);
                            $month_first_day = $month_f_l[0];
                            $month_last_day = $month_f_l[1];

                            // 查询学期中这个月这个老师的实际排课
                            $this_month_duration = MonthDuration::where('term_id',$term_id)->where('teacher_id',$t->id)->where('month',$search_month);
                            if (count($this_month_duration->get()) == 0) // 这个月没有实际排课
                            {
                                $teacher_durations[$search_month][1] = 0;
                            }
                            else //
                            {
                                $teacher_durations[$search_month][1] = $this_month_duration->value('actual_duration');
                            }
                            // 进入下一个月
                            if ($search_month == 12)
                            {
                                $search_year +=1;
                                $search_month = 1;
                            }
                            else
                            {
                                $search_month += 1;
                            }
                            $search_m_y = $search_year.'-'.$search_month;

                        }
                        $actual_duration = 0;
                        foreach ($teacher_durations as $d) {
                            if ($d != null)
                            {
                                $actual_duration += $d[1];
                            }
                        }

                        $all_teacher_total_durations[][1] = $actual_duration; // 老师的实际时长学期合计
                        $all_teacher_durations[] = $teacher_durations;
                    }

                    // dump($all_teacher_total_durations);
                    // dump($all_teacher_durations);
                    // exit();

                    return view('lesson_attendances/teacher_multiple_results',compact('teachers','all_teacher_durations','all_teacher_total_durations','term'));
                }
            }
        }
        else
        {
            return view('lesson_attendances/index',compact('terms','term_id'));
        }
    }
}
