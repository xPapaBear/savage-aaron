<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MultiplierSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Multiplier::factory(1)->create();
    }
}
