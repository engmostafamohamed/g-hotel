<?php

namespace App\Http\Requests\V1\Api\Feedback;

use App\Http\Requests\ApiFormRequest;

class UpdateFeedbackRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return auth('guest')->check();
    }

    public function rules(): array
    {
        return [
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return __('feedback.validation');
    }
}
