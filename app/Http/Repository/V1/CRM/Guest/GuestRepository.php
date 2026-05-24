<?php

namespace App\Http\Repository\V1\CRM\Guest;

use App\Contracts\Guest\GuestRepositoryInterface;
use App\Models\Guest;
use App\Models\Tier;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Str;

class GuestRepository implements GuestRepositoryInterface
{
    protected array $availableFields = [
        'id',
        'first_name',
        'last_name',
        'email',
        'loyalty_tier',
        'member_since',
        'is_verified',
        'phone_no'
    ];

    public function export(array $fields, string $format): StreamedResponse|JsonResponse
    {
        $fields = array_intersect($fields, $this->availableFields);

        if (empty($fields)) {
            abort(400, 'No valid fields provided for export.');
        }

        $guests = Guest::select($fields)->limit(10000)->get();

        if ($guests->count() >= 10000) {
            return response()->json([
                'success' => false,
                'statusCode' => 413,
                'message' => 'Payload Too Large. Please filter to fewer than 10,000 records.',
            ], 413);
        }

        if (!in_array($format, ['csv', 'json', 'xlsx', 'excel'])) {
            return response()->json([
                'success' => false,
                'statusCode' => 406,
                'message' => 'Format not supported.',
            ], 406);
        }

        switch ($format) {
            case 'csv':
                return $this->exportCsv($guests, $fields);
            case 'json':
                return $this->exportJson($guests);
            case 'xlsx':
            case 'excel':
                return $this->exportExcel($guests, $fields);
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Format not supported.',
                ], 406);
        }
    }

    protected function exportCsv($guests, $fields): StreamedResponse
    {
        $filename = 'guests_' . now()->timestamp . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
        ];

        $callback = function () use ($guests, $fields) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $fields);
            foreach ($guests as $guest) {
                fputcsv($handle, $guest->only($fields));
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function exportJson($guests): StreamedResponse
    {
        $filename = 'guests_' . now()->timestamp . '.json';
        $headers = [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function () use ($guests) {
            echo json_encode($guests, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        };

        return response()->stream($callback, 200, $headers);
    }

    protected function exportExcel($guests, $fields): StreamedResponse
    {
        // Generate raw Excel XML manually
        $filename = 'guests_' . now()->timestamp . '.xls';
        $headers = [
            "Content-type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename={$filename}",
        ];

        $callback = function () use ($guests, $fields) {
            $output = "<table><tr>";
            foreach ($fields as $field) {
                $output .= "<th>{$field}</th>";
            }
            $output .= "</tr>";
            foreach ($guests as $guest) {
                $output .= "<tr>";
                foreach ($fields as $field) {
                    $output .= "<td>{$guest->$field}</td>";
                }
                $output .= "</tr>";
            }
            $output .= "</table>";

            echo $output;
        };

        return response()->stream($callback, 200, $headers);
    }

    // public function exportGuests(Request $request): StreamedResponse
    // {
    //     $format = $request->get('format', 'csv');
    //     $fields = explode(',', $request->get('fields', 'id,name,email'));

    //     // Validate supported formats
    //     if (!in_array($format, ['csv', 'json'])) {
    //         abort(406, 'Unsupported format requested.');
    //     }

    //     // Limit the payload
    //     $count = Guest::count();
    //     if ($count > 10000) {
    //         abort(413, 'Payload Too Large');
    //     }

    //     if ($format === 'json') {
    //         $guests = Guest::select($fields)->get();

    //         return response()->streamDownload(function () use ($guests) {
    //             echo $guests->toJson(JSON_PRETTY_PRINT);
    //         }, 'guests_export.json', [
    //             'Content-Type' => 'application/json',
    //             'Content-Disposition' => 'attachment; filename="guests_export.json"',
    //         ]);
    //     }

    //     // CSV Export
    //     $filename = 'guests_export_' . now()->format('Ymd_His') . '.csv';

    //     return Response::streamDownload(function () use ($fields) {
    //         $handle = fopen('php://output', 'w');

    //         // Write header row
    //         fputcsv($handle, $fields);

    //         // Write rows in chunks
    //         Guest::select($fields)->chunk(500, function ($guests) use ($handle, $fields) {
    //             foreach ($guests as $guest) {
    //                 fputcsv($handle, $guest->only($fields));
    //             }
    //         });

    //         fclose($handle);
    //     }, $filename, [
    //         'Content-Type' => 'text/csv',
    //         'Content-Disposition' => "attachment; filename=\"$filename\"",
    //     ]);
    // }

    public function filterGuests(Request $request, $perPage)
    {
        $query = Guest::query()
            // ->where('is_loyalty_member', true)
            ->with(['city', 'tier'])
            ->whereNull('deleted_at');

        $filters = [];

        // Tier
        // if ($request->filled('tier')) {
        //     $tier = strtolower($request->query('tier'));
        //     if (!array_key_exists($tier, self::TIER_MAP)) {
        //         throw new Exception("Invalid tier.");
        //     }
        //     $query->where('loyalty_tier', self::TIER_MAP[$tier]);
        //     $filters['tier'] = $tier;
        // }
        if ($request->filled('tier')) {
            $tier = strtolower($request->query('tier'));

            $tierModel = Tier::where('tier_name->en', ucfirst($tier))
                ->orWhere('tier_name->ar', $tier)
                ->first();

            if (!$tierModel) {
                throw new Exception("Invalid tier.");
            }

            $query->where('tier_id', $tierModel->id);
            $filters['tier'] = $tier;
        }

        // Status
        if ($request->filled('status')) {
            $status = strtolower($request->query('status'));
            if (!in_array($status, ['active', 'suspended'])) {
                throw new Exception('Invalid status.');
            }
            $query->where('status', $status);
            $filters['status'] = $status;
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
            $filters['search'] = $search;
        }

        // Paginate
        $guests = $query->orderByDesc('id')->paginate($perPage);

        return $guests;
    }

    public function get(int $id)
    {
        return Guest::findOrFail($id);
    }

    public function getForDropdown(Request $request)
    {
        $query = Guest::query()
            ->select('id', 'first_name', 'last_name')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = trim($request->input('search'));

                $q->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->when($request->boolean('active_bookings'), function ($q) {
                $now = now()->toDateString();

                $q->whereHas('bookings', function ($bookingQuery) use ($now) {
                    $bookingQuery->where('arrival_date', '<=', $now)
                        ->where('departure_date', '>=', $now);
                });
            });

        return $query->paginate($request->get('per_page', 10));
    }


}
