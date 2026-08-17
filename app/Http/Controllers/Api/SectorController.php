<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SectorResource;
use App\Models\District;
use App\Services\SectorService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SectorController extends Controller
{
    public function __construct(
        private readonly SectorService $sectorService
    ){}

    public function index(District $district): AnonymousResourceCollection
    {
        return SectorResource::collection(
            $this->sectorService::getByDistrict($district)
        );
    }
}
