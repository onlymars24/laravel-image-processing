<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\v1\ImageManipulationResource;
use App\Models\Album;

class ImageManipulation extends Model
{
    const TYPE_RESIZE = 'resize';
    const TYPE_TEXT = 'text';


    const UPDATED_AT = null;

    protected $fillable = [
        'name', 'path', 'type', 'data', 'output_path', 'user_id', 'album_id'
    ];
}