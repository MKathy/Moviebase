<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TopMoviesController extends Controller
{
    public function index ()
    {        
        $data = DB::table('comments')
        ->select('movie_id', DB::raw('count(*) as total_comments'))
        ->when(request()->has('date_from') && request()->has('date_to'), function ($q) {
            
            $from = (new Carbon(request('date_from')))->hour(0)->minute(0)->second(0);
            $to = (new Carbon(request('date_to')))->hour(23)->minute(59)->second(59);
            
            return $q->whereBetween('created_at',  [$from, $to]);
        })
        ->groupBy('movie_id')
        ->orderBy('total_comments', 'desc')
        ->get();
        
        $index = 1;
        
        for ($i = 0; $i < count($data); $i++) 
        {
            if($i>0)
            {
                if($data[$i]->total_comments < $data[$i-1]->total_comments)
                {
                    $index++;
                }
            }
            $data[$i]->rank = $index;
        }
    
        return $data;
    }
}