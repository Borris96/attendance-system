<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MonthDuration;
use App\Holiday;

class Teacher extends Model
{
    protected $table = 'teachers';

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function monthDurations()
    {
        return $this->hasMany(MonthDuration::class);
    }

    public function substitutes()
    {
        return $this->hasMany(Substitute::class);
    }

    public function termTotal()
    {
        return $this->hasMany(TermTotal::class);
    }

    /**
     * 获取这个学期所有月份
     * @param date $start_date Y-m-d
     * @param date $end_date Y-m-d
     * @param array $term_months
     * @return array $term_months
     *
     */
    public static function getTermMonths($start_date, $end_date, $term_months=[])
    {
        $start_year = date('Y',strtotime($start_date));
        $end_year = date('Y',strtotime($end_date));
        $start_month = date('m',strtotime($start_date));
        $end_month = date('m',strtotime($end_date));

        // 获取这个学期所有的月份
        $term_months = [];
        if ($start_year == $end_year) // 学期在同一年时
        {
            if ($start_month == $end_month)
            {
                $term_months[] = (int)$start_month;
            }
            else
            {
                for ($i = $start_month; $i<=$end_month; $i++)
                {
                    $term_months[] = (int)$i;
                }
            }
        }
        else // 当开始年份小于结束年份时(只允许是相邻的两年)
        {
            for($i = $start_month; $i<=12; $i++)
            {
                $term_months[] = (int)$i;
            }
            for($i = 1; $i<=$end_month; $i++)
            {
                $term_months[] = (int)$i;
            }
        }
        return $term_months;
    }

    /**
     * 为老师关联课程后，自动计算该学期每个月份的实际排课课时
     * @param date $month_first_day Y-m-d
     * @param date $month_last_day Y-m-d
     * @param int $month
     * @param int $year
     * @param object $lesson
     *
     */
    public static function calMonthDuration($month_first_day,$month_last_day,$lesson,$month,$year)
    {
        $lesson_day_array = ['Sun'=>0,'Fri'=>5,'Sat'=>6, 'Mon'=>1, 'Wed'=>3];
        $want_day = $lesson_day_array[$lesson->day]; // 获取所添加课程的星期并转换为数字
        // 先找一下这个月是否已经有了记录
        $month_duration_id = MonthDuration::where('teacher_id',$lesson->teacher_id)->where('year',$year)->where('month',$month)->value('id');
        if ($month_duration_id == null)
        {
            $month_duration = new MonthDuration();
        }
        else
        {
            $month_duration = MonthDuration::find($month_duration_id);
        }

        $month_duration->year = $year;
        $month_duration->month = $month;
        $month_duration->term_id = $lesson->term_id;
        $month_duration->teacher_id = $lesson->teacher_id;
        $str_this_date = strtotime($month_first_day);
        // 寻找这个月中是礼拜六的那一天，累加礼拜六上课时长
        while($str_this_date<=strtotime($month_last_day))
        {
            $this_day = date('w',$str_this_date); // 获取这一天是星期几
            if ($this_day == $want_day)
            {
                if ($want_day == 0)
                {
                    $month_duration->sun_duration += $lesson->duration;
                }
                elseif ($want_day == 5)
                {
                   $month_duration->fri_duration += $lesson->duration;
                }
                elseif ($want_day == 6)
                {
                    $month_duration->sat_duration += $lesson->duration;
                }
                elseif ($want_day == 1)
                {
                    $month_duration->mon_duration += $lesson->duration;
                }
                elseif ($want_day == 3)
                {
                    $month_duration->wed_duration += $lesson->duration;
                }
                $month_duration->actual_duration += $lesson->duration;
            }
            $str_this_date = $str_this_date+3600*24;
        }
        // exit();
        $month_duration->save();
    }

    /**
     * 计算老师实际排课
     * @param object $teacher
     * @param date $month_first_day Y-m-d
     * @param date $month_last_day Y-m-d
     * @param collection $holidays
     * @return int $duration
     *
     */
    // 考虑到员工可能每周排班都会变化，之后要根据排班的起止时间来获取工作时长
    public static function calShouldMonthDuration($teacher, $month_first_day, $month_last_day){

        $day_array = [0=>'日', 1=>'一', 2=>'二', 3=>'三', 4=>'四', 5=>'五', 6=>'六'];
        $work_duration = 0;
        $office_duration = 0;
        $work_hour = 8;
        $teacher_work_days = $teacher->staff->staffworkdays; // 获取老师的工作日

        $str_this_date = strtotime($month_first_day);
        // 这个月应上班总时长（默认标准工作时间）
        while($str_this_date<=strtotime($month_last_day))
        {
            $this_day = date('w',$str_this_date); // 获取这一天是星期几
            $this_date = date('Y-m-d',$str_this_date); // 这一天日期
            $holiday = Holiday::where('date',$this_date);
            if ($this_day != 0 && $this_day != 6)
            {
                if (count($holiday->get()) == 0)
                {
                    $work_duration += $work_hour; // 这个月应该工作的时长
                }
                else
                {
                    if ($holiday->value('holiday_type') != '休息')
                    {
                        $work_duration += $work_hour;
                    }
                }
            }
            else // 周六周日上班的话
            {
                if ($holiday->value('holiday_type') == '上班')
                {
                    $work_duration += $work_hour;
                }
            }
            $str_this_date = $str_this_date+3600*24; // 加一天
        }

        $str_this_date = strtotime($month_first_day);
        // 这个月老师的坐班时间
        while($str_this_date<=strtotime($month_last_day))
        {
            $this_day = date('w',$str_this_date); // 获取这一天是星期几
            foreach($teacher_work_days as $twd) // 如果其中一天和这一天的星期相等，坐班时间累加
            {
                if ($day_array[$this_day] == $twd->workday_name) // 查找这一天的工作时长并加上
                {
                    $office_duration += $twd->duration;
                    $is_work = $twd->is_work;
                    $this_day_duration = $twd->duration;
                }
            }

            $holiday = Holiday::where('date',date('Y-m-d',$str_this_date));
            if (count($holiday->get())!=0) // 如果在节假日中，进一步处理
            {
                $holiday_date = $holiday->value('date');
                $holiday_day = date('w',strtotime($holiday_date));

                // 是老师的工作日，且节假日休息的，减时长
                if ($is_work == true)
                {
                    if ($holiday->value('holiday_type')=='休息')
                    {
                        $office_duration -= $this_day_duration;
                    }
                }
                // 不是老师工作日，节假日上班的，加时长
                else
                {
                    if ($holiday->value('holiday_type')=='上班')
                    {
                        $holiday_workday = $holiday->value('workday_name');
                        foreach($teacher_work_days as $twd) // 如果其中一天和这一天的星期相等，坐班时间累加
                        {
                            if ($day_array[$holiday_workday] == $twd->workday_name) // 查找这一天的工作时长并加上
                            {
                                $office_duration += $twd->duration;
                            }
                        }
                    }
                }
            }
            $str_this_date = $str_this_date+3600*24; // 加一天
        }

        $duration = $work_duration - $office_duration;
        return $duration;
    }
}
