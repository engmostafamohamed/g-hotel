<?php

namespace App\Http\Requests\V1\CRM\BlackoutDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class BlackoutDateReqest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add extra authorization logic if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'blackoutDate_id' => $this->route('id'),
            // 'hotel_id' => $this->input('hotel_id'),
        ]);
    }
     public function rules(): array
    {
        return [
            // 'hotel_id' => 'required|integer|exists:hotel_locations,id',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('blackoutDate_id', [
            'required',
            Rule::exists('categories', 'id')->where(function ($query) {
                $query->where('hotel_id', $this->input('hotel_id'))
                      ->whereNull('deleted_at');
            }),
        ], function () {
            return $this->hasHeader('hotel_id') && is_numeric($this->input('hotel_id'));
        });
    }
    public function messages(): array
    {
        return [
            // 'hotel_id.integer' => __('blackoutDate.hotel_id_integer'),
            // 'hotel_id.exists' => __('blackoutDate.hotel_id_not_found'),

            'blackoutDate_id.integer' => __('blackoutDate.blackoutDate_id_integer'),
            'blackoutDate_id.exists' => __('blackoutDate.blackoutDate_id_not_found'),
        ];
    }
}
