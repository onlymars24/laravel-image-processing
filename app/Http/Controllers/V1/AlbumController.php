<?php

namespace App\Http\Controllers\V1;

use App\Models\Album;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use App\Http\Resources\V1\AlbumResource;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Resources\V1\AlbumResource;
use App\Http\Requests\UpdateAlbumRequest;
use App\Models\ImageManipulation;
use App\Services\ImageManipulationService;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AlbumResource::collection(Album::where('user_id', Auth::id())->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAlbumRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = Auth::id();
        $album = Album::create($data);

        return new AlbumResource($album);
    }

    /**
     * Display the specified resource.
     */
    public function show(Album $album)
    {
        
        return new AlbumResource($album);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlbumRequest $request, Album $album)
    {
        $album->update($request->all());

        return new AlbumResource($album);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Album $album)
    {
        foreach($album->images as $image){
            ImageManipulationService::deleteDir($image);
        }
        $album->delete();

        return response('', 204);
    }
}
