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
            ['order_by' => '1', 'statistic_type' => 'الأسرى الإجمالي', 'statistic_number' => '100'],
            ['order_by' => '2', 'statistic_type' => 'الأسيرات', 'statistic_number' => '100'],
            ['order_by' => '3', 'statistic_type' => 'كبار السن (فوق 60 سنة)', 'statistic_number' => '100'],
            ['order_by' => '4', 'statistic_type' => 'الأسرى الأشبال', 'statistic_number' => '100'],
            ['order_by' => '5', 'statistic_type' => 'الأسرى المؤبدات', 'statistic_number' => '100'],
            ['order_by' => '6', 'statistic_type' => 'الأحكام العالية (فوق 10 سنوات)', 'statistic_number' => '100'],
            ['order_by' => '7', 'statistic_type' => 'الأسرى الإداريون', 'statistic_number' => '100'],
            ['order_by' => '8', 'statistic_type' => 'شهداء الحركة الأسيرة', 'statistic_number' => '100'],
        ];
        DB::table('statistics')->insert($belongs);
    }
}
