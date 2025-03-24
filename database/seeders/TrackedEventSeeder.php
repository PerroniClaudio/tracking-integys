<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TrackedEvents;

class TrackedEventSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //

        TrackedEvents::factory()
            ->count(1000)
            ->create();
    }
}
