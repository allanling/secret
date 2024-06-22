<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObjectGetRequest;
use App\Http\Requests\ObjectPostRequest;
use App\Models\ObjectKey;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ObjectController extends Controller
{
    public function index()
    {
        $objects = ObjectKey::with('objectValuesLimited')
            ->paginate(10);
        $formattedObjects = $objects->map(
            fn ($object) => [
                'key' => $object['key'],
                'values' => collect($object->toArray()['object_values_limited'])->map(
                    fn ($value) => ['value' => json_decode($value['value']), 'created_at' => $value['created_at']]
                ),
            ]
        );

        return response()->json(['data' => $formattedObjects]);
    }

    public function show(string $key, ObjectGetRequest $request)
    {
        $timeStamp = $request->input('timestamp', null);
        $foundKey = ObjectKey::with(['objectValues' => function (Builder $query) use ($timeStamp) {
            if ($timeStamp) {
                $query->onThisDate($timeStamp);
            } else {
                $query->latest('created_at');
            }
        }])
            ->where('key', $key)
            ->firstOrFail();

        return response()->json(['data' => json_decode($foundKey->toArray()['object_values'][0]['value'])]);
    }

    public function store(ObjectPostRequest $request)
    {
        extract($request->validated());
        $foundKey = ObjectKey::firstOrCreate(['key' => $objectKey]);

        $foundKey->objectValues()->create(
            ['value' => $objectValue]
        );

        return response()->json(
            ['data' => ['key' => $objectKey, 'value' => json_decode($objectValue)]],
            201
        );
    }
}
