<?php

namespace App\DataTransferObjects\V1\CRM\Dashboard;

use App\Http\Requests\V1\CRM\Dashboard\DashboardRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardFilterDTO
{
    public ?string $from_date;
    public ?string $to_date;
    public ?string $granularity;

    private function __construct(?string $from, ?string $to, ?string $granularity)
    {
        $this->from_date = $from;
        $this->to_date = $to;
        $this->granularity = $granularity;
    }

    public static function fromRequest(DashboardRequest $request): self
    {
        $from = $request->input('from_date');
        $to = $request->input('to_date');

        // sensible defaults: last 30 days if not provided
        if (!$from && !$to) {
            $to = now()->toDateString();
            $from = now()->subDays(29)->toDateString();
        } elseif ($from && !$to) {
            $to = $from;
        } elseif (!$from && $to) {
            $from = $to;
        }

        $granularity = $request->input('granularity');
        // if single day and granularity not provided, prefer 'hour'
        if ($from === $to && !$granularity) {
            $granularity = 'hour';
        }

        return new self($from, $to, $granularity);
    }

    /** Helper: whether range is single day */
    public function isSingleDay(): bool
    {
        if (!$this->from_date || !$this->to_date) return false;
        return Carbon::parse($this->from_date)->isSameDay(Carbon::parse($this->to_date));
    }
}
