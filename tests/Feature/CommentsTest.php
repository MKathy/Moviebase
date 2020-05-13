<?php

namespace Tests\Feature;

use App\Comment;
use App\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentsTest extends TestCase
{
   use RefreshDatabase;
   
    /** @test */
    public function comment_can_be_added_to_movie()
    {
        $response = $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        
        $movie = Movie::first();
        
        $this->post('/comments', [
            'movie_id' => $movie->id,
            'description' => "Great movie!"
        ])->assertStatus(201);
       
        $this->assertCount(1, Comment::all());
        $this->assertEquals($movie->id, Comment::first()->movie_id);
        $this->assertEquals('Great movie!', Comment::first()->description);
    }
    
    /** @test */
    public function movie_id_is_required()
    {
        $this->post('/comments', [
            'movie_id' => '',
            'description' => "Great movie!"
        ])->assertStatus(422);
        
        $this->assertCount(0, Comment::all());
    }
    
    /** @test */
    public function description_is_required()
    {
        $response = $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        
        $movie = Movie::first();
        
        $this->post('/comments', [
            'movie_id' => $movie->id,
            'description' => ""
        ])->assertStatus(422);
        
        $this->assertCount(0, Comment::all());
    }
    
    /** @test */
    public function comment_can_be_added_to_only_existing_movie()
    {
        $this->post('/comments', [
            'movie_id' => 1,
            'description' => "Great movie!"
        ])->assertStatus(404);
        
        $this->assertCount(0, Comment::all());
    }
    
    /** @test */
    public function all_comments_can_be_returned()
    {
        $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        $movie = Movie::first();
        
        $this->post('/comments', [
            'movie_id' => $movie->id,
            'description' => "Great movie!"
        ])->assertStatus(201);
        
        $this->post('/comments', [
            'movie_id' => $movie->id,
            'description' => "Really good!"
        ])->assertStatus(201);
        
        $this->assertCount(2, Comment::all());
        $this->assertCount(1, Movie::all());
        
        $this->get('/comments')
            ->assertOk()
            ->assertJsonFragment(['id'=>Comment::first()->id])
            ->assertJsonFragment(['id'=>Comment::latest()->first()->id])
            ->assertJsonFragment(['movie_id'=>$movie->id]);
    }
    
    /** @test */
    public function comments_can_be_filtrated_by_movie_id()
    {
        $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        $this->post('/movies', [
            'title' => 'Smith'
        ]);

        $movie1 = Movie::first();
        $movie2 = Movie::orderBy('id', 'DESC')->first();
        
        $this->post('/comments', [
            'movie_id' => $movie1->id,
            'description' => "Great movie!"
        ])->assertStatus(201);
        
        $this->post('/comments', [
            'movie_id' => $movie2->id,
            'description' => "Really good!"
            ])->assertStatus(201);
        
        $this->assertCount(2, Comment::all());
        
        $this->get('/comments/?movie_id=' . $movie1->id)
            ->assertJsonMissing(['movie_id'=>$movie2->id])
            ->assertJsonFragment(['movie_id'=>$movie1->id])
            ->assertJsonCount(1);
    }
}
