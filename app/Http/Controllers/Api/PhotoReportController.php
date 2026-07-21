<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhotoReportRequest;
use App\Http\Requests\UpdatePhotoReportRequest;
use App\Http\Resources\PhotoReportResource;
use App\Models\PhotoReport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class PhotoReportController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $photoReports = PhotoReport::with([
            'advertisingObject',
            'photoReportStatus',
            'createdBy',
            'checkedBy',
            'photos',
        ])->paginate();

        return PhotoReportResource::collection($photoReports);
    }

    public function store(StorePhotoReportRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['created_by'] = Auth::id();

        $photoReport = PhotoReport::create($data);

        $photoReport->load([
            'advertisingObject',
            'photoReportStatus',
            'createdBy',
            'checkedBy',
            'photos',
        ]);

        return (new PhotoReportResource($photoReport))
            ->response()
            ->setStatusCode(201);
    }

    public function show(PhotoReport $photoReport): PhotoReportResource
    {
        $photoReport->load([
            'advertisingObject',
            'photoReportStatus',
            'createdBy',
            'checkedBy',
            'photos',
        ]);

        return new PhotoReportResource($photoReport);
    }

    public function update(UpdatePhotoReportRequest $request, PhotoReport $photoReport): PhotoReportResource {
        $photoReport->update($request->validated());

        $photoReport->load([
            'advertisingObject',
            'photoReportStatus',
            'createdBy',
            'checkedBy',
            'photos',
        ]);

        return new PhotoReportResource($photoReport);
    }

    public function destroy(PhotoReport $photoReport): JsonResponse
    {
        $photoReport->delete();

        return response()->json(null, 204);
    }
}