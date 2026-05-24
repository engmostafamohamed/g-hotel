<?php

namespace App\Http\Requests\V1\CRM\StaticPage;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaticPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Add authorization logic if needed
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|array',
            'title.en' => 'sometimes|string|max:100',
            'title.ar' => 'sometimes|string|max:100',

            'content' => 'sometimes|array',
            'content.en' => 'sometimes|string',
            'content.ar' => 'sometimes|string',

            'is_active' => 'sometimes|boolean',
        ];
    }
}
