<?php

namespace App\Http\Resources\V1\CRM\Countries;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CRM\Service\ServiceResource;
class CountriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $name = json_decode($this->name, true);
        return [
            'id'         => $this->id,
            'name'       => [
                'ar' => $name['ar'] ?? null,
                'en' => $name['en'] ?? null,
            ],
            'country_code' => $this->country_code,
            'iso_code'       => $this->iso_code,
        ];
    }
}
