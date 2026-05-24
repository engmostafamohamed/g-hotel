<?php
namespace App\Http\Resources\V1\Api\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'title'      => $this->title,
            'message'    => $this->message,
            'type'       => $this->type,
            'extra_data' => $this->extra_data,
            'guest'     => $this->guest ? [
                'id'    => $this->guest->id,
                'name'  => $this->guest->name,
                'email' => $this->guest->email,
            ] : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
