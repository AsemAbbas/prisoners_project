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
            ['statistic_type' => 'الأسرى الإجمالي','statistic_number' => '100'],
            ['statistic_type' => 'أسرى وفاء الأحرار','statistic_number' => '100'],
            ['statistic_type' => 'الأسيرات','statistic_number' => '100'],
            ['statistic_type' => 'الأسرى الأشبال','statistic_number' => '100'],
            ['statistic_type' => 'الأسرى المرضى','statistic_number' => '100'],
            ['statistic_type' => 'الأسرى المؤبدات','statistic_number' => '100'],
            ['statistic_type' => 'الأسرى الإداريون','statistic_number' => '100'],
            ['statistic_type' => 'أسرى 48','statistic_number' => '100'],
            ['statistic_type' => 'أسرى القدس','statistic_number' => '100'],
            ['statistic_type' => 'أسرى الدوريات','statistic_number' => '100'],
            ['statistic_type' => 'عمداء الأسرى','statistic_number' => '100'],
        ];
        DB::table('statistics')->insert($belongs);
    }
}
