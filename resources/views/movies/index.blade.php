@extends('layout')

@section('content')
	<h1>Movies list </h1>
	<div class="get_movies">
    	@foreach($movies as $movie)
    		<li>{{ $movie->title }}</li>
    	@endforeach
	</div>
	<p><b>Add a new movie. </b></p>
	<div class="post_movies">
    	<form method="post" action="/movies">
    	@csrf
    		<label for="title">Title</label>
    		<input type="text" name="title">
    		<input type="submit" value="Post">
    	</form>
	</div>
	@if($errors->any())
		<div class="error">
    			@foreach($errors->all() as $error)
    				<p> {{ $error }} </p>
    			@endforeach
		</div>
	@endif
@endsection