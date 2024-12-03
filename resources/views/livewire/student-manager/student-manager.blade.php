<div class="container mt-3">
    <div wire:loading class="spinner-border"
        style="width: 3rem; height: 3rem; position: fixed; z-index: 1031; top: 50%; left: 50%;" role="status"></div>
    
    <!-- Título de la sección -->
    <div class="row mb-3">
        <h2 class="text-center mb-2" style="font-size: 25px;">
            <strong>Mis cursos </strong>
        </h2>
    </div>

    <!-- Filtros -->
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="search" class="form-label">Buscar</label>
            <input type="text" id="search" class="form-control" placeholder="Buscar" wire:model="search">
        </div>
        <div class="col-md-3">
            <label for="category" class="form-label">Categoría</label>
            <select id="category" class="form-control" wire:model="category">
                <option value="">Selecciona una categoría</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="ageGroupFilter" class="form-label">Grupos de edades</label>
            <select id="ageGroupFilter" class="form-control" wire:model="ageGroupFilter">
                <option value="">Selecciona un grupo de edad</option>
                <option value="5-8">5-8</option>
                <option value="9-13">9-13</option>
                <option value="14-16">14-16</option>
                <option value="16+">16+</option>
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end py-1">
            <button class="btn btn-primary w-100 mx-2" wire:click="$refresh">Buscar</button>
            <button class="btn btn-success w-100 mx-2" data-bs-toggle="modal" data-bs-target="#crearCourseModal">Crear curso</button>
        </div>
    </div>

    <!-- Listado de Cursos -->
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        @foreach ($courses as $course)
            <div class="col">
                <div class="card shadow-sm rounded-lg h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0" style="font-size: 1rem; white-space: normal; word-wrap: break-word;">
                            {{ $course->title }}
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="mx-auto" style="max-width: 300px;">
                            <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}"
                                class="img-fluid mb-2 rounded" style="cursor: pointer;">
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('course.videos', $course->id) }}" class="btn btn-sm btn-outline-primary">
                            Ver curso
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-4">
        {{ $courses->links() }}
    </div>
    @include('livewire.courses-manager.modal-create')
</div>

@push('script')
    <script>
        document.addEventListener('livewire:initialized', function() {
            Livewire.on('alertSuccess', (message) => {
                alertSuccess(message);
            });
            Livewire.on('alertFailed', (message) => {
                alertFailed(message);
            });
        });
    </script>
@endpush
