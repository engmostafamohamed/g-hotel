<?php
namespace App\Contracts\V1\CRM\Countries;
use Illuminate\Http\Request;

interface CountriesRepositoryInterface
{
    public function showCountriesRepository(Request $request);
}
