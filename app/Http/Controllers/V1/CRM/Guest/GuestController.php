<?php

namespace App\Http\Controllers\V1\CRM\Guest;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Repository\V1\CRM\Guest\GuestRepository;
use App\Http\Resources\V1\CRM\Guest\GuestResource;
use App\Http\Resources\V1\CRM\Guest\PaginatedGuestListResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Throwable;

class GuestController extends Controller
{
    //
    public function __construct(protected GuestRepository $guestRepository)
    {
    }

    public function export(Request $request)
    {
        $fields = explode(',', $request->query('fields', 'id,first_name,last_name,email'));
        $format = strtolower($request->query('format', 'csv'));

        return $this->guestRepository->export($fields, $format);
        // return $this->guestRepository->exportGuests($request);
    }

    public function index(Request $request)
    {
        try {
            $guests = $this->guestRepository->filterGuests($request, $request->input('per_page', 50));

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Guests fetched successfully',
                'data' => GuestResource::collection($guests),
                'meta' => [
                    'current_page' => $guests->currentPage(),
                    'total' => $guests->total(),
                    'per_page' => $guests->perPage(),
                    'total_count' => $guests->total(),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => "Guests not found.",
                'errors' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Guests could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
    public function get(int $id)
    {
        try {
            $guest = $this->guestRepository->get($id);

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Guest fetched successfully',
                'data' => new GuestResource($guest),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => "Guest not found.",
                'errors' => $e->getMessage()
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Guest could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function listNames(Request $request)
    {
        try{
            $guests = $this->guestRepository->getForDropdown($request);

            return ApiResponse::success(
                __('guest.fetched'),
                new PaginatedGuestListResource($guests),
                200
            );
        } catch (Throwable $e) {
            return ApiResponse::error(
                __('guest.unexpected'),
                [$e->getMessage()],
                500
            );
        }
    }
}
