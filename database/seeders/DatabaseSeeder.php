<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(UserSeeder::class);
        $this->call(CitiesSeeder::class);
        $this->call(BelongsSeeder::class);
        $this->call(RelationshipsSeeder::class);
        $this->call(StatisticsSeeder::class);
        $this->call(TownsSeeder::class);
    }
}
