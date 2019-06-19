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
}
