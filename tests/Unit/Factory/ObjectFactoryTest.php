<?php

namespace Tests\Unit\Factory;

use App\Models\ObjectKey;
use App\Models\ObjectValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ObjectFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_object_factory_returns_correct_num_of_results()
    {
        $keyCount = 5;
        $valPerKeyCount = 3;
        ObjectValue::truncate();
        ObjectKey::truncate();
        ObjectKey::factory()
            ->has(ObjectValue::factory()->count($valPerKeyCount))
            ->count($keyCount)
            ->create();
        $this->assertDatabaseCount('object_keys', $keyCount);
        $this->assertDatabaseCount('object_values', $keyCount * $valPerKeyCount);
    }
}
