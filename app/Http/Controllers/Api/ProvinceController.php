<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceResource;
use App\Services\ProvinceService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProvinceController extends Controller
{
    public function __construct(
        private readonly ProvinceService $provinceService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return ProvinceResource::collection(
            $this->provinceService::getAll()
        );
    }
}
