<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelRequest;
use App\Http\Resources\TravelResource;
use App\Models\Travel;
use Illuminate\Http\Request;

class TravelController extends Controller
{
    public function index()
    {
        $travels = Travel::where('is_public', true)->latest()->paginate();
        return TravelResource::collection($travels);
    }

    public function store(StoreTravelRequest $request, Travel $travel)
    {
        $data = $travel->create($request->validated());
        return new TravelResource($data);
    }
}
