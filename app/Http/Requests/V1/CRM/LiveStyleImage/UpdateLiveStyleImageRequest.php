<?php

namespace App\Http\Requests\V1\CRM\LiveStyleImage;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLiveStyleImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'liveStyleImage_id' => $this->route('id'),
            'hotel_id' => $this->input('hotel_id'),
        ]);
    }

    public function rules(): array
    {
        return [
            'hotel_id' => 'required|integer|exists:hotel_locations,id',

            'images_url' => 'nullable|array',
            'images_url.*' => 'file|mimes:jpg,jpeg,png,gif|max:2048',
            'caption' => 'nullable|array',
            'caption.en' => 'nullable|string|max:255',
            'caption.ar' => 'nullable|string|max:255',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('liveStyleImage_id', [
            'required',
            Rule::exists('live_style_images', 'id')->where(function ($query) {
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
