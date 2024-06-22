<?php

namespace Tests\Feature\Controller;

use App\Models\ObjectKey;
use Database\Seeders\ObjectTableSeeder;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
        ]);
    }

    public function test_show_route_returns_successful_response()
    {
        $aKey = ObjectKey::with('objectValuesLatest')->get()->first();

        $response = $this->getJson('/api/object/'.$aKey->key);
        $latestValueOfAKey = json_decode($aKey->toArray()['object_values_latest'][0]['value']);

        $response->assertStatus(200)->assertJsonFragment([
            $latestValueOfAKey,
        ]);
    }

    public function test_show_route_with_timestamp_returns_successful_response()
    {
        $aKey = ObjectKey::with('objectValues')->get()->first();
        $valueOfAKey = $aKey->toArray()['object_values'][1];
        $timeStamp = strtotime($valueOfAKey['created_at']);

        $response = $this->getJson('/api/object/'.$aKey->key."?timestamp=$timeStamp");

        $response->assertStatus(200)->assertJsonFragment([
            json_decode($valueOfAKey['value']),
        ]);
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

        ObjectKey::with('objectValuesLatest')->get()->first();
        $foundKey = ObjectKey::with('objectValuesLatest')
            ->where('key', $key)
            ->first()
            ->toArray();

        $this->assertEquals($value, $foundKey['object_values_latest'][0]['value'] ?? null);
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
