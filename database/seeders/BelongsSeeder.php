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
            ['belong_name' => 'الجهاد الإسلامي'],
            ['belong_name' => 'الجبهة الشعبية'],
            ['belong_name' => 'الجبهة الديمقراطية'],
            ['belong_name' => 'فدا'],
            ['belong_name' => 'حزب الشعب'],
            ['belong_name' => 'مستقل'],
        ];
        DB::table('belongs')->insert($belongs);
    }
}
