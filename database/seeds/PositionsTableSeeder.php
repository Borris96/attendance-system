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
        '助理', // 1
        '经理', // 2
        '文员', // 3
        '主任', // 4
        '主管', // 5
        '教师', // 6
        '设计', // 7
        '兼职', // 8
        '兼职教师', // 9
        '实习生', // 10
        '其他', // 11
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
