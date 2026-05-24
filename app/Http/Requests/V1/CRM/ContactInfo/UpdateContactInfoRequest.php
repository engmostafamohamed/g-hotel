<?php

namespace App\Http\Requests\V1\CRM\ContactInfo;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactInfoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_location_id' => 'nullable|exists:hotel_locations,id',
            'type' => 'sometimes|string',
            'label' => 'nullable|array',
            'value' => 'sometimes|string',
        ];
    }
}
