$(document).ready(function() {

	// Adjust the page if it's a display page automatically
	if (window.name === 'display')
	{
		$('BODY').addClass('display');
		$('BODY').prepend('<img src="/pics/7w_logo.png" height="100" />');
		$('DIV.footer').hide();

		// Move H1 down a bit if there's no H2
		if ($('H2').length === 0)
		{
			$('H1').css('padding-top','0.65em');
		}
	}

	// Generate a table if required
	if (typeof generateTable !== 'undefined')
	{
		doGenerateTable();
	}

});

/**
 * When a select is changed, remove the option from matching selects
 * to try to ensure uniqueness
 */
function uniqueSelect(name)
{
	$('SELECT[name^="' + name + '"]').each(function() {
		var id = $(this).attr('name').replace('' + name + '', '');
		var select = $(this);
		select.find('option').show();
		select.find('option').removeAttr('disabled');
		$('SELECT[name^="' + name + '"]').each(function() {
			if ($(this).val() != "")
			{
				var thisID = $(this).attr('name').replace('' + name + '', '');
				if (id != thisID)
				{
					select.find('option[value="' + $(this).val() + '"]').hide();
					select.find('option[value="' + $(this).val() + '"]').attr('disabled','disabled');
				}
			}
		});
	});
}

/**
 * Generate a table based on variables set
 */
function doGenerateTable()
{
	// Create the basic table, with header
	baseTable = $('<table>')
				.addClass('borders')
				.append(generateHead());

	// Set up the the current table
	curTable = baseTable.clone();

	// Set up the layout table, add the current table
	$('.footer').before(
		$('<table>').append(
			$('<tr>').attr('id', 'tableLayoutCells').append(
				$('<td>').append(
					curTable
				)
			)
		)
	);

	// Initialise default values for loop
	rowHeight = 0;
	lastRowPos = 0;
	lastRankStr = 'sth';
	lastShownRank = -1;
	// Loop through standings
	for(i = 0; i < standings.length; i++)
	{
		// If this row will go off the bottom of the window, create a new table
		if ((lastRowPos + rowHeight + 5) > window.innerHeight)
		{
			// Generate a new table
			curTable = baseTable.clone();
			// Add it alongside the current table
			$('#tableLayoutCells').append($('<td>').append(curTable));

			// Set up rank display; if equal, add the number back in
			if (lastRankStr === standings[i]['RankStr'])
			{
				rank = lastShownRank+'=';
			} else {
				rank = i+1;
				lastShownRank = rank;
			}
		} else {
			// Set up the rank display
			if (lastRankStr === standings[i]['RankStr'])
			{
				rank = '=';
			} else {
				rank = i+1;
				lastShownRank = rank;
			}
		}

		// Start building the row
		row = $('<tr>')
				.append($('<td>').html(rank))
				.append($('<td>').html(standings[i].FullName));
		// Add in results for each round
		for(j = 1; j <= rounds; j++)
		{
			row.append($('<td>').html(standings[i]['Points'][j]));
		}
		row.append($('<td>')
				.addClass('points')
				.html(standings[i]['TotalPoints']));

//		row.append($('<td>')
//				.html(standings[i]['RankStr']));

		// Add the row to the current table
		curTable.append(row);

		// Set values for information in next iteration
		lastRowPos = row.position().top + row.height();
		rowHeight = row.height();
		lastRankStr = standings[i]['RankStr'];
	}
}

function generateHead()
{
	row = $('<tr>')
			.append($('<th>').html('Rank'))
			.append($('<th>').html('Player'));
	for(i = 1; i <= rounds; i++)
	{
		row.append($('<th>').html('Round '+i));
	}
	row.append($('<th>').html('Points'));

	return $('<thead>').append(row);
}