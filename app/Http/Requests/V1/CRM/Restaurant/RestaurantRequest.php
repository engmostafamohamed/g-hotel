<?php

namespace App\Http\Requests\V1\CRM\Restaurant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class RestaurantRequest extends FormRequest
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
            'restaurant_id' => $this->route('id'),
            'hotel_id' => $this->input('hotel_id'),
        ]);
    }
    public function rules(): array
    {
        return [
            'hotel_id' => 'required|integer|exists:hotel_locations,id',
            // 'restaurant_id' => 'required|integer|exists:restaurants,id',

            'in_dining' => 'nullable|bool',
            'room_service' => 'nullable|bool',
            'schedules' => 'nullable|array',
            'schedules.*.day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'schedules.*.work_from' => 'required|date_format:H:i',
            'schedules.*.work_to' => 'required|date_format:H:i|after:schedules.*.work_from',

            'exception_dates' => 'nullable|array',
            'exception_dates.*.date' => 'required|date_format:Y-m-d',
            'exception_dates.*.exception_from' => 'required|date_format:H:i',
            'exception_dates.*.exception_to' => 'required|date_format:H:i',

        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('restaurant_id', [
            'required',
            Rule::exists('restaurants', 'id')->where(function ($query) {
                $query->where('hotel_id', $this->input('hotel_id'))
                      ->whereNull('deleted_at');
            }),
        ], function () {
            return $this->hasHeader('hotel_id') && is_numeric($this->input('hotel_id'));
        });

        $validator->after(function ($validator) {
            // Validate exception_from < exception_to
            $exceptions = $this->input('exception_dates', []);
            foreach ($exceptions as $index => $exception) {
                if (
                    isset($exception['exception_from'], $exception['exception_to']) &&
                    strtotime($exception['exception_from']) >= strtotime($exception['exception_to'])
                ) {
                    $validator->errors()->add("exception_dates.$index.exception_from", __('restaurant.exception_from_must_be_before_to'));
                }
            }

            // Validate work_from < work_to
            $schedules = $this->input('schedules', []);
            foreach ($schedules as $index => $schedule) {
                if (
                    isset($schedule['work_from'], $schedule['work_to']) &&
                    strtotime($schedule['work_from']) >= strtotime($schedule['work_to'])
                ) {
                    $validator->errors()->add("schedules.$index.work_from", __('restaurant.work_from_must_be_before_to'));
                }
            }
        });
    }
    public function messages(): array
    {
        return [
            'hotel_id.required' => __('restaurant.hotel_id_required'),
            'hotel_id.integer' => __('restaurant.hotel_id_integer'),
            'hotel_id.exists' => __('restaurant.hotel_id_not_found'),

            'in_dining.bool' => __('restaurant.in_dining_must_bool'),
            'room_service.bool' => __('restaurant.room_service_must_bool'),

            'restaurant_id.integer' => __('restaurant.restaurant_id_integer'),
            'restaurant_id.exists' => __('restaurant.restaurant_id_not_found'),

            'schedules.*.day_of_week.required' => __('restaurant.schedule_day_required'),
            'schedules.*.day_of_week.in' => __('restaurant.schedule_day_invalid'),
            'schedules.*.work_from.required' => __('restaurant.schedule_work_from_required'),
            'schedules.*.work_from.date_format' => __('restaurant.schedule_work_from_format'),
            'schedules.*.work_to.required' => __('restaurant.schedule_work_to_required'),
            'schedules.*.work_to.date_format' => __('restaurant.schedule_work_to_format'),
            'schedules.*.work_to.after' => __('restaurant.schedule_work_to_after'),

            'exception_dates.*.date.required' => __('restaurant.exception_date_required'),
            'exception_dates.*.date.date_format' => __('restaurant.exception_date_format'),
            'exception_dates.*.exception_from.required' => __('restaurant.exception_from_required'),
            'exception_dates.*.exception_from.date_format' => __('restaurant.exception_from_format'),
            'exception_dates.*.exception_to.required' => __('restaurant.exception_to_required'),
            'exception_dates.*.exception_to.date_format' => __('restaurant.exception_to_format'),
        ];
    
    }
}
