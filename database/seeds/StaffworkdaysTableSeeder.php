<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class StaffworkdaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 陈嘉琛
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018050701',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018050701',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018050701',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018050701',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018050701',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018050701',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018050701',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // 舒旻
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2011110101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2011110101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2011110101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2011110101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2011110101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2011110101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2011110101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        // 朱莹
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2016031601',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2016031601',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2016031601',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2016031601',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2016031601',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2016031601',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2016031601',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        // 张琳
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070301',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070301',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070301',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070301',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070301',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070301',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070301',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        // 沈艳萍
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017070101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // 郑欣
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018070101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018070101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018070101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018070101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018070101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018070101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018070101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        // 张颖
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        // 贡雯
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018060401',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018060401',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018060401',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018060401',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018060401',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018060401',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018060401',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        // 卢宁
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030102',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030102',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030102',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030102',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030102',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030102',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017030102',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // 江焕新
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018090101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018090101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018090101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018090101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018090101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018090101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018090101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // 王盛
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018112601',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018112601',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018112601',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018112601',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018112601',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018112601',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2018112601',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        // 范佳秋
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017040501',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'11:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017040501',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'5.00',
            'work_time'=>'13:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017040501',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017040501',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017040501',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'9.00',
            'work_time'=>'10:00:00',
            'home_time'=>'20:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017040501',
            'workday_name'=>'六',
            'is_work'=>true, 'duration'=>'10.00',
            'work_time'=>'08:00:00',
            'home_time'=>'19:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017040501',
            'workday_name'=>'日',
            'is_work'=>true, 'duration'=>'10.00',
            'work_time'=>'08:00:00',
            'home_time'=>'19:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // 侯春燕
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2015060401',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2015060401',
            'workday_name'=>'二',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2015060401',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2015060401',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2015060401',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2015060401',
            'workday_name'=>'六',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2015060401',
            'workday_name'=>'日',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // JasmxnJin
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // MichelleZhang
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019032501',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019032501',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019032501',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019032501',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019032501',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019032501',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019032501',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // Rosalia
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010102',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010102',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010102',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010102',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010102',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010102',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010102',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // AmieeHu
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010103',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010103',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010103',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010103',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010103',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010103',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010103',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // ThomasBacker
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010104',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010104',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010104',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010104',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010104',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010104',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010104',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // ShirleyLi
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010105',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010105',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010105',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010105',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010105',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010105',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010105',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // AsaSmithXue
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019030101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019030101',
            'workday_name'=>'二',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019030101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019030101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019030101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019030101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2019030101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // GingerJiao
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010106',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010106',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010106',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010106',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010106',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010106',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010106',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // FinnFeng
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010107',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010107',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010107',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010107',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010107',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010107',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010107',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // SherryXiong
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010108',
            'workday_name'=>'一',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010108',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010108',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010108',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010108',
            'workday_name'=>'五',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010108',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010108',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // RyanWu
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010109',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010109',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'4.00',
            'work_time'=>'13:00:00',
            'home_time'=>'17:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010109',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010109',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);


        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010109',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'4.50',
            'work_time'=>'13:00:00',
            'home_time'=>'17:30:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010109',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010109',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        // Sarah
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010110',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010110',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010110',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010110',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010110',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010110',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);

        DB::table('staffworkdays')->insert([
            'staff_id'=>'2017010110',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),
        ]);
    }
}
