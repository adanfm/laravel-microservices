<?php

namespace Database\Seeders;

use App\Models\Genrer;
use Illuminate\Database\Seeder;

class GenrerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Genrer::factory(100)->create();
    }
}
