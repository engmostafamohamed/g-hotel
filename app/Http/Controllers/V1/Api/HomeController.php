<?php

namespace App\Http\Controllers\V1\Api;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\V1\Api\RestaurantReqest;
use App\Http\Repository\V1\Api\HomeRepository;
class HomeController extends Controller
{
    protected $home;

    public function __construct(HomeRepository $HomeRepository)
    {
        $this->home = $HomeRepository;
    }
    public function home(Request  $request)
    {
        $result= $this->home->showHomeRepository($request);
        if ($result['status'] === 'not_found') {
            return ApiResponse::error(__('home.data_not_found'), [], statusCode: 200);
        }
        return ApiResponse::success(
            __('home.data_fetched_successfully'),
            $result['homeData'],
            statusCode: 200
        );

    }
}
