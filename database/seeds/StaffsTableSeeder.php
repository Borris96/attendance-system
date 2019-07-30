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
        // 全职员工

        DB::table('staffs')->insert([
            'id'=>'2019070101',
            'staffname'=>'周琤',
            'englishname'=>'JoyZhou',
            'department_id'=>'7',
            'position_id'=>'12',
            'department_name'=>'其他',
            'position_name'=>'其他',
            'join_company'=>'2019-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2012060101',
            'staffname'=>'胡遐近',
            'englishname'=>'IreneHu',
            'department_id'=>'1',
            'position_id'=>'4',
            'department_name'=>'客服部',
            'position_name'=>'主任',
            'join_company'=>'2012-06-01',
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
            'id'=>'2018082901',
            'staffname'=>'唐菲菲',
            'englishname'=>'ConnieTang',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2018-08-29',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019070801',
            'staffname'=>'李茹君',
            'englishname'=>'AlinaLi',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2019-07-08',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019071501',
            'staffname'=>'夏璐',
            'englishname'=>'SherryXia',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2019-07-15',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019070102',
            'staffname'=>'侯晨',
            'englishname'=>'LucyHou',
            'department_id'=>'1',
            'position_id'=>'1',
            'department_name'=>'客服部',
            'position_name'=>'助理',
            'join_company'=>'2019-07-01',
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
            'id'=>'2013031501',
            'staffname'=>'余婕婷',
            'englishname'=>'JessieYu',
            'department_id'=>'6',
            'position_id'=>'2',
            'department_name'=>'财务部',
            'position_name'=>'经理',
            'join_company'=>'2013-03-15',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017030101',
            'staffname'=>'张颖',
            'englishname'=>'LillianZhang',
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
            'join_company'=>'2018-11-26',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019070103',
            'staffname'=>'杨霁',
            'englishname'=>'JessieYang',
            'department_id'=>'4',
            'position_id'=>'1',
            'department_name'=>'网课部',
            'position_name'=>'助理',
            'join_company'=>'2019-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        // DB::table('staffs')->insert([
        //     'id'=>'',
        //     'staffname'=>'ShirleyLi',
        //     'englishname'=>'ShirleyLi',
        //     'department_id'=>'7',
        //     'position_id'=>'12',
        //     'department_name'=>'其他',
        //     'position_name'=>'其他',
        //     'join_company'=>'',
        //     'created_at'=>now(),
        //     'updated_at'=>now(),
        //     'status'=>false,
        //     'leave_company'=>'',
        //     ]);

        DB::table('staffs')->insert([
            'id'=>'2019040101',
            'staffname'=>'杨蓓',
            'englishname'=>'BeiYang',
            'department_id'=>'6',
            'position_id'=>'5',
            'department_name'=>'财务部',
            'position_name'=>'主管',
            'join_company'=>'2019-04-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        // 外教
        DB::table('staffs')->insert([
            'id'=>'2016082901',
            'teacher_id'=>'1',
            'staffname'=>'Stephan Mukai',
            'englishname'=>'StephanMaKai',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2016-08-29',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018080101',
            'teacher_id'=>'2',
            'staffname'=>'Dieter Michael Woehrle',
            'englishname'=>'DieterWoehrle',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2018-08-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2017090101',
            'teacher_id'=>'3',
            'staffname'=>'Sarah Vetter',
            'englishname'=>'Sarah',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2017-09-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018070102',
            'teacher_id'=>'4',
            'staffname'=>'Rosalia Romas Guerra',
            'englishname'=>'Rosalia',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2018-07-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018010101',
            'teacher_id'=>'5',
            'staffname'=>'James Cody Wilson',
            'englishname'=>'James',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2018-01-01',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2018092001',
            'teacher_id'=>'6',
            'staffname'=>'Nicole Jillian Heldt',
            'englishname'=>'HeldtNicole',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2018-09-20',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019021801',
            'teacher_id'=>'7',
            'staffname'=>'Emma Case',
            'englishname'=>'EmmaCase',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2019-02-18',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019021802',
            'teacher_id'=>'8',
            'staffname'=>'Anastasiia Eremina',
            'englishname'=>'Anastasia',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2019-02-18',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

        DB::table('staffs')->insert([
            'id'=>'2019022201',
            'teacher_id'=>'9',
            'staffname'=>'Joel Boulet',
            'englishname'=>'JoelBoulet',
            'department_id'=>'3',
            'position_id'=>'6',
            'department_name'=>'外教部',
            'position_name'=>'全职教师',
            'join_company'=>'2019-02-22',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true, 'leave_company'=>'2038-01-01',
            ]);

    }
}
