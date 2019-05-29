<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs';


    public function getAllWorkdays($workdays_array){
        $workdaysall = '';
        foreach($workdays_array as $wd){

            $workdaysall.=$wd.',';

        }
        $workdaysall = rtrim($workdaysall,',');
        return $workdaysall;
    }
}
