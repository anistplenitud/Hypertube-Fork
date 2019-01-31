<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once("setup.php");
error_reporting(E_ALL);
session_start(); 

	$db->exec("USE hypertube");
	$query = $db->prepare("SELECT * FROM users WHERE id = :id");
	$query->bindParam(":id", $_SESSION['id']);
	$query->execute();
	$data = $query->fetch(PDO::FETCH_ASSOC);
	$username = $data['username'];
	$profilep = $data['picture'];


	if (!isset($_SESSION['id'])) 
	{
		header ('Location: ./');
	}

?>

<!DOCTYPE html>
<html>
<head>
<title>Hypertube</title>
<link rel="apple-touch-icon" sizes="57x57" href="/Hypertube/favicon/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/Hypertube/favicon/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/Hypertube/favicon/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/Hypertube/favicon/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/Hypertube/favicon/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/Hypertube/favicon/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/Hypertube/favicon/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/Hypertube/favicon/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/Hypertube/favicon/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/Hypertube/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/Hypertube/favicon/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/Hypertube/favicon/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/Hypertube/favicon/favicon-16x16.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/Hypertube/favicon/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<style>
	.paginationjs .paginationjs-pages li
	{
		background-color: #272727;
		border: none;
	}
	.paginationjs .paginationjs-pages li>a
	{
		background-color: #272727;
		color: white;
		border: none;		
		outline: none;
		height: 30px;
		line-height: 30px;
	}
	.paginationjs .paginationjs-pages li>a:hover
	{
		background-color: #9933CC;
		border: none;		
		outline: none;
		line-height: 25px;
	}
	.paginationjs .paginationjs-pages li.active>a
	{
		background-color: #9933CC;
		color: white
		border: none;		
		outline: none;
	}
	.disabled
	{
		color: #888888;
		border: none;		
		outline: none;
	}
	.paginationjs .paginationjs-pages li:last-child
	{
		
	}
	
</style>
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
		<!-- Pagination -->
	<link 
		rel="stylesheet" 
		href="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.css" 
		integrity="sha256-fFqxRJ9q487bQTOBfn4T8jkJt8IGlrVzTeauNnuRHVA=" 
		crossorigin="anonymous" />
	<script 
		src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.js" 
		integrity="sha256-4f5caNpqq/YzL53GMoOrN6Bna+a4NDUZrAVT+hUHZjU=" 
		crossorigin="anonymous">
	</script>
	 <script 
	 src="https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.4/pagination.min.js" 
	 integrity="sha256-E+fw0vUbuPq4p3FWWtX7FzzlcMTe7hvrgZxOk8LPAh4=" 
	 crossorigin="anonymous">	 	
	 </script>
	<style type="text/css">
		/* AESTHETIC */
	</style>
