<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ObjectKey;
use App\Models\ObjectValue;

class ObjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run($keyCount = 3, $valuePerKeyCount = 3): void
    {
        ObjectKey::factory()
            ->has(ObjectValue::factory()->count($valuePerKeyCount))
            ->count($keyCount)
            ->create();
    }
}
