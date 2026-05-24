<?php

namespace App\Http\Controllers\V1\CRM\ContactInfo;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CRM\ContactInfo\StoreContactInfoRequest;
use App\Http\Requests\V1\CRM\ContactInfo\UpdateContactInfoRequest;
use App\Http\Resources\V1\CRM\ContactInfo\ContactInfoResource;
use App\Http\Resources\V1\CRM\ContactInfo\PaginatedContactInfoListResource;
use App\Services\V1\CRM\ContactInfoService;
use App\DataTransferObjects\ContactInfoDTOs\ContactInfoDTO;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContactInfoController extends Controller
{
    public function __construct(private ContactInfoService $service) {}

    public function index(Request $request)
    {
        try {
            $result = $this->service->list($request);
            return ApiResponse::success('Contact info fetched successfully.', new PaginatedContactInfoListResource($result), 200);
        } catch (Throwable $e) {
            return ApiResponse::error('An error occurred.', [$e->getMessage()], 500);
        }
    }

    public function store(StoreContactInfoRequest $request)
    {
        try {
            $created = $this->service->create(ContactInfoDTO::fromRequest($request));
            return ApiResponse::success('Contact info created successfully.', new ContactInfoResource($created), 201);
        } catch (Throwable $e) {
            return ApiResponse::error('Failed to create contact info.', [$e->getMessage()], 500);
        }
    }

    public function show(int $id)
    {
        try {
            $contactInfo = $this->service->find($id);
            return ApiResponse::success('Contact info fetched successfully.', new ContactInfoResource($contactInfo), 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Contact info not found.', [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error('An error occurred.', [$e->getMessage()], 500);
        }
    }

    public function update(UpdateContactInfoRequest $request, int $id)
    {
        try {
            $updated = $this->service->update($id, ContactInfoDTO::fromRequest($request));
            return ApiResponse::success('Contact info updated successfully.', new ContactInfoResource($updated), 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Contact info not found.', [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error('An error occurred.', [$e->getMessage()], 500);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->service->delete($id);
            return ApiResponse::success('Contact info deleted successfully.', [], 200);
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error('Contact info not found.', [], 404);
        } catch (Throwable $e) {
            return ApiResponse::error('An error occurred.', [$e->getMessage()], 500);
        }
    }
}
