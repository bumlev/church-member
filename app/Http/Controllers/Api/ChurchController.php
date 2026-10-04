<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChurchResource;
use App\Services\ChurchService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ChurchController extends Controller
{
    public function __construct(
        private readonly ChurchService $churchService
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return ChurchResource::collection(
            $this->churchService::getAll()
        );
    }
}
