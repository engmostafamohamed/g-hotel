<?php

namespace App\Contracts\BlackoutDate;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\DataTransferObjects\BlackoutDateDTOs\BlackoutDateDTO;
use App\Models\BlackoutDate;
use Illuminate\Http\Request;
interface BlackoutDateRepositoryInterface
{
    public function showBlackoutDatesRepository(Request $dto);

    public function storeBlackoutDateRepository(BlackoutDateDTO $request);

   public function updateBlackoutDateRepository(int $id ,BlackoutDateDTO $request);

    public function deleteBlackoutDateRepository(Request $request,int $id);

    // public function exists(int $id): bool;

}
