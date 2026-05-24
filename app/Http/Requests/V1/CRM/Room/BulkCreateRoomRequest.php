<?php

namespace App\Http\Requests\V1\CRM\Room;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkCreateRoomRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id' => 'required|exists:room_types,id',
            'room_numbers' => 'required|array|min:1',
            'room_numbers.*' => [
                'required',
                'string',
                'distinct',
                Rule::unique('rooms', 'room_number'),
            ],
        ];
    }

    public function messages(): array
    {
        return __('room.validation');
    }
}
