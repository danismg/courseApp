<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    //cara pertama dalam mempersiapkan mass assigment
    protected $fillable = ['name', 'slug', 'icon'];

    //cara kedua dalam mempersiapkan mass assigment
    // protected $guarded = ['id'];

    // category memiliki banyak course
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
