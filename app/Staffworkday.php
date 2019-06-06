<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staffworkday extends Model
{
    protected $table = 'staffworkdays';

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function getWorkdayName($i){
        switch ($i) {
            case '0':
                $workday_name = '周一';
                return $workday_name;
                break;
            case '1':
                $workday_name = '周二';
                return $workday_name;
                break;
            case '2':
                $workday_name = '周三';
                return $workday_name;
                break;
            case '3':
                $workday_name = '周四';
                return $workday_name;
                break;
            case '4':
                $workday_name = '周五';
                return $workday_name;
                break;
            case '5':
                $workday_name = '周六';
                return $workday_name;
                break;
            case '6':
                $workday_name = '周日';
                return $workday_name;
                break;

            default:
                break;
        }
    }
}
