<?php
namespace App\Contracts\V1\CRM\Loyalty\Reward;
use Illuminate\Http\Request;
use App\DataTransferObjects\V1\CRM\Loyalty\Reward\CreateRewardDTO;
use App\DataTransferObjects\V1\CRM\Loyalty\Reward\UpdateRewardDTO;
interface RewardRepositoryInterface
{
    public function showRewardsRepository(Request $request);
    public function showRewardRepository($id);
    public function storeRewardRepository(CreateRewardDTO $request);
    public function updateRewardRepository(UpdateRewardDTO $request, string $id);
    public function destroyRewardRepository(string $id);
}
