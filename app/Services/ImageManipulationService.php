<?php

namespace App\Services;

use App\Models\Album;
use Illuminate\Support\Str;
use App\Services\ResizeService;
use App\Models\ImageManipulation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Http\Resources\v1\ImageManipulationResource;

class ImageManipulationService
{
    // общий метод для всех видов манипуляций с изображениями
    public static function handle($request, $manipulationCallback){
            /** @var UploadedFile|string $image */
            $image = $request->image;
            Log::info('handle start');
            $data = [
                'user_id' => Auth::id(),
                'album_id' => $request->album_id,
                'data' => json_encode($request->except(['image', 'album_id']))
            ];
    
            $dir = 'images/' . Str::random() . '/';
            $absolutePath = public_path($dir);
            if (!File::exists($absolutePath)) {
                File::makeDirectory($absolutePath, 0755, true);
            }
            if ($image instanceof UploadedFile) {
                $data['name'] = $image->getClientOriginalName();
                $filename = pathinfo($data['name'], PATHINFO_FILENAME);
                $extension = $image->getClientOriginalExtension();
                $originalPath = $absolutePath . $data['name'];
                $data['path'] = $dir . $data['name'];
                $image->move($absolutePath, $data['name']);
            } else {
                $data['name'] = pathinfo($image, PATHINFO_BASENAME);
                $filename = pathinfo($image, PATHINFO_FILENAME);
                $extension = pathinfo($image, PATHINFO_EXTENSION);
                $originalPath = $absolutePath . $data['name'];
    
                copy($image, $originalPath);
                $data['path'] = $dir . $data['name'];
            }

            $resizedFilename = $filename . '-changed.' . $extension;
            $data['output_path'] = $dir . $resizedFilename;            

            //вызов callback
            $data = $manipulationCallback($data, $request);
            $imageManipulation = ImageManipulation::create($data);

            return new ImageManipulationResource($imageManipulation);
    }

    public static function deleteDir($image){
        $folder = dirname($image->path); // Получаем путь к папке
        if (File::exists($folder)) {
            File::deleteDirectory($folder);
        }
    }
}