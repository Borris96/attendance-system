<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class ExtraWork extends Model
{
    protected $table = 'extra_works';

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

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

}
