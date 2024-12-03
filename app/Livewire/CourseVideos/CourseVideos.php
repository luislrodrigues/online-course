<?php
namespace App\Livewire\CourseVideos;

use App\Models\Course;
use App\Models\CourseUser;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CourseVideos extends Component
{
    public $course;
    public $currentVideo;
    public $userProgress;
    public $hasPreviousVideo = false;
    public $hasNextVideo = false;
    public $showCongratulations = false;
    public $hasLiked = false;
    public $likesCount;
    public $newComment;
    public $comments = [];

    public function mount($course)
    {
        $this->course = Course::with('videos.comments')->findOrFail($course);
    
        $this->currentVideo = $this->course->videos->first();
    
        $this->setCurrentVideo();
        
        $this->calculateProgress();
    }

    public function render()
    {
        return view('livewire.course-videos.course-videos')
            ->layout('layouts.app');
    }
    

    public function nextVideo()
    {
        if (!$this->currentVideo) {
            return;
        }
    
        $nextVideo = $this->getNextVideo();
    
        if ($nextVideo) {
            $this->markAsWatched($this->currentVideo->id);
            $this->currentVideo = $nextVideo;
        } else {
            $this->markAsWatched($this->currentVideo->id);
            $this->showCongratulations = true; 
        }
    
        $this->calculateProgress();
        $this->setCurrentVideo();
        
    }
    
    
    public function previousVideo()
    {
        if (!$this->currentVideo) {
            return;
        }
    
        $previousVideo = $this->getPreviousVideo();
    
        if ($previousVideo) {
            $this->currentVideo = $previousVideo;
            $this->setCurrentVideo();
        }
    }
    
    public function setCurrentVideo()
    {
        if (!$this->currentVideo) {
            return;
        }
    
        $videoIndex = $this->course->videos->search(function($video) {
            return $video->id == $this->currentVideo->id;
        });
        $this->hasPreviousVideo = $videoIndex > 0;
        
        $this->hasNextVideo = $videoIndex < $this->course->videos->count() - 1;

    }
    
    
    public function getNextVideo()
    {
        $videoIndex = $this->course->videos->search(function($video) {
            return $video->id == $this->currentVideo->id;
        });

        return $this->course->videos[$videoIndex + 1] ?? null;
    }

    public function getPreviousVideo()
    {
        $videoIndex = $this->course->videos->search(function($video) {
            return $video->id == $this->currentVideo->id;
        });

        return $this->course->videos[$videoIndex - 1] ?? null;
    }

    public function markAsWatched($videoId)
    {
        $video = Video::find($videoId);
        
        if ($video) {
            auth()->user()->watchedVideos()->syncWithoutDetaching([
                $videoId => ['course_id' => $this->course->id]
            ]);
            $this->calculateProgress();
        }
    }

    public function calculateProgress()
    {
        $totalVideos = $this->course->videos->count();
        $watchedVideos = DB::table('user_video_watches')->where('course_id',$this->course->id)->count();
        
        $this->userProgress = $totalVideos > 0 ? round(($watchedVideos / $totalVideos) * 100, 2) : 0;
        CourseUser::updateOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $this->course->id],
            ['progress' => $this->userProgress]
        );
    }

    public function likeCourse()
    {
        DB::table('likes')->insert([
            'user_id' => auth()->id(),
            'video_id' => $this->currentVideo->id,
        ]);
        $this->hasLiked = true;
        $this->likesCount++;
    }

    public function submitComment()
    {
        DB::table('comments')->insert([
            'user_id' => auth()->id(),
            'video_id' => $this->currentVideo->id,
            'content' => $this->newComment,
        ]);
        $this->newComment = '';
    }
}
