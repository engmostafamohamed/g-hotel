<?php

namespace App\Http\Controllers\V1\Api;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Repository\V1\Api\CountryCityRepository;
class CountriesWithCitiesController extends Controller
{
    protected $countriesWithCities;

    public function __construct(CountryCityRepository $countriesWithCitiesRepository)
    {
        $this->countriesWithCities = $countriesWithCitiesRepository;
    }
    public function countriesWithCities(Request  $request)
    {
        $result= $this->countriesWithCities->getCountriesWithCities($request);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('auth.country_not_found'), [], 200);
        }
        return ApiResponse::success(
            __('validation.data_fetched_successfully'),
            $result['countries'],
            200
        );
    }
}
