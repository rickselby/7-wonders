<?php	include('views'.DIRECTORY_SEPARATOR.'header.php');	?>

<h1>Player Management</h1>

<?php	if (isset($message)): ?><?=$message; ?><?php	endif;	?>

<h2>Current Players</h2>

<?php	if (count($players) != 0):	?>
<form method="post" action="<?=General::frameworkLink('player/update/') ?>">
	<table class="centre borders">
		<thead>
			<tr>
				<th colspan="2">Name</th>
				<th>Paid?</th>
				<th>Arrived?</th>
			</tr>
		</thead>
		<tbody>
<?php foreach($players AS $p):	?>
			<tr>
				<td>
					<input type="text" size="10" value="<?=$p['FirstName'] ?>"
						   name="FirstName[<?=$p['PlayerID'] ?>]" />
				</td>
				<td>
					<input type="text" size="10" value="<?=$p['LastName'] ?>"
						   name="LastName[<?=$p['PlayerID'] ?>]" />
				</td>
				<td>
<?php	if (!$games):	?>
					<input type="checkbox" name="Paid[<?=$p['PlayerID'] ?>]"
						   <?=($p['Paid'] ? 'checked="checked"' : '') ?>
						   />
<?php	else:	?>
					<?=$p['Paid'] ? 'Yes' : 'No' ?>
<?php	endif;	?>
				</td>
				<td>
<?php	if (!$games):	?>
					<input type="checkbox" name="Arrived[<?=$p['PlayerID'] ?>]"
						   <?=($p['Arrived'] ? 'checked="checked"' : '') ?>
						   />
<?php	else:	?>
					<?=$p['Arrived'] ? 'Yes' : 'No' ?>
<?php	endif;	?>
				</td>
			</tr>
<?php endforeach;	?>
			<tr>
				<td colspan="4" class="submit">
					<input type="submit" value="Update Player Details" />
				</td>
		</tbody>
	</table>
</form>
<?php	else:	?>
<p>No current players</p>
<?php	endif;	?>

<?php	if (!$games):	?>
<h2>Add a Player</h2>

<form method="post" action="<?=General::frameworkLink('player/add/') ?>">
	<fieldset>
		<legend>Player Details</legend>
		<label for="FirstName">First Name:</label>
		<input type="text" size="40" name="FirstName" autofocus="autofocus" />
		<br />
		<label for="LastName">Last Name:</label>
		<input type="text" size="40" name="LastName" />
		<br />
		<label></label>
		<input type="submit" value="Add Player"	/>
	</fieldset>

</form>
<?php	endif;	?>

<div class="footer">
	<a href="<?=General::frameworkLink('') ?>">Back to index</a>
</div>

<?php	if (count($players) == 0):	?>
<hr />
<h2>Fill with random names</h2>
<form method="post" action="<?=General::frameworkLink('player/fill/') ?>">
	Amount: <input type="text" size="2" value="20" name="amount" />
	<input type="submit" value="Add Players" />
</form>
<?php	endif;	?>

<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>
