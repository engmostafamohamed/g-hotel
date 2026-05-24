<?php

namespace App\Services\CRM;

use App\Http\Repository\V1\CRM\Campaign\CampaignRepository;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class CampaignService
{
    public function __construct(private CampaignRepository $campaignRepo)
    {
    }

    public function createCampaign(array $data, int $employeeId): Campaign
    {
        return DB::transaction(function () use ($data, $employeeId) {
            $campaign = $this->campaignRepo->create([
                'name' => $data['name'],
                'channels' => $data['channels'],
                'created_by' => $employeeId,
                'approval_required' => $data['approval_required'],
                'estimated_reach' => $data['estimated_reach'],
                'status' => 'draft',
            ]);

            $this->campaignRepo->createOffer($campaign, $data['offer']);

            // Pass preview if it exists in request
            $previewData = $data['preview'] ?? [
                'email_html' => null,
                'push_message' => null,
            ];
            $this->campaignRepo->createPreview($campaign, $previewData);

            return $campaign->fresh(['offer', 'preview']);
        });
    }

    public function index(array $filters = [], $perPage)
    {
        return $this->campaignRepo->paginate($filters, $perPage);

    }

    public function findById($id)
    {
        return $this->campaignRepo->findById($id);
    }

    public function update($id, array $data): Campaign
    {
        return DB::transaction(function () use ($id, $data) {
            $campaign = $this->campaignRepo->findById($id);

            if (!$campaign) {
                throw new ModelNotFoundException("Campaign not found.");
            }

            // Update campaign main fields
            $campaign->update([
                'name' => $data['name'] ?? $campaign->name,
                'channels' => $data['channels'] ?? $campaign->channels,
                'estimated_reach' => $data['estimated_reach'] ?? $campaign->estimated_reach
            ]);

            // Update offer if present
            if (isset($data['offer'])) {
                $campaign->offer()->update([
                    'type' => $data['offer']['type'] ?? $campaign->offer->type,
                    'value' => $data['offer']['value'] ?? $campaign->offer->value,
                    'min_booking' => $data['offer']['min_booking'] ?? $campaign->offer->min_booking,
                ]);
            }

            // Update preview if present
            if (isset($data['content_preview'])) {
                $campaign->preview()->update([
                    'email_html' => $data['content_preview']['email'] ?? $campaign->preview->email_html,
                    'push_message' => $data['content_preview']['push'] ?? $campaign->preview->push_message,
                ]);
            }

            return $campaign->refresh();
        });
    }
    public function approve($id): Campaign
    {
        return DB::transaction(function () use ($id) {
            $campaign = $this->campaignRepo->findById($id);

            if (!$campaign) {
                throw new ModelNotFoundException("Campaign not found.");
            }

            if ($campaign->is_approved) {
                throw new \Exception("Campaign is already approved.");
            }

            if (!$campaign->approval_required) {
                throw new \Exception("Campaign does not require approval.");
            }

            $employeeId = auth()->id();

            if ($campaign->created_by == $employeeId) {
                throw new \Exception("You cannot approve a campaign you created.");
            }


            $campaign->update([
                'is_approved' => true,
                'status' => 'approved',
                'approved_by' => $employeeId,
            ]);

            return $campaign->refresh();
        });
    }

    public function archive(int $id): Campaign
    {
        $campaign = $this->campaignRepo->findById($id);

        if (!$campaign) {
            throw new ModelNotFoundException("Campaign not found.");
        }

        if ($campaign->status === 'archived') {
            throw new \Exception("Campaign is already archived.");
        }

        return $this->campaignRepo->archive($id);
    }

    public function delete($id): void
    {
        $campaign = $this->campaignRepo->findById($id);
        if (!$campaign) {
            throw new ModelNotFoundException("Campaign not found.");
        }
        $this->campaignRepo->delete($campaign);
    }

}