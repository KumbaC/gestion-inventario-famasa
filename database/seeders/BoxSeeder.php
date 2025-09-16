<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Box;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BoxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Box::insert([
            [
                'name' => 'Caja Dolar',
                'amount' => '5000',
                'type_coin_id' => 1,
                'user_id' => 1,
            ],
            [
                'name' => 'Caja Euro',
                'amount' => '2500',
                'type_coin_id' => 2,
                'user_id' => 1,
            ],
            [
                'name' => 'Caja Inglesa',
                'amount' => '80',
                'type_coin_id' => 3,
                'user_id' => 1,
            ],
            [
                'name' => 'Caja Japonesa',
                'amount' => '1200',
                'type_coin_id' => 4,
                'user_id' => 1,
            ],
            [
                'name' => 'Caja efectivo',
                'amount' => '250',
                'type_coin_id' => 5,
                'user_id' => 1,
            ],
        ]);
        
    }
}