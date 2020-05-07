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
You need to generate your apikey at web service: http://www.omdbapi.com

## Endpoints

GET /movies  
POST /movies  
GET /comments  
POST /comments  
GET /top  



