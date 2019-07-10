<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Holiday;
use App\ExtraWork;
use App\Absence;

class Attendance extends Model
{
    protected $table = "attendances";

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function absence()
    {
        return $this->belongsTo(Absence::class);
    }

    public function extraWork()
    {
        return $this->belongsTo(ExtraWork::class);
    }

    public function totalAttendance()
    {
        return $this->belongsTo(TotalAttendance::class);
    }

    public function addTimes()
    {
        return $this->hasMany(AddTime::class);
    }

    public function abnormalNote()
    {
        return $this->hasOne(AbnormalNote::class);
    }

    /**
     * 计算上班时长（小时）
     * @param timestamp $start_time,
     * @param timestamp $end_time
     * @return double duration
     */
    public function calDuration($start_time, $end_time)
    {
        if ($end_time>$start_time) {
            if (date('H:i',$start_time)<'12:00')
            {
                if (date('H:i',$end_time)<='13:00')
                {
                    return ($end_time-$start_time)/(60*60);
                }
                else
                {
                    return ($end_time-$start_time)/(60*60)-1;
                }
            }
            else
            {
                return ($end_time-$start_time)/(60*60);
            }
        }
        else {
            return 0;
        }
    }

    /**
     * 拆分请假天
     * @param datetime $absence_start_time
     * @param datetime $absence_end_time
     * @param array $date_day
     * @return array $date_day
     */
    public static function separateAbsence($absence_start_time, $absence_end_time, $date_day)
    {
        $absence_start_date = date('Y-m-d',strtotime($absence_start_time));
        $absence_end_date = date('Y-m-d',strtotime($absence_end_time));
        $ts_absence_start_date = strtotime($absence_start_date);
        $ts_absence_end_date = strtotime($absence_end_date);
        // 总天数
        $count = ($ts_absence_end_date - $ts_absence_start_date)/(24*3600)+1;
        for ($i=0; $i<$count; $i++)
        {
            $date_day[] = date('Y-m-d', $ts_absence_start_date+3600*24*$i);
        }
        return $date_day;
    }

    /**
     * 判断是否迟到或者早退
     * @param int $late_early
     * @param int $should_duration
     * @param int $actual_duration
     * @return boolean $is_late_early
     *
     */
    public static function lateOrEarly($late_early, $should_duration, $actual_duration, $is_late_early = false)
    {
        // $is_late_early = false;
        if ($late_early>0){ // 迟到是实际上班晚于应该上班
            // 后续还需要考虑到是否请假！！！！！
            if ($late_early > 5 && $actual_duration<$should_duration) //迟到5分钟以上，并且没有补上工时算迟到
            {
                $is_late_early = true;
            }
            else {
                $is_late_early = false;
            }
        }
        else {
            $is_late_early = false;
        }
        return $is_late_early;
    }

    /**
     * 计算基本工时, 根据有无加班判断用哪种计算方法
     * @param object $attendance
     * @param int $extra_work_id
     * @return decimal $basic_duration
     */
    public static function calBasic($attendance, $extra_work_id)
    {
        // 计算当日基本工时：用 实-加 和 应 来比:如果 (实-加)>应, 记应工时; 反之记 (实-加)
        // 每日基本工时:(实-加)>应？应:(实-加)
        if ($extra_work_id != null)
        {
            $basic_duration = ($attendance->actual_duration-$attendance->extraWork->duration)>($attendance->should_duration)?$attendance->should_duration:($attendance->actual_duration-$attendance->extraWork->duration);
        }
        else
        {
            $basic_duration = $attendance->actual_duration>($attendance->should_duration)?$attendance->should_duration:$attendance->actual_duration;
        }

        return $basic_duration;
    }



