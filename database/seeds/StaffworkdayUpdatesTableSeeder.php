<?php

use Illuminate\Database\Seeder;

class StaffworkdayUpdatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 陈嘉琛
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'1',
            'staff_id'=>'2018050701',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-05-07','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'2',
            'staff_id'=>'2018050701',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-05-07','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'3',
            'staff_id'=>'2018050701',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-05-07','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'4',
            'staff_id'=>'2018050701',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-05-07','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'5',
            'staff_id'=>'2018050701',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-05-07','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'6',
            'staff_id'=>'2018050701',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-05-07','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'7',
            'staff_id'=>'2018050701',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-05-07','end_date'=>'2038-01-01',
        ]);

        // 舒旻
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'8',
            'staff_id'=>'2011110101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2011-11-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'9',
            'staff_id'=>'2011110101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2011-11-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'10',
            'staff_id'=>'2011110101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2011-11-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'11',
            'staff_id'=>'2011110101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2011-11-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'12',
            'staff_id'=>'2011110101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2011-11-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'13',
            'staff_id'=>'2011110101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2011-11-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'14',
            'staff_id'=>'2011110101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2011-11-01','end_date'=>'2038-01-01',
        ]);

        // 陈嘉琛
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'15',
            'staff_id'=>'2019010101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'16',
            'staff_id'=>'2019010101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'17',
            'staff_id'=>'2019010101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'18',
            'staff_id'=>'2019010101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'19',
            'staff_id'=>'2019010101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'20',
            'staff_id'=>'2019010101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'21',
            'staff_id'=>'2019010101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-01-01','end_date'=>'2038-01-01',
        ]);

        // 朱莹
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'22',
            'staff_id'=>'2016031601',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2016-03-16','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'23',
            'staff_id'=>'2016031601',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2016-03-16','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'24',
            'staff_id'=>'2016031601',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2016-03-16','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'25',
            'staff_id'=>'2016031601',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2016-03-16','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'26',
            'staff_id'=>'2016031601',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2016-03-16','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'27',
            'staff_id'=>'2016031601',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2016-03-16','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'28',
            'staff_id'=>'2016031601',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2016-03-16','end_date'=>'2038-01-01',
        ]);


        // 张琳
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'29',
            'staff_id'=>'2017070301',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-03','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'30',
            'staff_id'=>'2017070301',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-03','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'31',
            'staff_id'=>'2017070301',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-03','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'32',
            'staff_id'=>'2017070301',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-03','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'33',
            'staff_id'=>'2017070301',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-03','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'34',
            'staff_id'=>'2017070301',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-03','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'35',
            'staff_id'=>'2017070301',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-03','end_date'=>'2038-01-01',
        ]);


        // 沈艳萍
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'36',
            'staff_id'=>'2017070101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'37',
            'staff_id'=>'2017070101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'38',
            'staff_id'=>'2017070101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'39',
            'staff_id'=>'2017070101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'40',
            'staff_id'=>'2017070101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'41',
            'staff_id'=>'2017070101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'42',
            'staff_id'=>'2017070101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-07-01','end_date'=>'2038-01-01',
        ]);

        // 郑欣
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'43',
            'staff_id'=>'2018070101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'44',
            'staff_id'=>'2018070101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'45',
            'staff_id'=>'2018070101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'46',
            'staff_id'=>'2018070101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'47',
            'staff_id'=>'2018070101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'48',
            'staff_id'=>'2018070101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-07-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'49',
            'staff_id'=>'2018070101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-07-01','end_date'=>'2038-01-01',
        ]);


        // 张颖
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'50',
            'staff_id'=>'2017030101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'51',
            'staff_id'=>'2017030101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'52',
            'staff_id'=>'2017030101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'53',
            'staff_id'=>'2017030101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'54',
            'staff_id'=>'2017030101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'09:00:00',
            'home_time'=>'16:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'55',
            'staff_id'=>'2017030101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'56',
            'staff_id'=>'2017030101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);


        // 贡雯
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'57',
            'staff_id'=>'2018060401',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'58',
            'staff_id'=>'2018060401',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'59',
            'staff_id'=>'2018060401',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'60',
            'staff_id'=>'2018060401',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'61',
            'staff_id'=>'2018060401',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'62',
            'staff_id'=>'2018060401',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'63',
            'staff_id'=>'2018060401',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-06-04','end_date'=>'2038-01-01',
        ]);


        // 卢宁
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'64',
            'staff_id'=>'2017030102',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'65',
            'staff_id'=>'2017030102',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'66',
            'staff_id'=>'2017030102',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'67',
            'staff_id'=>'2017030102',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'68',
            'staff_id'=>'2017030102',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'69',
            'staff_id'=>'2017030102',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'70',
            'staff_id'=>'2017030102',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-03-01','end_date'=>'2038-01-01',
        ]);

        // 江焕新
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'71',
            'staff_id'=>'2018090101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-09-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'72',
            'staff_id'=>'2018090101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-09-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'73',
            'staff_id'=>'2018090101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-09-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'74',
            'staff_id'=>'2018090101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-09-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'75',
            'staff_id'=>'2018090101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-09-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'76',
            'staff_id'=>'2018090101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-09-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'77',
            'staff_id'=>'2018090101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-09-01','end_date'=>'2038-01-01',
        ]);

        // 王盛
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'78',
            'staff_id'=>'2018112601',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-11-26','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'79',
            'staff_id'=>'2018112601',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-11-26','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'80',
            'staff_id'=>'2018112601',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-11-26','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'81',
            'staff_id'=>'2018112601',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-11-26','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'82',
            'staff_id'=>'2018112601',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-11-26','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'83',
            'staff_id'=>'2018112601',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-11-26','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'84',
            'staff_id'=>'2018112601',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-11-26','end_date'=>'2038-01-01',
        ]);


        // 范佳秋
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'85',
            'staff_id'=>'2017040501',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'6.00',
            'work_time'=>'11:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-04-05','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'86',
            'staff_id'=>'2017040501',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'5.00',
            'work_time'=>'13:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-04-05','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'87',
            'staff_id'=>'2017040501',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-04-05','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'88',
            'staff_id'=>'2017040501',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-04-05','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'89',
            'staff_id'=>'2017040501',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'9.00',
            'work_time'=>'10:00:00',
            'home_time'=>'20:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-04-05','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'90',
            'staff_id'=>'2017040501',
            'workday_name'=>'六',
            'is_work'=>true, 'duration'=>'10.00',
            'work_time'=>'08:00:00',
            'home_time'=>'19:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-04-05','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'91',
            'staff_id'=>'2017040501',
            'workday_name'=>'日',
            'is_work'=>true, 'duration'=>'10.00',
            'work_time'=>'08:00:00',
            'home_time'=>'19:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-04-05','end_date'=>'2038-01-01',
        ]);

        // 侯春燕
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'92',
            'staff_id'=>'2015060401',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2015-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'93',
            'staff_id'=>'2015060401',
            'workday_name'=>'二',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2015-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'94',
            'staff_id'=>'2015060401',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2015-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'95',
            'staff_id'=>'2015060401',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2015-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'96',
            'staff_id'=>'2015060401',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2015-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'97',
            'staff_id'=>'2015060401',
            'workday_name'=>'六',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2015-06-04','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'98',
            'staff_id'=>'2015060401',
            'workday_name'=>'日',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2015-06-04','end_date'=>'2038-01-01',
        ]);

        // JasmxnJin
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'99',
            'staff_id'=>'2017010101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'100',
            'staff_id'=>'2017010101',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'101',
            'staff_id'=>'2017010101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'102',
            'staff_id'=>'2017010101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'103',
            'staff_id'=>'2017010101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'104',
            'staff_id'=>'2017010101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'105',
            'staff_id'=>'2017010101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // MichelleZhang
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'106',
            'staff_id'=>'2019032501',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-25','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'107',
            'staff_id'=>'2019032501',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-25','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'108',
            'staff_id'=>'2019032501',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-25','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'109',
            'staff_id'=>'2019032501',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-25','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'110',
            'staff_id'=>'2019032501',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-25','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'111',
            'staff_id'=>'2019032501',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-25','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'112',
            'staff_id'=>'2019032501',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-25','end_date'=>'2038-01-01',
        ]);

        // Rosalia
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'113',
            'staff_id'=>'2017010102',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'114',
            'staff_id'=>'2017010102',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'115',
            'staff_id'=>'2017010102',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'116',
            'staff_id'=>'2017010102',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'117',
            'staff_id'=>'2017010102',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'118',
            'staff_id'=>'2017010102',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'119',
            'staff_id'=>'2017010102',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // AmieeHu
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'120',
            'staff_id'=>'2017010103',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'121',
            'staff_id'=>'2017010103',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'122',
            'staff_id'=>'2017010103',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'123',
            'staff_id'=>'2017010103',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'124',
            'staff_id'=>'2017010103',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'125',
            'staff_id'=>'2017010103',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'126',
            'staff_id'=>'2017010103',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // ThomasBacker
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'127',
            'staff_id'=>'2017010104',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'128',
            'staff_id'=>'2017010104',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'129',
            'staff_id'=>'2017010104',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'130',
            'staff_id'=>'2017010104',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'131',
            'staff_id'=>'2017010104',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'132',
            'staff_id'=>'2017010104',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'133',
            'staff_id'=>'2017010104',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // ShirleyLi
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'134',
            'staff_id'=>'2017010105',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'135',
            'staff_id'=>'2017010105',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'136',
            'staff_id'=>'2017010105',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'137',
            'staff_id'=>'2017010105',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'138',
            'staff_id'=>'2017010105',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'139',
            'staff_id'=>'2017010105',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'140',
            'staff_id'=>'2017010105',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // AsaSmithXue
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'141',
            'staff_id'=>'2019030101',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'142',
            'staff_id'=>'2019030101',
            'workday_name'=>'二',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'143',
            'staff_id'=>'2019030101',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'144',
            'staff_id'=>'2019030101',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'145',
            'staff_id'=>'2019030101',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'146',
            'staff_id'=>'2019030101',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'147',
            'staff_id'=>'2019030101',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2019-03-01','end_date'=>'2038-01-01',
        ]);

        // GingerJiao
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'148',
            'staff_id'=>'2017010106',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'149',
            'staff_id'=>'2017010106',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'150',
            'staff_id'=>'2017010106',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'151',
            'staff_id'=>'2017010106',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'152',
            'staff_id'=>'2017010106',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'153',
            'staff_id'=>'2017010106',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'154',
            'staff_id'=>'2017010106',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // FinnFeng
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'155',
            'staff_id'=>'2017010107',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'156',
            'staff_id'=>'2017010107',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'157',
            'staff_id'=>'2017010107',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'158',
            'staff_id'=>'2017010107',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'159',
            'staff_id'=>'2017010107',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'160',
            'staff_id'=>'2017010107',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'161',
            'staff_id'=>'2017010107',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // SherryXiong
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'162',
            'staff_id'=>'2017010108',
            'workday_name'=>'一',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'163',
            'staff_id'=>'2017010108',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'164',
            'staff_id'=>'2017010108',
            'workday_name'=>'三',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'165',
            'staff_id'=>'2017010108',
            'workday_name'=>'四',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'166',
            'staff_id'=>'2017010108',
            'workday_name'=>'五',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'167',
            'staff_id'=>'2017010108',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'168',
            'staff_id'=>'2017010108',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // RyanWu
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'169',
            'staff_id'=>'2017010109',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'170',
            'staff_id'=>'2017010109',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'4.00',
            'work_time'=>'13:00:00',
            'home_time'=>'17:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'171',
            'staff_id'=>'2017010109',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);


        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'172',
            'staff_id'=>'2017010109',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);


        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'173',
            'staff_id'=>'2017010109',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'4.50',
            'work_time'=>'13:00:00',
            'home_time'=>'17:30:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'174',
            'staff_id'=>'2017010109',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'175',
            'staff_id'=>'2017010109',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // Sarah
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'176',
            'staff_id'=>'2017010110',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'177',
            'staff_id'=>'2017010110',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'178',
            'staff_id'=>'2017010110',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'179',
            'staff_id'=>'2017010110',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'180',
            'staff_id'=>'2017010110',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'181',
            'staff_id'=>'2017010110',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'182',
            'staff_id'=>'2017010110',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2017-01-01','end_date'=>'2038-01-01',
        ]);

        // James
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'183',
            'staff_id'=>'2018010110',
            'workday_name'=>'一',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'184',
            'staff_id'=>'2018010110',
            'workday_name'=>'二',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-01-01','end_date'=>'2038-01-01',
        ]);
        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'185',
            'staff_id'=>'2018010110',
            'workday_name'=>'三',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'186',
            'staff_id'=>'2018010110',
            'workday_name'=>'四',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'187',
            'staff_id'=>'2018010110',
            'workday_name'=>'五',
            'is_work'=>true, 'duration'=>'8.00',
            'work_time'=>'09:00:00',
            'home_time'=>'18:00:00',
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'188',
            'staff_id'=>'2018010110',
            'workday_name'=>'六',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-01-01','end_date'=>'2038-01-01',
        ]);

        DB::table('staffworkday_updates')->insert(['staffworkday_id'=>'189',
            'staff_id'=>'2018010110',
            'workday_name'=>'日',
            'is_work'=>false,
            'work_time'=>null,
            'home_time'=>null,
            'created_at'=>now(),
            'updated_at'=>now(),'start_date'=>'2018-01-01','end_date'=>'2038-01-01',
        ]);
    }
}
