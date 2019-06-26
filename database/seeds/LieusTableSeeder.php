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
            'staff_id'=>'2018090101',
            'total_time'=>'8.00',
            'remaining_time'=>'0.00',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lieus')->insert([
            'staff_id'=>'2011110101',
            'total_time'=>'8.00',
            'remaining_time'=>'0.00',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
