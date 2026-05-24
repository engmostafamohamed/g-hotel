<?php

namespace App\Http\Requests\V1\CRM\BlackoutDate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreBlackoutDateRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'hotel_id'      => 'required|integer|exists:hotel_locations,id',
            'blackoutDate_name' => 'required|array',
            'blackoutDate_name.en' => 'required|string|max:255',
            'blackoutDate_name.ar' => 'required|string|max:255',
            'blackoutDate_start_date' => 'required|date',
            'blackoutDate_end_date' => 'required|date',
            'allow_existing_booking' => 'required|boolean',

            'category_ids' => 'required|array',
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
            'category_ids.required' => __('blackoutDate.category_id_required'),
            'category_ids.*.integer' => __('blackoutDate.category_id_integer'),
            'category_ids.*.exists' => __('blackoutDate.category_not_found'),
            'hotel_id.required' => __('blackoutDate.hotel_id_required'),
            'hotel_id.integer' => __('blackoutDate.hotel_id_integer'),
            'hotel_id.exists' => __('blackoutDate.hotel_id_not_found'),

            'blackoutDate_id.required' => __('blackoutDate.blackoutDate_id_required'),
            'blackoutDate_id.integer' => __('blackoutDate.blackoutDate_id_integer'),
            'blackoutDate_id.exists' => __('blackoutDate.blackoutDate_id_not_found'),

            'blackoutDate_name.en.required' => __('blackoutDate.blackoutDate_name_en_required'),
            'blackoutDate_name.ar.required' => __('blackoutDate.blackoutDate_name_ar_required'),
        ];
    }
}
