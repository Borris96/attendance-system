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
            'staff_id'=>'2016082901',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2016-08-29',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'2',
            'staff_id'=>'2018080101',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2018-08-01',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'3',
            'staff_id'=>'2017090101',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2017-09-01',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'4',
            'staff_id'=>'2018070102',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2018-07-01',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'5',
            'staff_id'=>'2018010101',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2018-01-01',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'6',
            'staff_id'=>'2018092001',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2018-09-20',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'7',
            'staff_id'=>'2019021801',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2019-02-18',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'8',
            'staff_id'=>'2019021802',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2019-02-18',
            'leave_date'=>'2038-01-01',
            ]);

        DB::table('teachers')->insert([
            'id'=>'9',
            'staff_id'=>'2019022201',
            'created_at'=>now(),
            'updated_at'=>now(),
            'status'=>true,
            'join_date'=>'2019-02-22',
            'leave_date'=>'2038-01-01',
            ]);


    }
}
