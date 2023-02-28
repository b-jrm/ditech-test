<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->truncateTable([
            'users',
        ]);

        $this->call([
            UserSeeder::class,
        ]);
    }

    function truncateTable(array $tables){
    	DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
    	foreach ($tables as $table) {
    		DB::table($table)->truncate();
    	}
    	DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
