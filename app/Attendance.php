<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = "attendances";

    public function staff()
    {
        $this->belongsTo(Staff::class);
    }

    /**
     * 计算上班时长（小时）
     * @param timestamp $work_time,
     * @param timestamp $home_time
     * @return double duration
     */
    public function calDuration($work_time, $home_time)
    {
        if ($home_time>$work_time) {
            if (date('H:i',$work_time)<='12:00')
            {
                if (date('H:i',$home_time)<='13:00')
                {
                    return ($home_time-$work_time)/(60*60);
                }
                else {
                    return ($home_time-$work_time)/(60*60)-1;
                }
            } else {
                return ($home_time-$work_time)/(60*60);
            }
        }
        else {
            return 0;
        }
    }
}
