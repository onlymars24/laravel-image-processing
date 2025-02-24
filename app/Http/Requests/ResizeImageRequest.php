<?php

namespace App\Http\Requests;

use Illuminate\Http\UploadedFile;
use Illuminate\Foundation\Http\FormRequest;

class ResizeImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'w' => ['required', 'regex:/^\d+(\.\d+)?%?$/'],
            'h' => 'regex:/^\d+(\.\d+)?%?$/',
            'album_id' => ['required', 'exists:\App\Models\Album,id'],
        ];

        $image = $this->image;

        if($image && $image instanceof UploadedFile){
            $rules['image'] = ['required', 'image'];
        }
        else{
            $rules['image'] = ['required', 'url'];
        }
        return $rules;
    }
}
