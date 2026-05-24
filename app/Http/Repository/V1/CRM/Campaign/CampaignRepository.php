<?php

namespace App\Http\Repository\V1\CRM\Campaign;

use App\Models\Campaign;
use App\Models\CampaignOffer;
use App\Models\CampaignPreview;
use App\Traits\UsesHotelScope;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CampaignRepository
{
    use UsesHotelScope;

    public function create(array $data): Campaign
    {
        return Campaign::create($data);
    }

    public function createOffer(Campaign $campaign, array $offerData): CampaignOffer
    {
        return $campaign->offer()->create([
            'type' => $offerData['type'],
            'value' => $offerData['value'],
            'min_booking' => $offerData['min_booking'],
        ]);
    }

    public function createPreview(Campaign $campaign, array $previewData): CampaignPreview
    {
        return $campaign->preview()->create([
            'email_html' => $previewData['email_html'] ?? null,
            'push_message' => $previewData['push_message'] ?? null,
        ]);
    }

    public function paginate(array $filters = [], int $perPage = 10)
    {
        return Campaign::with(['offer', 'preview', 'createdBy', 'approvedBy'])
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['is_approved']), function ($query) use ($filters) {
                $query->where('is_approved', $filters['is_approved']);
            })
            ->when(isset($filters['approval_required']), function ($query) use ($filters) {
                $query->where('approval_required', $filters['approval_required']);
            })
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    public function findById($id): ?Campaign
    {
        return Campaign::with(['offer', 'preview', 'createdBy', 'approvedBy'])->find($id);
    }

    public function archive(int $id): Campaign
    {
        $campaign = $this->findById($id);

        $campaign->update(['status' => 'archived']);

        return $campaign->refresh();
    }
    public function delete(Campaign $campaign): void
    {
        $campaign->delete();
    }
}
