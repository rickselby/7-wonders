var players = [];

$(document).ready(function() {

	// Get a list of players based on the currently selected option
	getPlayerList($('#order option:selected').text());

	// If seats are changed, regenerate the list
	$('form').on('change', '[name^="tableseats"]', function() {
		checkTables();
		fillTable();
	});

	// If the ordering is changed, get the new list of players
	$('#order').change(function() {
		getPlayerList($('#order option:selected').val());
	});

	$('FORM').on('submit', function() {
		var maxSeats = 0;
		$('INPUT[name^="tableseats"]').each(function() {
			maxSeats = Math.max(maxSeats, $(this).val());
		});

		var selected = ($('#wonders').val() || []);
		if (maxSeats !== selected.length)
		{
			alert('You must select ' + maxSeats + ' wonders');
		return false;
		}

	});

});

// Check the tables; remove any unnecessary or add more if required
function checkTables()
{
	totalSeats = 0;

	// Get the list of table inputs
	tableSeats = $('INPUT[name^="tableseats"]');
	// Step through each table input
	tableSeats.each(function() {
		// Sum the total seats allocated
		totalSeats += parseInt($(this).val());
		// If we have enough already, try to delete any unnecessary tables
		if (totalSeats >= playerCount)
		{
			// Get the id of the current table
			id=parseInt($(this).attr('name').substring(11,$(this).attr('name').length-1));
			// Delete all tables with higher numbers
			for(i = (id+1); i <= tableSeats.length; i++)
			{
				$('INPUT[name="tableseats['+i+']"]').parent().remove();
			}
			// Stop the each() loop
			return false;
		}
	});

	// If there aren't enough seats yet, add another table
	if (totalSeats < playerCount)
	{
		// Set the new table number
		tNum = tableSeats.length + 1;
		// Add after last table input
		$('DIV.table').last().after(
				"\n\t\t"+'<div class="table">'
				+"\n\t\t\t"+'<label for="tableseats['+tNum+']">'
					+'Table '+tNum+' seats: '
					+'</label>'
				+"\n\t\t\t"+'<input type="text" name="tableseats['+tNum+']" '
						+'size="1" class="table" value="7" />'
				+"\n\t\t"+'</div>'
				);
	}
}

// Get the player list from the database; wait on this response (?)

function getPlayerList(order)
{
	$.ajax({
		url: 'index.php?url=player/get/'+order+'/',
		async: false,
		dataType : "json"
	}).done(function(data) {
		players = data;
		fillTable();
	});
}

// Fill the table based on the current player list and table seats
function fillTable()
{
	var tbody = $('#playerList TBODY');
	// Empty the table
	tbody.empty();

	table=1;
	seat=1;

	// Step through each player
	for(i = 0; i < players.length; i++)
	{
		tableSeats = $('input[name="tableseats['+table+']"]').val();

		tbody.append($('<tr>')
			.append(
				// If seat 1, show the table number, with the appropriate
				// rowspan for the number of seats
				seat === 1
					? $('<td>').html(table).attr('rowspan', tableSeats)
					: ''
			)
			.append($('<td>').html(seat))
			.append($('<td>').html(players[i].FullName)
				.append($('<input>')
					.attr('name', 'table['+i+']')
					.attr('type', 'hidden')
					.attr('value', table)
				)
				.append($('<input>')
					.attr('name', 'seat['+i+']')
					.attr('type', 'hidden')
					.attr('value', seat)
				)
				.append($('<input>')
					.attr('name', 'player['+i+']')
					.attr('type', 'hidden')
					.attr('value', players[i].PlayerID)
				)
			)
		);

		// If we have filled the table, move to the next table, and reset the seat
		if (++seat > tableSeats)
		{
			table++;
			seat=1;
		}

	}

}

function showWonders()
{
}