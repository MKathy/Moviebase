<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $guarded = [];
    
    public function comments() 
    {
        return $this->hasMany(Comment::class);
    }
}