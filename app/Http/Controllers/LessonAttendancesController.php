<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LessonAttendance;
use App\Term;
use App\MonthDuration;
use App\Teacher;
use App\Substitute;
use App\ExtraWork;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

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
        $start_year = date('Y',strtotime($term->start_date));
        $end_year = date('Y',strtotime($term->end_date));
        $term_first_month = $start_year.'-'.date('m',strtotime($term->start_date));
        $term_last_month = $end_year.'-'.date('m',strtotime($term->end_date));
        /////////////// 此处之后要改成真实的今天日期！
        $this_today = '2019-09-15'; // 测试用
        // $this_today = date('Y-m-d');
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

                // 只能查询这个月以前的上课考勤数据。
                if (strtotime($s_m_y)>=strtotime(date('Y-m',strtotime($this_today))))
                {
                    session()->flash('warning','只能查询本月前的上课考勤数据！');
                    return view('lesson_attendances/index',compact('terms','term_id'));
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
                    $should_durations = []; // 应该上课时长
                    $teachers = [];

                    foreach ($this_month_durations as $d) {
                        $teacher_ids[] = $d->teacher_id;
                        $actual_durations[] = $d->actual_duration;
                        $actual_goes[] = LessonAttendance::monthActualGo($month_first_day, $month_last_day, $term_id, $d->actual_duration, $d->teacher_id); // 实际上课时长

                        // 针对开学首周和期末最后一周的应该时常计算
                        if ($month_first_day == $term->start_date) // 首月补满首周
                        {
                            while (date('w',strtotime($month_first_day)) != 1)
                            {
                                $month_first_day = date('Y-m-d', strtotime("$month_first_day -1 day"));
                            }
                        }
                        elseif ($month_last_day == $term->end_date) // 末月补满末周
                        {
                            while (date('w',strtotime($month_last_day)) != 0)
                            {
                                $month_last_day = date('Y-m-d', strtotime("$month_last_day +1 day"));
                            }
                            // dump($month_last_day);
                            // exit();
                        }
                        $teachers[] = Teacher::find($d->teacher_id);
                        $should_durations[] = Teacher::calShouldMonthDuration(Teacher::find($d->teacher_id), $month_first_day,$month_last_day); // 应该排课

                    }

                    $month = $search_start_month;
                    return view('lesson_attendances/teacher_results',compact('teachers','actual_durations','should_durations','actual_goes','month','term','term_id'));
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

                // 只能查询这个月以前的上课考勤数据。
                if (strtotime($s_m_y)>=strtotime(date('Y-m',strtotime($this_today))) || strtotime($e_m_y)>=strtotime(date('Y-m',strtotime($this_today))))
                {
                    session()->flash('warning','只能查询本月前的上课考勤数据！');
                    return view('lesson_attendances/index',compact('terms','term_id'));
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
                    foreach ($teachers as $index=>$t)
                    {
                        // 从查询开始月遍历到结束月,如果到12月了年份+1
                        $search_year = $search_start_year;
                        $search_month = $search_start_month;
                        $search_m_y = $search_year.'-'.$search_month;
                        $teacher_durations[] = array();
                        // $teacher_durations[][0]: 应排课, $teacher_durations[][1]: 实际排课, $teacher_durations[][2]: 实际上课, $teacher_durations[][3]: 缺少课时

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
                            else // 查到排课的情况
                            {
                                // 实际排课
                                $teacher_durations[$search_month][1] = $this_month_duration->value('actual_duration');
                            }
                            // 应排课
                            $teacher_durations[$search_month][0] = Teacher::calShouldMonthDuration($t, $month_first_day,$month_last_day);
                            // 实际上课
                            $teacher_durations[$search_month][2] = LessonAttendance::monthActualGo($month_first_day, $month_last_day, $term_id, $teacher_durations[$search_month][1], $t->id);
                            // 缺少课时
                            $teacher_durations[$search_month][3] = $teacher_durations[$search_month][0]-$teacher_durations[$search_month][2];
                            if ($teacher_durations[$search_month][3]<0) // 如果实际上课比应上课多，缺少课时归零
                            {
                                $teacher_durations[$search_month][3] = 0;
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
                        $actual_duration = 0; // 实际排课合计
                        $actual_goes = 0; // 实际上课合计
                        $should_duration = 0; // 应排课合计
                        $lack_duration = 0; // 缺课时合计
                        foreach ($teacher_durations as $d) {
                            if ($d != null)
                            {
                                $should_duration += $d[0];
                                $actual_duration += $d[1];
                                $actual_goes += $d[2];
                                $lack_duration += $d[3];
                            }
                        }
                        $all_teacher_durations[] = $teacher_durations; // 所有老师所有查询月的数据合集
                        $all_teacher_total_durations[$index][0] = $should_duration; // 所有老师应排课时长合计
                        $all_teacher_total_durations[$index][1] = $actual_duration; // 所有老师实际排课时长合计
                        $all_teacher_total_durations[$index][2] = $actual_goes; // 老师的实际上课时长合计
                        $all_teacher_total_durations[$index][3] = $lack_duration; // 老师的缺少课时合计

                        $term_start_date = date('Y-m-d H:i:s',strtotime($term->start_date));
                        $term_end_date = date('Y-m-d H:i:s',strtotime($term->end_date));

                        // 加班目前取学期中的所有加班记录(除了调休加班外)
                        $extra_works = ExtraWork::where('staff_id',$t->staff->id)->where('extra_work_type','<>','调休')->where('extra_work_end_time','<=',$term_end_date)->where('extra_work_end_time','>=',$term_start_date)->get();
                        // 计算总计加班工时（转换后）
                        $total = 0;
                        foreach ($extra_works as $ew) {
                            if ($ew->extra_work_type == '测试')
                            {
                                $total += ($ew->duration)*1.2;
                            }
                            else
                            {
                                $total += ($ew->duration)*1.0;
                            }

                        }
                        $all_teacher_extra_works[$index] = $extra_works;// 所有老师的加班记录
                        $all_teacher_extra_work_durations[$index] = $total; //所有老师加班记录总时长
                    }

                    // dump($all_teacher_total_durations);
                    // dump($all_teacher_durations);
                    // dump($all_teacher_extra_works);
                    // exit();

                    return view('lesson_attendances/teacher_multiple_results',compact('teachers','all_teacher_durations','all_teacher_total_durations','all_teacher_extra_works','all_teacher_extra_work_durations','term','search_start_month','search_end_month','term_id'));
                }
            }
        }
        else
        {
            return view('lesson_attendances/index',compact('terms','term_id'));
        }
    }

    public function exportOne(Request $request)
    {
        $term_id = $request->input('term_id');
        $start_month = $request->input('start_month');
        $option = $request->input('option');
        $term = Term::find($term_id);
        // $end_month = $request->input('end_month');
        $spreadsheet = new Spreadsheet();
        LessonAttendance::exportTotalOne($spreadsheet, $start_month, $term, $option);

    }

    public function exportMultiple(Request $request)
    {
        $term_id = $request->input('term_id');
        $start_month = $request->input('start_month');
        $end_month = $request->input('end_month');
        $option = $request->input('option');
        $term = Term::find($term_id);
        $spreadsheet = new Spreadsheet();
        LessonAttendance::exportTotalMultiple($spreadsheet, $start_month, $end_month, $term, $option);

    }
}
