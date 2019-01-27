function filterFunction(movieArray, filterType)
{
	console.log("\n\n filter\n\n")
	if (filterType != "None")
	{
		if (filterType == "Year")
		{
			let to = $("#filterFormYearSelectorTo").children("option:selected").val();
			let from = $("#filterFormYearSelectorFrom").children("option:selected").val();

			movieArray = isBetweenValue(movieArray, filterType, from, to);
		}
		else if (filterType == "imdbRating")
		{
			let to = $("#filterFormRatingSelectorTo").children("option:selected").val();
			let from = $("#filterFormRatingSelectorFrom").children("option:selected").val();

			movieArray = isBetweenValue(movieArray, filterType, from, to);
		}
		else if (filterType == "genre_ids")
		{
			let genreId = $("#filterFormGenreSelector").children("option:selected").val();
			
			movieArray = sortGenre(movieArray, genreId , "filter");
		}
	}
	return movieArray;
}

function isBetweenValue(result, sortType, value1, value2)
{
	let arr = [];
	let small = parseFloat(value1);
	let big = parseFloat(value2);

	// this is just incase someone tries to invert the values
	if (small > big)
	{
		console.log("value2 bigger");
		[small, big] = [big, small]
	}

	for(let i = 0; i < result.length; i++) 
	{
		if (((small <= Number(result[i][sortType])) && (Number(result[i][sortType]) <= big)) || ((value1 == value2) && (result[i][sortType] == value2)))
			arr.push(result[i]);
	}

	return arr;	
}