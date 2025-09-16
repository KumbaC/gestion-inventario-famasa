<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Type_coin as TypeCoin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TypeCoinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        TypeCoin::insert([
            [
                'name' => 'Dólar',
                'symbol' => 'USD',
            ],
            [
                'name' => 'Euro',
                'symbol' => 'EUR', 
            ],
            [
                'name' => 'Libra Esterlina',
                'symbol' => 'GBP',  
            ],
            [
                'name' => 'Yen Japonés',
                'symbol' => 'JPY',
            ],
            [
                'name' => 'Bolivares',
                'symbol' => 'VES',
            ],
        ]);
        
    }
}
