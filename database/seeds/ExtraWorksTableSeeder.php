<?php

use Illuminate\Database\Seeder;

class ExtraWorksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('extra_works')->insert([
        //     'staff_id'=>'2016031601',
        //     'extra_work_type'=>'调休',
        //     'extra_work_start_time'=>'2019-03-09 09:00:00',
        //     'extra_work_end_time'=>'2019-03-09 18:00:00',
        //     'duration'=>'8.00',
        //     'approve'=>true,
        //     'created_at'=>now(),
        //     'updated_at'=>now(),
        //     ]);

        // DB::table('extra_works')->insert([
        //     'staff_id'=>'2016031601',
        //     'extra_work_type'=>'带薪',
        //     'extra_work_start_time'=>'2019-03-13 09:00:00',
        //     'extra_work_end_time'=>'2019-03-13 18:00:00',
        //     'duration'=>'8.00',
        //     'approve'=>true,
        //     'created_at'=>now(),
        //     'updated_at'=>now(),
        //     ]);

        // DB::table('extra_works')->insert([
        //     'staff_id'=>'2016031601',
        //     'extra_work_type'=>'带薪',
        //     'extra_work_start_time'=>'2019-03-21 08:00:00',
        //     'extra_work_end_time'=>'2019-03-21 09:00:00',
        //     'duration'=>'1.00',
        //     'approve'=>false,
        //     'created_at'=>now(),
        //     'updated_at'=>now(),
        //     ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2011110101',
            'extra_work_type'=>'调休',
            'extra_work_start_time'=>'2019-03-24 09:00:00',
            'extra_work_end_time'=>'2019-03-24 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2018090101',
            'extra_work_type'=>'调休',
            'extra_work_start_time'=>'2019-03-24 09:00:00',
            'extra_work_end_time'=>'2019-03-24 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2017010108',
            'extra_work_type'=>'带薪',
            'extra_work_start_time'=>'2019-03-14 18:00:00',
            'extra_work_end_time'=>'2019-03-14 19:30:00',
            'duration'=>'1.50',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2017010108',
            'extra_work_type'=>'带薪',
            'extra_work_start_time'=>'2019-03-28 18:00:00',
            'extra_work_end_time'=>'2019-03-28 19:00:00',
            'duration'=>'1.00',
            'approve'=>false,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2019010101',
            'extra_work_type'=>'调休',
            'extra_work_start_time'=>'2019-03-10 09:00:00',
            'extra_work_end_time'=>'2019-03-10 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2019010101',
            'extra_work_type'=>'调休',
            'extra_work_start_time'=>'2019-03-31 09:00:00',
            'extra_work_end_time'=>'2019-03-31 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2017010102',
            'extra_work_type'=>'测试',
            'extra_work_start_time'=>'2019-03-20 09:00:00',
            'extra_work_end_time'=>'2019-03-20 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2017010102',
            'extra_work_type'=>'带薪',
            'extra_work_start_time'=>'2019-03-21 09:00:00',
            'extra_work_end_time'=>'2019-03-21 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2017010102',
            'extra_work_type'=>'带薪',
            'extra_work_start_time'=>'2019-06-26 13:00:00',
            'extra_work_end_time'=>'2019-06-26 18:00:00',
            'duration'=>'5.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2017010102',
            'extra_work_type'=>'测试',
            'extra_work_start_time'=>'2019-06-27 13:00:00',
            'extra_work_end_time'=>'2019-06-27 18:00:00',
            'duration'=>'5.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2017010102',
            'extra_work_type'=>'带薪',
            'extra_work_start_time'=>'2019-07-24 09:00:00',
            'extra_work_end_time'=>'2019-07-24 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
