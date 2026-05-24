<?php

namespace App\Http\Requests\V1\Api;
use Illuminate\Foundation\Http\FormRequest;

class LocationPermissionReqest extends FormRequest
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
            'hotel_video' => 'nullable|file|mimetypes:video/mp4,video/avi,video/mpeg|max:204800',
            'location_name' => 'nullable|string|max:255',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'address' => 'required|array',
            'address.en' => 'required|string',
            'address.ar' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'location_name.max' => __('validation.location_name_max'),
            'hotel_video.file' => __('validation.hotel_video_file'),
            'hotel_video.mimetypes' => __('validation.hotel_video_mimetypes'),
            'hotel_video.max' => __('validation.hotel_video_max'),
        ];
    }
}
