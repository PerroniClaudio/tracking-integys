<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactFormRequestSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        //

        \App\Models\ContactFormRequest::factory(10)->create();
    }
}
