# MovieBase REST API

## Installation

1. Clone repository
2. `cd ./moviebase`
3. Install composer dependencies `composer install`
4. Create a copy of your .env file `cp .env.example .env`
5. Generate an app encryption key `php artisan key:generate`
6. Create an empty database
7. Set your own .env file with the connection credentials, add OMDB_API_KEY=apikey
8. Run server `php artisan serve`
9. Migrate the database `php artisan migrate`

### Notes
You need to generate your apikey at web service: [http://www.omdbapi.com](http://www.omdbapi.com).

## Endpoints

### GET /movies:

Returns list of all movies already present in application database.  

**Content-Type:** application/json  
**Response:**  
Status code: 200  
Sample content:

	[
		{
		    "title": "Some",
		    "genre": "Action, Crime, Mystery",
		    "writer": "Eun-Jeong Kim, Eun-shil Kim",
		    "updated_at": "2020-05-11T08:08:35.000000Z",
		    "created_at": "2020-05-11T08:08:35.000000Z",
		    "id": 8
		},
		{
		 ...
	]
	
Allows filtering movies by passing id, title, genre, writer.  
**URL:**  
/movies?id={integer}  
/movies?title={string}  
/movies?genre={string}  
/movies?writer={string}  

Allows sorting movies by id, title, genre, writer.  
**URL:** /movies?sortBy={key}

### POST /movies:
Adds new full movie object to application database (fetched from [http://www.omdbapi.com/](http://www.omdbapi.com/) based on passed title) and returns it.

**Content-Type:** application/json  
**Required:** title  
**Success response:**  
Status code: 200  
Sample Content:  

	{
	    "title": "Some",
	    "genre": "Action, Crime, Mystery",
	    "writer": "Eun-Jeong Kim, Eun-shil Kim",
	    "updated_at": "2020-05-11T08:08:35.000000Z",
	    "created_at": "2020-05-11T08:08:35.000000Z",
	    "id": 8
	}
		
**Error Response:**

Status code: 422 Unprocessable Entity
Content:  

	{
	    "Info": [
	        "The title field is required."
	    ]
	}

Status code: 404 Not found
Content:  

	{ "Info": "Movie not found!" }

Status code: 409 Conflict
Content:  

	{ "Info": "The movie already exists in a database!" }

### GET /comments:

Returns list of all comments already present in application database.

**Content-Type:** application/json  
**Response:**  
Status code: 200  
Sample content:  

	[
	    {
	        "id": 1,
	        "movie_id": 1,
	        "description": "Great movie!",
	        "created_at": "2020-04-29T06:50:11.000000Z",
	        "updated_at": "2020-04-29T06:50:11.000000Z"
	    },
	...
	]


Allows filtering comments by associated movie, by passing its ID.  
**URL:** /comments?movie_id={integer}

### POST /comments:

Adds new comment to application database and returns it in request response.  
Request body contains ID of movie already present in database, and comment text body.

**Content-Type:** application/json  
**Required:** movie_id, description  
**Success Response:**  
Status code: 200  
Sample Content: 

    {
        "id": 1,
        "movie_id": 1,
        "description": "Great movie!",
        "created_at": "2020-04-29T06:50:11.000000Z",
        "updated_at": "2020-04-29T06:50:11.000000Z"
    }

**Error Response:**  
    
Status code: 422 Unprocessable Entity  
Content: 

	{
	    "Info": [
		"The movie id field is required.",
		"The description field is required."
	    ]
	}


Status code: 404 Not found  
Content:  

	{ "Info": "Movie doesn’exist!" }  

### GET /top:
Returns top movies already present in the database ranking based on a number of comments added to the movie.  

Allows filtering top movies by specifying a date range for which statistics should be generated.  
**Optional params:** date_from, date_to  
**Content-Type:** application/json  
**Success Response:**  
Status code: 200  
Sample Content:  

	[
	    {
		"movie_id": 3,
		"total_comments": 2,
		"rank": 1
	    },
	...
	]
