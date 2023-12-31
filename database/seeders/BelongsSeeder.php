<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BelongsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $belongs = [
            ['belong_name' => 'حماس'],
            ['belong_name' => 'فتح'],
            ['belong_name' => 'جهاد إسلامي'],
            ['belong_name' => 'جبهة شعبية'],
            ['belong_name' => 'جبهة ديمقراطية'],
        ];
        DB::table('belongs')->insert($belongs);
    }
}
