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
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
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
                $total_attendances = TotalAttendance::where('year',$year)->where('month',$month)->orderBy('staff_id','asc')->get();
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

    public function show($id)
    {
        $total_attendance = TotalAttendance::find($id); // 这个show的id是属于total attendance的，不是staff!!!
        $staff = $total_attendance->staff;
        // $attendances = $total_attendance->attendances;
        $attendances = Attendance::where('total_attendance_id',$total_attendance->id)->orderBy('date','asc')->get();
        return view('attendances.show',compact('staff','attendances'));
    }


    public function changeAbnormal($id)
    {
        $attendance = Attendance::find($id);
        if ($attendance->abnormal == true)
        {
            $attendance->abnormal = false;
        }
        else
        {
            $attendance->abnormal = true;
        }

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
            session()->flash('success','更改异常成功！');
            return redirect()->back();
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
            // dump($attendance->add_duration);

            // 计算这一条attendance是否异常
            // 只要四项有一项是空的，直接报异常 （因为实际上下班必须对应应该上下班）
            if ($attendance->should_work_time == null || $attendance->should_home_time == null || $attendance->actual_work_time == null || $attendance->actual_home_time == null)
            {
                $attendance->abnormal = true;
            }
            else
            {
                if ($attendance->extraWork == null)
                {
                    $extrawork_duration = 0;
                }
                else
                {
                    $extrawork_duration = $attendance->extraWork->duration;
                }
                $cal_duration = $attendance->actual_duration-$extrawork_duration+$attendance->absence_duration+$attendance->add_duration; // 实际工时-加班+请假+总增补 >= (应该工时-5分钟)
                // dump($attendance->actual_duration);
                // dump($extrawork_duration);
                // dump($attendance->absence_duration);
                // dump($attendance->add_duration);
                // dump($cal_duration);
                // exit();
                if ($cal_duration>=($attendance->should_duration-5/60))
                {
                    $attendance->abnormal = false;
                }
                else
                {
                    $attendance->abnormal = true;
                }
            }


            // 再处理两种情况
            // 第一种：应上下班有时间，而实上下班没打卡。如果请了假，看请假时长和应时长能不能对上
            if ($attendance->should_work_time != null && $attendance->should_home_time != null && $attendance->actual_work_time == null && $attendance->actual_home_time == null)
            {
                if ($attendance->absence_id != null)
                {
                    // dump($total_add);

                    // exit();
                    if ($attendance->absence_duration + $attendance->add_duration >= ($attendance->should_duration-5/60))
                    {
                        $attendance->abnormal = false;
                    }
                }
            }
            // 第二种：应上下班没有时间，而实上下班打卡了。如果有加班记录，看应加班时长是否与实际打卡时长对上。
            if ($attendance->should_work_time == null && $attendance->should_home_time == null && $attendance->actual_work_time != null && $attendance->actual_home_time != null)
            {
                if ($attendance->extra_work_id != null)
                {
                    if ($attendance->actual_duration + $attendance->add_duration >= ($attendance->extraWork->duration-5/60))
                    {
                        $attendance->abnormal = false;
                    }
                }
            }

            // 如果全空，说明是休息日，不报异常
            if ($attendance->should_work_time == null && $attendance->should_home_time == null && $attendance->actual_work_time == null && $attendance->actual_home_time == null)
            {
                $attendance->abnormal = false;
            }

            // 根据增补时间来更新是否迟到早退
            // 这个是最优先条件
            if ($attendance->abnormal == false)
            {
                $attendance->is_early = false;
                $attendance->is_late = false;
            }
            else
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
                    {

                        $late_work = ($start-$work)/60; // 转换成分钟
                        if ($late_work>0){ // 迟到是实际上班晚于应该上班
                            if ($late_work > 5) // 迟到5分钟以上，并且没有补上工时算迟到
                            {
                                $attendance->is_late = true;
                            }
                            else {
                                $attendance->is_late = false;
                            }
                        }
                        else {
                            $attendance->is_late = false;
                        }
                    }
                    else
                    {
                        $early_home = ($home-$end)/60;
                        if ($early_home>0){ // 早退是实际下班早于应该下班
                            // 后续还需要考虑到是否请假！！！！！
                            if ($early_home > 5) // 早退5分钟以上算早退
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
                }
            }
            // }

            if ($attendance->save())
            {
                // 这条记录保存之后，判断该月记录是否仍然异常
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

                // $total_should_duration = 0;
                // $total_actual_duration = 0;
                $total_is_late = 0;
                $total_is_early = 0;
                $total_late_work = 0;
                $total_early_home = 0;
                $should_attend = 0;
                $actual_attend = 0;
                // $total_extra_work_duration = 0;
                // $total_absence_duration = 0;
                $total_add_duration = 0;
                // $total_abnormal = false;
                foreach ($this_month_attendances as $at) {
                    // if ($at->should_duration != null)
                    // {
                    //     $total_should_duration += $at->should_duration;
                    //     $should_attend += 1;
                    // }

                    // if ($at->actual_duration != null)
                    // {
                    //     $total_actual_duration += $at->actual_duration;
                    //     $actual_attend += 1;
                    // }

                    // 录入总增补时间
                    if ($at->add_duration != null)
                    {
                        $total_add_duration += $at->add_duration;
                    }

                    $total_is_late += $at->is_late;
                    $total_is_early += $at->is_early;
                    if ($at->late_work>0 && $at->is_late == true)
                    {
                        $total_late_work += $at->late_work;
                    }

                    if ($at->early_home>0 && $at->is_early == true)
                    {
                        $total_early_home += $at->early_home;
                    }

                    // if ($at->extra_work_id != null)
                    // {
                    //     $total_extra_work_duration += $at->extraWork->duration;
                    // }

                    // if ($at->absence_id != null)
                    // {
                    //     $total_absence_duration += $at->absence_duration;
                    // }
                }

                $total_abnormal = $this_month_attendances->where('abnormal',true);
                if (count($total_abnormal) == 0)
                {
                    // 没有异常记录即不异常
                    $total_attendance->abnormal = false;
                }
                else
                {
                    $total_attendance->abnormal = true;
                }
                // $attendance->totalAttendance->total_should_duration = $total_should_duration;
                // $attendance->totalAttendance->total_actual_duration = $total_actual_duration;
                $attendance->totalAttendance->total_is_late = $total_is_late;
                $attendance->totalAttendance->total_is_early = $total_is_early;
                $attendance->totalAttendance->total_late_work = $total_late_work;
                $attendance->totalAttendance->total_early_home = $total_early_home;
                // $attendance->totalAttendance->should_attend = $should_attend;
                // $attendance->totalAttendance->actual_attend = $actual_attend;
                // $attendance->totalAttendance->total_extra_work_duration = $total_extra_work_duration;
                // $attendance->totalAttendance->total_absence_duration = $total_absence_duration;
                // $attendance->totalAttendance->total_basic_duration = $total_basic_duration - $total_extra_work_duration;
                // $attendance->totalAttendance->difference = $total_attendance->total_basic_duration - $total_should_duration;
                $attendance->totalAttendance->total_add_duration = $total_add_duration;
                $total_attendance->save();

                $attendance->totalAttendance->save();
                session()->flash('success','增补时间成功！');
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

        // 只有考勤异常时才能进行补打卡操作
        if ($attendance->abnormal)
        {
        // 只有在应上下班时间不为空的情况下才能进行补打卡
        if ($attendance->should_work_time != null && $attendance->should_home_time != null)
             // && $attendance->actual_work_time != null && $attendance->actual_home_time != null
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

            // 计算这一条attendance是否异常
            // 只要四项有一项是空的，直接报异常 （因为实际上下班必须对应应该上下班）
            if ($attendance->should_work_time == null || $attendance->should_home_time == null || $attendance->actual_work_time == null || $attendance->actual_home_time == null)
            {
                $attendance->abnormal = true;
            }
            else
            {
                if ($attendance->add_duration == null)
                {
                    $add_duration = 0;
                }
                else
                {
                    $add_duration = $attendance->add_duration;
                }

                if ($attendance->extraWork == null)
                {
                    $extrawork_duration = 0;
                }
                else
                {
                    $extrawork_duration = $attendance->extraWork->duration;
                }
                $cal_duration = $attendance->actual_duration-$extrawork_duration+$attendance->absence_duration+$add_duration; // 实际工时-加班+请假 >= (应该工时-5分钟)
                if ($cal_duration>=($attendance->should_duration-5/60))
                {
                    $attendance->abnormal = false;
                }
                else
                {
                    $attendance->abnormal = true;
                }
            }


            // 再处理两种情况
            // 第一种：应上下班有时间，而实上下班没打卡。如果请了假，看请假时长和应时长能不能对上
            if ($attendance->should_work_time != null && $attendance->should_home_time != null && $attendance->actual_work_time == null && $attendance->actual_home_time == null)
            {
                if ($attendance->absence_id != null)
                {
                    if ($attendance->absence_duration+$add_duration >= ($attendance->should_duration-5/60))
                    {
                        $attendance->abnormal = false;
                    }
                }
            }
            // 第二种：应上下班没有时间，而实上下班打卡了。如果有加班记录，看应加班时长是否与实际打卡时长对上。
            if ($attendance->should_work_time == null && $attendance->should_home_time == null && $attendance->actual_work_time != null && $attendance->actual_home_time != null)
            {
                if ($attendance->extra_work_id != null)
                {
                    if ($attendance->actual_duration+$add_duration >= ($attendance->extraWork->duration-5/60))
                    {
                        $attendance->abnormal = false;
                    }
                }
            }

            // 如果全空，说明是休息日，不报异常
            if ($attendance->should_work_time == null && $attendance->should_home_time == null && $attendance->actual_work_time == null && $attendance->actual_home_time == null)
            {
                $attendance->abnormal = false;
            }

            // 如果记录不异常，那么不计早退和迟到
            if ($attendance->abnormal == false)
            {
                $attendance->is_early = false;
                $attendance->is_late = false;
            }

            // 计算当日基本工时：用 实-加 和 应 来比:如果 (实-加)>应, 记应工时; 反之记 (实-加)
            // 每日基本工时:(实-加)>应？应:(实-加)
            if ($attendance->extra_work_id != null)
            {
                $attendance->basic_duration = ($attendance->actual_duration-$attendance->extraWork->duration)>$attendance->should_duration?$attendance->should_duration:($attendance->actual_duration-$attendance->extraWork->duration);
            }
            else
            {
                $attendance->basic_duration = $attendance->actual_duration>$attendance->should_duration?$attendance->should_duration:$attendance->actual_duration;
            }

            if ($attendance->save())
            {
                // 这条记录保存之后，判断该月记录是否仍然异常
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

                $total_should_duration = 0;
                $total_actual_duration = 0;
                $total_is_late = 0;
                $total_is_early = 0;
                $total_late_work = 0;
                $total_early_home = 0;
                $should_attend = 0;
                $actual_attend = 0;
                $total_extra_work_duration = 0;
                $total_absence_duration = 0;
                $total_basic_duration = 0;
                // $total_abnormal = false;
                foreach ($this_month_attendances as $at) {
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
                    if ($at->late_work>0 && $at->is_late == true)
                    {
                        $total_late_work += $at->late_work;
                    }

                    if ($at->early_home>0 && $at->is_early == true)
                    {
                        $total_early_home += $at->early_home;
                    }

                    if ($at->extra_work_id != null)
                    {
                        $total_extra_work_duration += $at->extraWork->duration;
                    }

                    $total_basic_duration += $at->basic_duration;

                    if ($at->absence_id != null)
                    {
                        $total_absence_duration += $at->absence_duration;
                    }
                }

                $total_abnormal = $this_month_attendances->where('abnormal',true);
                if (count($total_abnormal) == 0)
                {
                    // 没有异常记录即不异常
                    $total_attendance->abnormal = false;
                }
                else
                {
                    $total_attendance->abnormal = true;
                }
                $attendance->totalAttendance->total_should_duration = $total_should_duration;
                $attendance->totalAttendance->total_actual_duration = $total_actual_duration;
                $attendance->totalAttendance->total_is_late = $total_is_late;
                $attendance->totalAttendance->total_is_early = $total_is_early;
                $attendance->totalAttendance->total_late_work = $total_late_work;
                $attendance->totalAttendance->total_early_home = $total_early_home;
                $attendance->totalAttendance->should_attend = $should_attend;
                $attendance->totalAttendance->actual_attend = $actual_attend;
                $attendance->totalAttendance->total_extra_work_duration = $total_extra_work_duration;
                $attendance->totalAttendance->total_absence_duration = $total_absence_duration;
                $attendance->totalAttendance->total_basic_duration = $total_basic_duration;
                $attendance->totalAttendance->difference = $total_attendance->total_basic_duration - $total_should_duration;
                $total_attendance->save();

                $attendance->totalAttendance->save();
                session()->flash('success','补打卡成功！');
                return redirect()->route('attendances.show',$total_attendance->id);
            }
        }
        else
        {
            session()->flash('danger','该日期无法进行补打卡！');
            return redirect()->back()->withInput();
        }
        }
        else
        {
            session()->flash('danger','该日期不异常，无法进行补打卡！');
            return redirect()->back()->withInput();
        }

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
                // 判断读取的表格格式是否正确
                $testsheet = $spreadsheet->getSheet(0);
                if ($testsheet->getTitle() != '排班信息')
                {
                    session()->flash('danger','导入表格格式错误');
                    return redirect()->back();
                }
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
                    // 查询这个月的节假日调休，接下来使用这个集合进行遍历
                    $get_holidays = Holiday::where('date','<=',$year.'-'.$month.'-31')->where('date','>=',$year.'-'.$month.'-01')->get();
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
                                    // 在异常判断之后，如果不报异常，该日不报迟到和早退记录。
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
                                    $look_for_start_time = $ymd.' 00:00:00';
                                    $look_for_end_time = $ymd.' 24:00:00';
                                    // 目前这个查询方法只适用于查询结果只有一条的。如果多条结果不能如此直接赋值
                                    // 无论有没有批准都记录进去。
                                    $extra_work_id = ExtraWork::where('staff_id',$staff->id)->where('extra_work_start_time','>=',$look_for_start_time)->where('extra_work_end_time','<=',$look_for_end_time)->value('id');
                                    $attendance->extra_work_id = $extra_work_id;
                                    // 计算当日基本工时：用 实-加 和 应 来比:如果 (实-加)>应, 记应工时; 反之记 (实-加)
                                    // 每日基本工时:(实-加)>应？应:(实-加)
                                    if ($attendance->extra_work_id != null)
                                    {
                                        $attendance->basic_duration = ($attendance->actual_duration-$attendance->extraWork->duration)>$attendance->should_duration?$attendance->should_duration:($attendance->actual_duration-$attendance->extraWork->duration);
                                    }
                                    else
                                    {
                                        $attendance->basic_duration = $attendance->actual_duration>$attendance->should_duration?$attendance->should_duration:$attendance->actual_duration;
                                    }
                                    $attendance->save();
                                }

                                // 录入请假记录分割多天的请假记录到每一天，以便计算每天请假的小时数
                                // 取出这个月的该员工所有请假 （如果涉及跨月还查不到，需要修改）
                                $absences = Absence::where('staff_id',$staff->id)->where('absence_start_time','>=',$year.'-'.$month.'-01 0:00:00')->where('absence_end_time','<=',$year.'-'.$month.'-31 24:00:00')->get();
                                if (count($absences) != 0)
                                {
                                    foreach ($absences as $absence) {
                                        // 分别对每一段请假记录进行拆分
                                        $date_day = [];
                                        // 存起止日的工作时长
                                        $duration_array = [];
                                        // 以下都是赋值给一条请假的
                                        $absence_id = $absence->id;
                                        $absence_type = $absence->absence_type;
                                        $absence_start_time = $absence->absence_start_time;
                                        $absence_end_time = $absence->absence_end_time;
                                        // 第一天到最后一天，日期及星期返回至数组(key为星期,value为日期)
                                        $date_day = Attendance::separateAbsence($absence_start_time, $absence_end_time, $date_day);

                                        $last_day = end($date_day);
                                        $first_day = reset($date_day);

                                        if (count($date_day) == 1) // 只请了一天的假
                                        {
                                            $workday_name = $weekarray[date('w',strtotime($date_day[0]))];
                                            $this_workday = $staff->staffworkdays->where('workday_name',$workday_name);
                                            foreach ($this_workday as $twd) {
                                                $first_day_home_time = $twd->work_time;
                                                $last_day_work_time = $twd->home_time;
                                            }
                                            $duration_array = $absence->separateDuration($first_day_home_time, $last_day_work_time, $absence_start_time, $absence_end_time, $duration_array);
                                            foreach ($date_day as $date) {
                                                // 找到这个日期的考勤
                                                // 考勤表中年月日时分开的
                                                $y_m_d = explode('-', $date);
                                                $this_attendance = Attendance::where('staff_id',$staff->id)->where('year',$y_m_d[0])->where('month',$y_m_d[1])->where('date',$y_m_d[2])->get();
                                                // dump($this_attendance);
                                                // exit();
                                                foreach ($this_attendance as $at) {
                                                    $at->absence_id = $absence_id;
                                                    $at->absence_duration = $duration_array[0];
                                                    $at->absence_type = $absence_type;
                                                    $at->save();
                                                }
                                            }
                                        }
                                        elseif (count($date_day) >1) // 请假天数大于一天
                                        {
                                            // 先录入起止日的请假数据
                                            $first_absence_day_name = $weekarray[date('w',strtotime($first_day))];
                                            $first_absence_day = $staff->staffworkdays->where('workday_name',$first_absence_day_name);

                                            $last_absence_day_name = $weekarray[date('w',strtotime($last_day))];
                                            $last_absence_day = $staff->staffworkdays->where('workday_name',$last_absence_day_name);
                                            foreach ($first_absence_day as $fad) {
                                                $first_day_home_time = $fad->home_time;
                                            }
                                            foreach ($last_absence_day as $lad) {
                                                $last_day_work_time = $lad->work_time;
                                            }
                                            $duration_array = $absence->separateDuration($first_day_home_time, $last_day_work_time, $absence_start_time, $absence_end_time, $duration_array);
                                            // 找到起止日的考勤
                                            // 起
                                            $f_y_m_d = explode('-', $first_day);
                                            $first_absence_day_attendance = Attendance::where('staff_id',$staff->id)->where('year',$f_y_m_d[0])->where('month',$f_y_m_d[1])->where('date',$f_y_m_d[2])->get();

                                            foreach ($first_absence_day_attendance as $at) {
                                                $at->absence_id = $absence_id;
                                                $at->absence_duration = $duration_array[0];
                                                $at->absence_type = $absence_type;
                                                $at->save();
                                            }

                                            // 止
                                            $l_y_m_d = explode('-', $last_day);
                                            $last_absence_day_attendance = Attendance::where('staff_id',$staff->id)->where('year',$l_y_m_d[0])->where('month',$l_y_m_d[1])->where('date',$l_y_m_d[2])->get();

                                            foreach ($last_absence_day_attendance as $at) {
                                                $at->absence_id = $absence_id;
                                                $at->absence_duration = $duration_array[1];
                                                $at->absence_type = $absence_type;
                                                $at->save();
                                            }

                                            // 录入中间日期的请假时长
                                            $count = count($date_day)-2; // 减去了起止日期
                                            for ($j=1; $j<=$count; $j++)
                                            {
                                                $workday_name = $weekarray[date('w',strtotime($date_day[$j]))];
                                                // 寻找这一天（星期）的该员工工作时长
                                                $this_workday = $staff->staffworkdays->where('workday_name',$workday_name);
                                                foreach ($this_workday as $twd) {
                                                    $absence_duration = $twd->duration;
                                                }
                                                $y_m_d = explode('-', $date_day[$j]);
                                                $this_attendance = Attendance::where('staff_id',$staff->id)->where('year',$y_m_d[0])->where('month',$y_m_d[1])->where('date',$y_m_d[2])->get();
                                                // dump($this_attendance);
                                                foreach ($this_attendance as $at) {
                                                    $at->absence_id = $absence_id;
                                                    $at->absence_duration = $absence_duration;
                                                    $at->absence_type = $absence_type;
                                                    $at->save();
                                                }
                                                // exit();
                                            }
                                        }
                                    }
                                }

                                // 计算每一条attendance是否异常
                                $staff_attendances = $staff->attendances->where('year',$year)->where('month',$month);
                                foreach ($staff_attendances as $s_a) {
                                    // 只要四项有一项是空的，直接报异常 （因为实际上下班必须对应应该上下班）
                                    if ($s_a->should_work_time == null || $s_a->should_home_time == null || $s_a->actual_work_time == null || $s_a->actual_home_time == null)
                                    {
                                        $s_a->abnormal = true;
                                    }
                                    else
                                    {
                                        if ($s_a->extraWork == null)
                                        {
                                            $extrawork_duration = 0;
                                        }
                                        else
                                        {
                                            $extrawork_duration = $s_a->extraWork->duration;
                                        }
                                        $cal_duration = $s_a->actual_duration-$extrawork_duration+$s_a->absence_duration; // 实际工时-加班+请假 >= (应该工时-5分钟)
                                        if ($cal_duration>=($s_a->should_duration-5/60))
                                        {
                                            $s_a->abnormal = false;
                                        }
                                        else
                                        {
                                            $s_a->abnormal = true;
                                        }
                                    }

                                    // 再处理两种情况
                                    // 第一种：应上下班有时间，而实上下班没打卡。如果请了假，看请假时长和应时长能不能对上
                                    if ($s_a->should_work_time != null && $s_a->should_home_time != null && $s_a->actual_work_time == null && $s_a->actual_home_time == null)
                                    {
                                        if ($s_a->absence_id != null)
                                        {
                                            if ($s_a->absence_duration >= ($s_a->should_duration-5/60))
                                            {
                                                $s_a->abnormal = false;
                                            }
                                        }
                                    }
                                    // 第二种：应上下班没有时间，而实上下班打卡了。如果有加班记录，看应加班时长是否与实际打卡时长对上。
                                    if ($s_a->should_work_time == null && $s_a->should_home_time == null && $s_a->actual_work_time != null && $s_a->actual_home_time != null)
                                    {
                                        if ($s_a->extra_work_id != null)
                                        {
                                            if ($s_a->actual_duration >= ($s_a->extraWork->duration-5/60))
                                            {
                                                $s_a->abnormal = false;
                                            }
                                        }
                                    }

                                    // 如果全空，说明是休息日，不报异常
                                    if ($s_a->should_work_time == null && $s_a->should_home_time == null && $s_a->actual_work_time == null && $s_a->actual_home_time == null)
                                    {
                                        $s_a->abnormal = false;
                                    }

                                    // 如果记录不异常，那么不计早退和迟到
                                    if ($s_a->abnormal == false)
                                    {
                                        $s_a->is_early = false;
                                        $s_a->is_late = false;
                                    }
                                    $s_a->save();
                                }

                                // 将刚才储存好的该员工当月每天数据进行汇总计算，录入总表
                                $total_attendance = new TotalAttendance();
                                $total_attendance->staff_id = $staff->id;
                                $total_attendance->year = $year;
                                $total_attendance->month = $month;
                                $attendances = $staff->attendances->where('year',$year)->where('month',$month);

                                // 判断该员工是否当月入职，如果是，入职前的日期统一改为不异常
                                $join_company = $staff->join_company;
                                $month_first_day = date('Y-m-01',strtotime($year.'-'.$month));
                                $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
                                if ($join_company >= $month_first_day && $join_company <= $month_last_day)
                                {
                                    foreach ($attendances as $at) {
                                        $this_day = $year.'-'.$month.'-'.$at->date;
                                        if (strtotime($this_day)<strtotime($join_company)) // 如果考勤日早于入职日，那么之前不算考勤
                                        {
                                            $at->workday_type = null;
                                            $at->should_home_time = null;
                                            $at->should_work_time = null;
                                            $at->should_duration = null;
                                            $at->abnormal = false;
                                            $at->save();
                                        }
                                    }
                                }

                                // 判断该员工是否当月离职，如果是，入职前的日期统一改为不异常
                                $leave_company = $staff->leave_company;
                                if ($leave_company >= $month_first_day && $leave_company <= $month_last_day)
                                {
                                    foreach ($attendances as $at) {
                                        $this_day = $year.'-'.$month.'-'.$at->date;
                                        if (strtotime($this_day)>=strtotime($leave_company)) // 如果考勤日晚于离职日，那么之后不算考勤
                                        {
                                            $at->workday_type = null;
                                            $at->should_home_time = null;
                                            $at->should_work_time = null;
                                            $at->should_duration = null;
                                            $at->abnormal = false;
                                            $at->save();
                                        }
                                    }
                                }

                                $total_should_duration = 0;
                                $total_actual_duration = 0;
                                $total_is_late = 0;
                                $total_is_early = 0;
                                $total_late_work = 0;
                                $total_early_home = 0;
                                $should_attend = 0;
                                $actual_attend = 0;
                                $total_extra_work_duration = 0;
                                $total_absence_duration = 0;
                                $total_basic_duration = 0;
                                // $total_abnormal = false;

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
                                    $total_basic_duration += $at->basic_duration;

                                    $total_is_late += $at->is_late;
                                    $total_is_early += $at->is_early;
                                    if ($at->late_work>0 && $at->is_late == true) // 晚上班，且记作迟到才算在总迟到里
                                    {
                                        $total_late_work += $at->late_work;
                                    }

                                    if ($at->early_home>0 && $at->is_early == true) // 早下班，且记作早退才算在总迟到里
                                    {
                                        $total_early_home += $at->early_home;
                                    }

                                    // if ($at->abnormal == true)
                                    // {
                                    //     $total_abnormal = true;
                                    //     break;
                                    // }

                                    if ($at->extra_work_id != null)
                                    {
                                        $total_extra_work_duration += $at->extraWork->duration;
                                    }

                                    if ($at->absence_id != null)
                                    {
                                        $total_absence_duration += $at->absence_duration;
                                    }
                                }

                                $total_abnormal = $attendances->where('abnormal',true);
                                if (count($total_abnormal) == 0)
                                {
                                    // 没有异常记录即不异常
                                    $total_attendance->abnormal = false;
                                }
                                else
                                {
                                    $total_attendance->abnormal = true;
                                }
                                $total_attendance->total_should_duration = $total_should_duration;
                                $total_attendance->total_actual_duration = $total_actual_duration;
                                $total_attendance->total_is_late = $total_is_late;
                                $total_attendance->total_is_early = $total_is_early;
                                $total_attendance->total_late_work = $total_late_work;
                                $total_attendance->total_early_home = $total_early_home;
                                $total_attendance->should_attend = $should_attend;
                                $total_attendance->actual_attend = $actual_attend;
                                $total_attendance->total_extra_work_duration = $total_extra_work_duration;
                                $total_attendance->total_absence_duration = $total_absence_duration;
                                $total_attendance->total_basic_duration = $total_basic_duration;
                                $total_attendance->difference = $total_attendance->total_basic_duration - $total_should_duration;

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
                                dump($find->get());
                                exit();
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

}
