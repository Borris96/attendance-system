<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $table = 'lessons';

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * 计算起始时间是否和之前的记录重叠
     * @param timestamp $ew_start_time
     * @param timestamp $ew_end_time
     * @param timestamp $old_ew_start_time
     * @param timestamp $old_ew_end_time
     * @return boolean
     */

    public static function isCrossing($ew_start_time, $ew_end_time, $old_ew_start_time, $old_ew_end_time)
    {
        if ($ew_end_time<=$old_ew_start_time || $old_ew_end_time<=$ew_start_time) { //时间不重叠
            return false;
        } else {
            return true;
        }
    }
}
