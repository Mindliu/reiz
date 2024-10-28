<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'urls' => ['required', 'array'],
            'selectors' => ['required', 'array'],
        ];
    }
}
