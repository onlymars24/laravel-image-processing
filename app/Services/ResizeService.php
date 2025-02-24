<?php

namespace App\Services;

use App\Models\ImageManipulation;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ResizeService
{
   
    public static function handle($data, $request){
        $w = $request->w;
        $h = $request->h ?? false;
        list($image, $width, $height) = self::getWidthAndHeight($w, $h, public_path($data['path']));
        $data['type'] = ImageManipulation::TYPE_RESIZE;
        $image->resize($width, $height)->save(public_path($data['output_path']));
        return $data;
    }

    protected static function getWidthAndHeight($w, $h, $originalPath)
    {
        $image = Image::make($originalPath);
        $originalWidth = $image->width();
        $originalHeight = $image->height();

        if (str_ends_with($w, '%')) {
            $ratioW = (float)(str_replace('%', '', $w));
            $ratioH = $h ? (float)(str_replace('%', '', $h)) : $ratioW;
            $newWidth = $originalWidth * $ratioW / 100;
            $newHeight = $originalHeight * $ratioH / 100;
        } else {
            $newWidth = (float)$w;
            $newHeight = $h ? (float)$h : ($originalHeight * $newWidth / $originalWidth);
        }

        return [$image, $newWidth, $newHeight];
    }


}