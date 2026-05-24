<?php

namespace App\Http\Controllers\V1\CRM\Dashboard;

use App\DataTransferObjects\V1\CRM\Dashboard\DashboardFilterDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\Dashboard\DashboardRequest;
use App\Services\V1\CRM\Dashboard\DashboardService;
use App\Http\Resources\V1\CRM\Dashboard\DashboardOverviewResource;
use App\Helpers\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $service) {}

    /**
     * Return dashboard overview with all widgets.
     */
    public function overview(DashboardRequest $request)
    {
        try {
            $filters = DashboardFilterDTO::fromRequest($request);
            $overview = $this->service->getOverview($filters);

            return ApiResponse::success(
                __('dashboard.fetched'),
                new DashboardOverviewResource($overview),
                200
            );
        } catch (AuthorizationException $e) {
            return ApiResponse::error($e->getMessage() ?: __('dashboard.unauthorized'), [], 403);
        } catch (Throwable $e) {
            return ApiResponse::error(__('dashboard.unexpected'), [$e->getMessage()], 500);
        }
    }
}
