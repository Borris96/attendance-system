<?php

use Illuminate\Database\Seeder;

class CardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全职员工
        // JoyZhou
        DB::table('cards')->insert([
            'staff_id'=>'2019070101',
            'card_number'=>'6217920155114239',
            'bank'=>'浦发三林或者东绣路支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // FannyFan
        DB::table('cards')->insert([
            'staff_id'=>'2017040501',
            'card_number'=>'6217920119888373',
            'bank'=>'浦发芷江支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // ReneeChen
        DB::table('cards')->insert([
            'staff_id'=>'2018050701',
            'card_number'=>'6217920119888373',
            'bank'=>'浦发静安支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // ConnieTang
        DB::table('cards')->insert([
            'staff_id'=>'2018082901',
            'card_number'=>'6217920119807720',
            'bank'=>'浦发宜川支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // AlinaLi
        DB::table('cards')->insert([
            'staff_id'=>'2019070801',
            'card_number'=>'6217920121889815',
            'bank'=>'浦发肇嘉浜路支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // SherryXia
        DB::table('cards')->insert([
            'staff_id'=>'2019071501',
            'card_number'=>'6217920121869742',
            'bank'=>'浦发闵行支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // LucyHou
        DB::table('cards')->insert([
            'staff_id'=>'2019070102',
            'card_number'=>'6217930175028269',
            'bank'=>'浦发彭浦支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // SunnyShu
        DB::table('cards')->insert([
            'staff_id'=>'2011110101',
            'card_number'=>'6217920154830314',
            'bank'=>'浦发上海延长中路支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // CindyZhu
        DB::table('cards')->insert([
            'staff_id'=>'2016031601',
            'card_number'=>'6217920119763378',
            'bank'=>'浦发曹杨支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // AmiZhang
        DB::table('cards')->insert([
            'staff_id'=>'2017070301',
            'card_number'=>'6217930175019680',
            'bank'=>'浦发上南路支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // CarolShen
        DB::table('cards')->insert([
            'staff_id'=>'2017070101',
            'card_number'=>'6217920120037481',
            'bank'=>'浦发金山支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // CocoZheng
        DB::table('cards')->insert([
            'staff_id'=>'2018070101',
            'card_number'=>'6217920120025544',
            'bank'=>'浦发上海长江西路支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // JessieYu

        // LillianZhang
        DB::table('cards')->insert([
            'staff_id'=>'2017030101',
            'card_number'=>'6217920120186627',
            'bank'=>'浦发齐河路支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // EuniceGong
        DB::table('cards')->insert([
            'staff_id'=>'2018060401',
            'card_number'=>'6217920120114587',
            'bank'=>'浦发漕宝支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // LucyLu
        DB::table('cards')->insert([
            'staff_id'=>'2017030102',
            'card_number'=>'6217920106778348',
            'bank'=>'浦发许昌路支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // JayJiang
        DB::table('cards')->insert([
            'staff_id'=>'2018090101',
            'card_number'=>'6217920120063230',
            'bank'=>'浦发大众大厦支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // MikeWang
        DB::table('cards')->insert([
            'staff_id'=>'2018112601',
            'card_number'=>'6217920120485854',
            'bank'=>'浦发古美支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);

        // JessieYang
        DB::table('cards')->insert([
            'staff_id'=>'2019070103',
            'card_number'=>'6217920120084525',
            'bank'=>'浦发四川北路支行',
            'created_at'=>now(),
            'updated_at'=>now(),
            ]);





    }
}
