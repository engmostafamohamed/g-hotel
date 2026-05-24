<?php
namespace App\Http\Requests\V1\Api\Notification;
use Illuminate\Foundation\Http\FormRequest;

class SendNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'      => 'required|array',
            'title.*'    => 'string',
            'message'    => 'required|array',
            'message.*'  => 'string',
            'type'       => 'nullable|string|in:offer,campaign,room_type,custom',
            'guest_ids' => 'nullable|array',
            'guest_ids.*' => 'integer|exists:guest,id',
            'data' => 'nullable|array',
            'scheduled_times' => 'nullable|array',
            'scheduled_times.*' => 'date|after:now',
        ];
    }
    public function messages(): array
    {
        return [
            'title.required'   => __('notification.title_required'),
            'title.array'      => __('notification.title_array'),
            'title.*.string'   => __('notification.title_string'),
            'message.required' => __('notification.message_required'),
            'message.array'    => __('notification.message_array'),
            'message.*.string' => __('notification.message_string'),
            'type.in'         => __('notification.type_in'),
            'guest_ids.array' => __('notification.guest_ids_array'),
            'guest_ids.*.exists' => __('notification.guest_ids_exists'),
            'data.array' => __('notification.extra_data_array'),
            'scheduled_times.array' => __('notification.scheduled_times_array'),
            'scheduled_times.*.date' => __('notification.scheduled_times_date'),
            'scheduled_times.*.after' => __('notification.scheduled_times_after'),
        ];
    }
}
