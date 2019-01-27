<?php session_start(); ?>
<!DOCTYPE html>
<html>
<title></title>

<script type="text/javascript" src="getData.js"></script>
	<script type="text/javascript" src="sort.js"></script>
	<script type="text/javascript" src="filter.js"></script>
	<script 
		src="https://unpkg.com/popper.js">
	</script>
		<script
		src="https://code.jquery.com/jquery-3.3.1.js"
		integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60="
		crossorigin="anonymous">
	</script>
	<script 
		src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
		integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" 
		crossorigin="anonymous">
	</script>

	<link 
		rel="stylesheet" 
		href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
		integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
		crossorigin="anonymous">
	<link 
		rel="stylesheet" 
		href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" 
		integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" 
		crossorigin="anonymous">
	<link 
		href="https://stackpath.bootstrapcdn.com/bootswatch/4.2.1/cyborg/bootstrap.min.css" 
		rel="stylesheet" 
		integrity="sha384-e4EhcNyUDF/kj6ZoPkLnURgmd8KW1B4z9GHYKb7eTG3w3uN8di6EBsN2wrEYr8Gc" 
		crossorigin="anonymous">
		<link href="style.css" rel="stylesheet" type="text/css" />
	<style type="text/css">
		/* AESTHETIC */
	</style>
	<style type="text/css">
		.vl
		{
			width: 1px;
			background: -webkit-linear-gradient(top, rgba(0, 0, 0, 0) 0%, rgb(139, 139, 139) 50%, rgba(0, 0, 0, 0) 100%);
		}
		.credit_table
		{
			width: 100%;
			overflow-x: scroll;
		}
		.cell_name
		{
			height: 45px;
			padding-bottom: 10px;
		}
		.cell_image
		{
			height: 90px;
		}
		.cell_role
		{
			height: 60px;
			font-size: smaller;
		}
		td:nth-child(even) 
		{
			background: #222222;
		}
		td:nth-child(odd)
		{
			background: #282828;
		}
		td
		{
			padding:0.25%;
		}
	</style>
</head>
<body>
<div class="topnav" id="myTopnav">
		<a class="navbar-brand" href="#">
    		<img src="<?php echo $_SESSION['picture']?>" alt="profile picture" style="width:40px;">
		</a>
		<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
        	<?php echo $_SESSION['first_name']?>
      	</a>
		<div class="dropdown-menu">
        	<a class="dropdown-item" href="#">My Profile</a>
        	<a class="dropdown-item" href="/Hypertube/logout.php">Logout</a>
    	</div>
		<center>
		<div class="topnav-centered">
			<a href="/Hypertube"><img src="logo.png" alt="logo" height="70%" width="70%"></a>
		</div>
		</center>
</div>
	<br>
	<div id="result" class="card border-info mb-3">		
	</div>
