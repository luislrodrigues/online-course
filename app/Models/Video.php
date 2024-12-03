<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'videos';

    protected $fillable = [
        'title', 
        'url', 
        'description', 
        'views',
        'status'
    ];

    //methods

    public function getYoutubeIdAttribute()
    {
        // Usamos una expresión regular para extraer el ID de YouTube
        preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $this->url, $matches);

        return $matches[1] ?? null;
    }

    //relations

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_video');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->where('status','<>','rejected');
    }

    public function categories()
    {
        return $this->belongsToMany(VideoCategory::class, 'video_category_video', 'video_id', 'category_id');
    }
}
