<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Absence extends Model
{
    protected $table = 'absences';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    /**
     *
     * 算法：
     * 如果请假起止在同一天，用加班时长算法;
     * 如果请假起止不在同一天，把这几天隔离开，
     * 完整天数乘以工时（8小时）;
     * 开始的那一天用下班时间减开始，结束的那一天用结束减上班时间，根据情况判定是否减午饭时间
     *
     * @param time $first_day_home_time
     * @param time $last_day_work_time
     * @param double $work_duration
     * @param datetime $absence_start_time
     * @param datetime $absence_end_time
     * @return double duration
     */
    public function calDuration($first_day_home_time, $last_day_work_time, $work_duration, $absence_start_time, $absence_end_time)
    {
        $start_time = strtotime($absence_start_time); // Convert it to string
        $end_time = strtotime($absence_end_time); // Convert it to sring
        $start_date = date("Y-m-d", $start_time);
        $end_date = date("Y-m-d", $end_time);

        if ($end_time > $start_time)
        {
            if ($start_date == $end_date) // 请假起止在同一天
            {
            // 只有当请假开始时间小于12点的时候才会计算午饭时间 （不要轻易试探11:59这个临界值）
                if (date('H:i',$start_time)<'12:00')
                {
                    // 12点到1点为午休时间，只要超过一点才离开就减去一小时午饭时间（不要轻易试探13:01这个值哦）
                    if (date('H:i',$end_time)<='13:00')
                    {
                        $duration =  ($end_time-$start_time)/(60*60);
                    }
                    else {
                        $duration = ($end_time-$start_time)/(60*60)-1;
                    }
                }
                else
                {
                    $duration = ($end_time-$start_time)/(60*60);
                }
            }
            else // 请假起止不在同一天
            {
                $cstart = strtotime("+1 day",strtotime($start_date)); // 完整的一个假期第一天
                $cend = strtotime("-1 day",strtotime($end_date)); // 完整的一个假期最后一天
                $cabsence = ($cend - $cstart)/(60*60*24) + 1; // 完整的假期总天数
                ////////// 如果每一天工作时长不一样，这里就会有问题了。
                $cabsence_hours = $cabsence*$work_duration; // 完整的假期小时数

                // 计算第一天小时数
                if (date('H:i',$start_time)<'12:00')
                {
                    if ($first_day_home_time<='13:00')
                    {
                        $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                    }
                    else {
                        $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60)-1;
                    }
                }
                else
                {
                    $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                }

                // 计算最后一天小时数
                if ($last_day_work_time<'12:00')
                {
                    if (date('H:i',$end_time)<='13:00')
                    {
                        $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                    }
                    else {
                        $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60)-1;
                    }
                }
                else
                {
                    $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                }

                $crest_hours = $crest_start+$crest_end; // 不完整请假的小时数
                $duration = $cabsence_hours + $crest_hours; // 总的小时数
            }
        }
        else
        {
            return 0;
        }
        return $duration;
    }


    /**
     *
     * 将一段请假时间每天时长作为数组返回
     *
     * @param time $first_day_home_time
     * @param time $last_day_work_time
     * @param datetime $absence_start_time
     * @param datetime $absence_end_time
     * @param array $duration_array
     * @return array $duration_array 若有多天，第一个元素为第一天时长，第二个元素为最后一天时长，完整天的时长等于当日工时
     */
    public function separateDuration($first_day_home_time, $last_day_work_time, $absence_start_time, $absence_end_time, $duration_array)
    {
        $start_time = strtotime($absence_start_time);
        $end_time = strtotime($absence_end_time);
        $start_date = date("Y-m-d", $start_time);
        $end_date = date("Y-m-d", $end_time);

        if ($end_time > $start_time)
        {
            if ($start_date == $end_date) // 请假起止在同一天
            {
                if (date('H:i',$start_time)<'12:00')
                {
                    if (date('H:i',$end_time)<='13:00')
                    {
                        $duration =  ($end_time-$start_time)/(60*60);
                    }
                    else {
                        $duration = ($end_time-$start_time)/(60*60)-1;
                    }
                }
                else
                {
                    $duration = ($end_time-$start_time)/(60*60);
                }
                // 数组里只存一个时长
                $duration_array[] = $duration;
                // echo '这是一';
                // dump($duration_array);
            }
            else // 请假起止不在同一天
            {
                $cstart = strtotime("+1 day",strtotime($start_date)); // 完整的一个假期第一天
                $cend = strtotime("-1 day",strtotime($end_date)); // 完整的一个假期最后一天

                // 计算第一天小时数
                if (date('H:i',$start_time)<'12:00')
                {
                    if ($first_day_home_time<='13:00')
                    {
                        $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                    }
                    else {
                        $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60)-1;
                    }
                }
                else
                {
                    $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                }

                $duration_array[] = $crest_start;
                // echo '这是二';
                // dump($duration_array);
                // 计算最后一天小时数
                if ($last_day_work_time<'12:00')
                {
                    if (date('H:i',$end_time)<='13:00')
                    {
                        $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                    }
                    else {
                        $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60)-1;
                    }
                }
                else
                {
                    $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                }

                $duration_array[] = $crest_end;
                // echo '这是三';
                // dump($duration_array);
            }
        }
        else
        {
            return false;
        }
        return $duration_array;
    }

    /**
     * 计算起始时间是否和之前的记录重叠
     * @param timestamp $absence_start_time
     * @param timestamp $absence_end_time
     * @param timestamp $old_absence_start_time
     * @param timestamp $old_absence_end_time
     * @return boolean
     */

    public function isCrossing($absence_start_time, $absence_end_time, $old_absence_start_time, $old_absence_end_time)
    {
        if ($absence_end_time<=$old_absence_start_time || $old_absence_end_time<=$absence_start_time) { //时间不重叠
            return false;
        } else {
            return true;
        }
    }
}
