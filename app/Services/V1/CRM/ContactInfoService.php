<?php

namespace App\Services\V1\CRM;

use App\DataTransferObjects\ContactInfoDTOs\ContactInfoDTO;
use App\Http\Repository\V1\CRM\ContactInfo\ContactInfoRepository;
use Illuminate\Http\Request;

class ContactInfoService
{
    public function __construct(private ContactInfoRepository $repository) {}

    public function list(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        return $this->repository->getAll($perPage,$request->only(['hotel_location_id', 'type']));
    }

    public function create(ContactInfoDTO $dto)
    {
        return $this->repository->create($dto);
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }

    public function update(int $id, ContactInfoDTO $dto)
    {
        $contactInfo = $this->find($id);
        return $this->repository->update($contactInfo, $dto);
    }

    public function delete(int $id): void
    {
        $contactInfo = $this->find($id);
        
        $this->repository->delete($contactInfo);
    }
}
