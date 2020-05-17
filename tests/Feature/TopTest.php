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
        $movie1 = $this->createMovie('Rocky');
        $movie2 = $this->createMovie('Smith');
        
        $this->createComment($movie1->id, 'Great movie!');
        $this->createComment($movie2->id, 'Really good!');
        $this->createComment($movie2->id, 'Funny!');
            
        $data = $this->get('/top')
            ->assertJsonCount(2)
            ->decodeResponseJson();
        
        $this->assertEquals($movie2->id, $data[0]['movie_id']);
        $this->assertEquals(2, $data[0]['total_comments']);
        $this->assertEquals(1, $data[1]['total_comments']);
        $this->assertEquals(1, $data[0]['rank']);
        $this->assertEquals(2, $data[1]['rank']);
        $this->assertArrayHasKey('movie_id', $data[0]);
        $this->assertArrayHasKey('total_comments', $data[0]);
        $this->assertArrayHasKey('rank', $data[0]);
    }
    
    /** @test */
    public function top_movies_can_be_specified_in_date_range()
    {
        $movie1 = $this->createMovie('Rocky');
        $movie2 = $this->createMovie('Smith');
        
        $this->createComment($movie1->id, 'Great movie!');
        $this->createComment($movie2->id, 'Really good!');
        
        Comment::create([
            'movie_id' => $movie2->id,
            'description' => "Funny!",
            'created_at' => now()->subDays(5)
            ]);
        
        $this->assertCount(3, Comment::all());
        
        $from = now()->subDay(1);
        $to = now()->addDay(1);
        
        $data = $this->get('/top/?date_from=' . $from . '&date_to=' . $to)
        ->assertJsonCount(2)
        ->assertJsonMissing(['rank'=>2])
        ->decodeResponseJson();
        
        $this->assertEquals($movie1->id, $data[0]['movie_id']);
        $this->assertEquals(1, $data[0]['rank']);
        $this->assertEquals(1, $data[1]['rank']);
    }
    
    private function createMovie($title = [])
    {
        $this->post('/movies', [
            'title' => $title
        ]);
        
        return Movie::orderBy('id', 'DESC')->first();
    }
    
    private function createComment($movieId, $content)
    {
        $this->post('/comments', [
           'movie_id' => $movieId,
            'description' => $content
        ]);
        
        return Comment::orderBy('id', 'DESC')->first();
    }
}
