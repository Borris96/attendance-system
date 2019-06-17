<?php

use Illuminate\Database\Seeder;

class AbsencesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('absences')->insert([
            'staff_id'=>'2016031601',
            'extra_work_type'=>'调休',
            'extra_work_start_time'=>'2019-03-15 10:00:00',
            'extra_work_end_time'=>'2019-03-15 17:00:00',
            'duration'=>'6.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2016031601',
            'extra_work_type'=>'病假',
            'extra_work_start_time'=>'2019-03-08 15:00:00',
            'extra_work_end_time'=>'2019-03-08 18:00:00',
            'duration'=>'3.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2016031601',
            'extra_work_type'=>'事假',
            'extra_work_start_time'=>'2019-03-13 9:00:00',
            'extra_work_end_time'=>'2019-03-14 18:00',
            'duration'=>'16.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
