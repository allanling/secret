<?php

namespace Tests\Feature\Controller;

use App\Models\ObjectKey;
use App\Models\ObjectValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ObjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_route_returns_successful_response()
    {
        $response = $this->getJson('/api/object/get_all_records');

        $response->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'key',
                    'values' => [
                        '*' => [
                            'value',
                            'created_at',
                        ],
                    ],
                ],
            ],
            'meta' => [
                'current_page',
                'from',
                'per_page',
                'to',
            ],
        ]);
    }

    public function test_index_route_pagination_returns_correct_num_of_results()
    {
        ObjectValue::truncate();
        ObjectKey::truncate();
        ObjectKey::factory()
            ->has(ObjectValue::factory()->count(1))
            ->count(config('object.max_key_results') + 5)
            ->create();

        $response = $this->getJson('/api/object/get_all_records?page=1');
        $response->assertJsonCount(config('object.max_key_results'), 'data');
    }

    public function test_show_route_returns_successful_response()
    {
        $aKey = ObjectKey::with('latestValue')->first();

        $response = $this->getJson('/api/object/'.$aKey->key);
        $latestValueOfAKey = $aKey->toArray()['latest_value']['value'];

        $response->assertStatus(200)->assertJsonFragment([
            $latestValueOfAKey,
        ]);
    }

    public function test_show_route_returns_latest_result()
    {
        $key = 'aKey';

        $this->postJson('/api/object', [
            $key => '"old"',
        ]);

        $this->postJson('/api/object', [
            $key => '"new"',
        ]);

        $response = $this->getJson('/api/object/'.$key);

        $response->assertStatus(200)->assertJsonFragment([
            'new',
        ]);
    }

    public function test_show_route_with_timestamp_returns_successful_response()
    {
        $aKey = ObjectKey::with('objectValues')->get()->first();
        $valueOfAKey = $aKey->toArray()['object_values'][1];
        $timeStamp = strtotime($valueOfAKey['created_at']);

        $response = $this->getJson('/api/object/'.$aKey->key."?timestamp=$timeStamp");

        $response->assertStatus(200)->assertJsonFragment([
            $valueOfAKey['value'],
        ]);
    }

    public function test_show_route_returns_not_found_response()
    {
        $aKey = ObjectKey::with('objectValues')->get()->first();
        $response = $this->getJson('/api/object/nonexistingkey');

        $response->assertStatus(404);
    }

    public function test_show_route_with_timestamp_returns_validation_failed_response()
    {
        $aKey = ObjectKey::with('objectValues')->get()->first();
        $timeStamp = '2024-01-01';
        $response = $this->getJson('/api/object/'.$aKey->key."?timestamp=$timeStamp");

        $response->assertStatus(403);
    }

    public function test_store_route_returns_successful_response()
    {
        $key = 'aKey';
        $value = '{"test": {"abc": 123, "def": 456}}';

        $response = $this->postJson('/api/object', [
            $key => $value,
        ]);

        $response->assertStatus(201);

        $foundKey = ObjectKey::with('latestValue')
            ->where('key', $key)
            ->first()
            ->toArray();

        $this->assertEquals(json_decode($value, true), $foundKey['latest_value']['value'] ?? null);
    }

    #[DataProvider('storeValidationDataProvider')]
    public function test_store_route_returns_validation_failed_response($key, $value)
    {
        $response = $this->postJson('/api/object', [
            $key => $value,
        ]);

        $response->assertStatus(403);
    }

    public static function storeValidationDataProvider(): array
    {
        return [
            'value must not be empty' => [
                '',
                'aString',
            ],
            'value must be json' => [
                'aKey',
                '',
            ],
            'key must not be empty' => [
                '',
                '{"test": 123}',
            ],
            'key must not contain space' => [
                'a Key',
                '{"test": 123}',
            ],
            'key must not go over 255 in length' => [
                'alongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkeyalongkey',
                '{"test": 123}',
            ],
        ];
    }
}
