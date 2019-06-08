<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkHistory extends Model
{
    protected $table = 'work_historys';

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * 计算起始时间是否和之前的记录重叠
     * @param timestamp $start_time
     * @param timestamp $end_time
     * @param timestamp $old_start_time
     * @param timestamp $old_end_time
     * @return boolean
     */

    public static function isCrossing($start_time, $end_time, $old_start_time, $old_end_time) //重叠吗？
    {
        if ($end_time<=$old_start_time || $old_end_time<=$start_time) { //时间不重叠 -> false
            return false;
        } else {
            return true; //时间重叠 -> true
        }
    }
}
