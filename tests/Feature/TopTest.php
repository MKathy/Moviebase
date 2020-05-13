<?php

namespace Tests\Feature;

use App\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Comment;

class TopTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function top_movies_can_be_returned()
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
        ]);
        
        $this->post('/comments', [
            'movie_id' => $movie2->id,
            'description' => "Really good!"
        ]);
        
        $this->post('/comments', [
            'movie_id' => $movie2->id,
            'description' => "Funny!"
        ]);
        
        $data = $this->get('/top')
            ->assertJsonCount(2)
            ->decodeResponseJson();
        
        $this->assertEquals($movie2->id, $data[0]['movie_id']);
        $this->assertArrayHasKey('movie_id', $data[0]);
        $this->assertArrayHasKey('total_comments', $data[0]);
        $this->assertArrayHasKey('rank', $data[0]);
    }
    
    /** @test */
    public function top_movies_can_be_specified_in_date_range()
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
        ]);
        
        Comment::create([
            'movie_id' => $movie2->id,
            'description' => "Really good!",
            'created_at' => now()->subDays(5)
            ]);
        
        $this->post('/comments', [
            'movie_id' => $movie2->id,
            'description' => "Funny!"
        ]);
        $this->assertCount(3, Comment::all());
        
        $from = now()->subDay(1);
        $to = now()->addDay(1);
        
        $data = $this->get('/top/?date_from=' . $from . '&date_to=' . $to)
        ->assertJsonCount(2)
        ->assertJsonMissing(['rank'=>2])
        ->decodeResponseJson();
        
        $this->assertEquals($movie1->id, $data[0]['movie_id']);
        $this->assertArrayHasKey('movie_id', $data[0]);
        $this->assertArrayHasKey('total_comments', $data[0]);
        $this->assertArrayHasKey('rank', $data[0]);
    }
}
