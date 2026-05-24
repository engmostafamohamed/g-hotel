<?php

namespace App\Http\Requests\V1\Api;
use Illuminate\Foundation\Http\FormRequest;

class LiveStyleImageReqest extends FormRequest
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
            'hotel_id' => 'required|integer|exists:hotel_locations,id',
            'liveStyleImage_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',

            // Caption is an optional array
            'liveStyleImage_caption' => 'nullable|array',

            // Require `en` if `ar` is present and vice versa
            'liveStyleImage_caption.en' => 'required_with:liveStyleImage_caption.ar|string|max:255',
            'liveStyleImage_caption.ar' => 'required_with:liveStyleImage_caption.en|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => __('validation.hotel_id_required'),
            'hotel_id.integer' => __('validation.hotel_id_integer'),
            'hotel_id.exists' => __('validation.hotel_id_not_found'),

            'liveStyleImage_image.file' => __('validation.LiveStyleImage_image_file'),
            'liveStyleImage_image.mimes' => __('validation.LiveStyleImage_image_mimes'),
            'liveStyleImage_image.max' => __('validation.LiveStyleImage_image_max'),

            'liveStyleImage_caption.en.required_with' => __('validation.LiveStyleImage_name_en_required'),
            'liveStyleImage_caption.ar.required_with' => __('validation.LiveStyleImage_name_ar_required'),
        ];
    }
}
