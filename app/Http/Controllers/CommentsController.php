<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Movie;

class CommentsController extends Controller
{
    public function index()
    {
        if(request()->has('movie_id'))
        {
            return Comment::where('movie_id', request('movie_id'))->get();
        }
        
        return Comment::all();
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            
            'movie_id' => 'required',
            'description' => 'required'
        ]);
        
        if($validator->fails())
        {
            $errors = $validator->errors();

            return response($errors, 422);
        }

        if(!DB::table('movies')->where('id', '=', request('movie_id'))->count())
        {
            return response()->json(['Info' => 'Movie doesn\'t exist.'], 404);
        }
        
        return Comment::create(request(['description', 'movie_id']));
    }   
}
