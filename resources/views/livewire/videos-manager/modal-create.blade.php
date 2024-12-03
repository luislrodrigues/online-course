<div class="modal fade" id="crearVideoModal" tabindex="-1" aria-labelledby="crearVideoLabel" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-md">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-info text-dark py-3">
                <h5 class="modal-title fw-bold" id="crearVideoLabel">
                    {{$videoId ? "Actualizar Video" : "Crear Video" }} 
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <form>
                    <div class="mb-4">
                        <label for="video-name" class="form-label fw-semibold text-dark">Título</label>
                        <input 
                            type="text" 
                            class="form-control border-primary shadow-sm" 
                            id="video-name" 
                            wire:model="title" 
                            placeholder="Escribe el título del video">
                        @error('title') 
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="video-url" class="form-label fw-semibold text-dark">URL del Video</label>
                        <input 
                            type="text" 
                            class="form-control border-primary shadow-sm" 
                            id="video-url" 
                            wire:model="url" 
                            placeholder="https://youtube.com/video-url">
                        @error('url') 
                            <small class="text-danger">
                                <i class="bi bi-exclamation-circle-fill"></i> {{ $message }}
                            </small>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="descripcion-text" class="form-label fw-semibold text-dark">Descripción</label>
                        <textarea 
                            class="form-control border-primary shadow-sm" 
                            id="descripcion-text" 
                            wire:model="description" 
                            placeholder="Agrega una breve descripción del video"></textarea>
                        @error('description') 
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
                </form>
            </div>
            <div class="modal-footer bg-white d-flex justify-content-between border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary"wire:click="{{ $videoId ? 'updateVideo(' . $videoId . ')' : 'storeVideo' }}">
                    <i class="bi bi-save me-1"></i> 
                    {{$videoId ? "Actualizar" : "Guardar" }} 

                </button>
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
document.addEventListener('livewire:initialized', function () {
    Livewire.on('closeModal', function () {
        $('#crearVideoModal').modal('hide');
    });
});

</script>
@endpush


