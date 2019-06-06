<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Staff extends Model
{
    protected $table = 'staffs';

    // public function workHistorys()
    // {
    //     return $this->hasMany(WorkHistory::class);
    // }

    public function absences(){
        return $this->hasMany(Absence::class);
    }

    public function extraWorks(){
        return $this->hasMany(ExtraWork::class);
    }

    public function lieu(){
        return $this->hasOne(Lieu::class);
    }

    public function staffworkdays(){
        return $this->hasMany(Staffworkday::class);
    }

    /**
     * 计算年假
     * @param int $work_year, date $join_company
     * @return int $annual_holiday
     */

    public function getAnnualHolidays($work_year, $join_company){

        if ($work_year<10 && $work_year>0){
            $default_holiday = 5*8;
        } elseif ($work_year>=10 && $work_year<20){
            $default_holiday = 10*8;
        } elseif ($work_year>=20){
            $default_holiday = 15*8;
        } else {
            $default_holiday = 0;
        }

        $end_date = strtotime("December 31");
        $join_company = strtotime($join_company);
        $annual_holiday = $default_holiday*($end_date-$join_company)/(365*24*60*60); //入职年剩余年假等于 百分比（入职时该年还剩下的天数除以365）乘以该年应得年假小时数
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
                $default_holiday = 5*8;
            } elseif ($work_year>=10 && $work_year<20){
                $default_holiday = 10*8;
            } elseif ($work_year>=20){
                $default_holiday = 15*8;
            } else {
                $default_holiday = 0;
            }
            $annual_holiday += $default_holiday;
        }
        return $annual_holiday;
    }

    public function insertWH($work_experiences_array, $leave_experiences_array, $staffid){ //实在不行重写数据表
        $length = max(count($work_experiences_array), count($leave_experiences_array));
        for ($i=0; $i<$length; $i++) {
            DB::table('work_historys')->insert([
                'staff_id'=>$staffid,
                'work_experience'=>$work_experiences_array[$i],
                'leave_experience'=>$leave_experiences_array[$i],
                'created_at' => date('Y-m-d H:i:s',time()),
                'updated_at' => date('Y-m-d H:i:s',time()),
            ]);
        }
        return;
    }
}
