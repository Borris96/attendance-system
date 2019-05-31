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
//Insert workdays into staffworkdays table
    public function insertWorkDays($workdays_array, $staffid){
        foreach ($workdays_array as $wd) {
            DB::table('staffworkdays')->insert([
                'staff_id'=>$staffid,
                'workday_name'=>$wd,
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time()),
            ]);
        }
        return;
    }

//Insert workdays into staffworkdays table
    public function updateWorkDays($workdays_array, $staffid){
        $origin = DB::table('staffworkdays')->where('staff_id',$staffid);
        if ($origin->get('workday_name') != $workdays_array)
        {
            // $created_at = $origin->get('created_at');
            DB::table('staffworkdays')->where('staff_id',$staffid)->delete();
            foreach ($workdays_array as $wd) {
                DB::table('staffworkdays')->insert([
                    'staff_id'=>$staffid,
                    'workday_name'=> $wd,
                    'updated_at' => date('Y-m-d H:i:s',time()),
                ]);
            }
        }
        return;
    }

//Calculate annual holidays for staffs
    public function getAnnualHolidays($work_year, $join_company){

        if ($work_year<10 && $work_year>0){
            $default_holiday = 5;
        } elseif ($work_year>=10 && $work_year<20){
            $default_holiday = 10;
        } elseif ($work_year>=20){
            $default_holiday = 15;
        } else {
            $default_holiday = 0;
        }

        $end_date = strtotime("December 31");
        $join_company = strtotime($join_company);
        $annual_holiday = $default_holiday*($end_date-$join_company)/(360*24*60*60);
        return $annual_holiday;
    }
}
