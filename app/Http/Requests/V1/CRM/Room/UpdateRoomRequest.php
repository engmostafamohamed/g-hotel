<?php

namespace App\Http\Requests\V1\CRM\Room;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomId = $this->route('id');
        $roomTypeId = $this->input('room_type_id');

        return [
            'room_number' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('rooms', 'room_number')
                    ->ignore($roomId)
                    ->where(function ($query) use ($roomTypeId) {
                        $query->whereIn('room_type_id', function ($sub) use ($roomTypeId) {
                            $sub->select('id')
                                ->from('room_types')
                                ->whereIn('category_id', function ($sub2) use ($roomTypeId) {
                                    $sub2->select('category_id')
                                         ->from('room_types')
                                         ->where('id', $roomTypeId);
                                });
                        });
                    }),
            ],
            'room_type_id' => 'sometimes|exists:room_types,id',
        ];
    }

    public function messages(): array
    {
        return __('room.validation');
    }
}