</head>
<body>
	<div id="google_translate_element"></div>
	<div class="topnav" id="myTopnav">
		<a class="navbar-brand" href="#">
    		<img src="<?php echo $profilep ?>" alt="profile picture" style="width:40px;">
		</a>
		<a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
        	<?php echo $username ?>
      	</a>
		<div class="dropdown-menu">
        	<a class="dropdown-item" href="./profile.php">My Profile</a>
        	<a class="dropdown-item" href="./logout.php">Logout</a>
    	</div>

		<center>
		<div class="topnav-centered">
			<a href="#"><img src="logo.png" alt="logo" height="70%" width="70%"></a>
		</div>
		</center>
		<button class="dropdown-btn" style="float: right;">
			<a href="#"><i class="fas fa-search"></i></a>
		</button>
		<div class="dropdown-container">
			<div>
				<div>
					<center>
						<br /><br /><br /><br />
  						<form class="card mb-3" style="background-color: transparent; border: none;">
							<?php
								if (isset($_GET['search'])) {
									echo'<input id="searchbar" class="fieldinput" type="text" name="search" value ='.$_GET['search'].' placeholder="Search for a movie" style="margin: auto;">';
								} else {
									echo'<input id="searchbar" class="fieldinput" type="text" name="search" placeholder="Search for a movie" style="margin: auto;">';
								}
							?>
						</form>
						<br>
					</center>
					<center>
						<div class="card border-secondary mb-3 form-group" id="sortandfiltercard" onmouseover="movieHoverIn(this)" onmouseout="movieHoverOut(this)">
							<div class="card-body">
								<!-- SORT  -->
								<h6>Sort</h6>
								<div id="sortForm" class="form-group row">
									<div class="custom-control custom-radio col-sm">
										<input type="radio" id="sortFormRadio1" name="sortFormRadio" class="custom-control-input sort fieldinput" value="" checked="">
										<label class="custom-control-label" for="sortFormRadio1"> None </label>
									</div>
									<div class="custom-control custom-radio col-sm">
										<input type="radio" id="sortFormRadio2" name="sortFormRadio" class="custom-control-input sort fieldinput" value="title"> <!-- value="&sort_by=original_title." -->
										<label class="custom-control-label" for="sortFormRadio2"> Name </label>
										<div id="sortFormName" class="">
											<select id="sortFormNameSelector" class="fieldinput">
												<option class="" value="asc" selected>A - Z</option> 
												<option class="" value="desc">Z - A</option> <!-- Ascending -->
											</select>						
										</div>
									</div>
									<div class="custom-control custom-radio col-sm">
										<input type="radio" id="sortFormRadio3" name="sortFormRadio" class="custom-control-input sort fieldinput" value="Year">
										<!-- value="&sort_by=release_date." -->
										<label class="custom-control-label" for="sortFormRadio3"> Year </label>
										<div id="sortFormYear" class="fieldinput">
											<select id="sortFormYearSelector">
												<option class="" value="asc" >Oldest - Newest</option> <!-- Ascending -->
												<option class="" value="desc" selected>Newest - Oldest</option> <!-- Descending release_date.desc primary_release_date.desc--> 
											</select>						
										</div>
									</div>
									<div class="custom-control custom-radio col-sm">
										<input type="radio" id="sortFormRadio4" name="sortFormRadio" class="custom-control-input sort fieldinput" value="imdbRating">
										<label class="custom-control-label" for="sortFormRadio4"> Rating </label>
										<div id="sortFormRating" class="">
											<select id="sortFormRatingSelector" class="fieldinput">
												<option class="" value="desc" selected>Highest - Lowest</option> <!-- Descending --> <!-- This I will have to make my own -->
												<option class="" value="asc">Lowest - Highest</option> <!-- Ascending -->
											</select>						
										</div>
									</div>
								</div>
								<br>
								<!-- Filter  -->
								<h6>Filter</h6>
								<div id="filterForm" class="form-group row">
									<div class="custom-control custom-radio col-sm">
										<input type="radio" id="filterFormRadio1" name="filterFormRadio" class="custom-control-input filter fieldinput" value="" checked="">
										<label class="custom-control-label" for="filterFormRadio1"> None </label>
									</div>
									<div class="custom-control custom-radio col-sm">
										<input type="radio" id="filterFormRadio2" name="filterFormRadio" class="custom-control-input filter fieldinput" value="Year">
										<label class="custom-control-label" for="filterFormRadio2"> Year </label>
										<div class="">
											<select id="filterFormYearSelectorFrom" class="fieldinput">
											</select> 
											to 
											<select id="filterFormYearSelectorTo" class="fieldinput">
											</select>
										</div>
									</div>
									<div class="custom-control custom-radio col-sm">
										<input type="radio" id="filterFormRadio3" name="filterFormRadio" class="custom-control-input filter fieldinput" value="imdbRating">
										<label class="custom-control-label" for="filterFormRadio3"> Rating </label>
										<div class="">
											<select id="filterFormRatingSelectorFrom" class="fieldinput">
											</select>
											to
											<select id="filterFormRatingSelectorTo" class="fieldinput">
											</select>
										</div>
									</div>
									<div class="custom-control custom-radio col-sm">
										<input type="radio" id="filterFormRadio4" name="filterFormRadio" class="custom-control-input filter fieldinput" value="genre_ids">
										<label class="custom-control-label" for="filterFormRadio4"> Genre </label>
										<div class="">
											<select id="filterFormGenreSelector" class="fieldinput">
												<option class="" value="28" selected>Action</option> <!-- 28 -->
												<option class="" value="12">Adventure</option> <!-- 12 -->
												<option class="" value="16">Animation</option> <!-- 16 -->
												<option class="" value="35">Comedy</option> <!-- 35 -->
												<option class="" value="80">Crime</option> <!-- 80 -->
												<option class="" value="99">Documentary</option> <!-- 99 -->
												<option class="" value="18">Drama</option> <!-- 18 -->
												<option class="" value="10751">Family</option> <!-- 10751 -->
												<option class="" value="14">Fantasy</option> <!-- 14 -->
												<option class="" value="36">History</option> <!-- 36 -->
												<option class="" value="27">Horror</option> <!-- 27 -->
												<option class="" value="10402">Music</option> <!-- 10402 -->
												<option class="" value="9648">Mystery</option> <!-- 9648 -->
												<option class="" value="10749">Romance</option> <!-- 10749 -->
												<option class="" value="878">Sci-Fi</option> <!-- 878 -->	 
												<option class="" value="53">Thriller</option> <!-- 53 -->
												<option class="" value="10752">War</option> <!-- 10752 -->
												<option class="" value="37">Western</option> <!-- 37 -->
											</select>						
										</div>
									</div>
								</div>
							</div>
						</div>
					</center>
				</div>
  			</div>
		</div>
	</div>

	<br />
	<div class="container-fluid">
		<div id="result" class="row">
			
		</div>
		<div class="row">
			<img id="loading" src="http://i68.tinypic.com/zk3gol.gif" style="margin-left: auto; margin-right: auto; display: none;" alt="Loading..." title="Loading..."/>
		</div>
		
		<div class="row">
			<div id="pagination-container" style="display: block; margin: auto; padding: 2%;"></div></div>
		</div>	
	</div>

	<script type="text/javascript">

			// Populate the sort/filter drop downs
			let currentYear = (new Date).getFullYear();

			for (let i = currentYear; i >= 1900; i--) 
			{	
				if (i == 0)
				{
					$('#filterFormYearSelectorTo').append('<option value='+ i +' selected>'+ i +'</option>');
					$('#filterFormYearSelectorFrom').append('<option value='+ i +' selected>'+ i +'</option>');
				}
				else 
				{
					$('#filterFormYearSelectorTo').append('<option value='+ i +'>'+ i +'</option>');
					$('#filterFormYearSelectorFrom').append('<option value='+ i +'>'+ i +'</option>');
				}
			}

			for (let i = 10; i >= 0; i--) 
			{
				if (i == 10)
				{
					$('#filterFormRatingSelectorTo').append('<option value='+ i +' selected>'+ i +' / 10</option>');
					$('#filterFormRatingSelectorFrom').append('<option value='+ i +' selected>'+ i +' / 10</option>');
				}
				else
				{
					$('#filterFormRatingSelectorTo').append('<option value='+ i +'>'+ i +' / 10</option>');
					$('#filterFormRatingSelectorFrom').append('<option value='+ i +'>'+ i +' / 10</option>');
				}
			}
		</script>

	<script>
		/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
		var dropdown = document.getElementsByClassName("dropdown-btn");
		var i;
		
		for (i = 0; i < dropdown.length; i++) {
		  dropdown[i].addEventListener("click", function() {
		  this.classList.toggle("active");
		  var dropdownContent = this.nextElementSibling;
		  if (dropdownContent.style.display === "block") {
		  dropdownContent.style.display = "none";
		  } else {
		  dropdownContent.style.display = "block";
		  }
		  });
		}
	</script>

	</body>
