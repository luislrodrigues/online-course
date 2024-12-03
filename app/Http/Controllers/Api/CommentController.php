<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Course;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Course $course)
    {
        try {
            $comment = new Comment();
            $comment->user_id = $request->input('user_id');
            $comment->course_id = $course->id;
            $comment->comment = $request->input('comment');
            $comment->save();
    
            return response()->json(['message' => 'Comment added successfully', 'data' => $comment], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error adding comment', 'message' => $e->getMessage()], 500);
        }
    }
}

