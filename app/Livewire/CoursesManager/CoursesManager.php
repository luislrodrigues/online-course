<?php

namespace App\Livewire\CoursesManager;

use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseUser;
use App\Models\Video;
use Livewire\Component;
use Livewire\WithFileUploads;

class CoursesManager extends Component
{

    use WithFileUploads;

    public $search = '';
    public $ageGroupFilter = '';
    public $category = null;
    public $description = '';
    public $title;
    public $image;
    public $ageGroup;
    public $courseId = null;
    public $selectedVideos = [];
    public $selectedCategories = [];

    

    public function render()
    {

        $query = Course::where('status', 'active')->with(['categories','users']);

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
        $courses->map(function($course){
            $course->users->map(function ($user) use ($course){
                $courseUser = CourseUser::where('course_id',$course->id)->where('user_id', auth()->id())->first();
                $user->progress = $courseUser ? $courseUser->progress : 0;
            });
        });
        
        // Paginación

        $categories = CourseCategory::where('status', 'active')->get();
        $videos = Video::where('status', 'active')->get();

        return view('livewire.courses-manager.courses-manager', [
            'courses' =>  $courses,
            'categories' =>  $categories,
            'videos' =>  $videos,
        ])
            ->layout('layouts.app');
    }

    public function storeCourse()
    {

        if (auth()->user()->can('actions courses')) {
            $this->validate([
                'title'          => 'required|string|max:255',
                'ageGroup'       => 'required',
                'description'    => 'required|string',
                'selectedVideos' => 'required|array|min:1',
                'selectedCategories' => 'required|array|min:1',
                'image'          => 'required|image|max:1024',
            ]);
            try {
                $pathImage = null;
                if ($this->image) $pathImage = $this->image->store('images', 'public');


                // Crear el curso
                $course = Course::create([
                    'title' => $this->title,
                    'description' => $this->description,
                    'age_group' => $this->ageGroup,
                    'image' => $pathImage ?? null,
                ]);
                // Asociar los videos seleccionados al curso
                $course->videos()->sync($this->selectedVideos);

                // Asociar las categorias seleccionadas al curso
                if (count($this->selectedCategories) > 0) {
                    $course->categories()->sync($this->selectedCategories);
                }

                $this->dispatch('alertSuccess', 'Se guardo el curso correctamente');
                $this->dispatch('closeModal');
            } catch (\Exception $e) {
                $this->dispatch('alertFailed', 'Error al guardar el curso: ' . $e->getMessage());
            }
        } else {
            abort(403, 'No tienes los permisos requeridos.');
        }
    }

    public function editCourse($id){
        $course = Course::with('categories','videos')->findOrFail($id);
        $this->title = $course->title;
        $this->courseId = $course->id;
        $this->ageGroup = $course->age_group;
        $this->description = $course->description;
        $this->selectedCategories = $course->categories->pluck('id')->toArray();
        $this->selectedVideos = $course->videos->pluck('id')->toArray();
        $this->dispatch('open-modal-course');
    }

    public function updateCourse($id)
    {
        if (auth()->user()->can('actions courses')) {
            $course = Course::findOrFail($id);
            $this->validate([
                'title'              => 'required|string|max:255',
                'ageGroup'           => 'required',
                'description'        => 'required|string',
                'selectedVideos'     => 'required|array|min:1',
                'selectedCategories' => 'required|array|min:1',
            ]);
            try {
                $pathImage = null;

                if ($this->image) $pathImage = $this->image->store('images', 'public');

                // Crear el curso
                $course->update([
                    'title'       => $this->title,
                    'description' => $this->description,
                    'age_group'   => $this->ageGroup,
                    'image'       => $pathImage ?? $course->image
                 ]);

                // Asociar los videos seleccionados al curso
                $course->videos()->sync($this->selectedVideos);

                // Asociar las categorias seleccionadas al curso
                if (count($this->selectedCategories) > 0) {
                    $course->categories()->sync($this->selectedCategories);
                }

                $this->dispatch('alertSuccess', 'Se actualizo el curso correctamente');
                $this->dispatch('closeModal');
            } catch (\Exception $e) {
                $this->dispatch('alertFailed', 'Error al actualizar el curso: ' . $e->getMessage());
            }
        } else {
            abort(403, 'No tienes los permisos requeridos.');
        }
    }

    public function resetForm()
    {
        $this->reset(['title', 'courseId','description','ageGroup','image']);
        $this->selectedCategories = [];
        $this->selectedVideos = [];
        $this->resetValidation();
    }

    public function destroy($id)
    {
        if (auth()->user()->can('actions courses')) {
            try{
                $course = Course::findOrFail($id);
                $course->categories()->delete();
                $course->users()->detach();
                $course->videos()->detach();
                $course->delete();
                $this->dispatch('alertSuccess', 'El curso se elimino correctamente.');
            }catch (\Exception $e) {
                $this->dispatch('alertFailed', 'Error al eliminar el  curso: ' . $e->getMessage());
            }
        } else {
            abort(403, 'No tienes los permisos requeridos.');
        }
    }

    public function enrollCourse($id){
        try{
            $user = auth()->user();
            if (!$user->courses->contains($id)) {
                $user->courses()->attach($id);
                $this->dispatch('alertSuccess', '¡Te has matriculado exitosamente!');
            } else {
                $this->dispatch('alertFailed', 'Ya estás matriculado en este curso.');
            }
        }catch (\Exception $e) {
            $this->dispatch('alertFailed', 'Error al eliminar el  curso: ' . $e->getMessage());
        }
    }
}
