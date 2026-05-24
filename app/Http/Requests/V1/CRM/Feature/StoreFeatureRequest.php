<?php

namespace App\Http\Requests\V1\CRM\Feature;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;
use App\Http\Requests\ApiFormRequest;

class StoreFeatureRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.en' => 'required|string|unique:features,name->en',
            'name.ar' => 'required|string|unique:features,name->ar',
            'hotel_id' => 'required|exists:hotel_locations,id',
            'logo' => 'nullable|image|max:5120',
        ];
    }

    public function messages(): array
    {
        return __('feature.validation');
    }
}
