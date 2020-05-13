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
        $response = $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        
        $this->assertCount(1, Movie::all());
        $this->assertEquals('Rocky', Movie::first()->title);
        $response->assertStatus(201);
    }
    
    /** @test */
    public function title_is_required()
    {
        $response = $this->post('/movies', [
            'title' => ''
        ]);
        
        $this->assertCount(0, Movie::all());
        $response->assertStatus(422);
    }
    
    /** @test */
    public function min_3_chars_required()
    {
        $response = $this->post('/movies', [
            'title' => 'a'
        ]);
        
        $this->assertCount(0, Movie::all());
        $response->assertStatus(422);
    }
    
    /** @test */
    public function movie_needs_to_exist_in_omdb()
    {
        $response = $this->post('/movies', [
            'title' => 'ThisIsTest'
        ]);
        
        $this->assertCount(0, Movie::all());
        $response->assertStatus(404);
    }
    
    /** @test */
    public function movie_can_not_be_duplicated()
    {
        $response = $this->post('/movies', [
            'title' => 'Smith'
        ]);
        
        $this->assertCount(1, Movie::all());
        $response->assertStatus(201);
        
        $response = $this->post('/movies', [
            'title' => 'Smith'
        ]);
        
        $this->assertCount(1, Movie::all());
        $response->assertStatus(409);
    }
    
    /** @test */
    public function all_movies_can_be_returned()
    {
        $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        
        $this->post('/movies', [
            'title' => 'Mr. & Mrs. Smith'
        ]);
        
        $this->post('/movies', [
            'title' => 'Smith'
        ]);
        
        $this->get('/movies')
            ->assertOk()
            ->assertJsonCount(3)
            ->assertJsonFragment(['title'=>'Rocky'])
            ->assertJsonFragment(['title'=>'Mr. & Mrs. Smith'])
            ->assertJsonFragment(['title'=>'Smith']);
        
        $this->assertCount(3, Movie::all());
    }
    
    /** @test */
    public function movie_can_be_finded_by_passing_id()
    {
        $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        
        $this->post('/movies', [
            'title' => 'Smith'
        ]);
        
        $movie = Movie::first();
        $this->assertCount(2, Movie::all());
        
        $this->get('/movies/?id=' . $movie->id)
            ->assertOk()
            ->assertJsonFragment(['id'=>$movie->id])
            ->assertJsonMissing(['title'=>'Smith']);
    }
    
    /** @test */
    public function movie_can_be_filtered_by_passing_title()
    {
        $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        $this->post('/movies', [
            'title' => 'Smith'
        ]);
        
        $this->assertCount(2, Movie::all());
        
        $this->get('/movies/?title=Rocky')
            ->assertOk()->assertJsonFragment(['title'=>'Rocky'])
            ->assertJsonMissingExact(['title'=>'Smith']);
    }
    
    /** @test */
    public function movie_can_be_filtered_by_passing_genre()
    {
        $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        $this->post('/movies', [
            'title' => 'Smith'
        ]);
        
        $this->assertCount(2, Movie::all());
        
        $this->get('/movies/?genre=Sport')
            ->assertOk()
            ->assertJsonFragment(['title'=>'Rocky'])
            ->assertJsonMissingExact(['title'=>'Smith']);
    }
    
    /** @test */
    public function movie_can_be_filtered_by_passing_writer()
    {
        $this->post('/movies', [
            'title' => 'Rocky'
        ]);
        $this->post('/movies', [
            'title' => 'Smith'
        ]);
        
        $this->assertCount(2, Movie::all());
        
        $this->get('/movies/?writer=Stallone')
        ->assertOk()
        ->assertJsonFragment(['title'=>'Rocky'])
        ->assertJsonMissingExact(['title'=>'Smith']);
    }
}
