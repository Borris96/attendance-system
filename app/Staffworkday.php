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
                $workday_name = '一';
                return $workday_name;
                break;
            case '1':
                $workday_name = '二';
                return $workday_name;
                break;
            case '2':
                $workday_name = '三';
                return $workday_name;
                break;
            case '3':
                $workday_name = '四';
                return $workday_name;
                break;
            case '4':
                $workday_name = '五';
                return $workday_name;
                break;
            case '5':
                $workday_name = '六';
                return $workday_name;
                break;
            case '6':
                $workday_name = '日';
                return $workday_name;
                break;

            default:
                break;
        }
    }
}
