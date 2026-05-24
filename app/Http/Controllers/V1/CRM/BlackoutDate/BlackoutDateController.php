<?php

namespace App\Http\Controllers\V1\CRM\BlackoutDate;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\V1\CRM\BlackoutDate\PaginatedBlackoutDateListResource;
use App\Http\Resources\V1\CRM\BlackoutDate\BlackoutDateResource;
use App\Http\Requests\V1\CRM\BlackoutDate\StoreBlackoutDateRequest;
use App\Http\Requests\V1\CRM\BlackoutDate\UpdateBlackoutDateRequest;
use App\Http\Requests\V1\CRM\BlackoutDate\BlackoutDateReqest;
use App\Http\Repository\V1\CRM\BlackoutDate\BlackoutDateRepository;
use App\DataTransferObjects\BlackoutDateDTOs\BlackoutDateDTO;
class BlackoutDateController extends Controller
{
    protected $BlackoutDate;

    public function __construct(private BlackoutDateRepository $blackoutDate){}
    public function showAllBlackoutDates(Request  $request)
    {
        $result= $this->blackoutDate->showBlackoutDatesRepository($request);
        if ($result['status'] === 'blackoutDate_not_found') {
            return ApiResponse::error(__('blackoutDate.blackoutDate_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('blackoutDate.data_fetched_successfully'),
            new PaginatedBlackoutDateListResource($result['blackoutDates']),
            200
        );
    }
    public function showBlackoutDate(BlackoutDateReqest  $request,$id)
    {
        $result= $this->blackoutDate->showBlackoutDateRepository($id,$request);
        if ($result['status'] === 'blackoutDate_not_found') {
            return ApiResponse::error(__('blackoutDate.blackoutDate_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('BlackoutDate.data_fetched_successfully'),
            new BlackoutDateResource($result['blackoutDate']),
            200
        );
    }
    public function addBlackoutDate(StoreBlackoutDateRequest  $request)
    {
        $result= $this->blackoutDate->storeBlackoutDateRepository(BlackoutDateDTO::fromRequest($request));
        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('blackoutDate.error_happend'), [], 500);
        }
        return ApiResponse::success(
            __('blackoutDate.data_added_successfully'),
            [],
            201
        );
    }

    public function updateBlackoutDate(UpdateBlackoutDateRequest $request, int $id)
    {
        $result= $this->blackoutDate->updateBlackoutDateRepository($id, BlackoutDateDTO::fromRequest($request));
        if ($result['status'] === 'not_have_date') {
            return ApiResponse::error(__('blackoutDate.not_have_date_to_update'), [], 200);
        }

        if ($result['status'] === 'db_error' || $result['status'] === 'error') {
            return ApiResponse::error(__('blackoutDate.error_happend'), [], 500);
        }

        return ApiResponse::success(
            __('blackoutDate.data_updated_successfully'),
            [],
            201
        );
    }

    public function deleteBlackoutDate(Request $request,int $id)
    {
        $result= $this->blackoutDate->deleteBlackoutDateRepository($request,$id);
        if ($result['status'] === 'blackoutDate_not_found') {
            return ApiResponse::error(__('blackoutDate.blackoutDate_id_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('blackoutDate.data_deleted_successfully'),
            [],
            201
        );
    }
}
