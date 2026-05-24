<?php

namespace App\Http\Repository\V1\Api\StaticPages;
use App\Models\HotelLocation;
use Illuminate\Http\Request;
use App\Utils\FileUpload;
use Illuminate\Support\Facades\File;
class LocationPermissionRepository
{
    public function showLocationPermissionRepository(Request $request)
    {
        $data = HotelLocation::where('is_active',true)->get();
        if (!$data) {
            return ['status' => 'not_found'];
        }

        return [
            'status' => 'success',
            'locationPermission' => $data,
        ];
    }
}
