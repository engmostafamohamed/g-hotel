<?php

namespace App\DataTransferObjects\PermissionDTOs;

use App\Http\Requests\V1\CRM\Permission\StorePermissionRequest;
use App\Http\Requests\V1\CRM\Permission\UpdatePermissionRequest;

class PermissionDTO
{
    public string $name;
    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromRequest(StorePermissionRequest|UpdatePermissionRequest $request): self
    {
        return new self(
            $request->input('name')
        );
    }
}
