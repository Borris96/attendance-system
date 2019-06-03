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
     * 计算请假时长（小时）
     * @param datetime $absence_start_time, datetime $absence_end_time
     * @return int duration
     */

    public static function calDuration($absence_start_time, $absence_end_time){
        $str_start = strtotime($absence_start_time); // Convert it to string
        $str_end = strtotime($absence_end_time); // Convert it to sring
        $start_date = date("Y-m-d", $str_start);
        $end_date = date("Y-m-d", $str_end);
        if ($absence_end_time>$absence_start_time){
            if ($start_date == $end_date){
                $duration = (min($str_end,strtotime($end_date.' 18:00:00'))-max($str_start,strtotime($start_date.' 9:00:00')))/(60*60); //Convert to hours
                if ($str_start<strtotime($start_date.' 12:00') && $str_end>strtotime($end_date.' 12:00'))
                {
                    $duration -= 1;
                }
            } elseif ($start_date<$end_date) {
                $cstart = strtotime("+1 day",strtotime($start_date)); // 完整的一个假期第一天
                $cend = strtotime("-1 day",strtotime($end_date)); // 完整的一个假期最后一天
                $cabsence = ($cend - $cstart)/(60*60*24) + 1; // 完整的假期总天数
                $cabsence_hours = $cabsence*8; // 完整的假期小时数
                $crest_start = (strtotime($start_date.' 18:00:00')-max($str_start,strtotime($start_date.' 9:00:00')))/3600; // 计算第一天小时数
                if ($str_start<strtotime($start_date.' 12:00'))
                {
                    $crest_start -= 1;
                }
                $crest_end = (min($str_end,strtotime($end_date.' 18:00:00'))-strtotime($end_date.' 9:00:00'))/3600; // 计算最后一天小时数
                if ($str_end>strtotime($start_date.' 12:00'))
                {
                    $crest_end -= 1;
                }
                $crest_hours = $crest_start+$crest_end; // 不完整请假的小时数
                $duration = $cabsence_hours + $crest_hours; // 总的小时数
            }
        } else {
            $duration=0;
        }

        return $duration;
    }
}
