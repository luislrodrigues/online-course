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
        'course_id', 
        'category_id',
        'status'
    ];


    //relations

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
