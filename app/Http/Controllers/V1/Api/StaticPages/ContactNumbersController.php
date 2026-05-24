<?php

namespace App\Http\Controllers\V1\Api\StaticPages;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\V1\Api\StaticPages\ContactNumbersResource;
use App\Http\Repository\V1\Api\StaticPages\ContactNumbersRepository;
class ContactNumbersController extends Controller
{
    protected $contactNumbersRepository;

    public function __construct(ContactNumbersRepository $contactNumbersRepository)
    {
        $this->contactNumbersRepository = $contactNumbersRepository;
    }
    public function showContactNumbers()
    {
        $result= $this->contactNumbersRepository->showContactNumbersRepository();
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('staticPages.data_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('staticPages.data_fetched_successfully'),
            ContactNumbersResource::collection($result['contactNumbersData']),
            200
        );
    }
    // public function addContactNumbers(Request  $request)
    // {
    //     $result= $this->contactNumbersRepository->storeContactNumbersRepository($request);
    //     if ($result['status'] === 'not_found') {
    //         return ApiResponse::error(__('staticPages.data_not_found'), [], 404);
    //     }
    //     return ApiResponse::success(
    //         __('staticPages.data_added_successfully'),
    //         [],
    //         200
    //     );
    // }
}
