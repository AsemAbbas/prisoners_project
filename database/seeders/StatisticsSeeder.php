<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatisticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $belongs = [
            ['order_by' => '1', 'statistic_type' => 'إجمالي الأسرى في سجون الإحتلال', 'statistic_number' => '8494'],
            ['order_by' => '2', 'statistic_type' => 'الأسيرات', 'statistic_number' => '206'],
            ['order_by' => '3', 'statistic_type' => 'كبار السن (فوق 60 سنة)', 'statistic_number' => '163'],
            ['order_by' => '4', 'statistic_type' => 'الأسرى الأشبال', 'statistic_number' => '100'],
            ['order_by' => '5', 'statistic_type' => 'الأسرى المؤبدات', 'statistic_number' => '588'],
            ['order_by' => '6', 'statistic_type' => 'الأحكام العالية (فوق 10 سنوات)', 'statistic_number' => '100'],
            ['order_by' => '7', 'statistic_type' => 'الأسرى الإداريون', 'statistic_number' => '100'],
        ];
        DB::table('statistics')->insert($belongs);
    }
}
