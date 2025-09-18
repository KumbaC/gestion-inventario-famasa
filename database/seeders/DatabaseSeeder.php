<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SettingsSeeder;
use App\Models\Box;
use App\Models\Client;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            RolePermissionSeeder::class,
            ClientSeeder::class,
            TypeCoinSeeder::class,
            BoxSeeder::class,
            SettingsSeeder::class,
        ]);

    }
}
