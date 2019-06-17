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
    }
}
