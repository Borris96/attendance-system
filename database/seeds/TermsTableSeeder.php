<?php

use Illuminate\Database\Seeder;

class TermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('terms')->insert([
            'term_name'=>'2019 Spring',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('terms')->insert([
            'term_name'=>'2019 Summer 1',
            'start_date'=>'2019-07-09',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('terms')->insert([
            'term_name'=>'2019 Summer 2',
            'start_date'=>'2019-08-05',
            'end_date'=>'2019-08-31',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
