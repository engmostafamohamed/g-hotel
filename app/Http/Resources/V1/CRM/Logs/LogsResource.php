<?php

namespace App\Http\Resources\V1\CRM\Logs;


use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\V1\CRM\Service\ServiceResource;
class LogsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $changes = json_decode($this->changes, true);

        return [
            'id'         => $this->id,
            'employee_id'   => $this->employee?->id,
            'employee'   => $this->employee?->name,
            'action'     => $this->action,
            'model_type' => $this->model_type,
            'model_id'   => $this->model_id,
            'message'    => $this->action === 'create'
                ? $changes['message'] ?? null
                : null,
            'changes'    => $this->action === 'update'
                ? $changes
                : null,
            'date'       => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
