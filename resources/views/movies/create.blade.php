@extends('layout')


@section('content')
	<h1>Choose a film</h1>
	
	<form method="post" action="/movies">
		@csrf
		Add title <input type="text" name="title">
		<input type="submit" value="Confirm">
	</form>
	
@endsection