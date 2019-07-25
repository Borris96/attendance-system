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

    public function termTotals()
    {
        return $this->hasMany(TermTotal::class);
    }

    public function lessonUpdates()
    {
        return $this->hasMany(LessonUpdate::class);
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
     * @param object $lesson_update
     * @param string $option 'add', 'substract' 用于区分减去还是加上时长。一些功能中，比如更换上课老师，原老师的时长需要减去，而新老师的时长要加上。
     * @param int $teacher_id 一般情况下不填，当更换上课老师时，原老师减去的时长要基于$new_update，所以需要赋值否则函数中将不能正确查找
     * @param decimal $origin_duration
     * @param string $origin_day
     * 上面两个参数一般情况为空，当课程基本信息更改，并且影响到已分配老师时，该老师需要先依据这两个减去原来的上课时长
     *
     */
    public static function calMonthDuration($month_first_day,$month_last_day,$lesson_update,$month,$year,$option = 'add',$teacher_id = '',$origin_duration = '',$origin_day = '')
    {

        $lesson_day_array = ['Sun'=>0,'Fri'=>5,'Sat'=>6, 'Mon'=>1, 'Wed'=>3];
        if ($origin_day == '')
        {
            $want_day = $lesson_day_array[$lesson_update->day]; // 获取所添加课程的星期并转换为数字
        }
        else
        {
            $want_day = $lesson_day_array[$origin_day];
        }

        if ($origin_duration == '')
        {
            $real_duration = $lesson_update->duration;
        }
        else
        {
            $real_duration = $origin_duration;
        }
        // 先找一下这个月是否已经有了记录
        if ($teacher_id == '')
        {
           $month_duration_id = MonthDuration::where('teacher_id',$lesson_update->teacher_id)->where('year',$year)->where('month',$month)->value('id');
        }
        else
        {
            $month_duration_id = MonthDuration::where('teacher_id',$teacher_id)->where('year',$year)->where('month',$month)->value('id');
        }

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
        $month_duration->term_id = $lesson_update->lesson->term_id;
        if ($teacher_id == '')
        {
            $month_duration->teacher_id = $lesson_update->teacher_id;
        }
        else
        {
            $month_duration->teacher_id = $teacher_id;
        }

        $str_this_date = strtotime($month_first_day);
        // 寻找这个月中是礼拜六的那一天，累加礼拜六上课时长
        if ($option == 'add')
        {
            while($str_this_date<=strtotime($month_last_day))
            {
                $this_day = date('w',$str_this_date); // 获取这一天是星期几
                if ($this_day == $want_day)
                {
                    if ($want_day == 0)
                    {
                        $month_duration->sun_duration += $real_duration;
                    }
                    elseif ($want_day == 5)
                    {
                       $month_duration->fri_duration += $real_duration;
                    }
                    elseif ($want_day == 6)
                    {
                        $month_duration->sat_duration += $real_duration;
                    }
                    elseif ($want_day == 1)
                    {
                        $month_duration->mon_duration += $real_duration;
                    }
                    elseif ($want_day == 3)
                    {
                        $month_duration->wed_duration += $real_duration;
                    }
                    else
                    {
                        $month_duration->other_duration += $real_duration;
                    }
                    $month_duration->actual_duration += $real_duration;
                }
                $str_this_date = $str_this_date+3600*24;
            }
        }
        elseif ($option == 'substract') // 如果这个老师和别的老师换课了，那么这段时间的时长需要减掉
        {
            while($str_this_date<=strtotime($month_last_day))
            {
                $this_day = date('w',$str_this_date); // 获取这一天是星期几
                if ($this_day == $want_day)
                {
                    if ($want_day == 0)
                    {
                        $month_duration->sun_duration -= $real_duration;
                    }
                    elseif ($want_day == 5)
                    {
                       $month_duration->fri_duration -= $real_duration;
                    }
                    elseif ($want_day == 6)
                    {
                        $month_duration->sat_duration -= $real_duration;
                    }
                    elseif ($want_day == 1)
                    {
                        $month_duration->mon_duration -= $real_duration;
                    }
                    elseif ($want_day == 3)
                    {
                        $month_duration->wed_duration -= $real_duration;
                    }
                    else
                    {
                        $month_duration->other_duration -= $real_duration;
                    }
                    $month_duration->actual_duration -= $real_duration;
                }
                $str_this_date = $str_this_date+3600*24;
            }
        }

        $month_duration->save();
    }

    /**
     * 计算老师应排课
     * @param object $teacher
     * @param date $month_first_day Y-m-d
     * @param date $month_last_day Y-m-d
     * @param collection $holidays
     * @return int $duration
     *
     */
    // 考虑到员工可能每周排班都会变化，之后要根据排班的起止时间来获取坐班工作时长
    public static function calShouldMonthDuration($teacher, $month_first_day, $month_last_day){

        $day_array = [0=>'日', 1=>'一', 2=>'二', 3=>'三', 4=>'四', 5=>'五', 6=>'六'];
        $work_duration = 0;
        $office_duration = 0;
        $work_hour = 8;
        $teacher_work_days = $teacher->staff->staffworkdays; // 获取老师的工作日

        $str_this_date = strtotime($month_first_day);
        // 这个月应上班总时长（默认标准工作时间）
        // 学期首月首周和末月末周的应上班: 由于学期开始不一定在周一，结束不一定在周日，所以要把首末月起止时间往前往后推移。
        while($str_this_date<=strtotime($month_last_day))
        {
            $this_day = date('w',$str_this_date); // 获取这一天是星期几
            $this_date = date('Y-m-d',$str_this_date); // 这一天日期
            $holiday = Holiday::where('date',$this_date); // 查询这一天是否有节假日调休
            if ($this_day != 0 && $this_day != 6)
            {
                if (count($holiday->get()) == 0)
                {
                    $work_duration += $work_hour; // 这个月应该工作的时长
                }
                else // 这一天是节假日调休
                {
                    if ($holiday->value('holiday_type') != '休息')
                    {
                        $work_duration += $work_hour;
                    }
                }
            }
            else // 周六周日
            {
                // 上班的话
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

        $duration = $work_duration - $office_duration; // 应排课 = 应上班时长-坐班时长
        return $duration;
    }

    /**
     * 计算一段时间内（几个月时间）的每月实际上课
     * @param date $start_date Y-m-d
     * @param date $end_date Y-m-d
     * @return int $duration
     *
     */
    public static function calTermDuration($start_date, $end_date, $lesson_update, $option = 'add', $teacher_id='',$origin_duration='',$origin_day='')
    {
        // 录入该学期每个月的实际排课课时
        $start_year = date('Y',strtotime($start_date));
        $term_months = Teacher::getTermMonths($start_date, $end_date);
        foreach ($term_months as $key=>$m)
        {
            if (count($term_months) == 1) // 学期只有一个月时，首尾日期就是学期起止日期
            {
                $year = $start_year;
                $month_first_day = $start_date;
                $month_last_day = $end_date;
            }
            else
            {
                if ($key == 0) // 第一个月
                {
                    $year = $start_year;
                    $first_month_first_day = date('Y-m-01',strtotime($year.'-'.$term_months[$key]));
                    $month_last_day = date('Y-m-d', strtotime("$first_month_first_day +1 month -1 day"));
                    $month_first_day = $start_date;
                }
                elseif ($key == count($term_months)-1) //最后一个月
                {
                    $month_first_day = date('Y-m-01',strtotime($year.'-'.$term_months[$key])); // 最后一月的第一天
                    $month_last_day = $end_date;
                }
                else // 中间月份
                {
                    $month_first_day = date('Y-m-01',strtotime($year.'-'.$term_months[$key]));
                    $month_last_day = date('Y-m-d', strtotime("$month_first_day +1 month -1 day"));
                }
            }
            Teacher::calMonthDuration($month_first_day,$month_last_day,$lesson_update,$term_months[$key],$year,$option,$teacher_id,$origin_duration,$origin_day); // 实际上课时长
            if ($term_months[$key] == 12) // 到12月了那么年数加一
            {
                $year+=1;
            }
        }
    }

    /**
     * 关联课程
     * @param int $id 课程的id
     * @param int $teacher_id
     * @return void
     *
     */
    public static function linkLessons($id,$teacher_id)
    {
        $lesson = Lesson::find($id);
        $lesson->teacher_id = $teacher_id;
        if ($lesson->save())
        {
            $lesson_updates = $lesson->lessonUpdates;
            $count = count($lesson_updates);

            foreach ($lesson_updates as $key => $lu) {
                if ($key == ($count-1))
                {
                    $lu->teacher_id = $lesson->teacher_id;
                    $lu->save();
                }
            }
        }
        // 随后计算这个学期每月实际排课 (不考虑节假日调休情况)
        $start_date = $lesson->term->start_date; // 学期开始日 计算第一个月实际排课要用
        $end_date = $lesson->term->end_date; // 学期结束日 计算最后月实际排课要用
        // $start_year = date('Y',strtotime($start_date));

        $lesson_updates = $lesson->lessonUpdates;
        foreach ($lesson_updates as $key => $lu) {
            // 使用最后一段课程更新记录的数据计算老师的学期实际排课时长
            if ($key == ($count-1) && $lu->teacher_id == $teacher_id)
            {
                Teacher::calTermDuration($start_date, $end_date, $lu);
            }
        }
    }
}
