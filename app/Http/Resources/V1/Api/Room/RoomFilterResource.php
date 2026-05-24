<?php

namespace App\Http\Resources\V1\Api\Room;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CRM\Category\CategoryBedResource;
use App\Http\Resources\V1\CRM\Category\CategoryMiniResource;
use App\Http\Resources\V1\CRM\Feature\FeatureMiniResource;
use App\Http\Resources\V1\CRM\View\ViewResource;

class RoomFilterResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'room_types' => CategoryMiniResource::collection($this['categories']),
            'features'   => FeatureMiniResource::collection($this['features']),
            'views'      => ViewResource::collection($this['views'])
        ];
    }
}
