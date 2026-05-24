<?php

namespace App\Http\Controllers\V1\Api\Service;

use App\Http\Controllers\Controller;
use App\Http\Repository\V1\Api\Service\ServiceRepository;
use App\Http\Resources\V1\CRM\Service\ServiceResource;
use App\Traits\UsesHotelScope;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function __construct(private ServiceRepository $repo)
    {
    }

    public function index(Request $request)
    {
        try {
            $services = $this->repo->filter($request);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'messge' => __('service.services_fetched'),
                'data' => ServiceResource::collection($services),
                'meta' => [
                    'current_page' => $services->currentPage(),
                    'total' => $services->total(),
                    'per_page' => $services->perPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => __('service.service_not_found'),
                'errors' => $e->getMessage()
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => __('service.services_error'),
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
}
