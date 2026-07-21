<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePhotoRequest;
use App\Http\Resources\PhotoResource;
use App\Models\Photo;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): AnonymousResourceCollection
    {
        $photos = Photo::query()
            ->orderBy('id')
            ->paginate();

        return PhotoResource::collection($photos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePhotoRequest $request): PhotoResource
    {
        /** @var UploadedFile $file */
        $file = $request->file('photo');

        $path = $file->store('photo-reports', 'public');

        $photo = Photo::query()->create([
            'photo_report_id' => $request->integer('photo_report_id'),
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'sort_order' => $request->validated()['sort_order'] ?? null,
        ]);

        return new PhotoResource($photo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo): PhotoResource
    {
        return new PhotoResource($photo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Photo $photo): Response
    {
        Storage::disk('public')->delete($photo->file_path);

        $photo->delete();

        return response()->noContent();
    }
}
