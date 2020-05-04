<?php

namespace App\Http\Controllers;

use App\Movie;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
// use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator;
use Exception;

class MoviesController extends Controller
{
    protected $serviceURL;
    
    public function __construct()
    {
        $this->serviceURL = "http://www.omdbapi.com/?apikey=" . config('services.omdbapi.key');
    }
    
    public function index() 
    {
        if(request()->has('id'))
        {
            return Movie::where('id', request('id'))->get();
        }
        
        if(request()->has('title'))
        {
            return Movie::where('title', 'LIKE','%' . request('title') . '%')->get();
        }
        
        if(request()->has('genre'))
        {
            return Movie::where('genre', 'LIKE','%' . request('genre') . '%')->get();
        }
        
        if(request()->has('sortBy'))
        {
            return Movie::all()->sortBy(request('sortBy'))->values();
            
        }
        return Movie::all();
    }
    
    public function filterBy() 
    {
        return Movie::all()->pluck(request()->filterBy);
    }
    
    public function store() {
       
        $validator = Validator::make(request()->all(), [
            'title' => 'required|min:3',
        ]);
        
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        if(Movie::where('title', 'LIKE','%' . request('title') . '%')->count())
        {
            return response()->json(['Info' => "The movie already exists in a database"], 404);
        }
        
        $title = request('title');
        $client = new Client();
        
            $response = $client->request('GET', $this->serviceURL . "&t=" . $title);
        
        $data = json_decode($response->getBody()->getContents(), true);
        
        if($data['Response'] == "False")
        {
            return response()->json(['Info' => $data['Error']], 404);
        }
        
        return Movie::create([
            'title' => $data['Title'],
            'genre' => $data['Genre'],
            'writer' => $data['Writer']
        ]);    
    }
}
