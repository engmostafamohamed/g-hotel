<?php

namespace App\Http\Requests\V1\CRM\Loyalty\Tier;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class TierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            // 'service_id' => $this->route('id'),
            'hotel_id' => $this->input('hotel_id'),
        ]);
    }
     public function rules(): array
    {
        return [
            'hotel_id' => 'nullable|integer|exists:hotel_locations,id',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes(
            'service_id',
            [
                'required',
                Rule::exists('services', 'id'),
            ],
            function () {
                return $this->route('id') !== null;
            }
        );
    }
    public function messages(): array
    {
        return [
            'hotel_id.integer' => __('tier.hotel_id_integer'),
            'hotel_id.exists' => __('tier.hotel_id_not_found'),

            'service_id.integer' => __('tier.service_id_integer'),
            'service_id.exists' => __('tier.service_id_not_found'),
        ];
    }
}
