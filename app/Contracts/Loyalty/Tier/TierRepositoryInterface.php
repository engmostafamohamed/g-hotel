<?php
namespace App\Contracts\Loyalty\Tier;
use App\DataTransferObjects\V1\CRM\Loyalty\TierDTOs\CreateTierDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\TierDTOs\UpdateTierDTO;
use App\Models\Tier;
use Illuminate\Http\Request;
interface TierRepositoryInterface
{
    public function showTiersRepository(Request $dto);
    public function showTierRepository(Request $dto,int $id);

    public function storeTierRepository(CreateTierDTO $request);

   public function updateTierRepository(int $id ,UpdateTierDTO $request);

    public function deleteTierRepository(Request $request,int $id);

    // public function exists(int $id): bool;

}
