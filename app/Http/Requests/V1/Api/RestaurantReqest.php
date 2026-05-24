<?php

namespace App\Http\Requests\V1\Api;
use Illuminate\Foundation\Http\FormRequest;

class RestaurantReqest extends FormRequest
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
     * Get the restaurant rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'hotel_id' => 'required|integer|exists:hotel_locations,id',
            'restaurant_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:5120', // 5MB max image
            'restaurant_name' => 'required|array',
            'restaurant_name.en' => 'required|string|max:255',
            'restaurant_name.ar' => 'required|string|max:255',
            'restaurant_cuisine' => 'required|array',
            'restaurant_cuisine.en' => 'required|string',
            'restaurant_cuisine.ar' => 'required|string',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required_with:schedules|string|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'schedules.*.work_from' => 'required_with:schedules|date_format:H:i',
            'schedules.*.work_to' => 'required_with:schedules|date_format:H:i|after:schedules.*.work_from',
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => __('restaurant.hotel_id_required'),
            'hotel_id.integer' => __('restaurant.hotel_id_integer'),
            'hotel_id.exists' => __('restaurant.hotel_id_not_found'),
            'restaurant_image.file' => __('restaurant.restaurant_image_file'),
            'restaurant_image.mimes' => __('restaurant.restaurant_image_mimes'),
            'restaurant_image.max' => __('restaurant.restaurant_image_max'),

            'restaurant_name.en.required' => __('restaurant.restaurant_name_en_required'),
            'restaurant_name.ar.required' => __('restaurant.restaurant_name_ar_required'),

            'restaurant_cuisine.en.required' => __('restaurant.restaurant_cuisine_en_required'),
            'restaurant_cuisine.ar.required' => __('restaurant.restaurant_cuisine_ar_required'),

            // Schedules
            'schedules.array' => __('restaurant.schedules_array'),
            'schedules.*.day_of_week.required_with' => __('restaurant.schedule_day_required'),
            'schedules.*.day_of_week.string' => __('restaurant.schedule_day_string'),
            'schedules.*.day_of_week.in' => __('restaurant.schedule_day_in'),

            'schedules.*.work_from.required_with' => __('restaurant.schedule_opening_required'),
            'schedules.*.work_from.date_format' => __('restaurant.schedule_opening_format'),

            'schedules.*.work_to.required_with' => __('restaurant.schedule_closing_required'),
            'schedules.*.work_to.date_format' => __('restaurant.schedule_closing_format'),
            'schedules.*.work_to.after' => __('restaurant.schedule_closing_after_opening'),

        ];
    }
}
