<?php

namespace App\Http\Repository\V1\Api\StaticPages;
use App\Models\ContactInfo;
use Illuminate\Http\Request;

class ContactNumbersRepository
{
    public function showContactNumbersRepository(): array|false
    {
        $data = ContactInfo::paginate(10);
        if (!$data) {
            return ['status' => 'not_found'];
        }
        return [
            'status' => 'success',
            'contactNumbersData' => $data,
        ];
    }
}
