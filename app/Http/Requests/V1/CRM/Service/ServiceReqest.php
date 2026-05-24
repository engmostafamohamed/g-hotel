<?php

namespace App\Http\Requests\V1\CRM\Service;
use Illuminate\Foundation\Http\FormRequest;

class ServiceReqest extends FormRequest
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
            'service_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120', // 5MB max image
            'service_name' => 'required|array',
            'service_name.en' => 'required|string|max:255',
            'service_name.ar' => 'required|string|max:255',
            'service_description' => 'required|array',
            'service_description.en' => 'required|string',
            'service_description.ar' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => __('validation.hotel_id_required'),
            'hotel_id.integer' => __('validation.hotel_id_integer'),
            'hotel_id.exists' => __('validation.hotel_id_not_found'),

            'service_id.required' => __('service.service_id_required'),
            'service_id.integer' => __('service.service_id_integer'),
            'service_id.exists' => __('service.service_id_not_found'),

            'service_image.file' => __('validation.service_image_file'),
            'service_image.mimes' => __('validation.service_image_mimes'),
            'service_image.max' => __('validation.service_image_max'),

            'service_name.en.required' => __('validation.service_name_en_required'),
            'service_name.ar.required' => __('validation.service_name_ar_required'),

            'service_description.en.required' => __('validation.service_description_en_required'),
            'service_description.ar.required' => __('validation.service_description_ar_required'),
        ];
    }
}
