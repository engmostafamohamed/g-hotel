<?php

namespace App\Services\V1\CRM;

use App\DataTransferObjects\FeatureDTOs\FeatureDTO;
use App\Http\Repository\V1\CRM\Feature\FeatureRepository;
use App\Services\FileUploadService;
use Illuminate\Http\Request;

class FeatureService
{
    public function __construct(
        private FeatureRepository $repository,
        private FileUploadService $fileUploadService
    ) {}

    public function list(Request $request)
    {
        $filters = $request->only(['hotel_id']);
        // $filters= [];
        
        // if ($request->input('hotel_id')) {
        //     $filters['hotel_id'] = $request->input('hotel_id');
        // }

        return $this->repository->getAll($filters);
    }

    // public function listUnpaginated(Request $request)
    // {
    //     $filters = $request->only(['hotel_id']);

    //     return $this->repository->getAllUnpaginated($filters);
    // }

    public function create(FeatureDTO $dto)
    {
        if ($dto->logo) {
            $dto->logoPath = $this->fileUploadService->upload($dto->logo, 'uploads/featureLogos');
        }

        return $this->repository->create($dto);
    }

    public function update(int $id, FeatureDTO $dto)
    {
        if ($dto->logo) {
            // Find the existing restaurant record
            $feature = $this->repository->find($id);

            // Delete old image if it exists
            if ($feature->logo) {
                $oldFilePath = public_path('uploads/featureLogos/' . basename($feature->logo));
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Upload the new image and set the path in DTO
            $dto->logoPath = $this->fileUploadService->upload($dto->logo, 'uploads/featureLogos');
        }

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
}