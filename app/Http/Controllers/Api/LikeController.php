<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(LikeRequest $request)
    {
        try {

            $userId = $request->input('user_id');
            $videoId = $request->input('video_id');
    
            $exists = Like::where('user_id', $userId)
                ->where('video_id', $videoId)
                ->exists();
    
            if ($exists) {
                return response()->json(['error' => 'User has already liked this video'], 400);
            }
    
            $like = new Like();
            $like->user_id = $userId;
            $like->video_id = $videoId;
            $like->save();
    
            return response()->json(['message' => 'Video liked successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error liking video', 'message' => $e->getMessage()], 500);
        }
    }
}