<?php

namespace App\Http\Requests\V1\CRM\Feature;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateFeatureRequest extends ApiFormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        $featureId = $this->route('id');

        return [
            'name' => 'sometimes|array',
            'name.en' => [
                'sometimes',
                'string',
                Rule::unique('features', 'name->en')->ignore($featureId),
            ],
            'name.ar' => [
                'sometimes',
                'string',
                Rule::unique('features', 'name->ar')->ignore($featureId),
            ],
            'hotel_id' => 'sometimes|exists:hotel_locations,id',
            'logo' => 'sometimes|nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return __('feature.validation');
    }
}
