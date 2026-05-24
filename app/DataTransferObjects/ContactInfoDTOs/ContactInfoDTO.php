<?php

namespace App\DataTransferObjects\ContactInfoDTOs;

use App\Http\Requests\V1\CRM\ContactInfo\StoreContactInfoRequest;
use App\Http\Requests\V1\CRM\ContactInfo\UpdateContactInfoRequest;
use Illuminate\Http\Request;

class ContactInfoDTO
{
    public function __construct(
        public readonly ?int $hotel_location_id,
        public readonly ?string $type,
        public readonly ?array $label,
        public readonly ?string $value,
    ) {}

    public static function fromRequest(StoreContactInfoRequest|UpdateContactInfoRequest $request): self
    {
        return new self(
            hotel_location_id: $request->input('hotel_location_id'),
            type: $request->input('type'),
            label: $request->input('label'),
            value: $request->input('value'),
        );
    }
}
