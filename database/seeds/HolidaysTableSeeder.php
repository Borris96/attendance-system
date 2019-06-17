<?php

use Illuminate\Database\Seeder;

class HolidaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('holidays')->insert([
            'date'=>'2019-03-03',
            'holiday_type'=>'上班',
            'note'=>'',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('holidays')->insert([
            'date'=>'2019-03-04',
            'holiday_type'=>'休息',
            'note'=>'',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
