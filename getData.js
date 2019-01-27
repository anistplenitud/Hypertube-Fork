
// This function goes through the multiple apis and then appens all the information into one json object so that other functions can extract data from it 
// and prevents the need to make multiple calls in different functions.

function appendMovieData(result, ID, pageType) // search or info page
{				
	//here we access OMDB and append some relevant fields we might need
	jQuery.ajaxSetup({async:false});	
	$.get("https://www.omdbapi.com/?i="+ ID +"&apikey=1f18a935",function(moviedata)
	{
		if(moviedata.Response)
		{	
			result["imdbID"] = ID;
			result["Year"] = Number(result.release_date.substring(0, 4));
			result["tmdbURL"] = "https://www.themoviedb.org/movie/"+ result.id +"";
			result["imdbURL"] = "https://www.imdb.com/title/"+ result["imdbID"] +"/";
			result["imdbRating"] = Number(moviedata.imdbRating);									
			result["Poster"] = moviedata.Poster;				

			if (pageType == "info")
			{
				//result["Actors"] = moviedata.Actors; // don't actually need this since this next query gets this info, but makes it easier to access
				//result["Director"] = moviedata.Director; // don't actually need this since this next query gets this info, but makes it easier to access
				//result["Writer"] = moviedata.Writer; // don't actually need this since this next query gets this info, but makes it easier to access
				result["Plot"] = moviedata.Plot;
				result["Production"] = moviedata.Production;
				result["Runtime"] = moviedata.Runtime;
				result["Rated"] = moviedata.Rated; // age restriction
				result["Website"] = moviedata.Website;
			}
			
		}
		if (pageType == "info")
		{
			$.get("https://api.themoviedb.org/3/movie/"+ result.id +"/credits?api_key=4084c07502a720532f5068169281abff",function(moviecredit)
			{
				result = $.extend({}, result, moviecredit);
			});
		}
	});			

	return result;	
}