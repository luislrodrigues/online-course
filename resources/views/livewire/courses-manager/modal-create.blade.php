<div class="modal fade" id="crearCourseModal" tabindex="-1" aria-labelledby="crearCursoLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-dark py-3">
                <h5 class="modal-title fw-bold" id="crearCursoLabel">
                    {{$courseId ? 'Actualizar curso' : 'Crear curso'}}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <form>
                    <div class="mb-4">
                        <label for="title" class="form-label fw-semibold text-dark">Título del Curso</label>
                        <input 
                            type="text" 
                            id="title" 
                            class="form-control border-primary shadow-sm" 
                            wire:model="title" 
                            placeholder="Escribe el título del curso">
                        @error('title') 
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="ageGroup" class="form-label fw-semibold text-dark">Grupos de edades</label>
                        <select id="ageGroup" class="form-control border-primary shadow-sm" wire:model="ageGroup">
                            <option value="">Seleccione</option>
                            <option value="5-8">5-8</option>
                            <option value="9-13">9-13</option>
                            <option value="14-16">14-16</option>
                            <option value="16+">16+</option>
                        </select>
                        @error('ageGroup') 
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="categories" class="form-label fw-semibold text-dark">Seleccionar Categorías</label>
                        <div class="p-3 bg-white border rounded shadow-sm">
                            @foreach ($categories as $category)
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        class="form-check-input" 
                                        id="category-{{ $category->id }}" 
                                        value="{{ $category->id }}" 
                                        wire:model="selectedCategories">
                                    <label class="form-check-label text-muted" for="category-{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('selectedCategories') 
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold text-dark">Descripción</label>
                        <textarea 
                            id="description" 
                            class="form-control border-primary shadow-sm" 
                            wire:model="description" 
                            placeholder="Describe brevemente el curso"></textarea>
                        @error('description') 
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-semibold text-dark">Imagen del Curso</label>
                        <input 
                            type="file" 
                            id="image" 
                            class="form-control border-primary shadow-sm" 
                            wire:model.defer="image">
                        @error('image') 
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="videos" class="form-label fw-semibold text-dark">Seleccionar Videos</label>
                        <div class="p-3 bg-white border rounded shadow-sm">
                            @foreach ($videos as $video)
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        class="form-check-input" 
                                        id="video-{{ $video->id }}" 
                                        value="{{ $video->id }}" 
                                        wire:model="selectedVideos">
                                    <label class="form-check-label text-muted" for="video-{{ $video->id }}">
                                        {{ $video->title }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('selectedVideos') 
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-white d-flex justify-content-between border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary"wire:click="{{ $courseId ? 'updateCourse(' . $courseId . ')' : 'storeCourse' }}">
                    <i class="bi bi-save me-1"></i> {{$courseId ? 'Actualizar' : 'Guardar'}}
                </button>
            </div>
        </div>
    </div>
</div>
@push('script')
<script>
document.addEventListener('livewire:initialized', function () {
    Livewire.on('closeModal', function () {
        $('#crearCourseModal').modal('hide');
    });
});

</script>
@endpush