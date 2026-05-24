<?php

namespace App\Http\Requests\V1\CRM\LiveStyleImage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLiveStyleImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // No exception will be thrown
    }

    public function rules(): array
    {
        return [
            'hotel_id' => 'required|integer|exists:hotel_locations,id',
            'images_url' => 'required|array',
            'images_url.*' => 'file|mimes:jpg,jpeg,png,gif|max:2048',
            'caption' => 'array',
            'caption.en' => 'string|max:255',
            'caption.ar' => 'string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'hotel_id.required' => __('liveStyleImage.hotel_id_required'),
            'hotel_id.integer' => __('liveStyleImage.hotel_id_integer'),
            'hotel_id.exists' => __('liveStyleImage.hotel_id_not_found'),

            'images_url.required' => __('liveStyleImage.images_required'),
            'images_url.array' => __('liveStyleImage.images_array'),
            'images_url.*.file' => __('liveStyleImage.images_file'),
            'images_url.*.mimetypes' => __('liveStyleImage.images_mimes'),
            'images_url.*.max' => __('liveStyleImage.images_max'),
        ];
    }
}
