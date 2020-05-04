<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Movie;

class Comment extends Model
{
    protected $fillable = ['movie_id', 'description'];
    
    public function movie() {
        return $this->belongsTo(Movie::class);
    }
}