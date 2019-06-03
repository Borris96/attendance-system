<?php

use Illuminate\Database\Seeder;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $position_names = [
        '主任',
        '主管',
        '设计',
        '经理',
        '助理',
        '文员',
    ];

    public function run()
    {
        foreach ($this->position_names as $position_name){
            DB::table('positions')->insert([
                'position_name'=>$position_name,
                // 'created_at' => date('Y-m-d H:i:s',time()),
            ]);
        }
    }
}