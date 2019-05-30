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
        '人事部',
        '客服部',
        '教材部',
        '网课部',
    ];

    public function run()
    {
        foreach ($this->department_names as $key => $department_name){
            DB::table('departments')->insert([
                'id'=>$key,
                'department_name'=>$department_name,
                // 'created_at' => date('Y-m-d H:i:s',time()),
            ]);
        }
    }


}
