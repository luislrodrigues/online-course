<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name', 
        'type',
        'status'
    ];

    //relations

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

}