</html>

<script src="./showMoviehelpers.js"></script>

<script type="text/javascript">

$(document).ready(function()
	{

		const moviedbAPI = "&api_key=4084c07502a720532f5068169281abff";		// https://www.themoviedb.org/documentation/api?language=en-US
		const omdbAPI = "&apikey=1f18a935"									// http://www.omdbapi.com/

		var moviedbMethod;

		var sort;
		var sortID;
		// var sortMethod;
		var filter;

		/* Popular Movies */

		var actionque = `https://api.themoviedb.org/3/discover/movie?sort_by=popularity.desc&api_key=4084c07502a720532f5068169281abff`;
		// test for pagination
		$('#pagination-container').pagination(
			{
				dataSource: function(done) 
				{
					$.ajax(
					{
						type: 'GET',
						url: actionque,
						success: function(response) 
						{
							$('#loading').fadeIn(50);

							if (!(response.total_results == 0))	
								$('#pagination-container').css("display", "block");

							let result = [];
							let totalPage = (response.total_pages * 20); // page size stores 20 items per page

							for (var i = 1; i < totalPage; i++) 
							{
								result.push(i);
							}

							done(result);							
						}
					});
				},
				pageSize: 20,
				ajax: 
				{
					beforeSend: function() 
					{
						console.log('Loading data ...');
					}
				},
				callback: function(data, pagination) 
				{
					// template method of yourself
					if ($('#loading').css('display') == 'none')
						$('#loading').fadeIn(50);
					

					async function showMovies(actionque) {

						fetch(actionque).then((response)=>{
							
							if (response.status !== 200) {
								console.log('Error Occured');
								return;
							}
							response.json().then(function(rawdata){

								getMovieDataPromise(rawdata.results,"search")
									.then((result) => {
									//		console.log(result);
											result = filterFunction(result, filter);
											result = sortFunction(result, sort);	

											$('#loading').fadeOut();
											$('#result').html('');

											result.forEach(createMovieCard);
											
									});
							});

						});

						return 1;
					}

					showMovies(actionque+`&page=`+ pagination.pageNumber +``);

				}
			});



		/* Popular Movies */

		//SEARCH OPTIONS
		// check for a change in sort or filter radios 
		$("input[type='radio']").click(function()
		{	
			if (this.name == "sortFormRadio") //SORT OPTIONS 
				sort = $("input[name='"+ this.name +"']:checked").val();  //elem.target
			else if (this.name == "filterFormRadio") //FILTER OPTIONS
				filter = $("input[name='"+ this.name +"']:checked").val();
		});

		// SEARCH
		//$('#searchbar').on('input', function(event) 
		$('.fieldinput').change(function(event) 
		{
			$('#result').fadeOut();
			$('#pagination-container').css("display", "none");
			$('#loading').fadeOut(50);
			
			if ($('.fieldinput').val() == '') {
				var actionque = `https://api.themoviedb.org/3/discover/movie?sort_by=popularity.desc&api_key=4084c07502a720532f5068169281abff`;
			} else {
				var actionque = `https://api.themoviedb.org/3/search/movie?query=`+ $('#searchbar').val() +`&api_key=4084c07502a720532f5068169281abff`;
			}

			$('#pagination-container').pagination(
			{
				dataSource: function(done) 
				{
					$.ajax(
					{
						type: 'GET',
						url: actionque,
						success: function(response) 
						{
							$('#loading').fadeIn(50);

							if (!(response.total_results == 0))	
								$('#pagination-container').css("display", "block");

							let result = [];
							let totalPage = (response.total_pages * 20); // page size stores 20 items per page

							for (var i = 1; i < totalPage; i++) 
							{
								result.push(i);
							}

							done(result);							
						}
					});
				},
				pageSize: 20,
				ajax: 
				{
					beforeSend: function() 
					{
						console.log('Loading data ...');
					}
				},
				callback: function(data, pagination) 
				{
					//$('#pagination-container').css('display') == 'none';

					// template method of yourself
					if ($('#loading').css('display') == 'none')
						$('#loading').fadeIn(50);

					async function showMovies(actionque) {


						fetch(actionque).then((response)=>{
							
							if (response.status !== 200) {
								console.log('Error Occured');
								return;
							}
							response.json().then(function(rawdata){

									getMovieDataPromise(rawdata.results,"search")
										.then((result) => {
										//	console.log(result);
											result = filterFunction(result, filter);
											result = sortFunction(result, sort);	

											console.log('---------------------------');
											console.log(result);
											console.log('---------------------------');

											$('#loading').fadeOut();
											$('#result').html('');

											result.forEach(createMovieCard);
											
										});
							});

						});

						return 1;
					}		
					
					showMovies(actionque+`&page=`+ pagination.pageNumber +``);
				}
			});
		});

		
	});

