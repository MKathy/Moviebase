<?php

namespace App\Http\Controllers;

use App\Movie;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;

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
        
        foreach (['title', 'genre', 'writer'] as $key)
        {
            if(request()->has($key))
            {
                return Movie::where($key, 'LIKE','%' . request($key) . '%')->get();
            }
        }
        
        if(request()->has('sortBy'))
        {
            return Movie::all()->sortBy(request('sortBy'))->values();
        }
        return Movie::all();
    }
        
    public function store() {
       
        $validator = Validator::make(request()->all(), [
            'title' => 'required|min:3',
        ]);
        
        if ($validator->fails()) {
            return response(['Info' => $validator->errors()->first()], 422);
        }
        
        if(Movie::where('title', 'LIKE','%' . request('title') . '%')->count())
        {
            return response()->json(['Info' => "The movie already exists in a database!"], 409);
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
