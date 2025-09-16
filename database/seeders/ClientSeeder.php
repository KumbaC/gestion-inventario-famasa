<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::insert([
            [
                'name' => 'Pedro Perez',
                'identification' => '855555',
                'phone' => '5555',
            ],
            [
                'name' => 'Maria Lopez',
                'identification' => '255',
                'phone' => '66666999',
            ],
            [
                'name' => 'Juan Garcia',
                'identification' => '9855',
                'phone' => '777777525',
            ],
        ]);
        
    }
}