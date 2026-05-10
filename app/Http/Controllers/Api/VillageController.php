<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VillageResource;
use App\Models\Cellule;
use App\Services\VillageService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class VillageController extends Controller
{
    public function __construct(
        private readonly VillageService $villageService
    ){}
    public function index(Cellule $cellule): AnonymousResourceCollection
    {
        return VillageResource::collection(
            $this->villageService::getByCellule($cellule)
        );
    }
}
