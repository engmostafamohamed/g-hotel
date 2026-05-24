<?php

namespace App\Services\V1\CRM;

use App\DataTransferObjects\RestaurantDTOs\RestaurantDTO;
use App\DataTransferObjects\RestaurantDTOs\AvailabilityDTO;
use App\Http\Repository\V1\CRM\Restaurant\RestaurantRepository;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class RestaurantService
{
    public function __construct(
        private RestaurantRepository $repository,
        private FileUploadService $fileUploadService
    ) {
    }

    public function list(Request $request)
    {
        $filters = $request->only(['hotel_id', 'cuisine', 'currently_open']);

        return $this->repository->list($filters);
    }

    // public function listUnpaginated(Request $request)
    // {
    //     $filters = $request->only(['hotel_id', 'cuisine', 'currently_open']);

    //     return $this->repository->listUnpaginated($filters);
    // }

    public function create(RestaurantDTO $dto)
    {
        if ($dto->image) {
            $dto->imagePath = $this->fileUploadService->upload($dto->image, 'uploads/restaurantImages');
        }

        return $this->repository->create($dto);
    }

    public function update(int $id, RestaurantDTO $dto)
    {
        if ($dto->image) {
            // Find the existing restaurant record
            $restaurant = $this->repository->find($id);

            // Delete old image if it exists
            if ($restaurant->image_url) {
                $oldFilePath = public_path('uploads/restaurantImages/' . basename($restaurant->image_url));
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Upload the new image and set the path in DTO
            $dto->imagePath = $this->fileUploadService->upload($dto->image, 'uploads/restaurantImages');
        }

        // Pass DTO to repository for DB update
        return $this->repository->update($id, $dto);
    }


    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function availability(AvailabilityDTO $request, $id)
    {
        return $this->repository->availability($request);
    }
    public function getRestaurantReservationsForRestaurant($id, $filters)
    {
        return $this->repository->getRestaurantReservationsForRestaurant($id, $filters);
    }

}
