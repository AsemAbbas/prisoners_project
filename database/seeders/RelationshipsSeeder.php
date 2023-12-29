<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RelationshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $relationships = [
            ['relationship_name' => 'اب'],
            ['relationship_name' => 'ام'],
            ['relationship_name' => 'اخ'],
            ['relationship_name' => 'اخت'],
            ['relationship_name' => 'زوج'],
            ['relationship_name' => 'زوجة'],
            ['relationship_name' => 'ابن'],
            ['relationship_name' => 'ابنه'],
        ];
        DB::table('relationships')->insert($relationships);
    }
}
