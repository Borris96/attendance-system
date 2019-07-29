<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Attendance;
use App\Staff;
use App\Holiday;
use App\ExtraWork;
use App\Absence;
use App\TotalAttendance;
use App\AddTime;
use App\AbnormalNote;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Carbon;

class AttendancesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->tolerance = 5;
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
                $total_attendances = TotalAttendance::where('staff_id',$staff_id)->where('year',$year)->where('month',$month)->orderBy('staff_id','asc')->get();
            }
            else
            {
                $total_attendances = TotalAttendance::where('year',$year)->where('month',$month)->orderBy('department_id','asc')->get();
            }
            if (count($total_attendances) == 0)
            {
                session()->flash('warning','该记录不存在。');
                return redirect()->back()->withInput();            }
            else
            {
                if ($request->get('staff_id') == null)
                {
                    return view('attendances/results',compact('total_attendances','year','month'));
                }
                else
                {
                    return view('attendances/results',compact('total_attendances','year','month','staff_id'));
                }
            }
        }
        else
        {
            $staffs = Staff::all();
            return view('attendances/index',compact('staffs'));
        }


    }

    public function show(Request $request, $id)
    {
        $month = $request->get('month');
        $year = $request->get('year');
        $total_attendance = TotalAttendance::find($id); // 这个show的id是属于total attendance的，不是staff!!!
        $staff = $total_attendance->staff;
        // $attendances = $total_attendance->attendances;
        $attendances = Attendance::where('total_attendance_id',$total_attendance->id)->orderBy('date','asc')->get();
        return view('attendances.show',compact('staff','attendances','staff_id','month','year'));
    }


    public function createExtra($id)
    {
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        $staff = Staff::find($attendance->staff_id);
        $year = $attendance->year;
        $month = $attendance->month;
        $date = $attendance->date;
        $staffs = null;
        return view('extra_works/create',compact('staffs','staff','year','month','date','attendance','total_attendance'));
    }

    public function createAbsence($id)
    {
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        $staff = Staff::find($attendance->staff_id);
        $year = $attendance->year;
        $month = $attendance->month;
        $date = $attendance->date;
        $staffs = null;
        return view('absences/create',compact('staffs','staff','year','month','date','attendance','total_attendance'));
    }

    public function changeAbnormal($id)
    {
        $attendance = Attendance::find($id);

        // if ($attendance->abnormalNote == null)
        // {
        //     session()->flash('warning','请在添加备注后修改异常');
        //     return redirect()->back();
        // }
        // else
        // {
            if ($attendance->abnormal == true)
            {
                $attendance->abnormal = false;
            }
            else
            {
                $attendance->abnormal = true;
            }
        // }

        if ($attendance->save())
        {
            $this_month_attendances = $attendance->totalAttendance->attendances;
            // 查一下还有没有异常
            $this_month_abnormal = $this_month_attendances->where('abnormal',true);
            if (count($this_month_abnormal) == 0)
            {
                // 如果没有异常返回 false
                $attendance->totalAttendance->abnormal = false;
            }
            else
            {
                $attendance->totalAttendance->abnormal = true;
            }
            $attendance->totalAttendance->save();
            session()->flash('success','更改'.$attendance->month.'月'.$attendance->date.'日异常成功！');
            return redirect()->back();
        }
    }

    public function addNote($id)
    {
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        return view('attendances.add_note',compact('attendance','total_attendance'));
    }

    public function createAddNote($id, Request $request)
    {
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        $this->validate($request, [
        'note'=>'required|max:140',
        ]);
        $abnormal_note = new AbnormalNote();
        $abnormal_note->note = $request->get('note');
        $abnormal_note->attendance_id = $attendance->id;
        if ($abnormal_note->save())
        {
            session()->flash('success', '异常备注添加成功！');
            return redirect()->route('attendances.show',$total_attendance->id);
        }
    }

    public function addTime($id)
    {
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        // 最多支持添加两段增补记录，一首一尾，以便解决更新是否迟到早退的问题。
        $all_add_times = count($attendance->addTimes);

        if ($all_add_times >= 2)
        {
            session()->flash('warning', '最多支持两段增补记录！');
            return redirect()->back();
        }
        else
        {
            return view('attendances.add_time',compact('attendance','total_attendance'));
        }
    }

    // 增补时间：一般是因为某些原因，实际时长少了，需要增加时长来补足空缺。主要适用于因合理原因迟到早退的情况：如地铁故障，哺乳期等
    // 原来的实际时间+请假时间-加班时间+增补的时间如果大于等于应该的时间，那么就不异常了。（前提是存在应该的时间）
    // 增补工时的目的是为了消除迟到早退次数，如果迟到早退本来就没有数据，那么是无法增补工时的。
    public function createAddTime(Request $request, $id)
    {
        $this->validate($request, [
            'add_start_time'=>'required',
            'add_end_time'=>'required',
            'reason'=>'required',
        ]);

        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;

        $add_time = new AddTime();
        $add_start_time = $request->get('add_start_time');
        $add_end_time = $request->get('add_end_time');

        $y_m_d = $attendance->year.'-'.$attendance->month.'-'.$attendance->date;
        $str_add_start_time = strtotime($y_m_d.' '.$add_start_time);
        $str_add_end_time = strtotime($y_m_d.' '.$add_end_time);

        // 检测时间填写是否正确
        if ($add_start_time == null || $add_end_time == null)
        {
            session()->flash('warning','时间填写不完整！');
            return redirect()->back()->withInput();
        }

        if (strtotime($add_start_time)>strtotime($add_end_time))
        {
            session()->flash('warning','开始时间晚于结束时间！');
            return redirect()->back()->withInput();
        }

        if ($attendance->actual_work_time != null && $attendance->actual_home_time!=null)
        {
            $actual_work_time = strtotime($y_m_d.' '.$attendance->actual_work_time);

            $actual_home_time = strtotime($y_m_d.' '.$attendance->actual_home_time);
            // 不能和上班时间重合
            if ($add_time->isCrossing($str_add_start_time, $str_add_end_time, $actual_work_time, $actual_home_time))
            {
                session()->flash('warning','时间与上班时间重叠！');
                return redirect()->back()->withInput();
            }
        }

        if ($attendance->extraWork != null && $attendance->extraWork!=null)
        {
            $extra_work_start_time = strtotime($attendance->extraWork->extra_work_start_time);
            $extra_work_end_time = strtotime($attendance->extraWork->extra_work_end_time);
            // 不能和加班时间重合
            if ($add_time->isCrossing($str_add_start_time, $str_add_end_time, $extra_work_start_time, $extra_work_end_time))
            {
                session()->flash('warning','时间与加班时间重叠！');
                return redirect()->back()->withInput();
            }
        }

        if ($attendance->absence != null && $attendance->absence!=null)
        {
            $absence_start_time = strtotime($attendance->absence->absence_start_time);
            $absence_end_time = strtotime($attendance->absence->absence_end_time);
            // 不能和请假时间重合
            if ($add_time->isCrossing($str_add_start_time, $str_add_end_time, $absence_start_time, $absence_end_time))
            {
                session()->flash('warning','时间与请假时间重叠！');
                return redirect()->back()->withInput();
            }
        }
        $reason = $request->get('reason');

        $add_time->attendance_id = $attendance->id;
        $add_time->add_start_time = $add_start_time;
        $add_time->add_end_time = $add_end_time;

        // 要判断是否与存在的记录重合
        $add_times = $attendance->addTimes; // 取出所有增补记录
        foreach ($add_times as $at) {
            $old_start_time = $at->add_start_time;
            $old_end_time = $at->add_end_time;
            if($add_time->isCrossing($add_start_time, $add_end_time, $old_start_time, $old_end_time))
            {
                session()->flash('warning','时间与已有记录重叠！');
                return redirect()->back()->withInput();
            }
        }

        $add_time->duration = $add_time->calDuration($add_start_time, $add_end_time);
        $add_time->reason = $reason;

        if ($add_time->save())
        {
            // 添加完增补时间之后，需要对这一条考勤重新计算是否异常
            // 先把所有增补时间都加起来
            $total_add = 0;
            $attendance = Attendance::find($attendance->id);
            $add_times = $attendance->addTimes;
            if (count($add_times) == 0)
            {
                $total_add = $add_time->duration;
            }
            else
            {
                foreach($add_times as $at)
                {
                    $total_add += $at->duration;
                }
            }

            $attendance->add_duration = $total_add;

            // 计算这一条attendance是否异常
            Attendance::isAbnormal($attendance);
            // 理论上上条保存的 abnormal 后面会更新。
            if ($attendance->abnormal != false)
            {
                $work = strtotime($attendance->should_work_time);
                $home = strtotime($attendance->should_home_time);
                // 先把这两段时间取出来
                foreach ($add_times as $at)
                {
                    $start = strtotime($at->add_start_time);
                    $end = strtotime($at->add_end_time);

                    // 判断开始、结束时间距离上下班哪个时间近，由此判断是该修改迟到还是早退。
                    $judge_late = abs($start-$work);
                    $judge_early = abs($home-$end);

                    if ($judge_late <= $judge_early) // 开始时间离上班时间近，所以用这个时间判断是否早退
                    { // 由于调用的函数里有 should 和 actual, 此处以1和0代替，以满足条件

                        $late_work = ($start-$work)/60; // 转换成分钟
                        $attendance->is_late = Attendance::lateOrEarly($late_work, 1, 0, $attendance->is_late);
                    }
                    else
                    {
                        $early_home = ($home-$end)/60;
                        $attendance->is_early = Attendance::lateOrEarly($early_home, 1, 0, $attendance->is_late);
                    }
                }
            }
            // }

            if ($attendance->save())
            {
                // 这条记录保存之后，判断该月记录是否仍然异常
                $this_month_attendances = $attendance->totalAttendance->attendances;
                TotalAttendance::updateTotal($this_month_attendances, $attendance);
                return redirect()->route('attendances.show',$total_attendance->id);
            }
        }
    }

    public function clock($id)
    {
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        return view('attendances.clock',compact('attendance','total_attendance'));
    }

    public function updateClock(Request $request, $id)
    {
        $this->validate($request, [
            'actual_work_time'=>'required',
            'actual_home_time'=>'required',
        ]);
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        $attendance->actual_home_time = $request->get('actual_home_time');
        $attendance->actual_work_time = $request->get('actual_work_time');

        // 检测时间填写是否正确
        if ($attendance->actual_home_time == null || $attendance->actual_work_time == null)
        {
            session()->flash('warning','时间填写不完整！');
            return redirect()->back()->withInput();
        }

        if (strtotime($attendance->actual_work_time)>strtotime($attendance->actual_home_time))
        {
            session()->flash('warning','上班时间晚于下班时间！');
            return redirect()->back()->withInput();
        }

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

        if ($attendance->should_work_time != null && $attendance->should_home_time != null)
        {
            $attendance->late_work = ($awt-$swt)/60; // 转换成分钟
            $attendance->is_late = Attendance::lateOrEarly($attendance->late_work, $attendance->should_duration, $attendance->actual_duration, $is_late_early = false);
            $attendance->early_home = ($sht-$aht)/60;
            $attendance->is_early = Attendance::lateOrEarly($attendance->early_home, $attendance->should_duration, $attendance->actual_duration, $is_late_early = false);
        }
        // 计算这一条attendance是否异常
        Attendance::isAbnormal($attendance);
        Attendance::calBasic($attendance, $attendance->extra_work_id);

        if ($attendance->save())
        {
            // 这条记录保存之后，判断该月记录是否仍然异常
            $this_month_attendances = $attendance->totalAttendance->attendances;
            TotalAttendance::updateTotal($this_month_attendances, $attendance, $type='clock');
            return redirect()->route('attendances.show',$total_attendance->id);
        }
        // }
        // else
        // {
        //     session()->flash('danger','该日期不异常，无法进行补打卡！');
        //     return redirect()->back()->withInput();
        // }

    }

    /**
     * 将上传的表格导入数据库并进行汇总计算 -- 这是这个考勤系统的最终目标
     *
     *
     */
    public function import(Request $request)
    {
        $this->validate($request, [
            'select_file'  => 'required|mimes:xls,xlsx'
        ]);
        $weekarray=array("日","一","二","三","四","五","六");
        if ($request->isMethod('POST')){
            $file = $request->file('select_file');
            // 判断文件是否上传成功
            if ($file->isValid()){
                // 扩展名
                $ext = $file->getClientOriginalExtension();
                // 临时绝对路径
                $realPath = $file->getRealPath();

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
                // 判断读取的表格格式是否正确
                $testsheet = $spreadsheet->getSheet(0);
                $sheet_title = $testsheet->getTitle();
                if ($sheet_title != '排班信息' && !stristr($sheet_title, '考勤月报')) // 既不等于，又不等于
                {
                    session()->flash('danger','导入表格格式错误!');
                    return redirect()->back();
                }
                // 首先要查询该员工是否有这一天的考勤记录，如果有，看实际上下班是否有数据，只要有一个有，就不录入，如果都没有，且导的表中有，则录入
                if ($sheet_title == '排班信息')
                {
                    // 从第五张表开始是我们需要读的原始数据
                    for ($i = 4; $i<$num; $i++)
                    {
                        $worksheet = $spreadsheet->getSheet($i); // 读取指定的sheet
                        $highest_row = $worksheet->getHighestRow(); // 总行数
                        $highest_column = $worksheet->getHighestColumn(); // 总列数
                        $highest_column_index = Coordinate::columnIndexFromString($highest_column);
                        $title = $worksheet->getCellByColumnAndRow(1,1)->getValue();
                        if ($title != "考 勤 卡 表")
                        {
                            session()->flash('danger',"'".$worksheet->getTitle()."'工作表格式错误");
                            return redirect()->back();
                        }

                        $month_period = $worksheet->getCellByColumnAndRow(4,2)->getValue();
                        $this_month = explode('-', $month_period);
                        $year = $this_month[0];
                        $month = $this_month[1];
                        $month_first_day = date('Y-m-01',strtotime($year.'-'.$month));
                        $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
                        // 查询这个月的节假日调休，接下来使用这个集合进行遍历
                        $get_holidays = Holiday::where('date','<=',$month_last_day)->where('date','>=',$month_first_day)->get();
                        for ($c = 1; $c < $highest_column_index; $c += 15)
                        {
                            $englishname = $worksheet->getCellByColumnAndRow($c+9,3)->getValue();
                            // 查询离职日期为空或者晚于这个考勤月离职的员工。
                            // 为了查询方便，我将未离职员工的离职日期默认设为时间戳能达到的最后一年第一天，即：2038-01-01
                            $staff = Staff::where('leave_company','>=',$year.'-'.$month.'-01')->where('englishname',$englishname);
                            // $staff = Staff::where('status',true)->where('englishname',$englishname);
                            $staff_id = $staff->value('id');

                            // 如果这个人存在于在职员工数据库中，那么我才读取他的数据。这样可以避免读取没有必要的数据。
                            if (count($staff->get()) != 0)
                            {
                                $repeat = false; // 用于判断是否有重复数据的导入。如果没有，正常新建total_attendance; 否则，删除原来的total_attendance再新建
                                $staff = Staff::find($staff_id);
                                // 导入该月每天数据
                                for ($r = 12; $r <= $highest_row; $r++ )
                                {
                                    $date_and_day = explode(' ', $worksheet->getCellByColumnAndRow($c,$r)->getValue());
                                    $date = $date_and_day[0];
                                    $day = $date_and_day[1];

                                    $find_id = Attendance::where('staff_id',$staff_id)->where('year',$year)->where('month',$month)->where('date',$date)->value('id');

                                    if ($find_id == null) // 这一天考勤数据中不存在
                                    {
                                        $attendance = new Attendance();
                                        // 把当日基础数据录入
                                        Attendance::postAttendance($worksheet, $c, $r, $attendance, $staff, $get_holidays, $year, $month, $date, $day, $month_first_day, $month_last_day);
                                    }
                                    else // 考勤中已存在这一天
                                    {
                                        $exist_attendance = Attendance::find($find_id);
                                        if ($exist_attendance->actual_home_time == null && $exist_attendance->actual_work_time == null) // 如果实际上下班记录之前都是空的，再在这张表上读取数据
                                        {
                                            Attendance::postAttendance($worksheet, $c, $r, $exist_attendance, $staff, $get_holidays, $year, $month, $date, $day, $month_first_day, $month_last_day);
                                            $repeat = true;
                                        }
                                        // 否则，保留原始数据，不做任何操作
                                    }


                                }

                                // 录入请假记录分割多天的请假记录到每一天，以便计算每天请假的小时数
                                // Attendance::postAbsences($absences, $staff);
                                // 计算每一条attendance是否异常
                                $attendances = $staff->attendances->where('year',$year)->where('month',$month);
                                foreach ($attendances as $s_a)
                                {
                                    Attendance::isAbnormal($s_a);
                                }
                                // 处理当日离职或入职员工的考勤异常
                                $join_company = $staff->join_company;
                                $leave_company = $staff->leave_company;
                                Attendance::joinOrLeave($attendances, $join_company, $leave_company, $month_first_day, $month_last_day, $year, $month);

                                // 将刚才储存好的该员工当月每天数据进行汇总计算，录入总表，并与每天考勤建立关联
                                if ($repeat) // 如果之前有数据，是漏录数据的补录，那么先把之前的那条total_attendance删掉
                                {
                                    $total_attendance_id = TotalAttendance::where('staff_id',$staff_id)->where('year',$year)->where('month',$month)->value('id');
                                    $total_attendance = TotalAttendance::find($total_attendance_id);
                                    $total_attendance->delete();
                                }
                                $total_attendance = new TotalAttendance();
                                TotalAttendance::calTotal($total_attendance, $attendances, $staff, $year, $month);
                            }
                        }
                    }
                }
                elseif (stristr($sheet_title, '考勤月报'))
                {
                    $worksheet = $spreadsheet->getSheet(0); // 读取指定的sheet
                    $highest_row = $worksheet->getHighestRow(); // 总行数
                    $highest_column = $worksheet->getHighestColumn(); // 总列数
                    $highest_column_index = Coordinate::columnIndexFromString($highest_column);
                    $title = $worksheet->getCellByColumnAndRow(1,1)->getValue();
                    $year = substr($title, 0,4); // 获取年份
                    $month = mb_substr($title, 5,2); // 获取月份
                    $count = ($highest_row-6+1)/2; // 录入表的人数
                    $days = $worksheet->getCellByColumnAndRow($highest_column_index,4)->getValue(); // 录入表的天数
                    $month_first_day = date('Y-m-01',strtotime($year.'-'.$month));
                    $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
                    // 查询这个月的节假日调休，接下来使用这个集合进行遍历
                    $get_holidays = Holiday::where('date','<=',$month_last_day)->where('date','>=',$month_first_day)->get();
                    for ($i = 0; $i<$count; $i++)
                    {
                        //录入一个人
                        $name = $worksheet->getCellByColumnAndRow(3,6+2*$i)->getValue();
                        $name_index = Attendance::getNameIndex($name);
                        if ($name_index == 0) // 说明开头就是英文
                        {
                            $englishname = $name; // 只能通过英文名查找 $name
                            $staff = Staff::where('leave_company','>=',$year.'-'.$month.'-01')->where('englishname',$englishname);
                        }
                        else
                        {
                            $staffname = mb_substr($name, 0,$name_index);
                            $staff = Staff::where('leave_company','>=',$year.'-'.$month.'-01')->where('staffname',$staffname);
                        }

                        $staff_id = $staff->value('id');

                        // 如果这个人存在于在职员工数据库中，那么我才读取他的数据。这样可以避免读取没有必要的数据。
                        if (count($staff->get()) != 0)
                        {
                            $repeat = false; // 用于判断是否有重复数据的导入。如果没有，正常新建total_attendance; 否则，删除原来的total_attendance再新建
                            $staff = Staff::find($staff_id);
                            // 导入该月每天数据
                            for ($j = 0; $j < $days; $j++ )
                            {
                                $date_and_day = explode('/', $worksheet->getCellByColumnAndRow($j+10,4)->getValue());
                                $date = $date_and_day[0];
                                $day = $date_and_day[1];

                                $find_id = Attendance::where('staff_id',$staff_id)->where('year',$year)->where('month',$month)->where('date',$date)->value('id');
                                // dump($find_id);
                                // exit();

                                if ($find_id == null) // 这一天考勤数据中不存在
                                {
                                    $attendance = new Attendance();
                                    // 把当日基础数据录入
                                    Attendance::postAttendance($worksheet, $j+10, 6+2*$i, $attendance, $staff, $get_holidays, $year, $month, $date, $day, $month_first_day, $month_last_day, $option = '兼职助教');
                                }
                                else // 考勤中已存在这一天
                                {
                                    $exist_attendance = Attendance::find($find_id);
                                    if ($exist_attendance->actual_home_time == null && $exist_attendance->actual_work_time == null) // 如果实际上下班记录之前都是空的，再在这张表上读取数据
                                    {
                                        Attendance::postAttendance($worksheet, $j+10, 6+2*$i, $exist_attendance, $staff, $get_holidays, $year, $month, $date, $day, $month_first_day, $month_last_day, $option = '兼职助教');
                                        $repeat = true;
                                    }
                                    // 否则，保留原始数据，不做任何操作
                                }
                            }

                            // 录入请假记录分割多天的请假记录到每一天，以便计算每天请假的小时数
                            // Attendance::postAbsences($absences, $staff);
                            // 计算每一条attendance是否异常
                            $attendances = $staff->attendances->where('year',$year)->where('month',$month);
                            foreach ($attendances as $s_a)
                            {
                                Attendance::isAbnormal($s_a);
                            }
                            // 处理当日离职或入职员工的考勤异常
                            $join_company = $staff->join_company;
                            $leave_company = $staff->leave_company;
                            Attendance::joinOrLeave($attendances, $join_company, $leave_company, $month_first_day, $month_last_day, $year, $month);

                            // 将刚才储存好的该员工当月每天数据进行汇总计算，录入总表，并与每天考勤建立关联
                            if ($repeat) // 如果之前有数据，是漏录数据的补录，那么先把之前的那条total_attendance删掉
                            {
                                $total_attendance_id = TotalAttendance::where('staff_id',$staff_id)->where('year',$year)->where('month',$month)->value('id');
                                $total_attendance = TotalAttendance::find($total_attendance_id);
                                $total_attendance->delete();
                            }
                            $total_attendance = new TotalAttendance();
                            TotalAttendance::calTotal($total_attendance, $attendances, $staff, $year, $month);
                        }
                    }
                }
            }
        }
        session()->flash('success','表格导入成功！');
        return redirect()->back();
    }

    public function export(Request $request)
    {
        // $staff_id = $request->input('staff_id');
        $year = $request->input('year');
        $month = $request->input('month');
        $option = $request->input('option');
        $spreadsheet = new Spreadsheet();
        TotalAttendance::exportTotal($spreadsheet, $year, $month, $option);
    }

    public function basic($id)
    {
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        return view('attendances.change_basic',compact('attendance','total_attendance'));
    }

        public function changeBasic($id, Request $request)
    {
        // 更改基本工时，对是否异常没有影响。
        $this->validate($request, [
            'basic_duration'=>'required|numeric',
        ]);
        // 对这一条考勤进行更改
        $attendance = Attendance::find($id);
        $total_attendance = $attendance->totalAttendance;
        $old_duration = $attendance->basic_duration;
        $new_duration = $request->get('basic_duration');
        $attendance->basic_duration = $new_duration;
        if ($attendance->save())
        {
            // 总记录上总基本时间更新
            $total_attendance->total_basic_duration = $total_attendance->total_basic_duration + ($new_duration-$old_duration);
            $total_attendance->difference = $total_attendance->total_basic_duration - $total_attendance->total_should_duration;
            $total_attendance->save();
            session()->flash('success','基本工时修改成功！');
            return redirect()->route('attendances.show',$total_attendance->id);
        }
        else
        {
            session()->flash('danger','基本工时修改失败！');
            return redirect()->back()->withInput();
        }
    }
}
