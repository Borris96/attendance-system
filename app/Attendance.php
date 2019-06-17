<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = "attendances";

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function absence()
    {
        return $this->hasOne(Absence::class);
    }

    public function extraWork()
    {
        return $this->belongsTo(ExtraWork::class);
    }

    public function totalAttendance()
    {
        return $this->belongsTo(TotalAttendance::class);
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
}
