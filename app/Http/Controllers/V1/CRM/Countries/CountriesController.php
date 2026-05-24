<?php

namespace App\Http\Controllers\V1\CRM\Countries;

use App\Http\Controllers\Controller;
use App\Http\Repository\V1\CRM\Countries\CountriesRepository;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Http\Resources\V1\CRM\Countries\PaginatedCountriesListResource;
use App\Http\Resources\V1\CRM\Countries\CountriesResource;
class CountriesController extends Controller
{
    public function __construct(private CountriesRepository $countriesRepository){}

    public function index(Request  $request ){

        $result= $this->countriesRepository->showCountriesRepository($request);
        if ($result['status'] === 'countries_not_found') {
            return ApiResponse::error(__('countries.countries_id_not_found'), [], 200);
        }
        $data = $result['data'];

        // check if result is paginated
        if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return ApiResponse::success(
                __('countries.data_fetched_successfully'),
                new PaginatedCountriesListResource($data),
                200
            );
        }

        // un-paginated  Countries
        return ApiResponse::success(
            __('countries.data_fetched_successfully'),
            CountriesResource::collection($data),
            200
        );
    }
}
