<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::truncate();

        Location::insert([
            ['name' => 'Уилмингтон (Северная Каролина)'],
            ['name' => 'Портленд (Орегон)'],
            ['name' => 'Торонто'],
            ['name' => 'Варшава'],
            ['name' => 'Валенсия'],
            ['name' => 'Шанхай'],
        ]);
    }
}
