<?php
namespace App\Http\Repository\V1\CRM\Logs;
use Illuminate\Http\Request;
use App\Http\Resources\V1\CRM\Logs\LogsResource;
use App\Contracts\V1\CRM\Logs\LogsRepositoryInterface;
use App\DataTransferObjects\Loyalty\LogsDTOs\LogsDTO;
use App\Models\Log;
use Illuminate\Database\QueryException;
use Exception;

class LogsRepository implements LogsRepositoryInterface
{
    public function showLogsRepository(Request $request)    {
        $query = Log::with('employee')->latest();
        $perPage = $request->input('per_page', 10);
        //  filtering by employee_id
        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        // filtering by action (create, update, delete)
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // filtering by model_type (Service)
        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        // filtering by date (from - to)
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $logs = $query->paginate($perPage);

        if ($logs->isEmpty()) {
            return ['status' => 'logs_not_found'];
        }

        return [
            'status' => true,
            'data'    => $logs
        ];
    }
}
