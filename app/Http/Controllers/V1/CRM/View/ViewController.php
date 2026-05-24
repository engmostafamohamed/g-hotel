<?php

namespace App\Http\Controllers\V1\CRM\View;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CRM\View\ViewResource;
use App\Services\V1\CRM\ViewService;
use Illuminate\Http\Request;
use Throwable;

class ViewController extends Controller
{
    public function __construct(private ViewService $service) {}

    public function index(Request $request)
    {
        try {
            $views = $this->service->list();

            return ApiResponse::success(
                __('view.fetched_successfully'),
                ViewResource::collection($views),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('view.unexpected_error'),
                [$e->getMessage()],
                500
            );
        }
    }

}
