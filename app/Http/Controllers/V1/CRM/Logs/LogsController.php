<?php

namespace App\Http\Controllers\V1\CRM\Logs;
use App\Http\Repository\V1\CRM\Logs\LogsRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\Log;
use App\Http\Resources\V1\CRM\Logs\PaginatedLogsListResource;
class LogsController extends Controller
{
    public function __construct(private LogsRepository $logsRepository) {}

    public function index(Request  $request ){

        $result= $this->logsRepository->showLogsRepository($request);
        if ($result['status'] === 'logs_not_found') {
            return ApiResponse::error(__('logs.logs_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('logs.data_fetched_successfully'),
            new PaginatedLogsListResource($result['data']),
            200
        );
    }
}
