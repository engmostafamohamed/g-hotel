<?php

namespace App\DataTransferObjects\FeatureDTOs;

use App\Http\Requests\V1\CRM\Feature\StoreFeatureRequest;
use App\Http\Requests\V1\CRM\Feature\UpdateFeatureRequest;



class FeatureDTO
{
    public ?string $logoPath = null;

    public function __construct(
        public array $name,
        public int $hotel_id,
        public mixed $logo = null,
    ) {}
    public static function fromRequest(StoreFeatureRequest|UpdateFeatureRequest $request): self
    {
        return new self(
            name: [
                'en' => $request->input('name.en'),
                'ar' => $request->input('name.ar'),
            ],
            hotel_id: (int) $request->input('hotel_id'),
            logo: $request->file('logo')
        );
    }
}
