<?php

namespace App\Http\Requests\V1\CRM\BlackoutDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateBlackoutDateRequest extends FormRequest
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
            'hotel_id' => $this->input('hotel_id'),
        ]);
    }
    public function rules(): array
    {
        return [
            'blackoutDate_id' => [
                'required',
                'integer',
                Rule::exists('blackout_dates', 'id')->where(function ($query) {
                    $query->where('hotel_id', $this->input('hotel_id'))
                        ->whereNull('deleted_at');
                }),
            ],
            'hotel_id' => 'required|integer|exists:hotel_locations,id',

            'blackoutDate_name' => 'nullable|array',
            'blackoutDate_name.en' => 'nullable|string|max:255',
            'blackoutDate_name.ar' => 'nullable|string|max:255',

            // Adjust this according to your DB column type (date or time)
            'blackoutDate_start_date' => 'nullable|date',
            'blackoutDate_end_date' => 'nullable|date',

            'allow_existing_booking' => 'nullable|boolean',

            'category_ids' => 'nullable|array',
            'category_ids.*' => [
                'integer',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('hotel_id', $this->input('hotel_id'))
                          ->whereNull('deleted_at');
                }),
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $start = $this->input('blackoutDate_start_date');
            $end = $this->input('blackoutDate_end_date');

            if ($start && $end && strtotime($start) >= strtotime($end)) {
                $validator->errors()->add('blackoutDate_start_date', __('blackoutDate.start_must_be_before_end'));
            }
        });
    }
    public function messages(): array
    {
        return [
            'blackoutDate_id.required' => __('blackoutDate.blackoutDate_id_required'),
            'blackoutDate_id.integer' => __('blackoutDate.blackoutDate_id_integer'),
            'blackoutDate_id.exists' => __('blackoutDate.blackoutDate_id_not_found'),

            'hotel_id.required' => __('blackoutDate.hotel_id_required'),
            'hotel_id.integer' => __('blackoutDate.hotel_id_integer'),
            'hotel_id.exists' => __('blackoutDate.hotel_id_not_found'),

            'blackoutDate_name.en.required' => __('blackoutDate.blackoutDate_name_en_required'),
            'blackoutDate_name.ar.required' => __('blackoutDate.blackoutDate_name_ar_required'),

            'category_ids.*.integer' => __('blackoutDate.category_id_integer'),
            'category_ids.*.exists' => __('blackoutDate.category_not_found'),
            'blackoutDate_end_date.date' => __('blackoutDate.end_date_not_valid'),
            'blackoutDate_start_date.date' => __('blackoutDate.start_date_not_valid'),
        ];
    }
}
