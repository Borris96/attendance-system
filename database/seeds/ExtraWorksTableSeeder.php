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
        DB::table('extra_works')->insert([
            'staff_id'=>'2016031601',
            'extra_work_type'=>'带薪',
            'extra_work_start_time'=>'2019-03-11 21:00:00',
            'extra_work_end_time'=>'2019-03-11 23:00',
            'duration'=>'2.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2016031601',
            'extra_work_type'=>'带薪',
            'extra_work_start_time'=>'2019-03-13 19:00:00',
            'extra_work_end_time'=>'2019-03-13 20:00:00',
            'duration'=>'1.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('extra_works')->insert([
            'staff_id'=>'2016031601',
            'extra_work_type'=>'带薪',
            'extra_work_start_time'=>'2019-03-21 08:00:00',
            'extra_work_end_time'=>'2019-03-21 09:00:00',
            'duration'=>'1.00',
            'approve'=>false,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
