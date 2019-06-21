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
     * 将一段请假时间每天时长作为数组返回
     *
     * @param time $first_day_home_time
     * @param time $last_day_work_time
     * @param datetime $absence_start_time
     * @param datetime $absence_end_time
     * @param array $duration_array
     * @return array $duration_array 若有多天，第一个元素为第一天时长，第二个元素为最后一天时长，完整天的时长等于当日工时（需要另读）
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
                // time date('H:i',$start_time) 当日开始时间
                // time date('H:i',$end_time) 当日结束时间
                if (date('H:i',$start_time)>'13:00')
                {
                    $duration = ($end_time-$start_time)/(60*60);
                }
                elseif (date('H:i',$start_time)>='12:00' && date('H:i',$start_time)<='13:00')
                {
                    if (date('H:i',$end_time)>='12:00' && date('H:i',$end_time)<='13:00')
                    {
                        $duration = 0;
                    }
                    elseif (date('H:i',$end_time)>'13:00')
                    $duration = ($end_time-strtotime($end_date.' 13:00'))/(60*60);
                }
                else
                {
                    if (date('H:i',$end_time)<'12:00')
                    {
                        $duration = ($end_time-$start_time)/(60*60);
                    }
                    elseif (date('H:i',$end_time)>='12:00' && date('H:i',$end_time)<='13:00')
                    {
                        $duration = (strtotime($start_date.' 12:00')-$start_time)/(60*60);
                    }
                    else
                    {
                        $duration = ($end_time-$start_time)/(60*60)-1;
                    }
                }
                // 数组里只存一个时长
                $duration_array[] = $duration;
            }
            else // 请假起止不在同一天
            {
                // time date('H:i',$start_time) 当日开始时间
                // time $first_day_home_time 当日结束时间
                // 计算第一天小时数
                // if (date('H:i',$start_time)<'12:00')
                // {
                //     if ($first_day_home_time<='13:00')
                //     {
                //         $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                //     }
                //     else {
                //         $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60)-1;
                //     }
                // }
                // else
                // {
                //     $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                // }

                // time date('H:i',$start_time) 当日开始时间
                // time date('H:i',$end_time) 当日结束时间 $first_day_home_time

                // $end_time <=> strtotime($start_date.' '.$first_day_home_time)
                if (date('H:i',$start_time)>'13:00')
                {
                    $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                }
                elseif (date('H:i',$start_time)>='12:00' && date('H:i',$start_time)<='13:00')
                {
                    if ($first_day_home_time>='12:00' && $first_day_home_time<='13:00')
                    {
                        $crest_start = 0;
                    }
                    elseif ($first_day_home_time>'13:00')
                    $crest_start = (strtotime($start_date.' '.$first_day_home_time)-strtotime($start_date.' 13:00'))/(60*60);
                }
                else
                {
                    if ($first_day_home_time<'12:00')
                    {
                        $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60);
                    }
                    elseif ($first_day_home_time>='12:00' && $first_day_home_time<='13:00')
                    {
                        $crest_start = (strtotime($start_date.' 12:00')-$start_time)/(60*60);
                    }
                    else
                    {
                        $crest_start = (strtotime($start_date.' '.$first_day_home_time)-$start_time)/(60*60)-1;
                    }

                }
                $duration_array[] = $crest_start;
                // 计算最后一天小时数
                // if ($last_day_work_time<'12:00')
                // {
                //     if (date('H:i',$end_time)<='13:00')
                //     {
                //         $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                //     }
                //     else {
                //         $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60)-1;
                //     }
                // }
                // else
                // {
                //     $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                // }



                // time date('H:i',$start_time) 当日开始时间 $last_day_work_time
                // time date('H:i',$end_time) 当日结束时间

                // $start_time <=> strtotime($end_date.' '.$last_day_work_time)
                if ($last_day_work_time>'13:00')
                {
                    $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                }
                elseif ($last_day_work_time>='12:00' && $last_day_work_time<='13:00')
                {
                    if (date('H:i',$end_time)>='12:00' && date('H:i',$end_time)<='13:00')
                    {
                        $crest_end = 0;
                    }
                    elseif (date('H:i',$end_time)>'13:00')
                    $crest_end = ($end_time-strtotime($end_date.' 13:00'))/(60*60);
                }
                else
                {
                    if (date('H:i',$end_time)<'12:00')
                    {
                        $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                    }
                    elseif (date('H:i',$end_time)>='12:00' && date('H:i',$end_time)<='13:00')
                    {
                        $crest_end = (strtotime($end_date.' 12:00')-strtotime($end_date.' '.$last_day_work_time))/(60*60);
                    }
                    else
                    {
                        $crest_end = ($end_time-strtotime($end_date.' '.$last_day_work_time))/(60*60)-1;
                    }
                }
                $duration_array[] = $crest_end;
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
