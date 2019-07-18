<?php

use Illuminate\Database\Seeder;

class LessonUpdatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lesson_updates')->insert([
            'lesson_id'=>1,
            'duration'=>'2.0',
            'day'=>'Fri',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>2,
            'duration'=>'1.5',
            'day'=>'Sat',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>3,
            'duration'=>'2.0',
            'day'=>'Sat',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>4,
            'duration'=>'1.5',
            'day'=>'Sat',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>5,
            'duration'=>'1.5',
            'day'=>'Sat',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>6,
            'duration'=>'1.5',
            'day'=>'Fri',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>7,
            'duration'=>'1.5',
            'day'=>'Sat',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>8,
            'duration'=>'2.0',
            'day'=>'Sat',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>9,
            'duration'=>'2.0',
            'day'=>'Sat',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>10,
            'duration'=>'2.0',
            'day'=>'Sat',
            'start_date'=>'2018-12-15',
            'end_date'=>'2019-06-30',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>11,
            'duration'=>'2.0',
            'day'=>'Mon',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>12,
            'duration'=>'1.5',
            'day'=>'Mon',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>13,
            'duration'=>'2.0',
            'day'=>'Mon',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>14,
            'duration'=>'1.5',
            'day'=>'Wed',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>15,
            'duration'=>'1.5',
            'day'=>'Wed',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>16,
            'duration'=>'1.5',
            'day'=>'Wed',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>17,
            'duration'=>'1.5',
            'day'=>'Wed',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>18,
            'duration'=>'2.0',
            'day'=>'Fri',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>19,
            'duration'=>'2.0',
            'day'=>'Fri',
            'start_date'=>'2019-07-05',
            'end_date'=>'2019-08-02',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);

        DB::table('lesson_updates')->insert([
            'lesson_id'=>20,
            'duration'=>'2.0',
            'day'=>'Fri',
            'start_date'=>'2019-08-05',
            'end_date'=>'2019-08-31',
            'created_at'=>now(),
            'updated_at'=>now(),'teacher_id'=>null,
            ]);
    }
}
