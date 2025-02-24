<?php

namespace App\Services;

use App\Models\ImageManipulation;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class AddTextService
{
    public static function handle($data, $request) {
        // public function handle($imagePath, $imageOutputPath, $text) {
        $data['type'] = ImageManipulation::TYPE_TEXT;
        $image = imagecreatefromjpeg($data['path']);
        
        $text_color = imagecolorallocate($image, 255, 255, 255);
        $shadow_color = imagecolorallocate($image, 59, 58, 58);
    
        $width = imagesx($image);
        $height = imagesy($image);
    
        $centerX = $width / 2;
        $centerY = $height / 2;
    
        $font = public_path('fonts/FiraSansCondensed-Medium.ttf');
    
        // Динамически рассчитываем размер шрифта
        $size = max(20, min($width, $height) / 15); // Минимальный размер 20px
    
        // Максимальная ширина строки (80% от ширины изображения)
        $maxWidth = $width * 0.8;
    
        // Разбиваем текст на строки
        $wrappedText = wordwrap($request->text, $maxWidth / ($size * 0.6), "\n", true);
        $lines = explode("\n", $wrappedText);
    
        // Рассчитываем высоту блока текста
        $lineHeight = $size * 1.2;
        $textBlockHeight = count($lines) * $lineHeight;
    
        // Начальная точка Y для центрирования
        $y = $centerY - ($textBlockHeight / 2) + $size;
    
        foreach ($lines as $line) {
            list($left, , $right) = imageftbbox($size, 0, $font, $line);
            $lineWidth = $right - $left;
            
            // Центрируем по X
            $x = $centerX - ($lineWidth / 2);
    
            // Тень
            imagefttext($image, $size, 0, $x + 3, $y + 3, $shadow_color, $font, $line);
            // Основной текст
            imagefttext($image, $size, 0, $x, $y, $text_color, $font, $line);
    
            // Смещаем Y на следующую строку
            $y += $lineHeight;
        }
    
        imagejpeg($image, public_path($data['output_path']));
        return $data;
    }
}