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
        'category_id'
    ];

    
    //relations

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
