<?php

namespace App\Http\Controllers\V1\CRM\Offer;

use App\Http\Controllers\Controller;
use App\Http\Repository\V1\CRM\Offer\OfferRepository;
use App\Http\Requests\V1\CRM\Offer\StoreOfferRequest;
use App\Http\Requests\V1\CRM\Offer\UpdateOfferRequest;
use App\Http\Resources\V1\CRM\Offer\OfferResource;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function __construct(protected OfferRepository $offerRepository)
    {
    }

    public function store(StoreOfferRequest $request)
    {
        try {
            $offer = $this->offerRepository->store($request->validated());
            return response()->json([
                'success' => true,
                'statusCode' => 201,
                'messge' => 'Offer created successfully.',
                'data' => new OfferResource($offer)
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Offer could not be created.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }

    public function update(UpdateOfferRequest $request, int $id)
    {
        try {
            $offer = $this->offerRepository->update($request->validated(), $id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Offer updated successfully',
                'data' => new OfferResource($offer)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Offer could not be updated.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
    public function index(Request $request)
    {
        try {
            $offers = $this->offerRepository->getAll($request->input('per_page', 10));
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Offers fetched successfully',
                'data' => OfferResource::collection($offers),
                'meta' => [
                    'current_page' => $offers->currentPage(),
                    'total' => $offers->total(),
                    'per_page' => $offers->perPage(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Offers could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
    public function show(int $id)
    {
        try {
            $offer = $this->offerRepository->get($id);

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Offer fetched successfully',
                'data' => new OfferResource($offer)
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Offer could not be fetched.',
                'errors' => $e->getMessage()
            ], 404);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Offer could not be fetched.',
                'errors' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Offer could not be fetched.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
    public function destroy(int $id)
    {
        try {
            $this->offerRepository->delete($id);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Offer deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500,
                'message' => 'Offer could not be deleted.',
                'errors' => $e->getMessage()
            ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
        }
    }
}
