<?php

namespace App\Http\Requests\V1\CRM\Booking;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class BookingHistoryRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return auth('employee')->check();
    }

    public function rules(): array
    {
        return [
            'hotel_id' => 'nullable|integer|exists:hotel_locations,id',
            'guest_id' => 'nullable|integer|exists:guests,id',
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'sort' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return __('booking.validation') ?: [];
    }
}
