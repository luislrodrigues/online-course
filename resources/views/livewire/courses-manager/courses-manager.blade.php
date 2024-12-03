<div>
    <div class="container mt-3">
        <!-- Spinner de carga -->
        <div wire:loading class="spinner-border"
            style="width: 3rem; height: 3rem;position: fixed; z-index: 1031;top: 50%;left: 50%;" role="status"></div>

        <!-- Título de la sección -->
        <div class="row">
            <h2 class="text-center mb-2" style="font-size: 25px;"><strong>Cursos</strong></h2>
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
                    <option value="">Selecciona una categoría</option>
                    <option value="5-8">5-8</option>
                    <option value="9-13">9-13</option>
                    <option value="14-16">14-16</option>
                    <option value="16+">16+</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end py-1">
                <button class="btn btn-primary w-100 mx-2" wire:click="$refresh">Buscar</button>
                <button class="btn btn-success w-100 mx-2" data-bs-toggle="modal"
                    data-bs-target="#crearCourseModal">Crear curso</button>
            </div>
        </div>

        <!-- Listado de cursos -->
        <div class="row">
            @foreach ($courses as $course)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card shadow-sm rounded-lg h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"
                                style="font-size: 1rem; white-space: normal; word-wrap: break-word;">
                                {{ $course->title }}
                            </h5>
                            @can('actions courses')
                                <div class="d-flex">
                                    <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal"
                                        wire:click="editCourse({{ $course->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger ms-2"
                                        wire:click="destroy({{ $course->id }})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                
                            @endcan
                        </div>

                        <div class="card-body text-center">
                            <div class="mx-auto" style="max-width: 300px;">
                                <img src="{{ Storage::url($course->image) }}" alt="{{ $course->title }}"
                                    class="img-fluid mb-2 rounded" style="cursor: pointer;">
                            </div>
                        </div>

                        <hr class="my-0">
                        <div class="card-body p-2">
                            <h6 class="text-muted mb-2" style="font-size: 0.875rem;">Descripción</h6>
                            <p>{{ $course->description }}</p>
                        </div>
                        <hr class="my-0">
                        <div class="card-body p-2">
                            <h6 class="text-muted mb-2" style="font-size: 0.875rem;">Grupo de edad</h6>
                            <p>{{ $course->age_group }}</p>
                        </div>
                        <hr class="my-0">
                        <div class="card-body p-2">
                            <h6 class="text-muted mb-2" style="font-size: 0.875rem;">Categorías</h6>
                            @foreach ($course->categories as $category)
                                <a href="#" class="badge bg-primary me-1 my-1">{{ $category->name }}</a>
                            @endforeach
                        </div>
                        @can('actions courses')
                            <hr class="my-0">
                            <div class="card-body text-center p-2">
                                <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#progressModal{{ $course->id }}">
                                    <i class="fas fa-user-graduate"></i>
                                    <span id="like-text-{{ $course->id }}" style="font-size: 0.875rem;">
                                        {{ $course->users()->count() }}
                                    </span> Estudiantes
                                </button>
                            </div>
                        @endcan

                        <div class="card-footer d-flex justify-content-between">
                            <button class="btn btn-sm btn-outline-primary"
                                wire:click="enrollCourse({{ $course->id }})">
                                Matricular
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal de progreso de los estudiantes -->
                <div class="modal fade" id="progressModal{{ $course->id }}" tabindex="-1"
                    aria-labelledby="progressModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="progressModalLabel">Progreso de los estudiantes</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Lista de estudiantes -->
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Estudiante</th>
                                            <th>Progreso (%)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($course->users as $student)
                                            <tr>
                                                <td>{{ $student->name }}</td>
                                                <td>{{ $student->progress }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $courses->links() }}
        </div>

        <!-- Modal para crear curso -->
        @include('livewire.courses-manager.modal-create')
    </div>
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

            Livewire.on('close-modal-comments', () => {
                $('#commentsModal').modal('hide');
            });
            Livewire.on('open-modal-course', () => {
                $('#crearCourseModal').modal('show');
            });
        });
    </script>
@endpush
