<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ExtraWork extends Model
{
    protected $table = 'extra_works';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    // public function attendance(){
    //     return $this->belongsTo(Attendance::class);
    // }

    public function calDuration($extra_work_start_time, $extra_work_end_time){
        $str_start = strtotime($extra_work_start_time); // Convert it to string
        $str_end = strtotime($extra_work_end_time); // Convert it to sring
        if ($extra_work_end_time>$extra_work_start_time){
            $duration = ($str_end-$str_start)/(60*60); //Convert to hours
        } else {
            $duration=0;
        }
        return $duration;
    }

    /**
     * 计算起始时间是否和之前的记录重叠
     * @param timestamp $ew_start_time
     * @param timestamp $ew_end_time
     * @param timestamp $old_ew_start_time
     * @param timestamp $old_ew_end_time
     * @return boolean
     */

    public function isCrossing($ew_start_time, $ew_end_time, $old_ew_start_time, $old_ew_end_time)
    {
        if ($ew_end_time<=$old_ew_start_time || $old_ew_end_time<=$ew_start_time) { //时间不重叠
            return false;
        } else {
            return true;
        }
    }

}
