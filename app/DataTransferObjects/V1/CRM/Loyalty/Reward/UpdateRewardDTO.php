<?php
namespace App\DataTransferObjects\V1\CRM\Loyalty\Reward;

// use App\Http\Requests\V1\CRM\Loyalty\Reward\StoreRewardRequest;
use App\Http\Requests\V1\CRM\Loyalty\Reward\UpdateRewardRequest;
use Mockery\Matcher\Type;


// enum RewardType: string {
//     case FIXED = 'fixed';
//     case PERCENTAGE = 'percentage';
//     case SERVICE = 'service';
// }

class UpdateRewardDTO
{
    public ?string $sku;
    public ?array $name;
    // public ?string $description_ar;
    // public ?string $description_en;
    // public ?RewardType $type;
    public ?int $cost_points;
    public ?int $stock;
    public ?bool $active;

    public static function fromRequest(UpdateRewardRequest $request): self
    {
        $dto = new self();
        $dto->sku = $request->input('sku');
        $dto->name = $request->input('reward_name');
        // $dto->description_ar = $request->input('description_ar');
        // $dto->description_en = $request->input('description_en');
        $type = $request->input('type');
        // $dto->type = $type ? RewardType::tryFrom($type) : null;
        $dto->cost_points = $request->filled('cost_points') ? (int) $request->input('cost_points') : 0;
        $dto->stock = $request->filled('stock') ? (int) $request->input('stock') : 0;
        $dto->active = $request->filled('active') ? (int) $request->input('active') : true;
        return $dto;
    }
}
