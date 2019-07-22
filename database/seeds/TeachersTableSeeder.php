<?php

use Illuminate\Database\Seeder;

class TeachersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('teachers')->insert([
            'id'=>'1',
            'staff_id'=>'2017010102',
            // 'englishname'=>'Rosalia',


            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2017-01-01',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'2',
            'staff_id'=>'2017010110',
            // 'englishname'=>'Sarah',


            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2017-01-01',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'3',
            'staff_id'=>'2018010110',
            // 'englishname'=>'James',


            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2018-01-01',
            'leave_date'=>'2038-01-01',
            ]);
    }
}
