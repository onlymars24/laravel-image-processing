<?php

namespace App\Http\Controllers\V1;

use App\Models\Album;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ImageManipulation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Http\Requests\TextImageRequest;
use App\Http\Requests\ResizeImageRequest;
use App\Services\ImageManipulationService;
use App\Http\Resources\v1\ImageManipulationResource;
use App\Http\Requests\UpdateImageManipulationRequest;

class ImageManipulationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ImageManipulationResource::collection(ImageManipulation::where('user_id', Auth::id())->paginate());
    }

    public function byAlbum(Album $album)
    {
        return ImageManipulationResource::collection(ImageManipulation::where('album_id', $album->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function resize(ResizeImageRequest $request)
    {   
        return ImageManipulationService::handle($request, 'App\Services\ResizeService::handle');
    }

    public function addText(TextImageRequest $request){
        return ImageManipulationService::handle($request, 'App\Services\AddTextService::handle');
    }

    /**
     * Display the specified resource.
     */
    public function show(ImageManipulation $image)
    {
        return new ImageManipulationResource($image);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ImageManipulation $image)
    {
        ImageManipulationService::deleteDir($image);
        $image->delete();
        return response('', 204);
    }
}