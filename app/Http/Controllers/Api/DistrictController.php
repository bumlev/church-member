<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Models\Province;
use App\Services\DistrictService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DistrictController extends Controller
{
    public function __construct(
        private readonly DistrictService $districtService
    ) {}

    public function index(Province $province): AnonymousResourceCollection
    {
        return DistrictResource::collection(
            $this->districtService::getByProvince($province)
        );
    }
}
