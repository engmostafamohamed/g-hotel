<?php

namespace App\Http\Requests\V1\CRM\Room;
use Illuminate\Foundation\Http\FormRequest;

class RoomReqest extends FormRequest
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
            'category_id' => 'required|integer|exists:categories,id',

            'number_of_adults' => 'nullable|integer|min:0',
            'number_of_children' => 'nullable|integer|min:0',
            'available_quantity' => 'nullable|integer|min:0',

            'room_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120',

            'room_name' => 'required|array',
            'room_name.en' => 'required|string|max:255',
            'room_name.ar' => 'required|string|max:255',

            'room_description' => 'required|array',
            'room_description.en' => 'required|string',
            'room_description.ar' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => __('room.hotel_id_required'),
            'hotel_id.integer' => __('room.hotel_id_integer'),
            'hotel_id.exists' => __('room.hotel_id_not_found'),

            'category_id.required' => __('room.category_id_required'),
            'category_id.integer' => __('room.category_id_integer'),
            'category_id.exists' => __('room.category_id_not_found'),


            'number_of_adults.integer' => __('room.number_of_adults_integer'),
            'number_of_adults.min' => __('room.number_of_adults_min'),

            'number_of_children.integer' => __('room.number_of_children_integer'),
            'number_of_children.min' => __('room.number_of_children_min'),

            'available_quantity.integer' => __('room.available_quantity_integer'),
            'available_quantity.min' => __('room.available_quantity_min'),

            'room_image.file' => __('room.image_file'),
            'room_image.mimes' => __('room.image_mimes'),
            'room_image.max' => __('room.room_image_max'),

            'room_name.en.required' => __('room.room_name_en_required'),
            'room_name.ar.required' => __('room.room_name_ar_required'),

            'room_description.en.required' => __('room.room_description_en_required'),
            'room_description.ar.required' => __('room.room_description_ar_required'),
        ];
    }
}
