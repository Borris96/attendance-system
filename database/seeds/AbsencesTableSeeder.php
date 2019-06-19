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

        DB::table('absences')->insert([
            'staff_id'=>'2011110101',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-07 09:00:00',
            'absence_end_time'=>'2019-03-07 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2011110101',
            'absence_type'=>'调休',
            'absence_start_time'=>'2019-03-21 09:00:00',
            'absence_end_time'=>'2019-03-21 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2011110101',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-12 09:00:00',
            'absence_end_time'=>'2019-03-12 13:00:00',
            'duration'=>'3.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2017030101',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-11 09:00:00',
            'absence_end_time'=>'2019-03-11 11:00:00',
            'duration'=>'2.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2017030101',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-12 13:00:00',
            'absence_end_time'=>'2019-03-12 16:00:00',
            'duration'=>'3.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2017030101',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-15 09:00:00',
            'absence_end_time'=>'2019-03-15 10:30:00',
            'duration'=>'1.50',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2017030102',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-08 12:00:00',
            'absence_end_time'=>'2019-03-08 18:00:00',
            'duration'=>'5.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2017070301',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-18 09:00:00',
            'absence_end_time'=>'2019-03-18 10:00:00',
            'duration'=>'1.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2018060401',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-18 09:00:00',
            'absence_end_time'=>'2019-03-18 11:30:00',
            'duration'=>'2.50',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2018070101',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-21 09:00:00',
            'absence_end_time'=>'2019-03-21 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2018070101',
            'absence_type'=>'病假',
            'absence_start_time'=>'2019-03-11 09:00:00',
            'absence_end_time'=>'2019-03-11 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2018090101',
            'absence_type'=>'调休',
            'absence_start_time'=>'2019-03-07 09:00:00',
            'absence_end_time'=>'2019-03-07 18:00:00',
            'duration'=>'8.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2018112601',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-26 16:00:00',
            'absence_end_time'=>'2019-03-26 18:00:00',
            'duration'=>'2.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('absences')->insert([
            'staff_id'=>'2018112601',
            'absence_type'=>'事假',
            'absence_start_time'=>'2019-03-14 13:00:00',
            'absence_end_time'=>'2019-03-14 18:00:00',
            'duration'=>'5.00',
            'approve'=>true,
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
