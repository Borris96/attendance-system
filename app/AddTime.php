<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddTime extends Model
{
    protected $table = 'add_times';

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    /**
     * 计算加班时长（小时）
     * @param timestamp $start_time,
     * @param timestamp $end_time
     * @return double
     */
    public function calDuration($start_time, $end_time)
    {
        $start_time = strtotime($start_time); // Convert it to string
        $end_time = strtotime($end_time);
        if ($end_time>$start_time) {
            // 只有当加班开始时间小于12点的时候才会计算午饭时间 （不要轻易试探11:59这个临界值）
            if (date('H:i',$start_time)<'12:00')
            {
                // 12点到1点为午休时间，只要超过一点才离开就减去一小时午饭时间（不要轻易试探13:01这个值哦）
                if (date('H:i',$end_time)<='13:00')
                {
                    return ($end_time-$start_time)/(60*60);
                }
                else {
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
     * 计算起始时间是否和之前的记录重叠
     * @param timestamp $start_time
     * @param timestamp $end_time
     * @param timestamp $old_start_time
     * @param timestamp $old_end_time
     * @return boolean
     */

    public function isCrossing($start_time, $end_time, $old_start_time, $old_end_time)
    {
        if ($end_time<=$old_start_time || $old_end_time<=$start_time) { //时间不重叠
            return false;
        } else {
            return true;
        }
    }
}