    /**
     * 录入基础考勤数据(除请假)
     * @param object $worksheet
     * @param int $c column
     * @param int $r row
     * @param object $attendance
     * @param collection $staff
     * @param collection $get_holidays
     * @param int $year
     * @param int $month
     * @param int $date
     * @param string $day
     * @param date $month_first_day
     * @param date $month_last_day
     * @return void
     *
     */
    public static function postAttendance($worksheet, $c, $r, $attendance, $staff, $get_holidays, $year, $month, $date, $day, $month_first_day, $month_last_day)
    {
        $attendance->staff_id = $staff->id;
        // dump($attendance->staff_id);
        // exit();
        // 录入年月日
        $attendance->year = $year;
        $attendance->month = $month;
        $attendance->date = $date;
        $attendance->day = $day;
        $ymd = $year.'-'.$month.'-'.$date;
        // 判断这一天是上班还是休息，录入该日期的类型
        if (count($get_holidays)!=0)
        {
            $holidays = Holiday::where('date','<=',$month_last_day)->where('date','>=',$month_first_day);
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
                // dump($staff->staffworkdays);
                // dump($this_workday);
                // dump($day);
                // exit();
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
            $late_work = ($awt-$swt)/60;
            $attendance->early_home = ($sht-$aht)/60;
            $early_home = ($sht-$aht)/60;
            $should = $attendance->should_duration;
            $actual = $attendance->actual_duration;

            $attendance->is_late = Attendance::lateOrEarly($late_work, $should, $actual);
            $attendance->is_early = Attendance::lateOrEarly($early_home, $should, $actual);
        }

        $look_for_start_time = $ymd.' 00:00:00';
        $look_for_end_time = $ymd.' 24:00:00';
        // 录入当日加班：目前这个查询方法只适用于查询结果只有一条的。如果多条结果不能如此直接赋值
        // 无论有没有批准都记录进去。
        $extra_work_id = ExtraWork::where('staff_id',$staff->id)->where('extra_work_start_time','>=',$look_for_start_time)->where('extra_work_end_time','<=',$look_for_end_time)->value('id');
        $attendance->extra_work_id = $extra_work_id;

        // 录入当日请假:
        $absence_id = SeparateAbsence::where('staff_id',$staff->id)->where('year',$year)->where('month',$month)->where('date',$date)->value('absence_id');
        if ($absence_id != null)
        {
            $attendance->absence_id = $absence_id;
            $attendance->absence_duration = SeparateAbsence::where('staff_id',$staff->id)->where('year',$year)->where('month',$month)->where('date',$date)->value('duration');
            $attendance->absence_type = Absence::find($absence_id)->absence_type;
        }
        $attendance->basic_duration = Attendance::calBasic($attendance, $extra_work_id);
        $attendance->save();
    }

    /**
     *
     * @param one item in collection $s_a
     * @return void
     *
     */
    public static function isAbnormal($s_a)
    {
        // 只要四项有一项是空的，直接报异常 （因为实际上下班必须对应应该上下班）
        if ($s_a->should_work_time == null || $s_a->should_home_time == null || $s_a->actual_work_time == null || $s_a->actual_home_time == null)
        {
            $s_a->abnormal = true;
        }
        else
        {
            if ($s_a->add_duration == null)
            {
                $add_duration = 0;
            }
            else
            {
                $add_duration = $s_a->add_duration;
            }

            if ($s_a->extraWork == null)
            {
                $extrawork_duration = 0;
            }
            else
            {
                $extrawork_duration = $s_a->extraWork->duration;
            }
            $cal_duration = $s_a->actual_duration-$extrawork_duration+$s_a->absence_duration+$s_a->add_duration; // 实际工时-加班+请假+总增补 >= (应该工时-5分钟)
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
                if ($s_a->absence_duration  + $s_a->add_duration>= ($s_a->should_duration-5/60))
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
                if ($s_a->actual_duration  + $s_a->add_duration>= ($s_a->extraWork->duration-5/60))
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

    /**
     *
     * @param collection $attendances
     * @param date $join_company
     * @param date $leave_company
     * @param date $month_first_day
     * @param date $month_last_day
     * @param int $year
     * @param int $month
     * @return void
     *
     */
    public static function joinOrLeave($attendances, $join_company, $leave_company, $month_first_day, $month_last_day, $year, $month)
    {
        // 判断该员工是否当月入职，如果是，入职前的日期统一改为不异常
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

        // 判断该员工是否当月离职，如果是，离职前的日期统一改为不异常
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
    }
}
