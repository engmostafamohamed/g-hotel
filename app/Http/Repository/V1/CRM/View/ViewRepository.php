<?php

namespace App\Http\Repository\V1\CRM\View;

use App\Contracts\View\ViewRepositoryInterface;
use App\Models\View;
use Illuminate\Support\Collection;

class ViewRepository implements ViewRepositoryInterface
{
    public function getAll(): Collection
    {
        return View::all();
    }
}
