<?php
namespace App\Contracts\StaticPages;

use App\DataTransferObjects\StaticPageDTOs\StaticPageDTO;
use App\Models\StaticPage;

interface StaticPageRepositoryInterface
{
    public function findBySlug(string $slug): StaticPage;

    public function updateBySlug(string $slug, StaticPageDTO $dto): StaticPage;

    public function getAll();
}