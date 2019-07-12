<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MonthDuration;

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

    /**
     * 为老师关联课程后，自动计算该学期每个月份的实际排课课时
     * @param date $month_first_day Y-m-d
     * @param date $month_last_day Y-m-d
     * @param int $month
     * @param object $lesson
     *
     */
    public static function calMonthDuration($month_first_day,$month_last_day,$lesson,$month,$year)
    {
        $lesson_day_array = ['Sun'=>0,'Fri'=>5,'Sat'=>6];
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
                $month_duration->actual_duration += $lesson->duration;
            }
            $str_this_date = $str_this_date+3600*24;
        }
        // exit();
        $month_duration->save();
    }
}
