<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('staffs')->insert([
        //     'id'=>'2012060101',
        //     'staffname'=>'胡遐近',
        //     'englishname'=>'IreneHu',
        //     'department_name'=>'客服部',
        //     'poistion_name'=>'主任',
        //     'join_company'=>'2012-06-01',
        //     'created_at'=>now(),
        //     'updated_at'=>now(),
        //     ]);

        DB::table('staffs')->insert([
            'id'=>'2018050701',
            'staffname'=>'陈嘉琛',
            'englishname'=>'ReneeChen',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2018-05-07',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2011110101',
            'staffname'=>'舒旻',
            'englishname'=>'SunnyShu',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2011-11-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2016031601',
            'staffname'=>'朱莹',
            'englishname'=>'CindyZhu',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2016-03-16',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017070301',
            'staffname'=>'张琳',
            'englishname'=>'AmyZhang',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2017-07-03',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017070101',
            'staffname'=>'沈艳萍',
            'englishname'=>'CarolShen',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2017-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018070101',
            'staffname'=>'郑欣',
            'englishname'=>'CocoZheng',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2018-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017030101',
            'staffname'=>'张颖',
            'englishname'=>'LillianZhang',
            'department_id'=>'4',
            'position_id'=>'2',
            'department_name'=>'人事行政部',
            'position_name'=>'经理',
            'join_company'=>'2017-03-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018060401',
            'staffname'=>'贡雯',
            'englishname'=>'EuniceGong',
            'department_id'=>'4',
            'position_id'=>'1',
            'department_name'=>'人事行政部',
            'position_name'=>'助理',
            'join_company'=>'2018-06-04',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017030102',
            'staffname'=>'卢宁',
            'englishname'=>'LucyLu',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'网课部',
            'position_name'=>'设计',
            'join_company'=>'2017-03-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018090101',
            'staffname'=>'江焕新',
            'englishname'=>'JayJiang',
            'department_id'=>'3',
            'position_id'=>'2',
            'department_name'=>'网课部',
            'position_name'=>'经理',
            'join_company'=>'2018-09-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018112601',
            'staffname'=>'王盛',
            'englishname'=>'MikeWang',
            'department_id'=>'3',
            'position_id'=>'5',
            'department_name'=>'网课部',
            'position_name'=>'主管',
            'join_company'=>'2016-11-26',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);
    }
}
