<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\General\database\seeders\EnumerationSeeder;
use Modules\User\database\seeders\PermissionSeeder;
use Modules\User\database\seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        $this->call(RoleSeeder::class);
//        $this->call(PermissionSeeder::class);
        $this->call(EnumerationSeeder::class);
    }
}
