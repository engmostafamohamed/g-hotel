<?php

namespace App\Http\Repository\V1\CRM\Countries;

use Illuminate\Http\Request;
use App\Http\Resources\V1\CRM\Countries\CountriesResource;
use App\Contracts\V1\CRM\Countries\CountriesRepositoryInterface;
use App\DataTransferObjects\Loyalty\CountriesDTOs\CountriesDTO;
use App\Models\Log;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\DB;

class CountriesRepository implements CountriesRepositoryInterface
{
    public function showCountriesRepository(Request $request)
    {
        $query = DB::table('countries');

        $perPage = $request->input('per_page', 10);
        $lang = $request->header('Accept-Language', 'en');

        // filter by country name
        if ($request->filled('country_name')) {
            $query->where("name->{$lang}", 'like', '%' . $request->country_name . '%');
        }

        // check if paginated or not
        if ($request->boolean('is_paginated', true)) {
            $countries = $query->paginate($perPage);
        } else {
            $countries = $query->get();
        }

        if ($countries->isEmpty()) {
            return ['status' => 'countries_not_found'];
        }

        // transform JSON name field into proper language string
        $countries->transform(function ($country) use ($lang) {
            $name = json_decode($country->name, true);
            $country->name = $name[$lang] ?? $name['en'];
            return $country;
        });
        return [
            'status' => true,
            'data'   => $countries
        ];
    }
}
