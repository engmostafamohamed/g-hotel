<?php

namespace App\Http\Repository\V1\Api;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryCityRepository
{
    public function getCountriesWithCities(Request $request)
    {
        app()->setLocale($request->header('Accept-Language', 'en'));

        $countries = Country::with('cities')->get()->map(function ($country) {
            return [
                'id' => $country->id,
                'name' => $country->name,
                'country_code' => $country->country_code,
                'iso_code' => $country->iso_code,
                'cities' => $country->cities->map(function ($city) {
                    return [
                        'id' => $city->id,
                        'name' => $city->name,
                    ];
                }),
            ];
        });
        if (!$countries) {
            return ['status' => 'not_found'];
        }

        return [
            'status' => 'success',
            'countries' => $countries,
        ];
    }

}
