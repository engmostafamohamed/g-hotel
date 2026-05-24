<?php

namespace App\Http\Requests\V1\CRM\ServiceReservation;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class UpdateServiceReservationRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'from' => 'nullable|date_format:H:i',
            'to' => 'nullable|date_format:H:i|after:from',
            'date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string',
            'status' => 'nullable|in:pending,confirmed,cancelled,completed',
            'cancellation_reason' => 'nullable|string',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $date = $this->input('date');
            $to = $this->input('to');

            if (!$date || !$to) {
                return;
            }

            $today = now()->toDateString();
            if ($date === $today) {
                $now = now()->format('H:i');

                if ($to < $now) {
                    $validator->errors()->add('to', 'The "to" time must be after or equal to the current time when the date is today.');
                }
            }
        });
    }

    public function messages(): array
    {
        return __('serviceReservation.validation');
    }
}