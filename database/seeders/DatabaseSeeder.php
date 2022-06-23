<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\FreezingRoom;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        User::truncate();
        Block::truncate();
        FreezingRoom::truncate();
        Location::truncate();


        User::factory()->count(1)->create();//login:test@gmail.com;pass:password;

        Location::factory()
                ->count(6)
                ->has(FreezingRoom::factory()
                    ->count(5)
                    ->has(Block::factory()
                        ->count(20)
                    )
                )
                ->create();


        DB::statement('SET FOREIGN_KEY_CHECKS=1;');


    }
}
