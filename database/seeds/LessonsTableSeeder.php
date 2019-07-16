<?php

use Illuminate\Database\Seeder;

class LessonsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lessons')->insert([
            'lesson_name'=>'K1-1',
            'classroom'=>'1',
            'day'=>'Fri',
            'start_time'=>'18:00:00',
            'end_time'=>'20:00:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'1;
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'K2-1',
            'classroom'=>'1',
            'day'=>'Sat',
            'start_time'=>'10:00:00',
            'end_time'=>'11:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'1;
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'G1-1',
            'classroom'=>'1',
            'day'=>'Sat',
            'start_time'=>'13:00:00',
            'end_time'=>'15:00:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'1;
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'G1-2',
            'classroom'=>'1',
            'day'=>'Sat',
            'start_time'=>'16:00:00',
            'end_time'=>'17:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'1;
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'G2-1',
            'classroom'=>'1',
            'day'=>'Sat',
            'start_time'=>'18:00:00',
            'end_time'=>'19:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'1;
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'G2-1',
            'classroom'=>'2',
            'day'=>'Fri',
            'start_time'=>'16:00:00',
            'end_time'=>'17:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'1;
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'G2-2',
            'classroom'=>'2',
            'day'=>'Sat',
            'start_time'=>'08:00:00',
            'end_time'=>'09:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'2',
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'SAT',
            'classroom'=>'2',
            'day'=>'Sat',
            'start_time'=>'10:00:00',
            'end_time'=>'12:00:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'2',
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'Bridge',
            'classroom'=>'2',
            'day'=>'Sat',
            'start_time'=>'13:00:00',
            'end_time'=>'15:00:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'1',
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'FW',
            'classroom'=>'3',
            'day'=>'Sat',
            'start_time'=>'11:30:00',
            'end_time'=>'13:30:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'1',
            'term_id'=>'1',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);



// 第二学期的课
        DB::table('lessons')->insert([
            'lesson_name'=>'F1-1',
            'classroom'=>'1',
            'day'=>'Mon',
            'start_time'=>'18:00:00',
            'end_time'=>'20:00:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'1;
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'F2-1',
            'classroom'=>'1',
            'day'=>'Mon',
            'start_time'=>'10:00:00',
            'end_time'=>'11:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'1;
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'F1-1',
            'classroom'=>'1',
            'day'=>'Mon',
            'start_time'=>'13:00:00',
            'end_time'=>'15:00:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'1;
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'F1-2',
            'classroom'=>'1',
            'day'=>'Wed',
            'start_time'=>'16:00:00',
            'end_time'=>'17:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'1;
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'F2-1',
            'classroom'=>'1',
            'day'=>'Wed',
            'start_time'=>'18:00:00',
            'end_time'=>'19:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'1;
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'F2-1',
            'classroom'=>'2',
            'day'=>'Wed',
            'start_time'=>'16:00:00',
            'end_time'=>'17:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'1;
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'F2-2',
            'classroom'=>'2',
            'day'=>'Wed',
            'start_time'=>'08:00:00',
            'end_time'=>'09:30:00',
            'duration'=>'1.5',
            // 'teacher_id'=>'2',
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'FAT',
            'classroom'=>'2',
            'day'=>'Fri',
            'start_time'=>'10:00:00',
            'end_time'=>'12:00:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'2',
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'Fridge',
            'classroom'=>'2',
            'day'=>'Fri',
            'start_time'=>'13:00:00',
            'end_time'=>'15:00:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'1',
            'term_id'=>'2',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        DB::table('lessons')->insert([
            'lesson_name'=>'FFW',
            'classroom'=>'3',
            'day'=>'Fri',
            'start_time'=>'11:30:00',
            'end_time'=>'13:30:00',
            'duration'=>'2.0',
            // 'teacher_id'=>'1',
            'term_id'=>'3',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);
    }
}
