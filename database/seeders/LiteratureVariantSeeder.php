<?php

namespace Database\Seeders;

use App\Models\LiteratureVariant;
use Illuminate\Database\Seeder;

class LiteratureVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LiteratureVariant::factory()->count(30)->create();
    }
}
