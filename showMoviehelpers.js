
async function getMovieDataPromise(result, pageType)
{

	for(let i = 0; i < result.length; i++) 
	{
		let response = await fetch("https://api.themoviedb.org/3/movie/"+ result[i].id +"/external_ids?api_key=4084c07502a720532f5068169281abff");

		if (response.status !== 200)
			alert("something went wrong");
		else 
			var movie = await response.json();

		let response2 = await fetch("https://www.omdbapi.com/?i="+ movie.imdb_id +"&apikey=1f18a935");
		if (response2.status !== 200)
			alert("something went wrong");
		else 
			var moviedata = await response2.json();

	if(moviedata.Response)
	{	
		result[i].imdbID = movie.imdb_id;
		result[i].Year = Number(result[i].release_date.substring(0, 4));
		result[i].tmdbURL = "https://www.themoviedb.org/movie/"+ result[i].id +"";
		result[i].imdbURL = "https://www.imdb.com/title/"+ result[i].imdbID +"/";
		result[i].imdbRating = Number(moviedata.imdbRating);									
		result[i].Poster = moviedata.Poster;
		result[i].genres = JSON.stringify(result[i].genres);// moviedata.Genre;
		//console.log();
		result[i].Title = moviedata.Title;				

		if (pageType == "info")
		{	
				result[i].Plot = moviedata.Plot;
				result[i].Production = moviedata.Production;
				result[i].Runtime = moviedata.Runtime;
				result[i].Rated = moviedata.Rated; // age restriction
				result[i].Website = moviedata.Website;
			}
			
	}
	if (pageType == "info")
	{
		let response3 = await fetch("https://api.themoviedb.org/3/movie/"+ result[i].id +"/credits?api_key=4084c07502a720532f5068169281abff");
		if (response3.status !== 200)
			alert("something went wrong");
		else 
			var moviecredit = await response3.json();
		
		let response4 = await fetch("https://api.themoviedb.org/3/movie/"+ result[i].id +"?api_key=4084c07502a720532f5068169281abff");
		if (response4.status !== 200)
			alert("something went wrong");
		else 
			var moviedetail = await response4.json();

		result[i] = $.extend({}, result[i], moviecredit, moviedetail);
	}
	console.log(result[i]);
			
	}
	return result;

}

function createMovieCard(moviedata) 
{

				var content;
				var imdbRating;
				var imdbURL;

				var rating;
				imdbRating = moviedata.imdbRating;
				if (imdbRating === 'N/A' || imdbRating === 'undefined' || imdbRating === undefined || imdbRating === 'null' || imdbRating === null || isNaN(imdbRating)) //imdbRating === NaN || imdbRating === "NaN" || movie.imdbID === NaN || movie.imdbID === "NaN"
					rating = 'N/A';
				else
					rating = imdbRating + "/10";	

				// check if there is an IMDB ID to have a URL
				if (moviedata.imdbID === 'N/A' || moviedata.imdbID === 'undefined' || moviedata.imdbID === undefined || moviedata.imdbID === 'null' || moviedata.imdbID === null) //|| rating === 'N/A'
					imdbURL = "<p> </p>";
				else
					imdbURL = "<a href='"+ moviedata.imdbURL +"'>Go to IMDb Page</a>";

				//check if there is a year provided
				var yearRelease = moviedata.Year;
				if (yearRelease === 'N/A' || yearRelease === 'undefined' || yearRelease === undefined || yearRelease === 'null' || yearRelease === null || isNaN(yearRelease) || yearRelease <= 0) 
					yearRelease = 'N/A';

				// check if there is a movie poster avaliable
				var srcImage;
				if (!(moviedata.poster_path === null))
					srcImage = "https://image.tmdb.org/t/p/w342" + moviedata.poster_path;
				else if (!(moviedata.Poster === 'N/A' || moviedata.Poster === undefined))
					srcImage = moviedata.Poster;
				else 
					srcImage = "http://i67.tinypic.com/10fc1lg.jpg";	

				// AESTHETIC - This is just a font size chaninging effect for if the movie name is too long.
				var titleSize;
				if(moviedata.title.length <= 65) 
					titleSize = "font-size: 1.2rem";
				else
					 titleSize = "font-size: 100%";
				
				var originalTitle;
				if (moviedata.title != moviedata.original_title)
					originalTitle = `<h6>(`+ moviedata.original_title +`)</h6>`;
				else
					originalTitle = ""
				
				var viewed;
				$.post('checkWatched.php', {movieID:moviedata.imdbID})
				.done(function( data ) 
				{
					if (data > 0)
						viewed = "display:block;"; 
					else
						viewed = "display:none;"; 

					// this is creating a div with the content inside of it
					content = 
					`<div id="`+ moviedata.imdbID +`"class="moviecards col-sm-4 card border-secondary sm-3" style="max-width: 20rem; min-width: 20rem; align-items: center; border-color: #9933CC;" onmouseover="movieHoverIn(this)" onmouseout="movieHoverOut(this)" onclick="loadInfo('`+ moviedata.imdbID +`','`+moviedata.Year+`')">
						<div class="card-header">
							<h5 class="card-title" style="`+ titleSize +`">`+ moviedata.title +`</h5>
							`+ originalTitle +`
						</div>
						<div class="card-body">
							<i class="far fa-eye" style="float: right; font-size: large; `+ viewed +`"></i>
							<br>
							<img src="` + srcImage + `" style="width: 100%; height: 450px; spadding-top: 0.5rem;"/>
							<br>
							<p text-muted>Year Released: ` + yearRelease +`</p>
						</div>
						<div class="card-footer">
							<p><i class="fas fa-star"></i> `+ rating +`</p>
							<br>
							`+ imdbURL +`
						</div>
					</div>`;
				
					$('#result').append(content).hide().fadeIn(); 
				})
				.fail(function() 
				{
					console.log("something went wrong..");
				});		
		
}
		