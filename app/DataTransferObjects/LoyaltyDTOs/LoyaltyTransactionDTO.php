<?php

namespace App\DataTransferObjects\LoyaltyDTOs;

use App\Http\Requests\V1\Api\Loyalty\AssignGuestToLoyaltyAccountRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class LoyaltyTransactionDTO
{
     public function __construct(

        public int $account_id,
        public int $points_change,
        public string $type,
        public ?Carbon $valid_from = null,
        public ?Carbon $expires_at = null,
        public ?string $source = null,
        public ?string $source_id = null,
    ) {}

    public static function fromRequest( $request): self
    {
        return new self(
            $request->account_id,
            $request->points_change,
            $request->type,
            $request->valid_from ? Carbon::parse($request->valid_from) : null,
            $request->expires_at ? Carbon::parse($request->expires_at) : null,
            $request->source,
            $request->source_id,
        );
    }

}
