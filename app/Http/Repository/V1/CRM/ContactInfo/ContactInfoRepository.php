<?php

namespace App\Http\Repository\V1\CRM\ContactInfo;

use App\Contracts\ContactInfo\ContactInfoRepositoryInterface;
use App\DataTransferObjects\ContactInfoDTOs\ContactInfoDTO;
use App\Models\ContactInfo;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class ContactInfoRepository implements ContactInfoRepositoryInterface
{
    public function getAll(int  $per_page,array $filters): LengthAwarePaginator
    {
        $query = ContactInfo::query();

        if (!empty($filters['hotel_location_id'])) {
            $query->where('hotel_location_id', $filters['hotel_location_id']);
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->paginate($per_page > 0 ? $per_page : 15);
    }

    public function create(ContactInfoDTO $dto): ContactInfo
    {
        return ContactInfo::create((array) $dto);
    }

    public function update(ContactInfo $contactInfo, ContactInfoDTO $dto): ContactInfo
    {
        $data = [];

        if (!is_null($dto->hotel_location_id)) {
            $data['hotel_location_id'] = $dto->hotel_location_id;
        }

        if (!is_null($dto->type)) {
            $data['type'] = $dto->type;
        }

        if (!is_null($dto->label)) {
            $data['label'] = $dto->label;
        }

        if (!is_null($dto->value)) {
            $data['value'] = $dto->value;
        }

        $contactInfo->update($data);

        return $contactInfo;
    }


    public function delete(ContactInfo $contactInfo): void
    {
        $contactInfo->delete();
    }

    public function find(int $id): ContactInfo
    {
        return ContactInfo::findOrFail($id);
    }
}
