<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $table = "holidays";

    /**
     * 比较是否创建了重复日期
     * @param timestamp $current_date
     * @param timestamp $old_date
     * @return boolean
     */
    public function isRepeat($current_date, $old_date)
    {
        if ($current_date == $old_date){
            return true;
        } else {
            return false;
        }
    }
}
