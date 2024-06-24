<?php

namespace App\Http\Controllers;

use App\Http\Requests\ObjectGetRequest;
use App\Http\Requests\ObjectPostRequest;
use App\Http\Resources\ObjectKeyCollection;
use App\Http\Resources\ObjectKeyResource;
use App\Models\ObjectKey;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ObjectController extends Controller
{
    public function index()
    {
        $objects = ObjectKey::with('objectValuesLimited')
            ->simplePaginate(config('object.max_key_results'));

        return new ObjectKeyCollection($objects);
    }

    public function show(string $key, ObjectGetRequest $request)
    {
        $timeStamp = $request->input('timestamp', null);

        $foundKey = ObjectKey::where('key', $key)
            ->when(
                $timeStamp,
                fn (Builder $query, string $timeStamp) => $query->whereHas('objectValues', fn (Builder $query) => $query->onThisDate($timeStamp))
            )
            ->firstOrFail();

        return new ObjectKeyResource($foundKey, $timeStamp);
    }

    public function store(ObjectPostRequest $request)
    {
        extract($request->validated());

        $foundKey = ObjectKey::firstOrCreate(['key' => $objectKey]);
        $foundKey->objectValues()->create(
            ['value' => $objectValue]
        );

        return response()->json(
            ['data' => ['message' => 'Successfully created store.']],
            201
        );
    }
}
