<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CelluleResource;
use App\Models\Sector;
use App\Services\CelluleService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CelluleController extends Controller
{
    public function __construct(
        private readonly CelluleService $celluleService,
    ){}
    public function index(Sector $sector): AnonymousResourceCollection
    {
        return CelluleResource::collection(
            $this->celluleService::getBySector($sector)
        );
    }
}
