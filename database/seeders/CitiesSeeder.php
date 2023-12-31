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
            ['id' => 1, 'city_name' => 'الخليل'],
            ['id' => 2, 'city_name' => 'طولكرم'],
            ['id' => 4, 'city_name' => 'رام الله والبيرة'],
            ['id' => 5, 'city_name' => 'قلقيلية'],
            ['id' => 7, 'city_name' => 'غزة'],
            ['id' => 8, 'city_name' => 'جنين'],
            ['id' => 9, 'city_name' => 'القدس'],
            ['id' => 10, 'city_name' => 'نابلس'],
            ['id' => 12, 'city_name' => 'خانيونس'],
            ['id' => 13, 'city_name' => 'رفح'],
            ['id' => 15, 'city_name' => 'بيت لحم'],
            ['id' => 16, 'city_name' => 'سلفيت'],
            ['id' => 17, 'city_name' => 'دير البلح'],
            ['id' => 6, 'city_name' => 'اريحا والاغوار'],
            ['id' => 19, 'city_name' => 'طوباس والأغوار الشمالية'],
            ['id' => 14, 'city_name' => 'طوباس'],
            ['id' => 18, 'city_name' => 'شمال غزة'],
            ['id' => 3, 'city_name' => 'الخط الأخضر'],
        ];
        DB::table('cities')->insert($cities);
    }
}
