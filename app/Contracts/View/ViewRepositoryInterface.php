<?php

namespace App\Contracts\View;

use Illuminate\Support\Collection;

interface ViewRepositoryInterface
{
    public function getAll(): Collection;
}