function getSortID(sortType)
{
	if (sortType == "title")
		return "sortFormNameSelector";
	if (sortType == "Year")
		return "sortFormYearSelector";
	if (sortType == "imdbRating")
		return "sortFormRatingSelector";
	if (sortType == "genre_ids")
		return "sortFormGenreSelector";
}

function getMovieData(result, pageType)
{
	for(let i = 0; i < result.length; i++) 
	{
		// get the IMDB ID
		jQuery.ajaxSetup({async:false});
		$.get("https://api.themoviedb.org/3/movie/"+ result[i].id +"/external_ids?api_key=4084c07502a720532f5068169281abff",function(movie)
		{
			appendMovieData(result[i], movie.imdb_id, "search");
			console.log("\n\n\nget ID:" + movie.imdb_id);
			console.log(result[i]);
		});			
	}
	return result;	
}

// AESTHETIC - This is just a hovering affect
function movieHoverIn(elem)
{
	$(elem).removeClass('border-secondary');
	$(elem).addClass('border-info');

	$(elem).children().css("color", "white");
};
function movieHoverOut(elem)
{
	$(elem).addClass('border-secondary');
	$(elem).removeClass('border-info');

	$(elem).children().css("color", "#888888");
};


// REDIRECT
function loadInfo(id,release_date)
{
	location.href = '/Hypertube/movieInfoPage.php?id='+ id +'&date='+release_date;
};

</script>

<script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

