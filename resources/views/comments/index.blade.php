@extends('layout')

@section('content')
	
	<div class="get_comments">
		<h1>All comments</h1>
		
		@foreach($comments as $comment)
			<li>
				{{ $comment->description }}
			</li>
		@endforeach
	</div>
	<div class="post_comments">
		<form method="post" action="/comments">
		@csrf
			Options 
			<div class="option">				
            		<select name="movie_id">
            			@foreach($movies as $movie)
            				<option value="{{ $movie->id }}">
            					(id: {{ $movie->id }}) {{ $movie->title }}
            				</option>
            			@endforeach
            		</select>
    		</div>
    		<label for="description">Comment</label>
    			<textarea id="description" name="description"></textarea>
    			<input type="submit" value="POST">
		</form>
	</div>
	
@endsection