</body>
</html>
<script type="text/javascript">

	var val = "<?php echo $_GET['id'] ?>";
	console.log(val);
	if (val)
	{
		
		$(document).ready(function()
		{
			var result;

			jQuery.ajaxSetup({async:false});
			$.get("https://api.themoviedb.org/3/movie/"+ val +"?api_key=4084c07502a720532f5068169281abff",function(rawdata)
			{
				result = appendMovieData(rawdata, val, "info");
						
			});

			console.log(result);
			var content;

			//ERROR CHECKING - so as not to get funny values displaying
			// check if there is a rating given
			var rating;
			if (result.imdbRating === 'N/A' || result.imdbRating === 'undefined' || result.imdbRating === undefined || result.imdbRating === 'null' || result.imdbRating === null || isNaN(result.imdbRating)) 
				rating = 'N/A';
			else
				rating = result.imdbRating + "/10";	

			// check if there is an IMDB ID to have a URL
			var imdbURL;
			if (result.imdbID === 'N/A' || result.imdbID === 'undefined' || result.imdbID === undefined || result.imdbID === 'null' || result.imdbID === null || rating === 'N/A')
				imdbURL = "<p> </p>";
			else
				imdbURL = "<a href='"+ result.imdbURL +"'>Go to IMDb Page</a>";

			//check if there is a year provided
			var yearRelease = result.Year;
			if (yearRelease === 'N/A' || yearRelease === 'undefined' || yearRelease === undefined || yearRelease === 'null' || yearRelease === null || isNaN(yearRelease) || yearRelease <= 0) 
				yearRelease = 'N/A';

			// check if there is a movie poster avaliable
			var srcImage;
			if (!(result.poster_path === null))
				srcImage = "https://image.tmdb.org/t/p/w342" + result.poster_path;
			else if (!(result.Poster === 'N/A' || result.Poster === undefined))
				srcImage = result.Poster;
			else 
				srcImage = "http://i67.tinypic.com/10fc1lg.jpg";

			var originalTitle;
			if (result.title != result.original_title)
				originalTitle = `<h6>(`+ result.original_title +`)</h6>`;
			else
				originalTitle = ""

			var genreList;
			genreList = stringifyGenre(result.genres);

			// http://i63.tinypic.com/2hp39tg.png
			var cast = fillTable(result.cast, "cast");
		//	console.log(cast);
			var crew = fillTable(result.crew, "crew");
		//	console.log(crew);
			window.title = result.title;
					// this is creating a div with the content inside of it
					content =
					`<div class="card-header">
						<h4 id="movieName" class="card-title">`+ result.title+ `</h4>
						`+ originalTitle +`
						<p class="text-muted">(`+ result.Year +`)</p>
					</div>
					<div class="card-body">	
						<div class="container-fluid">
							<div class="row">
								<div class="col-sm-4 gallery-pad">
									<img src="` + srcImage + `" style="width:100%;"/>
									<div class="row IMDb" style="padding: 5px;">
										<div class="col-sm gallery-pad">
											<p><i class="fas fa-star"></i> `+ rating +`</p>
										</div>
										<div class="vl"></div>
										<div class="col-sm gallery-pad">
											<a href="`+ result.imdbURL +`">Go to IMDb Page</a>
										</div>
									</div>
								</div>
								<div class="col-sm-8 gallery-pad">
									<p><b>Genre:</b> `+ genreList +`</p>
									<br>
									<p><b>Plot:</b> `+ result.Plot +`</p>
									<br>
									 <center>
        <div class="col">
            <button class="btn"><i class="fa fa-download"></i> Download</button> 
            <button class="btn" onclick="sendobj_to_video()"><i class="fa fa-tv"></i> Stream</button>
        </div>
    </center>
								</div>

							</div>
							<div class="row">
								<div class="credit_table">
									<p><b>Cast:</b><p>
										<table>
											<tr>`+ cast +`</tr>
										</table>
								</div>
								<div class="credit_table">
									<p><b>Crew:<b><p>
									<table>
										<tr>`+ crew +`</tr>
									</table>
								</div>
							</div>
							<div class="row">

							</div>
						</div>
					</div>`;

				$('#result').append(content).hide().fadeIn(); 			
		});
	}

	function stringifyGenre(result)
	{
		let names = result.map(item => item.name);
		result = names.join(', ');

		return result;
	}

	function fillTable(result, type) // type = cast or crew
	{
		let content = "";

		for (var i = 0; i < result.length; i++) 
		{
			
			let role;

			let srcImage;
			if (!(result[i].profile_path === null))
			srcImage = "https://image.tmdb.org/t/p/w90_and_h90_face/" + result[i].profile_path; // w342 //https://image.tmdb.org/t/p/w90_and_h90_face/kU3B75TyRiCgE270EyZnHjfivoq.jpg
			else
				srcImage = "http://i63.tinypic.com/2hp39tg.png"
			
			if (type == "cast")
				role = result[i].character;
			if (type == "crew")
				role = result[i].job;  // ""+ result[i].job +" ("+ result[i].department +")";
			 
			content += 
			"<td><table><tr><div class='cell_name'><b>"+ result[i].name +"</b></div></tr><tr><div class='cell_image'><img src='"+ srcImage +"'/></div></tr><tr><div class='cell_role'><p>"+ role +"</p></div></tr></table></td>";
		}

		return content; 
	}

</script>



    <br />

    <script>
      var val = "<?php echo $_GET['id'] ?>";
      

      function sendobj_to_video() {
        window.open("http://localhost:8080/Hypertube/video.php?torrent_id=" +val+"&title="+window.title);
      }

        function myFunction() {
          var x = document.getElementById("myTopnav");
          if (x.className === "topnav") {
            x.className += " responsive";
          } else {
            x.className = "topnav";
          }
        }
    </script>
