<?php

namespace Database\Seeders;

use App\Models\ObjectKey;
use App\Models\ObjectValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObjectTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ObjectKey::factory()
            ->has(ObjectValue::factory()->count(3))
            ->count(3)
            ->create();
    }
}
