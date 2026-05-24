<?php

namespace App\Http\Repository\V1\CRM\LiveStyleImage;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\DataTransferObjects\RoleDTOs\RoleDTO;

interface LiveStyleImageRepositoryInterface
{
    public function showAllLiveStyleImagesRepository(Request $request);
    public function showLiveStyleImageRepository(int $id,Request $request);

    public function storeLiveStyleImageRepository(Request $request);

    public function updateLiveStyleImageRepository(int $id, Request $request);

   public function deleteLiveStyleImageRepository(Request $request,int $id);

    // public function exists(int $id): bool;

}
