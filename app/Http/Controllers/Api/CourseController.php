<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Http\Resources\VideoResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        try {
            $courses = Course::all();
            return CourseResource::collection($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching courses', 'message' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $request->validate([
                'category' => 'nullable|string',
                'age_range' => 'nullable|string',
                'name' => 'nullable|string|max:255',
            ]);

            $query = Course::query();

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            if ($request->filled('age_range')) {
                $query->where('age_range', $request->age_range);
            }

            if ($request->filled('name')) {
                $query->where('title', 'like', '%' . $request->name . '%');
            }

            return CourseResource::collection($query->get());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error searching courses', 'message' => $e->getMessage()], 500);
        }
    }

    public function registerUser(Request $request, Course $course)
    {
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);
    
            $userId = $request->input('user_id');
    
            if ($course->users()->where('user_id', $userId)->exists()) {
                return response()->json(['error' => 'User already registered for this course'], 400);
            }
    
            $course->users()->attach($userId);
    
            return response()->json(['message' => 'User successfully registered to the course']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error registering user to the course', 'message' => $e->getMessage()], 500);
        }
    }

    public function getVideos(Course $course)
    {
        try {
            $videos = $course->videos;
            if ($videos->isEmpty()) {
                return response()->json(['error' => 'No videos found for this course'], 404);
            }

            return VideoResource::collection($videos);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching videos', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateProgress(Request $request, Course $course)
    {
        try {
            $validated = $request->validate([
                'video_id' => 'required|exists:videos,id',
                'user_id' => 'required|exists:users,id',
            ]);
    
            $video = $course->videos()->find($validated['video_id']);
    
            if (!$video) {
                return response()->json(['error' => 'Video not found in this course'], 404);
            }
    
            $video->users()->syncWithoutDetaching([$validated['user_id'] => ['updated_at' => now()]]);
    
            return response()->json(['message' => 'Progress updated successfully']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating progress', 'message' => $e->getMessage()], 500);
        }
    }
    
}
