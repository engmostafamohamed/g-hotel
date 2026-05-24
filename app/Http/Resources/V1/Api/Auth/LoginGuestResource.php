<?php

namespace App\Http\Resources\V1\Api\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginGuestResource extends JsonResource
{
    protected string $token;

    public function __construct($resource, string $token)
    {
        parent::__construct($resource);
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function toArray($request): array
    {
        return [
            'token' => $this->token,
            'guest' => [
                'id'                  => $this->id,
                'first_name'          => $this->first_name,
                'last_name'           => $this->last_name,
                'email'               => $this->email,
                'passport_no'         => $this->passport_no,
                'passport_or_id_num'  => $this->passport_or_id_num,
                'passport_or_id_flag' => $this->passport_or_id_flag,
                'country_id'          => $this->country_id,
                'country_name'        => $this->country?->name,
                'city_id'             => $this->city_id,
                'city_name'           => $this->city?->name,
                'is_verified'         => $this->is_verified,
                'created_at'          => $this->created_at,
            ],
        ];
    }
}
