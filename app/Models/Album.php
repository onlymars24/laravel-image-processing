<?php

namespace App\Models;

use App\Models\ImageManipulation;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    protected $fillable = ['name', 'user_id'];

    public function images()
    {
        return $this->hasMany(ImageManipulation::class);
    }

}
