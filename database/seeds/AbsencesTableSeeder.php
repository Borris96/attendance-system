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
        // DB::table('absences')->insert([
        //     'staff_id'=>'2016031601',
        //     'absence_type'=>'调休',
        //     'absence_start_time'=>'2019-03-15 10:00:00',
        //     'absence_end_time'=>'2019-03-15 17:00:00',
        //     'duration'=>'6.00',
        //     'approve'=>true,
        //     'created_at'=>now(),
        //     'updated_at'=>now(),
        //     ]);

        DB::table('absences')->insert([
            'staff_id'=>'2016031601',
            'absence_type'=>'病假',
            'absence_start_time'=>'2019-03-08 15:00:00',
            'absence_end_time'=>'2019-03-08 18:00:00',
            'duration'=>'3.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2016031601',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-13 10:00:00',
            'absence_end_time'=>'2019-03-15 17:00',
            'duration'=>'22.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
