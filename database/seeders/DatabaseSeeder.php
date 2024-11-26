<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $path = base_path('database/thingy-seed.sql');
        $sql = file_get_contents($path);
        DB::unprepared($sql);
        DB::table('roles')->insert([
            ['role_id' => 1, 'name' => 'admin'], // Ensure admin role has ID 1
            ['role_id' => 2, 'name' => 'user'],  // Ensure user role has ID 2
        ]);
        $this->command->info('Database seeded!');
    }
}
