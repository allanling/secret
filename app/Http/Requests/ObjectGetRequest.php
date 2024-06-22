<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObjectGetRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'timestamp' => 'numeric',
        ];
    }
}
