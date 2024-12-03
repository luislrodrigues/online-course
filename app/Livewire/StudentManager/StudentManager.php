<?php

namespace App\Livewire\StudentManager;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\Video;
use Livewire\Component;

class StudentManager extends Component
{
    public $search;
    public $ageGroupFilter;
    public $category;
    public $courseId;

    public function render()
    {

        
        $user = auth()->user();

        $query = Course::where('status', 'active')
        ->whereHas('users', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('categories');
        // Filtrar por búsqueda
        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }
        if ($this->ageGroupFilter != '') {
            $query->where('age_group', $this->ageGroupFilter);
        }

        // Filtrar por categoría
        if ($this->category) {
            $query->whereHas('categories', function ($q) {
                $q->where('course_categories.id', $this->category);
            });
        }

        $courses = $query->paginate(3);

        // Paginación

        $categories = CourseCategory::where('status', 'active')->get();
        $videos = Video::where('status', 'active')->get();

        return view('livewire.student-manager.student-manager', [
            'courses' =>  $courses,
            'categories' =>  $categories,
            'videos' =>  $videos,
        ])
        ->layout('layouts.app');
    
    }
}
