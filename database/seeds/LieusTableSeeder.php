<?php

use Illuminate\Database\Seeder;

class LieusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lieus')->insert([
            'staff_id'=>'2016031601',
            'total_time'=>'8.00',
            'remaining_time'=>'2.00',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
