<div class="container mt-5">
    <h1 class="text-center mb-4">{{ $course->title }}</h1>

    <!-- Barra de progreso -->
    <div class="mb-4">
        <h5>Progreso del curso</h5>
        <div class="progress" style="height: 20px;">
            <div 
                class="progress-bar bg-success" 
                role="progressbar" 
                style="width: {{ $userProgress }}%;" 
                aria-valuenow="{{ $userProgress }}" 
                aria-valuemin="0" 
                aria-valuemax="100">
                {{ $userProgress }}%
            </div>
        </div>
    </div>

    <!-- Mensaje de felicitaciones -->
    @if ($showCongratulations)
        <div class="alert alert-success mt-4 text-center">
            ¡Felicidades! Has completado el curso.
        </div>
    @else 
        <!-- Reproductor de video -->
        <div class="card shadow mb-4">
            <div class="card-body text-center">
                <iframe 
                    id="video-player"
                    class="rounded border"
                    width="100%" 
                    height="400" 
                    src="https://www.youtube.com/embed/{{ $currentVideo->youtube_id ?? '' }}" 
                    frameborder="0" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button 
                    class="btn btn-secondary" 
                    wire:click="previousVideo" 
                    @if (!$hasPreviousVideo) disabled @endif>
                    Anterior
                </button>
                <button 
                    class="btn btn-primary" 
                    wire:click="nextVideo"
                    @if ($showCongratulations) disabled @endif>
                    Siguiente
                </button>
            </div>
        </div>
    @endif

    <!-- Like y Comentarios -->
    <div class="mt-5">
        <!-- Like -->
        <div class="d-flex align-items-center">
            <button 
                class="btn btn-outline-primary me-2" 
                wire:click="likeCourse" 
                @if ($hasLiked) disabled @endif>
                <i class="fas fa-thumbs-up"></i> Like
            </button>
        </div>

        <!-- Comentarios -->
        <div class="mt-4">
            <h5>Comentarios</h5>
            <div class="mb-3">
                <textarea class="form-control" rows="3" wire:model="newComment" placeholder="Escribe tu comentario..."></textarea>
            </div>
            <button class="btn btn-success" wire:click="submitComment">
                Publicar Comentario
            </button>
            
            <div class="mt-4">
                <h6>Comentarios anteriores</h6>
                @foreach ($currentVideo->comments as $comment)
                    <div class="card mb-2">
                        <div class="card-body">
                            <p class="mb-0">{{ $comment->user->name }}: {{ $comment->content }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
