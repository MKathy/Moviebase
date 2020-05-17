<?php

namespace Tests\Feature;

use App\Movie;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoviesTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function new_movie_can_be_added()
    {
        $this->createMovie('Rocky');
        $this->assertCount(1, Movie::all());
        $this->assertEquals('Rocky', Movie::first()->title);
    }
    
    /** @test */
    public function title_is_required()
    {
       $this->post('/movies')
            ->assertStatus(422);
        
        $this->assertCount(0, Movie::all());
    }
    
    /** @test */
    public function min_3_chars_required()
    {
        $this->createMovie('a', 422);

        $this->assertCount(0, Movie::all());
    }
    
    /** @test */
    public function movie_needs_to_exist_in_omdb()
    {
        $this->createMovie('ThisIsTest', 404);
        
        $this->assertCount(0, Movie::all());
    }
    
    /** @test */
    public function movie_can_not_be_duplicated()
    {
        $this->createMovie('Rocky');
        
        $this->assertCount(1, Movie::all());
        
        $this->createMovie('Rocky', 409);
        
        $this->assertCount(1, Movie::all());
    }
    
    /** @test */
    public function all_movies_can_be_returned()
    {
        $this->createMovie('Rocky');
        $this->createMovie('Mr. & Mrs. Smith');
        $this->createMovie('Smith');
        
        $this->assertCount(3, Movie::all());
        
        $this->get('/movies')
            ->assertOk()
            ->assertJsonCount(3)
            ->assertJsonFragment(['title'=>'Rocky'])
            ->assertJsonFragment(['title'=>'Mr. & Mrs. Smith'])
            ->assertJsonFragment(['title'=>'Smith']);
    }
    
    /** @test */
    public function movie_can_be_finded_by_passing_id()
    {
        $movie = $this->createMovie('Rocky');
        $this->createMovie('Smith');
        
        $this->assertCount(2, Movie::all());
        
        $this->get('/movies/?id=' . $movie->id)
            ->assertOk()
            ->assertJsonFragment(['id'=>$movie->id])
            ->assertJsonMissing(['title'=>'Smith']);
    }
    
    /** @test */
    public function movie_can_be_filtered_by_passing_title()
    {
        $this->createMovie('Rocky');
        $this->createMovie('Smith');
        
        $this->get('/movies/?title=Rocky')
            ->assertOk()->assertJsonFragment(['title'=>'Rocky'])
            ->assertJsonMissingExact(['title'=>'Smith']);
    }
    
    /** @test */
    public function movie_can_be_filtered_by_passing_genre()
    {
        $this->createMovie('Rocky');
        $this->createMovie('Smith');
        
        $this->get('/movies/?genre=Sport')
            ->assertOk()
            ->assertJsonFragment(['title'=>'Rocky'])
            ->assertJsonMissingExact(['title'=>'Smith']);
    }
    
    /** @test */
    public function movie_can_be_filtered_by_passing_writer()
    {
        $this->createMovie('Rocky');
        $this->createMovie('Smith');
        
        $this->get('/movies/?writer=Stallone')
        ->assertOk()
        ->assertJsonFragment(['title'=>'Rocky'])
        ->assertJsonMissingExact(['title'=>'Smith']);
    }
    
    private function createMovie($title, $statusCode=201)
    {
        $this->post('/movies', [
            'title' => $title
        ])->assertStatus($statusCode);
        
        return Movie::orderBy('id', 'DESC')->first();
    }
}
