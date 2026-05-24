<?php

namespace App\Services\V1\CRM;


use App\DataTransferObjects\StaticPageDTOs\StaticPageDTO;
use App\Http\Repository\V1\CRM\StaticPage\StaticPageRepository;
use App\Models\StaticPage;

class StaticPageService
{
    public function __construct(private StaticPageRepository $repository) {}

    public function getBySlug(string $slug): StaticPage
    {
        return $this->repository->findBySlug($slug);
    }

    public function update(string $slug, StaticPageDTO $dto): StaticPage
    {
        return $this->repository->updateBySlug($slug, $dto);
    }

    public function listAll()
    {
        return $this->repository->getAll();
    }
}
