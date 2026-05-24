<?php

namespace App\Http\Requests\V1\Api\ServiceReservation;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class StoreServiceReservationRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_id' => 'required|exists:services,id',
            'date' => 'nullable|date|after_or_equal:today',
            'from' => 'nullable|date_format:H:i',
            'to' => 'nullable|date_format:H:i|after:from',
            'notes' => 'nullable|string',
        ];
    }


    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $service = \App\Models\Service::find($this->input('service_id'));

            if (!$service) {
                return;
            }

            $isSchedulable = $service->timeSlots()->exists();

            if ($isSchedulable) {
                // Conditionally require 'date'
                if (!$this->filled('date')) {
                    $validator->errors()->add('date', 'The date is required for this service.');
                }

                // Conditionally require 'from'
                if (!$this->filled('from')) {
                    $validator->errors()->add('from', 'The from time is required for this service.');
                }

                // Conditionally require 'to'
                if (!$this->filled('to')) {
                    $validator->errors()->add('to', 'The to time is required for this service.');
                }

                // Validate "to" is not in the past if date is today
                $date = $this->input('date');
                $to = $this->input('to');

                if ($date === now()->toDateString() && $to && $to < now()->format('H:i')) {
                    $validator->errors()->add('to', 'The "to" time must be after the current time when the date is today.');
                }
            }
        });
    }

    public function messages(): array
    {
        return __('serviceReservation.validation');
    }

}