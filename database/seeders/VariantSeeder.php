<?php

namespace Database\Seeders;

use App\Models\Literature;
use App\Models\Variant;
use Illuminate\Database\Seeder;

class VariantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Variant::factory()
            ->count(5)
            ->create();

            Variant::factory()
            ->set('literature_id', Literature::factory()->createOne()->id)
            ->count(3)
            ->create();
    }
}
