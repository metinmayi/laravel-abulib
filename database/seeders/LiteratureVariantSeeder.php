<?php

namespace Database\Seeders;

use App\Models\Literature;
use App\Models\LiteratureVariant;
use Illuminate\Database\Seeder;

class LiteratureVariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        LiteratureVariant::factory()
            ->count(20)
            ->create();
    }
}
