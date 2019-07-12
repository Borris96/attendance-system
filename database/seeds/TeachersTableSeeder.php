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
            ]);

        DB::table('teachers')->insert([
            'id'=>'2',
            'staff_id'=>'2017010110',
            // 'englishname'=>'Rosalia',


            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            ]);
    }
}
