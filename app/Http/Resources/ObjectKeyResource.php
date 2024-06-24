<?php

namespace App\Http\Resources;

use App\Models\ObjectKey;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ObjectKeyResource extends JsonResource
{
    public function __construct(ObjectKey $resource, private $timeStamp = false)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        $return = [];
        $return['key'] = $this->key;

        if ($this->relationLoaded('objectValuesLimited')) {
            $return['values'] = $this->objectValuesLimited;
        } else {
            $values = $this->objectValues();
            if ($this->timeStamp) {
                $values->onThisDate($this->timeStamp);
            } else {
                $values = $this->latestValue();
            }
            $firstValue = $values->first();
            if ($firstValue) {
                $return['value'] = $firstValue->value;
                $return['value_created_at'] = $firstValue->created_at;
            }
        }

        return $return;
    }
}
