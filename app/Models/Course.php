<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'courses';

    protected $fillable = [
        'title',
        'description',
        'age_group',
        'image',
        'status'
    ];

    
    //relations

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function videos()
    {
        return $this->belongsToMany(Video::class,'course_video');
    }

    public function categories()
    {
        return $this->belongsToMany(CourseCategory::class, 'course_category_course', 'course_id', 'category_id');
    }

}
