<?php

namespace App\Http\Repository\V1\CRM\StaticPage;


use App\Contracts\StaticPages\StaticPageRepositoryInterface;
use App\Models\StaticPage;
use App\DataTransferObjects\StaticPageDTOs\StaticPageDTO;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class StaticPageRepository implements StaticPageRepositoryInterface
{
    public function findBySlug(string $slug): StaticPage
    {
        return StaticPage::where('slug', $slug)->firstOrFail();
    }


    public function updateBySlug(string $slug, StaticPageDTO $dto): StaticPage
    {
        $page = $this->findBySlug($slug);

        $page->setTranslations('title', $dto->title);
        $page->setTranslations('content', $dto->content);

        if (!is_null($dto->is_active)) {
            $page->is_active = $dto->is_active;
        }

        $page->save();

        return $page;
    }

    public function getAll()
    {
        return StaticPage::select(['title', 'slug', 'is_active'])->get();
    }
}
