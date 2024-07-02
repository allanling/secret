<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ObjectPostRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $params = $this->all();
        foreach ($params as $key => $value) {
            $this->merge(['objectKey' => $key]);
            $this->merge(['objectValue' => $value]);
            break;
        }
    }

    public function rules(): array
    {
        return [
            'objectKey' => 'required|alpha_dash:ascii|max:255',
            'objectValue' => 'nullable|string',
        ];
    }
}
