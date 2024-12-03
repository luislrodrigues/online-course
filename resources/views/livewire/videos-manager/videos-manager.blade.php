<div class="container mt-3">
    <div wire:loading class="spinner-border"
        style="width: 3rem; height: 3rem;position: fixed; z-index: 1031;top: 50%;left: 50%;" role="status"></div>
    <div class="row">
        <h2 class="text-center mb-2" style="font-size: 25px;"><strong>Videos</strong></h2>
    </div>
    <!-- Filtros -->
    <div class="row mb-4">
        <!-- Campo de búsqueda -->
        <div class="col-md-4">
            <label for="search" class="form-label">Buscar</label>
            <input type="text" id="search" class="form-control" placeholder="Buscar" wire:model="search">
        </div>

        <!-- Selector de categoría -->
        <div class="col-md-4">
            <label for="category" class="form-label">Categoría</label>
            <select id="category" class="form-control" wire:model="category">
                <option value="">Selecciona una categoría</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Botón de búsqueda -->
        <div class="col-md-2 d-flex align-items-end py-1">
            <button class="btn btn-primary w-100" wire:click="$refresh">Buscar</button>
        </div>
        <!-- Botón de crear -->
        <div class="col-md-2 d-flex align-items-end py-1">
            <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#crearVideoModal">Crear
                video</button>
        </div>
    </div>
    <div class="row">
        @foreach ($videos as $video)
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm rounded-lg h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0"
                            style="font-size: 1rem; white-space: normal; word-wrap: break-word;">{{ $video->title }}
                        </h5>
                        <div class="d-flex">
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" wire:click="editVideo({{$video->id}})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger ms-2" wire:click="destroy({{ $video->id }})">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body text-center">
                        <!-- Miniatura del video -->
                        <img src="https://img.youtube.com/vi/{{ $video->youtube_id }}/0.jpg" alt="{{ $video->title }}"
                            class="img-fluid mb-2 w-100 rounded" onclick="showVideo('{{ $video->id }}')"
                            style="cursor: pointer;">
                        <div class="text-muted mb-2" style="font-size: 0.875rem;">
                            {{ Str::limit($video->description, 80) }}</div>
                    </div>
                    <hr class="my-0">
                    <div class="card-body p-2">
                        <h6 class="text-muted mb-2" style="font-size: 0.875rem;">Categorías</h6>
                        @foreach ($video->categories as $category)
                            <a href="#" class="badge bg-primary me-1 my-1">{{ $category->name }}</a>
                        @endforeach
                    </div>
                    <hr class="my-0">
                    <div class="card-body text-center p-2">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye"></i>
                            <span id="like-text-{{ $video->id }}"
                                style="font-size: 0.875rem;">{{ $video->views }}</span> Vistas
                        </button>
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-thumbs-up"></i>
                            <span id="like-text-{{ $video->id }}"
                                style="font-size: 0.875rem;">{{ $video->likes()->count() }}</span> Likes
                        </button>
  
                        <button class="btn btn-outline-secondary btn-sm ms-2" data-bs-toggle="modal"
                            data-bs-target="#commentsModal">
                            <i class="fas fa-comment"></i> Comentarios
                        </button>
                    </div>
                </div>
            </div>
            <div class="video-container text-center" id="video-{{ $video->id }}"
                style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 999; background: rgba(0, 0, 0, 0.8); padding: 10px; border-radius: 8px; max-width: 60%; max-height: 80%;">
                <!-- Botón de cierre -->
                <button onclick="closeVideo('{{ $video->id }}')" class="btn-close btn-light position-absolute"
                    style="top: 10px; right: 10px;"></button>

                <iframe width="100%" height="400" src="https://www.youtube.com/embed/{{ $video->youtube_id }}"
                    frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen></iframe>
            </div>
            <div class="modal modal-lg fade" id="commentsModal" tabindex="-1"
                aria-labelledby="commentsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="commentsModalLabel">Comentarios de {{ $video->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                            <!-- Lista de Comentarios -->
                            <div class="container mt-3">
                                <div class="mt-4">
                                    <h5>Comentarios Aprobados</h5>
                                    @foreach ($video->comments as $comment)
                                        @if ($comment->status == 'approved')
                                            <div class="card mb-2" id="commentsModal{{ $video->id }}">
                                                <div class="card-body">
                                                    <p><strong>{{ $comment->user->name }}</strong></p>
                                                    <p>{{ $comment->content }}</p>
                                                    <span class="badge bg-success">Aprobado</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <h5>Comentarios Pendientes de Aprobación</h5>
                                    @foreach ($video->comments as $comment)
                                        @if ($comment->status == 'pending')
                                            <div class="card mb-2" id="commentsModal2{{ $video->id }}">
                                                <div class="card-body">
                                                    <p><strong>{{ $comment->user->name }}</strong></p>
                                                    <p>{{ $comment->content }}</p>
                                                    <span class="badge bg-warning">Pendiente</span>
                                                    <button class="btn btn-success btn-sm float-end mx-1"
                                                        wire:click="approveComment({{ $comment->id }},'approved')">Aprobar</button>
                                                    <button class="btn btn-danger btn-sm float-end mx-1"
                                                        wire:click="approveComment({{ $comment->id }},'rejected')">Rechazar</button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <!-- Paginación -->
        <div class="d-flex justify-content-center mt-4">
            {{ $videos->links() }}
        </div>
    </div>
    @include('livewire.videos-manager.modal-create')
</div>
@push('script')
    <script>
        function showVideo(videoId) {
            // Ocultar todos los videos
            const videos = document.querySelectorAll('.video-container');
            videos.forEach(function(video) {
                video.style.display = 'none';
            });

            // Verificar si el video existe antes de intentar cambiar su estilo
            const selectedVideo = document.getElementById('video-' + videoId);

            if (selectedVideo) {
                selectedVideo.style.display = 'block';
            } else {
                console.error('No se encontró el video con id:', videoId);
            }
        }

        // Función para cerrar el video
        function closeVideo(videoId) {
            const videoContainer = document.getElementById('video-' + videoId);
            if (videoContainer) {
                videoContainer.style.display = 'none';
            }
        }

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
            Livewire.on('open-modal-video', () => {
                $('#crearVideoModal').modal('show');
            });
        });
    </script>
@endpush
