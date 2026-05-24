<?php

namespace App\Services\V1\CRM;

use App\Http\Repository\V1\CRM\View\ViewRepository;


class ViewService
{
    public function __construct(private ViewRepository $repository) {}

    public function list()
    {
        return $this->repository->getAll();
    }
}
