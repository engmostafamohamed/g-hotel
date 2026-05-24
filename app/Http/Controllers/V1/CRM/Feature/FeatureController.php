<?php

namespace App\Http\Controllers\V1\CRM\Feature;

use App\DataTransferObjects\FeatureDTOs\FeatureDTO;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Feature\StoreFeatureRequest;
use App\Http\Requests\V1\CRM\Feature\UpdateFeatureRequest;
use App\Http\Resources\V1\CRM\Feature\FeatureResource;
use App\Http\Resources\V1\CRM\Feature\PaginatedFeatureListResource;
use App\Services\V1\CRM\FeatureService;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class FeatureController extends Controller
{
    public function __construct(private FeatureService $service) {}

    public function index(Request $request)
    {
        try {
            $features = $this->service->list($request);

            return ApiResponse::success(
                __('feature.fetched'),
                new PaginatedFeatureListResource($features),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('feature.unexpected'),
                [$e->getMessage()],
                500
            );
        }
    }

    // public function indexUnpaginated(Request $request)
    // {
    //     try {
    //         $features = $this->service->listUnpaginated($request);

    //         return ApiResponse::success(
    //             'Features fetched successfully.',
    //             FeatureResource::collection($features),
    //             200
    //         );
    //     } catch (Throwable $e) {
    //         return ApiResponse::error(
    //             'An unexpected error occurred.',
    //             [$e->getMessage()],
    //             500
    //         );
    //     }
    // }

    public function store(StoreFeatureRequest $request)
    {
        try {
            $feature = $this->service->create(FeatureDTO::fromRequest($request));

            return ApiResponse::success(
                __('feature.created'),
                new FeatureResource($feature),
                201
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('feature.unexpected_create'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function show(int $id)
    {
        try {
            $feature = $this->service->find($id);

            return ApiResponse::success(
                __('feature.fetched_single'),
                new FeatureResource($feature),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                __('feature.not_found'),
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('feature.unexpected'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function update(UpdateFeatureRequest $request, int $id)
    {
        try {
            $feature = $this->service->update($id, FeatureDTO::fromRequest($request));

            return ApiResponse::success(
                __('feature.updated'),
                new FeatureResource($feature),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                __('feature.not_found'),
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('feature.unexpected_update'),
                [$e->getMessage()],
                500
            );
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->service->delete($id);

            return ApiResponse::success(
                __('feature.deleted'),
                [],
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                __('feature.not_found'),
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('feature.unexpected_delete'),
                [$e->getMessage()],
                500
            );
        }
    }
}