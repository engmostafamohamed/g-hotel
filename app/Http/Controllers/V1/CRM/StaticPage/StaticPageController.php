<?php

namespace App\Http\Controllers\V1\CRM\StaticPage;

use App\DataTransferObjects\StaticPageDTOs\StaticPageDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\StaticPage\UpdateStaticPageRequest;
use App\Http\Resources\V1\CRM\StaticPage\StaticPageResource;
use App\Http\Resources\V1\CRM\StaticPage\StaticPageSummaryResource;
use App\Services\V1\CRM\StaticPageService;
use App\Helpers\ApiResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class StaticPageController extends Controller
{
    public function __construct(private StaticPageService $staticPageService) {}

    public function show(string $slug)
    {
        try {
            $page = $this->staticPageService->getBySlug($slug);

            return ApiResponse::success(
                'Page fetched successfully.',
                new StaticPageResource($page),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                'Page not found.',
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                'An unexpected error occurred while fetching the page.',
                [$e->getMessage()],
                500
            );
        }
    }

    public function update(UpdateStaticPageRequest $request, string $slug)
    {
        try {
            $dto = StaticPageDTO::fromRequest($request);
            $page = $this->staticPageService->update($slug, $dto);

            return ApiResponse::success(
                'Page updated successfully.',
                new StaticPageResource($page),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                'Page not found.',
                [],
                404
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                'An unexpected error occurred while updating the page.',
                [$e->getMessage()],
                500
            );
        }
    }

    public function index()
    {
        try {
            $pages = $this->staticPageService->listAll();

            return ApiResponse::success(
                'Pages fetched successfully.',
                StaticPageSummaryResource::collection($pages),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                'An unexpected error occurred while fetching pages.',
                [$e->getMessage()],
                500
            );
        }
    }
}