<?php

namespace App\Http\Resources\V1\CRM\Campaign;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'campaign_id' => $this->id,
            'name' => $this->name,
            'channels' => $this->channels,
            'estimated_reach' => $this->estimated_reach,
            'approval_required' => $this->approval_required,
            'status' => $this->status,
            'offer' => [
                'type' => $this->offer?->type,
                'value' => $this->offer?->value,
                'min_booking' => $this->offer?->min_booking,
            ],
            'content_preview' => [
                'email' => $this->preview?->email_html,
                'push' => $this->preview?->push_message,
            ],
            'created_by' => [
                'id' => $this->createdBy?->id,
                'name' => $this->createdBy?->name,
            ],
            'approved_by' => $this->approvedBy ? [
                'id' => $this->approvedBy->id,
                'name' => $this->approvedBy->name,
            ] : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
