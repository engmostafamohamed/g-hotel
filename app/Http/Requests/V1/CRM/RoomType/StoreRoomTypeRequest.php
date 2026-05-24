<?php

namespace App\Http\Requests\V1\CRM\RoomType;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoomTypeRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_code' => 'required|string|max:50|unique:room_types,room_code',
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.ar' => 'required|string',
            'description' => 'nullable|array',
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'views' => 'nullable|array',
            'views.*' => 'exists:views,id',
        ];
    }

    public function messages(): array
    {
        return __('roomType.validation');
    }
}
