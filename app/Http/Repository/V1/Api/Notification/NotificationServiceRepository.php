<?php

namespace App\Http\Repository\V1\Api\Notification;

use App\Models\Notification;

class NotificationServiceRepository
{
    public function create(array $data):Notification{
        return Notification::create($data);
    }

    public function getAll(int $perPage)
    {
        return Notification::latest()->paginate($perPage);
    }

}
