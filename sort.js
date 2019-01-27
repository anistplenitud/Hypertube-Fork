function sortFunction(movieArray, sortType)
{	
	if (sortType != "None")  
	{
		let sortID = getSortID(sortType);
		let sortMeth = $("#"+ sortID +"").children("option:selected").val();

		// SORT TYPES
		if (sortType == "genre_ids")
			movieArray = sortGenre(movieArray, sortMeth, "sort"); // sortMeth is the genre.id in this case

		// SORT METHODS
		if (sortMeth == "asc")
			movieArray = sortAscending(movieArray, sortType);
		else if (sortMeth == "desc")
			movieArray = sortDescending(movieArray, sortType);

		// this is used to remove or append movies that don't have ratings
		if (sortType == "imdbRating")
			movieArray = appendNoRating(movieArray, sortMeth); //movieArray = removeNoRating(movieArray); 
	}
	return movieArray;
}

function sortAscending(result, field) 
{
	let arr = [];
	for(let i = 0; i < result.length; i++) 
	{
		for(let j = i; j < result.length; j++) 
		{
			if (result[i][field] > result[j][field]) 
				[result[i], result[j]] = [result[j], result[i]]; // simplified swap
		}
			arr.push(result[i]);
	}
	return arr;
}

function sortDescending(result, field) 
{
	let arr = [];

	for(let i = 0; i < result.length; i++) 
	{
		for(let j = i; j < result.length; j++) 
		{
			if (result[i][field] < result[j][field]) 
				[result[i], result[j]] = [result[j], result[i]]; // simplified swap
		}
			arr.push(result[i]);
	}
	return arr;
}

// look for selected genre and put that at the top.
// in the case that we want only the selected genre to show, remove the concat
function sortGenre(result, genreID, type)
{
	let arr = [];
	let arr2 = [];

		// iterate through the movie arrays
		for(let i = 0; i < result.length; i++) 
		{
			// iterate through the genre array
			for(let j = 0; j < result[i].genre_ids.length; j++) 
			{
				if (result[i].genre_ids[j] == genreID)
				{
					if (!(arr.includes(result[i]))) 
					{
						arr.push(result[i]);
						console.log(arr);
					}
				}

			}
			if ((!(arr.includes(result[i]))) && (!(arr.includes(result[i]))))
				arr2.push(result[i]);
		}
		if (type == "sort")
			arr = arr.concat(arr2);
		return arr;	
}

//Rating
// remove results that have no rating
function removeNoRating(result)
{
	let arr = [];

	for(let i = 0; i < result.length; i++) 
	{
		if (!(result[i].imdbRating === 'N/A' || result[i].imdbRating === 'undefined' || result[i].imdbRating === undefined || result[i].imdbRating === 'null' && result[i].imdbRating === null || isNaN(result[i].imdbRating)))
		{
			arr.push(result[i]);
		}
	}
	return arr;	
}

// place not rating items at the end
function appendNoRating(result, sort)
{
	let arr = [];
	let arr2 = [];

	for(let i = 0; i < result.length; i++) 
	{
		if (!(result[i].imdbRating === 'N/A' || result[i].imdbRating === 'undefined' || result[i].imdbRating === undefined || result[i].imdbRating === 'null' && result[i].imdbRating === null || isNaN(result[i].imdbRating)))
		{
			arr.push(result[i]);
		}
		else 
			arr2.push(result[i]);
	}

	if (sort == "asc")
		arr = arr2.concat(arr);
	else if (sort == "desc")
		arr = arr.concat(arr2);
	return arr;	
}

// removes any duplicate movies just in case
function remove_Dup(arr) 
{
	var result = []; // this is what must be returned
	var temp = []; // this array will store the ID and can compare against 

	for (var i = 0; i < arr.length; i++) 
	{
		for (var j = 1; j < arr.length; j++) 
		{	
			if (arr[i].imdbID != arr[j].imdbID)
			{
				if (!(temp.includes(arr[i].imdbID)))
				{
					temp.push(arr[i].imdbID);
					if (!(result.includes(arr[i])))
						result.push(arr[i]);
				}
			}
		}			
	}
	return result;
}