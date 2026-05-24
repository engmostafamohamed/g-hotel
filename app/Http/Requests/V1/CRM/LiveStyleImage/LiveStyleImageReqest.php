<?php

namespace App\Http\Requests\V1\CRM\LiveStyleImage;
use Illuminate\Foundation\Http\FormRequest;

class LiveStyleImageReqest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Override to include header values in validation.
     */

    public function rules(): array
    {
        return [
            // 'hotel_id' => 'required|integer|exists:hotel_locations,id',
        ];
    }

    public function messages(): array
    {
        return [
            // 'hotel_id.required' => __('liveStyleImage.hotel_id_required'),
            // 'hotel_id.integer' => __('liveStyleImage.hotel_id_integer'),
            // 'hotel_id.exists' => __('liveStyleImage.hotel_id_not_found'),
        ];
    }
}
