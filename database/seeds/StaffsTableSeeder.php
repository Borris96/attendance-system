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

        // 固定时长员工
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
            'status'=>true, 'leave_company'=>'2038-01-01',
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
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019010101',
            'staffname'=>'TinaChen',
            'englishname'=>'TinaChen',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2019-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
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
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017070301',
            'staffname'=>'张琳',
            'englishname'=>'AmiZhang',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2017-07-03',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
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
            'status'=>true, 'leave_company'=>'2038-01-01',
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
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017030101',
            'staffname'=>'张颖',
            'englishname'=>'lillianZhang',
            'department_id'=>'5',
            'position_id'=>'2',
            'department_name'=>'人事行政部',
            'position_name'=>'经理',
            'join_company'=>'2017-03-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018060401',
            'staffname'=>'贡雯',
            'englishname'=>'EuniceGong',
            'department_id'=>'5',
            'position_id'=>'1',
            'department_name'=>'人事行政部',
            'position_name'=>'助理',
            'join_company'=>'2018-06-04',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017030102',
            'staffname'=>'卢宁',
            'englishname'=>'LucyLu',
            'department_id'=>'4',
            'position_id'=>'7',
            'department_name'=>'网课部',
            'position_name'=>'设计',
            'join_company'=>'2017-03-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018090101',
            'staffname'=>'江焕新',
            'englishname'=>'JayJiang',
            'department_id'=>'4',
            'position_id'=>'2',
            'department_name'=>'网课部',
            'position_name'=>'经理',
            'join_company'=>'2018-09-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018112601',
            'staffname'=>'王盛',
            'englishname'=>'MikeWang',
            'department_id'=>'4',
            'position_id'=>'5',
            'department_name'=>'网课部',
            'position_name'=>'主管',
            'join_company'=>'2016-11-26',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        // 非固定员工
        DB::table('staffs')->insert([
            'id'=>'2015060401',
            'staffname'=>'侯春燕',
            'englishname'=>'JoeyHou',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2015-06-04',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017040501',
            'staffname'=>'范佳秋',
            'englishname'=>'FannyFan',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2017-04-05',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017010106',
            'staffname'=>'GingerJiao',
            'englishname'=>'GingerJiao',
            'department_id'=>'1',
            'position_id'=>'11',
            'department_name'=>'客服部',
            'position_name'=>'实习生',
            'join_company'=>'2017-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017010107',
            'staffname'=>'FinnFeng',
            'englishname'=>'FinnFeng',
            'department_id'=>'7',
            'position_id'=>'8',
            'department_name'=>'其他',
            'position_name'=>'兼职批作业',
            'join_company'=>'2017-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017010108',
            'staffname'=>'SherryXiong',
            'englishname'=>'SherryXiong',
            'department_id'=>'7',
            'position_id'=>'8',
            'department_name'=>'其他',
            'position_name'=>'兼职批作业',
            'join_company'=>'2017-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017010109',
            'staffname'=>'RyanWu',
            'englishname'=>'RyanWu',
            'department_id'=>'7',
            'position_id'=>'8',
            'department_name'=>'其他',
            'position_name'=>'兼职批作业',
            'join_company'=>'2017-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        // 外教
        DB::table('staffs')->insert([
            'id'=>'2017010102',
            'teacher_id'=>'1',
            'staffname'=>'Rosalia',
            'englishname'=>'Rosalia',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2017-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017010110',
            'teacher_id'=>'2',
            'staffname'=>'Sarah',
            'englishname'=>'Sarah',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2017-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018010110',
            'teacher_id'=>'3',
            'staffname'=>'James',
            'englishname'=>'James',
            'department_id'=>'3',
            'position_id'=>'9',
            'department_name'=>'外教部',
            'position_name'=>'兼职教师',
            'join_company'=>'2018-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        // 当月入职
        DB::table('staffs')->insert([
            'id'=>'2019032501',
            'staffname'=>'MichelleZhang',
            'englishname'=>'MichelleZhang',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2019-03-25',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019030101',
            'staffname'=>'AsaSmithXue',
            'englishname'=>'AsaSmithXue',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2019-03-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        // 离职员工
        // 当月
        DB::table('staffs')->insert([
            'id'=>'2017010101',
            'staffname'=>'JasmxnJin',
            'englishname'=>'JasmxnJin',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2017-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>false,
            'leave_company'=>'2019-03-11',
            ]);

        // 当月初
        DB::table('staffs')->insert([
            'id'=>'2017010103',
            'staffname'=>'AmieeHu',
            'englishname'=>'AmieeHu',
            'department_id'=>'2',
            'position_id'=>'3',
            'department_name'=>'教材部',
            'position_name'=>'文员',
            'join_company'=>'2017-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>false,
            'leave_company'=>'2019-03-01',
            ]);

        // 上月
        DB::table('staffs')->insert([
            'id'=>'2017010104',
            'staffname'=>'ThomasBacker',
            'englishname'=>'ThomasBacker',
            'department_id'=>'3',
            'position_id'=>'9',
            'department_name'=>'外教部',
            'position_name'=>'兼职教师',
            'join_company'=>'2017-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>false,
            'leave_company'=>'2019-02-16',
            ]);

        // 下月
        DB::table('staffs')->insert([
            'id'=>'2017010105',
            'staffname'=>'ShirleyLi',
            'englishname'=>'ShirleyLi',
            'department_id'=>'3',
            'position_id'=>'10',
            'department_name'=>'外教部',
            'position_name'=>'兼职助教',
            'join_company'=>'2017-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>false,
            'leave_company'=>'2019-04-11',
            ]);
    }
}
