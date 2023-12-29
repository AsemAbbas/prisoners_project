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
            ['belong_name' => 'جبة شعبية'],
            ['belong_name' => 'جبة ديمقراطية'],
        ];
        DB::table('belongs')->insert($belongs);
    }
}
