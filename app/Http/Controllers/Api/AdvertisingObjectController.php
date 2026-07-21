<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdvertisingObjectRequest;
use App\Http\Requests\UpdateAdvertisingObjectRequest;
use App\Http\Resources\AdvertisingObjectResource;
use App\Models\AdvertisingObject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class AdvertisingObjectController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $objects = AdvertisingObject::with([
            'contract',
            'advertisingType',
            'region',
            'city',
            'objectStatus',
            'createdBy',
        ])->paginate();

        return AdvertisingObjectResource::collection($objects);
    }

    public function store(StoreAdvertisingObjectRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['created_by'] = Auth::id();

        $advertisingObject = AdvertisingObject::create($data);

        $advertisingObject->load([
            'contract',
            'advertisingType',
            'region',
            'city',
            'objectStatus',
            'createdBy',
        ]);

        return (new AdvertisingObjectResource($advertisingObject))
            ->response()
            ->setStatusCode(201);
    }

    public function show(AdvertisingObject $advertisingObject): AdvertisingObjectResource
    {
        $advertisingObject->load([
            'contract',
            'advertisingType',
            'region',
            'city',
            'objectStatus',
            'createdBy',
        ]);

        return new AdvertisingObjectResource($advertisingObject);
    }

    public function update(UpdateAdvertisingObjectRequest $request, AdvertisingObject $advertisingObject): AdvertisingObjectResource
    {
        $advertisingObject->update($request->validated());

        $advertisingObject->load([
            'contract',
            'advertisingType',
            'region',
            'city',
            'objectStatus',
            'createdBy',
        ]);

        return new AdvertisingObjectResource($advertisingObject);
    }

    public function destroy(AdvertisingObject $advertisingObject): JsonResponse
    {
        $advertisingObject->delete();

        return response()->json(null, 204);
    }
}