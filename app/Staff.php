<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Staff extends Model
{
    protected $table = 'staffs';

    // public function hasOneDepartment(){
    //     $this->hasOne('App\Department','id','department_id');
    // }

    // public function hasManyStaffworkdays(){
    //     $this->hasMany('App\Staffworkdays','id','department_id');
    // }

    /**
     * 把选中的工作日转换成string
     * @param arr $workdays_array
     * @return string $workdaysall
     */
    public function getAllWorkdays($workdays_array){
        $workdaysall = '';
        foreach($workdays_array as $wd){

            $workdaysall.=$wd.',';

        }
        $workdaysall = rtrim($workdaysall,',');
        return $workdaysall;
    }
//

    /**
     * 把选中的工作日录入staffworkday表
     * @param arr $workdays_array, int $staffid
     * @return void
     */

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

    /**
     * 把编辑时选中的工作日通过先录入后删除的形式更新至staffworkday表
     * @param arr $workdays_array, int $staffid
     * @return void
     */
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

    /**
     * 计算年假
     * @param int $work_year, date $join_company
     * @return int $annual_holiday
     */

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


    /**
     * 如果进入新一年，更新工作年数
     * @param date $updated_at, int $work_year
     * @return int $work_year
     */
    public function updateWorkYears($updated_at, $work_year){
        if ($updated_at->isLastYear() ){
            $work_year += 1;
        }
        return $work_year;
    }

    /**
     * 如果进入新一年，更新年假
     * @param date $updated_at, int $annual_holiday, int $work_year
     * @return int $annual_holiday
     */
    public function updateAnnualHolidays($updated_at, $annual_holiday, $work_year){

        if ($updated_at->isLastYear() ){
            if ($work_year<10 && $work_year>0){
                $default_holiday = 5;
            } elseif ($work_year>=10 && $work_year<20){
                $default_holiday = 10;
            } elseif ($work_year>=20){
                $default_holiday = 15;
            } else {
                $default_holiday = 0;
            }
            $annual_holiday += $default_holiday;
        }
        return $annual_holiday;
    }
}
