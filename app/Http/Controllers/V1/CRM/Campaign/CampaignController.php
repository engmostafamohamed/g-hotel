<?php

namespace App\Http\Controllers\V1\CRM\Campaign;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Campaign\StoreCampaignRequest;
use App\Http\Requests\V1\CRM\Campaign\UpdateCampaignRequest;
use App\Http\Resources\V1\CRM\Campaign\CampaignResource;
use App\Services\CRM\CampaignService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function __construct(private CampaignService $campaignService)
    {
    }

    public function store(StoreCampaignRequest $request)
    {
        try {

            $campaign = $this->campaignService->createCampaign($request->validated(), auth()->id());
            return response()->json([
                'success' => true,
                'statusCode' => '201',
                'message' => 'Campaign created successfully',
                'data' => new CampaignResource($campaign)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Campaign could not be created.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
    public function index(Request $request)
    {
        try {
            $filters = $request->only(['status', 'is_approved', 'approval_required']);
            $perPage = $request->input('per_page', 10);

            $campaigns = $this->campaignService->index($filters, $perPage);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Campaigns fetched successfully',
                'data' => CampaignResource::collection($campaigns),
                'meta' => [
                    'current_page' => $campaigns->currentPage(),
                    'total' => $campaigns->total(),
                    'per_page' => $campaigns->perPage(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Campaign could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function show($id)
    {
        try {
            $campaign = $this->campaignService->findById($id);

            if (!$campaign) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 404,
                    'message' => 'Campaign not found.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Campaign fetched successfully',
                'data' => new CampaignResource($campaign),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => 'Campaign could not be fetched.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateCampaignRequest $request, $id)
    {
        try {
            $campaign = $this->campaignService->update($id, $request->validated());

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Campaign updated successfully.',
                'data' => new CampaignResource($campaign),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => 'Campaign could not be updated.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }


    public function approve($id)
    {
        try {
            $campaign = $this->campaignService->approve($id);

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Campaign approved successfully.',
                'data' => new CampaignResource($campaign),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Campaign not found.',
                'errors' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => 'Campaign could not be approved.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    public function archive($id)
    {
        try {
            $campaign = $this->campaignService->archive($id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Campaign archived successfully.',
                'data' => new CampaignResource($campaign)
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Campaign not found.',
                'errors' => $e->getMessage(),
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => 'Could not archive campaign.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
{
    try {
        $this->campaignService->delete($id);

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Campaign deleted successfully.',
        ], 200);
        
    } catch (ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'statusCode' => 404,
            'message' => 'Campaign not found.',
            'errors' => $e->getMessage()
        ], 404);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'statusCode' => 500,
            'message' => 'Failed to delete campaign.',
            'errors' => $e->getMessage()
        ], 500);
    }
}

}
