<?php

namespace App\DataTransferObjects\V1\CRM\Loyalty\TierDTOs;

use App\Http\Requests\V1\CRM\Loyalty\Tier\StoreTierRequest;
use App\Http\Requests\V1\CRM\Loyalty\Tier\UpdateTierRequest;

class CreateTierDTO
{

    public function __construct(
        public array $tier_name,
        public string $code,
        // public int $hotel_id,
        public int $threshold,
        // public array $service_ids,
        public array $content,
    ) {}

    public static function fromRequest(StoreTierRequest $request): self
    {
        return new self(
            tier_name: $request->input('tier_name'),
            code: $request->input('code'),
            threshold: (float) $request->input('threshold'),
            // hotel_id: $request->input('hotel_id'),
            // service_ids: $request->input('service_ids',[]),
            content: $request->input('content'),
        );
    }
}
