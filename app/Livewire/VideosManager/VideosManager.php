<?php

namespace App\Livewire\VideosManager;

use App\Models\Comment;
use App\Models\Video;
use App\Models\VideoCategory;
use Livewire\Component;
use Livewire\WithPagination;

class VideosManager extends Component
{   

    use WithPagination;

    public $search = '';
    public $category = null;
    public $title;
    public $url;
    public $description;
    public $selectedCategories = [];
    public $videoId;

    protected $listeners = ['selectedCategories'];
    
    public function render()
    {
       $query = Video::where('status','active')->with('categories','comments.user');

       // Filtrar por búsqueda
       if ($this->search) {
           $query->where('title', 'like', '%' . $this->search . '%');
       }

       // Filtrar por categoría
       if ($this->category) {
           $query->whereHas('categories', function ($q) {
               $q->where('video_categories.id', $this->category);
           });
       }

       $videos = $query->paginate(3);
    
        // Paginación
    
        $categories = VideoCategory::where('status','active')->get();
        return view('livewire.videos-manager.videos-manager',[
            'videos'     => $videos,
            'categories' => $categories
        ])
        ->layout('layouts.app');
    }

    public function storeVideo(){


        if (auth()->user()->can('actions videos')) {
            $this->validate([
                'title' => 'required|string|max:255',
                'url' => 'required|url',
                'description' => 'required|string',
                'selectedCategories' => 'required|array'
            ]);
            try {
                if ($this->isVideoUrlValid($this->url)) {
                    $video = Video::create([
                        'title' => $this->title,
                        'url' => $this->url,
                        'description' => $this->description,
                    ]);
    
                    if (count($this->selectedCategories) > 0) {
                        $video->categories()->sync($this->selectedCategories);
                    }
    
                    $this->dispatch('alertSuccess','Se guardo el video correctamente');
                    $this->dispatch('closeModal');
                    $this->resetForm();
                } else{
                    $this->dispatch('alertFailed', 'La URL no es valida');
                }
            } catch (\Exception $e) {
                $this->dispatch('alertFailed', 'Error al guardar el video: ' . $e->getMessage());
            }

        } else {
            abort(403, 'No tienes los permisos requeridos.');
        }
    }

    public function resetForm()
    {
        $this->reset(['title', 'url','description','videoId']);
        $this->selectedCategories = [];
        $this->resetValidation();
    }

    public function isVideoUrlValid($url)
    {
        $headers = @get_headers($url);
        return strpos($headers[0], '200') !== false;
    }

    public function approveComment($id,$status){
        if (auth()->user()->can('actions videos')) {
            try {
                $comment = Comment::find($id);
                $comment->status = $status;
                $comment->save();
                $this->dispatch('$refresh');
                $this->dispatch('alertSuccess','Se actualizo el comentario correctamente');
                $this->dispatch('close-modal-comments');

            } catch (\Exception $e) {
                $this->dispatch('alertFailed', 'Error al guardar: ' . $e->getMessage());
            }

        } else {
            abort(403, 'No tienes los permisos requeridos.');
        }

    }

    public function destroy($id)
    {
        if (auth()->user()->can('actions videos')) {
            try{
                $video = Video::findOrFail($id);
                $video->categories()->delete();
                $video->comments()->delete();
                $video->courses()->detach();
                $video->delete();
                $this->dispatch('alertSuccess', 'El video se elimino correctamente.');
            }catch (\Exception $e) {
                $this->dispatch('alertFailed', 'Error al eliminar el  video: ' . $e->getMessage());
            }
        } else {
            abort(403, 'No tienes los permisos requeridos.');
        }
    }

    public function editVideo($id){
        $video = Video::where('id',$id)->with('categories')->first();
        $this->videoId = $video->id;
        $this->description = $video->description;
        $this->url = $video->url;
        $this->title = $video->title;
        $this->selectedCategories = $video->categories->pluck('id')->toArray();
        $this->dispatch('open-modal-video');
    }

    public function updateVideo($id){
        if (auth()->user()->can('actions videos')) {
            $video = Video::findOrFail($id);
            $this->validate([
                'title'              => 'required|string|max:255',
                'url'                => 'required|url',
                'description'        => 'required|string',
                'selectedCategories' => 'required|array'
            ]);
            try {
                if ($this->isVideoUrlValid($this->url)) {
                    $video->update([
                        'title'       => $this->title,
                        'url'         => $this->url,
                        'description' => $this->description,
                    ]);
    
                    if (count($this->selectedCategories) > 0) {
                        $video->categories()->sync($this->selectedCategories);
                    }
    
                    $this->dispatch('alertSuccess','Se actualizo el video correctamente');
                    $this->dispatch('closeModal');
                    $this->resetForm();
                } else{
                    $this->dispatch('alertFailed', 'La URL no es valida');
                }
            } catch (\Exception $e) {
                $this->dispatch('alertFailed', 'Error al actualizo el video: ' . $e->getMessage());
            }

        } else {
            abort(403, 'No tienes los permisos requeridos.');
        }
    }
}
