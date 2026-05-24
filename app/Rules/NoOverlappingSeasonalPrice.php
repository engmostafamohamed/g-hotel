<?php

namespace App\Rules;

use App\Models\SeasonalPrice;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoOverlappingSeasonalPrice implements ValidationRule
{
    private int $roomTypeId;
    private ?int $exceptId;

    public function __construct(int $roomTypeId, ?int $exceptId = null)
    {
        $this->roomTypeId = $roomTypeId;
        $this->exceptId = $exceptId; // for updates
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $from = request('from');
        $to = request('to');

        if (!$from || !$to) {
            return; // Skip if dates are missing
        }

        $query = SeasonalPrice::where('room_type_id', $this->roomTypeId)
            ->where(function ($q) use ($from, $to) {
                $q->where(function ($q2) use ($from, $to) {
                    $q2->where('from', '<=', $to)
                       ->where('to', '>=', $from);
                });
            });

        if ($this->exceptId) {
            $query->where('id', '!=', $this->exceptId);
        }

        if ($query->exists()) {
            $fail(__('seasonalPrice.validation.overlap'));
        }
    }
}
