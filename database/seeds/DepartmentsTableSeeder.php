<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $department_names = [
        '客服部',
        '教材部',
        '外教部',
        '网课部',
        '人事行政部',
        '财务部',
        '其他',
    ];

    public function run()
    {
        foreach ($this->department_names as $department_name){
            DB::table('departments')->insert([
                'department_name'=>$department_name,
                // 'created_at' => date('Y-m-d H:i:s',time()),
            ]);
        }
    }


}
