<?php

namespace App\Http\Requests\V1\CRM\RoomType;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomTypeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomTypeId = $this->route('id');

        return [
            'room_code' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('room_types', 'room_code')->ignore($roomTypeId),
            ],
            'name' => 'sometimes|array',
            'name.en' => 'sometimes|string',
            'name.ar' => 'sometimes|string',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'base_price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'views' => 'sometimes|array',
            'views.*' => 'exists:views,id',
        ];
    }

    public function messages(): array
    {
        return __('roomType.validation');
    }
}
