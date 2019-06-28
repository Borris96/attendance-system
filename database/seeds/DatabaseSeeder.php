<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */


    public function run()
    {
        Model::unguard();
        $this->call(UsersTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(PositionsTableSeeder::class);
        $this->call(StaffsTableSeeder::class);
        $this->call(StaffworkdaysTableSeeder::class);
        $this->call(ExtraWorksTableSeeder::class);
        $this->call(AbsencesTableSeeder::class);
        $this->call(LieusTableSeeder::class);
        // $this->call(HolidaysTableSeeder::class);
        Model::reguard();
    }


}
