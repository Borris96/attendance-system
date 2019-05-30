<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Staff extends Model
{
    protected $table = 'staffs';

    // public function hasOneDepartment(){
    //     $this->hasOne('App\Department','id','department_id');
    // }

    // public function hasManyStaffworkdays(){
    //     $this->hasMany('App\Staffworkdays','id','department_id');
    // }


    public function getAllWorkdays($workdays_array){
        $workdaysall = '';
        foreach($workdays_array as $wd){

            $workdaysall.=$wd.',';

        }
        $workdaysall = rtrim($workdaysall,',');
        return $workdaysall;
    }

    public function insertWorkDays($workdays_array, $staffid){ //Insert workdays into staffworkdays table
        foreach ($workdays_array as $wd) {
            DB::table('staffworkdays')->insert([
                'staff_id'=>$staffid,
                'workday_name'=>$wd,
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time()),
            ]);
        }
    }
}
