<?php

namespace App\Http\Requests\V1\CRM\Dashboard;

use App\Http\Requests\ApiFormRequest;

class DashboardRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Optional date filters. If not provided repo will use reasonable defaults.
            'from_date' => ['nullable', 'date'],
            'to_date'   => ['nullable', 'date', 'after_or_equal:from_date'],
            // optional granularity: 'day' (default), 'hour' when from_date == to_date
            'granularity' => ['nullable', 'in:day,hour'],
        ];
    }

    public function messages(): array
    {
        return __('dashboard.validation') ?: [];
    }
}
