<?php

namespace App\Http\Middleware;

use App\Models\Album;
use Closure;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AlbumBelongsToUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->album){
            $album = $request->album;
        }
        else{
            $album = Album::find($request->album_id);
        }
        if(!$album){
            return response([
                'message' => 'Альбом не найден'
            ], 404);
        }
        if (Auth::id() == $album->user_id) {
            return $next($request);
        }
        return response([
            'message' => 'Ошибка доступа'
        ], 403);
    }
}