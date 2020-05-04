<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title> @yield('title') </title>
  <link rel="stylesheet" href="css/styles.css?v=1.0">
	<style>
	body {
	   /* Background pattern from Toptal Subtle Patterns */
	   background-image: url("{{ asset('img/background.png') }}");
	}	
	.containter {
	   width: 1000px;
	   margin-left: auto;
	   margin-right: auto;
	   
	}
    input[type=text] {
      width: 50%;
      padding: 5px;
      margin: 8px 5px;
      box-sizing: border-box;
      border: 1px solid #b5aaa8;
      border-radius: 3px;
    }
    #description {
      width: 100%;
      min-height: 50px;
      padding: 5px;
      margin: 8px 0;
      box-sizing: border-box;
    }
    .get_comments {
      min-height: 200px;
    }
    .get_movies {
      min-height: 300px;
    }
    .post_comments input[type=submit]{
        display: block;
        text-align: center;
        margin-left: auto;
        margin-right: auto;
    }
    .option {
      min-height: 10px;
      min-width: 20px;
      border: 1px solid #b5aaa8;
      padding: 8px;
      margin: 8px 0;
      border-radius: 5px;
      background: white;
       
    }
    textarea {
        border-radius: 5px;
    }
    select {
        font-size: 14px;
        border: none;
        width: 100%;
        background: white;
    }
    input[type=submit] {

        font-size: 18px;
        border: 1px solid #b5aaa8;
        -webkit-border-radius: 5px;
        cursor:pointer;
    }
    input[type=submit]:hover {
        background-color: #c6bbb9;
    }
    .error {
            color: red;
     }
	</style>
</head>

<body>
  <div class="containter">
  	@yield('content')
  </div>
</body>
</html>