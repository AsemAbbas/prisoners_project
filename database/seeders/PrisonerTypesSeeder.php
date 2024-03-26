<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrisonerTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $belongs = [
            ['prisoner_type_name' => 'طوفان الأقصى - معاد اعتقاله'],
            ['prisoner_type_name' => 'متوقع حكم مرتفع'],
            ['prisoner_type_name' => 'مرضى أولوية'],
            ['prisoner_type_name' => 'وفاء الأحرار - معاد اعتقاله'],
            ['prisoner_type_name' => 'مفرج عنه ضمن صفقات طوفان الأقصى'],
            ['prisoner_type_name' => 'مفرج عنه ضمن صفقات طوفان الأقصى'],
        ];
        DB::table('prisoner_types')->insert($belongs);
    }
}
