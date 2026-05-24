<?php

namespace App\Contracts\ContactInfo;

use App\DataTransferObjects\ContactInfoDTOs\ContactInfoDTO;
use App\Models\ContactInfo;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
interface ContactInfoRepositoryInterface
{
    public function getAll(int $per_page,array $filters): LengthAwarePaginator;
    public function create(ContactInfoDTO $dto): ContactInfo;
    public function update(ContactInfo $contactInfo, ContactInfoDTO $dto): ContactInfo;
    public function delete(ContactInfo $contactInfo): void;
    public function find(int $id): ContactInfo;
}
