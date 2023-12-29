<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $cities = [
            ['city_name' => 'الخليل'],
            ['city_name' => 'طولكرم'],
            ['city_name' => 'الخط الأخضر'],
            ['city_name' => 'رام الله'],
            ['city_name' => 'قلقيلية'],
            ['city_name' => 'اريحا'],
            ['city_name' => 'غزة'],
            ['city_name' => 'جنين'],
            ['city_name' => 'القدس'],
            ['city_name' => 'نابلس'],
            ['city_name' => 'جباليا'],
            ['city_name' => 'خانيونس'],
            ['city_name' => 'رفح'],
            ['city_name' => 'طوباس'],
            ['city_name' => 'بيت لحم'],
            ['city_name' => 'سلفيت'],
            ['city_name' => 'دير البلح'],
        ];
        DB::table('cities')->insert($cities);
    }
}
