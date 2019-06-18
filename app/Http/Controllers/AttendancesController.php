<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\Staff;
use App\Holiday;
use App\ExtraWork;
use App\Absence;
use App\TotalAttendance;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Carbon;

function returnYear($year)
{
    return $year;
}

class AttendancesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->get('month') != null && $request->get('year') != null)
        {
            $year = $request->get('year');
            $month = $request->get('month');
            if ($request->get('staff_id') != null)
            {
                $staff_id = $request->get('staff_id');
                $total_attendances = TotalAttendance::where('staff_id',$staff_id)->where('year',$year)->where('month',$month)->orderBy('staff_id','asc')->paginate(30);
            }
            else
            {
                $total_attendances = TotalAttendance::where('year',$year)->where('month',$month)->orderBy('staff_id','asc')->paginate(30);
            }
            if (count($total_attendances) == 0)
            {
                session()->flash('warning','该记录不存在。');
                return redirect()->back()->withInput();            }
            else
            {
                return view('attendances/results',compact('total_attendances','year','month','staff_id'));
            }
        }
        else
        {
            $staffs = Staff::all();
            return view('attendances/index',compact('staffs'));
        }


    }

    public function show($id)
    {
        $total_attendance = TotalAttendance::find($id); // 这个show的id是属于total attendance的，不是staff!!!
        $staff = $total_attendance->staff;
        $attendances = $total_attendance->attendances;
        return view('attendances.show',compact('staff','attendances'));
    }
    /**
     * 将上传的表格导入数据库
     *
     * @return str $filename
     */
    public function import(Request $request)
    {
         $this->validate($request, [
          'select_file'  => 'required|mimes:xls,xlsx'
         ]);

        if ($request->isMethod('POST')){
            $file = $request->file('select_file');
            // 判断文件是否上传成功
            if ($file->isValid()){
                // 原文件名
                // $originalName = $file->getClientOriginalName();
                // 扩展名
                $ext = $file->getClientOriginalExtension();
                // 临时绝对路径
                $realPath = $file->getRealPath();
                // MimeType 这是 HTTP 标准中该资源的媒体类型
                // $type = $file->getClientMimeType();
                // 判断是哪种表格格式
                if ($ext == 'xlsx') {
                    $reader = new Xlsx();
                } elseif ($ext == 'xls') {
                    $reader = new Xls();
                } else {
                    session()->flash('danger','文件格式错误！');
                    redirect()->back();
                }
                $reader->setReadDataOnly(TRUE); // 只读
                $spreadsheet = $reader->load($realPath);
                $num = $spreadsheet->getSheetCount(); // Sheet的总数
                //////目前读的只是第五个worksheet!
                for ($i = 5; $i<$num; $i++)
                {
                    $worksheet = $spreadsheet->getSheet($i); // 读取指定的sheet
                    $highest_row = $worksheet->getHighestRow(); // 总行数
                    $highest_column = $worksheet->getHighestColumn(); // 总列数
                    $highest_column_index = Coordinate::columnIndexFromString($highest_column);
                    $month_period = $worksheet->getCellByColumnAndRow(4,2)->getValue();
                    $this_month = explode('-', $month_period);
                    $year = $this_month[0];
                    $month = $this_month[1];
                    // 查询这个月的节假日调休，接下来使用这个集合进行遍历
                    $get_holidays = Holiday::where('date','<=',$year.'-'.$month.'-31')->where('date','>=',$year.'-'.$month.'-01')->get();
                    for ($c = 1; $c < $highest_column_index; $c += 15)
                    {
                        $englishname = $worksheet->getCellByColumnAndRow($c+9,3)->getValue();
                        $staff = Staff::where('status',true)->where('englishname',$englishname);
                        $staff_id = $staff->value('id');
                        // $staff_id = $worksheet->getCellByColumnAndRow($c+9,4)->getValue();
                        // dump($englishname);
                        // 如果这个人存在于在职员工数据库中，那么我才读取他的数据。这样可以避免读取没有必要的数据。
                        if (count($staff->get()) != 0)
                        {
                            $find = Attendance::where('staff_id',$staff_id)->where('year',$year)->where('month',$month); // 查询一下是否有该年该月的数据，防止导入重复的数据
                            if (count($find->get()) == 0)
                            { // 如果记录是新导入的，先把该员工当月每天的出勤记录导入，储存完成后，计算该员工当月的汇总记录。

                                // 导入该月每天数据
                                for ($r = 12; $r <= $highest_row; $r++ )
                                {
                                    $attendance = new Attendance();
                                    $staff = Staff::find($staff_id);
                                    $attendance->staff_id = $staff->id;
                                    // 录入年月日
                                    $attendance->year = $year;
                                    $attendance->month = $month;

                                    $date_and_day = explode(' ', $worksheet->getCellByColumnAndRow($c,$r)->getValue());
                                    $date = $date_and_day[0];
                                    $day = $date_and_day[1];
                                    $attendance->date = $date;
                                    $attendance->day = $day;
                                    $ymd = $year.'-'.$month.'-'.$date;
                                    // 判断这一天是上班还是休息，录入该日期的类型
                                    if (count($get_holidays)!=0)
                                    {
                                        $holidays = Holiday::where('date','<=',$year.'-'.$month.'-31')->where('date','>=',$year.'-'.$month.'-01');
                                        // 如果在holidays找到了这个日子，这个日子以holidays里的那个为准
                                        $find_holiday = $holidays->where('date', date('Y-m-d',strtotime($ymd)))->get();

                                        if (count($find_holiday)!=0)
                                        {
                                            foreach ($find_holiday as $h) {
                                                $attendance->workday_type = $h->holiday_type;
                                                $workday_name = $h->workday_name;
                                                $this_workday = $staff->staffworkdays->where('workday_name',$workday_name);
                                                // 如果节假日调休了，需要找到调上班那天应上下班时间
                                                if ($attendance->workday_type == '上班')
                                                {
                                                    foreach ($this_workday as $twd) {
                                                        $should_work_time = $twd->work_time;
                                                        $should_home_time = $twd->home_time;
                                                    }
                                                }
                                                if ($attendance->workday_type == '休息')
                                                {
                                                    $should_work_time = null;
                                                    $should_home_time = null;
                                                }
                                                // if ($r == 15)
                                                // {
                                                //     echo '第0处';
                                                //     dump($attendance->date);
                                                //     dump($should_work_time);
                                                //     dump($should_home_time);
                                                //     // dump($attendance->should_work_time);
                                                //     // dump($attendance->should_home_time);
                                                //     exit();
                                                // }
                                            }
                                        }
                                        // 否则以员工的为准
                                        else {
                                            // 否则寻找这一天是该员工休息日还是工作日
                                            $this_workday = $staff->staffworkdays->where('workday_name',$day);
                                            foreach ($this_workday as $twd) {
                                                $should_work_time = $twd->work_time;
                                                $should_home_time = $twd->home_time;
                                            }
                                            if (count($this_workday->where('work_time',!null)) != 0 && $attendance->workday_type == null) { // work_time非null，上班
                                                $attendance->workday_type = '上班';
                                            }
                                            elseif ($attendance->workday_type == null) {
                                                $attendance->workday_type = '休息';
                                            }
                                        }
                                        $attendance->should_work_time = $should_work_time;
                                        $attendance->should_home_time = $should_home_time;
                                    }
                                    // if ($r == 14)
                                    // {
                                    //     echo '第A处';
                                    //     dump($attendance->date);
                                    //     dump($should_work_time);
                                    //     dump($should_home_time);
                                    //     // dump($attendance->should_work_time);
                                    //     // dump($attendance->should_home_time);
                                    //     // exit();
                                    // }
                                    else
                                    {
                                        // 直接判断这一天是该员工休息日还是工作日
                                        $this_workday = $staff->staffworkdays->where('workday_name',$day);
                                        foreach ($this_workday as $twd) {
                                            $should_work_time = $twd->work_time;
                                            $should_home_time = $twd->home_time;
                                        }
                                        if (count($this_workday->where('work_time',!null)) != 0) { // work_time非null，上班
                                            $attendance->workday_type = '上班';
                                        }
                                        else {
                                            $attendance->workday_type = '休息';
                                        }
                                        $attendance->should_work_time = $should_work_time;
                                        $attendance->should_home_time = $should_home_time;
                                    }
                                    // if ($r == 15)
                                    // {
                                    //     echo '第B处';
                                    //     dump($attendance->date);
                                    //     dump($should_work_time);
                                    //     dump($should_home_time);
                                    //     // dump($attendance->should_work_time);
                                    //     // dump($attendance->should_home_time);
                                    //     exit();
                                    // }

                                    // $attendance->should_work_time = $should_work_time;
                                    // $attendance->should_home_time = $should_home_time;
                                    // 录入实际上下班时间
                                    // 默认工作日休息日读取的列不同
                                    if ($day == '日' || $day == '六') {
                                        $work_time = $worksheet->getCellByColumnAndRow($c+10,$r)->getValue();
                                        $home_time = $worksheet->getCellByColumnAndRow($c+12,$r)->getValue();
                                    }
                                    else {
                                        $work_time = $worksheet->getCellByColumnAndRow($c+1,$r)->getValue();
                                        if ($work_time == '旷工') {
                                            $work_time = null;
                                        }
                                        $home_time = $worksheet->getCellByColumnAndRow($c+3,$r)->getValue();
                                    }
                                    $attendance->actual_work_time = $work_time;
                                    $attendance->actual_home_time = $home_time;

                                    // 计算迟到早退分钟数 以及 实际上班时长 判断是否异常
                                    if ($attendance->should_work_time != null && $attendance->should_home_time != null) {
                                        $swt = strtotime($attendance->should_work_time);
                                        $sht = strtotime($attendance->should_home_time);
                                        $attendance->should_duration = $attendance->calDuration($swt,$sht);
                                    }
                                    if ($attendance->actual_work_time != null && $attendance->actual_home_time != null)
                                    {
                                        $awt = strtotime($attendance->actual_work_time);
                                        $aht = strtotime($attendance->actual_home_time);
                                        $attendance->actual_duration = $attendance->calDuration($awt,$aht);
                                    }

                                    if ($attendance->should_work_time != null && $attendance->should_home_time != null && $attendance->actual_work_time != null && $attendance->actual_home_time != null)
                                    {
                                        $attendance->late_work = ($awt-$swt)/60; // 转换成分钟
                                        if (($awt-$swt)>0){ // 迟到是实际上班晚于应该上班
                                            // 后续还需要考虑到是否请假！！！！！
                                            if ($attendance->late_work > 5 && $attendance->actual_duration<$attendance->should_duration) //迟到5分钟以上，并且没有补上工时算迟到
                                            {
                                                $attendance->is_late = true;
                                            }
                                            else {
                                                $attendance->is_late = false;
                                            }
                                        }
                                        else {
                                            $attendance->is_late = false;
                                            // dump($attendance->is_late);
                                        }
                                        // dump($sht);
                                        // dump($aht);
                                        // dump($sht-$aht);
                                        // exit();
                                        $attendance->early_home = ($sht-$aht)/60;
                                        if (($sht-$aht)>0){ // 早退是实际下班早于应该下班
                                            // 后续还需要考虑到是否请假！！！！！
                                            if ($attendance->early_home > 5 && $attendance->actual_duration<$attendance->should_duration) // 早退5分钟以上算早退
                                            {
                                                $attendance->is_early = true;
                                            }
                                            else {
                                                $attendance->is_early = false;
                                            }
                                        }
                                        else {
                                            $attendance->is_early = false;
                                        }

                                    }
                                    // 异常计算还需要获取当天加班、请假的时间，所以还是比较复杂的。 异常判断标准还需明确一下。
                                    $look_for_start_time = $ymd.' 00:00:00';
                                    $look_for_end_time = $ymd.' 24:00:00';
                                    // 查询当日被批准的加班记录

                                    // 目前这个查询方法只适用于查询结果只有一条的。如果多条结果不能如此直接赋值

                                    // 无论有没有批准都记录进去。
                                    $extra_work_id = ExtraWork::where('staff_id',$staff->id)->where('extra_work_start_time','>=',$look_for_start_time)->where('extra_work_end_time','<=',$look_for_end_time)->value('id');
                                    $attendance->extra_work_id = $extra_work_id;
                                    // if ($r == 14)
                                    // {
                                    //     echo '第五处';
                                    //     dump($attendance->date);
                                    //     dump($attendance->workday_type);
                                    //     dump($attendance->should_work_time);
                                    //     dump($attendance->should_home_time);
                                    //     // exit();
                                    // }
                                    $attendance->save();
                                    // if ($r == 14)
                                    // {
                                    //     echo '第六处';
                                    //     dump($attendance->date);
                                    //     dump($attendance->should_home_time);
                                    //     // exit();
                                    // }
                                }

                                // 将刚才储存好的该员工当月每天数据进行汇总计算，录入总表
                                $total_attendance = new TotalAttendance();
                                $total_attendance->staff_id = $staff->id;
                                $total_attendance->year = $year;
                                $total_attendance->month = $month;
                                $attendances = $staff->attendances->where('year',$year)->where('month',$month);
                                $total_should_duration = 0;
                                $total_actual_duration = 0;
                                $total_is_late = 0;
                                $total_is_early = 0;
                                $total_late_work = 0;
                                $total_early_home = 0;
                                $should_attend = 0;
                                $actual_attend = 0;
                                $total_extra_work_duration = 0;

                                foreach ($attendances as $at)
                                {
                                    if ($at->should_duration != null)
                                    {
                                        $total_should_duration += $at->should_duration;
                                        $should_attend += 1;
                                    }

                                    if ($at->actual_duration != null)
                                    {
                                        $total_actual_duration += $at->actual_duration;
                                        $actual_attend += 1;
                                    }

                                    $total_is_late += $at->is_late;
                                    $total_is_early += $at->is_early;
                                    if ($at->late_work>0)
                                    {
                                        $total_late_work += $at->late_work;
                                    }

                                    if ($at->early_home>0)
                                    {
                                        $total_early_home += $at->early_home;
                                    }

                                    if ($at->abnormal == true)
                                    {
                                        $abnormal = true;
                                    }

                                    if ($at->extra_work_id != null)
                                    {
                                        $total_extra_work_duration += $at->extraWork->duration;
                                    }
                                }
                                $total_attendance->total_should_duration = $total_should_duration ;
                                $total_attendance->total_actual_duration = $total_actual_duration;
                                $total_attendance->total_is_late = $total_is_late ;
                                $total_attendance->total_is_early = $total_is_early ;
                                $total_attendance->total_late_work = $total_late_work ;
                                $total_attendance->total_early_home = $total_early_home ;
                                $total_attendance->should_attend = $should_attend ;
                                $total_attendance->actual_attend = $actual_attend ;
                                $total_attendance->total_extra_work_duration = $total_extra_work_duration;

                                $total_attendance->save();

                                $total_attendance_id = $total_attendance->id;

                                // 当该员工当月考勤汇总计算好之后，应当为当月每一天的考勤加入汇总数据的关联
                                foreach ($attendances as $at)
                                {
                                    $at->total_attendance_id = $total_attendance_id;
                                    $at->save();
                                }

                            }
                            else {
                                session()->flash('danger','该员工该月记录已存在！');
                                return redirect()->back();
                            }

                        }
                    }
                }
            }
        }
        session()->flash('success','表格导入成功！');
        return redirect()->back();
    }

    // public function results(Request $request)
    // {
    //     if ($request->get('month') != null)
    //     {

    //         return view('attendances.results');
    //     }
    // }
}
