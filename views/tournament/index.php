<?php	include('views'.DIRECTORY_SEPARATOR.'header.php');	?>

<img src="pics/7w_logo.png" height="100" />

<?php	if (isset($message)): ?><?=$message; ?><?php	endif;	?>


<h2>Players</h2>
<ul>
	<li>
		<a href="<?=General::frameworkLink('player/manage/') ?>">Manage Players</a>
	</li>
</ul>


<?php	if ($playerCount):	?>

<?php	if (!$wondersSet):	?>
<h2>Wonders</h2>
<ul>
	<li>
		<a href="<?=General::frameworkLink('tournament/wonders/') ?>">Select Wonders</a>
	</li>
</ul>
<?php	endif;	?>

<?php	if (count($gamesResults)):	?>

<h2>Rounds</h2>
<table class="borders">
	<?=showlinks(
		'Seating Chart for Round '.$gamesResults[0]['RoundNum'],
		'game/seating/'.$gamesResults[0]['RoundNum'].'/')
		?>
</table>

<h3>Results to be entered</h3>
<ul>
<?php		foreach($gamesResults AS $g):	?>
	<li>
		<a href="<?=General::frameworkLink('game/results/'.$g['RoundNum'].'/'.$g['TableNum'].'/') ?>">
			Round <?=$g['RoundNum'] ?>, Table <?=$g['TableNum'] ?>
		</a>
	</li>
<?php		endforeach;	?>
</ul>
<?php	elseif ($nextRound != 0 && $nextRound <= $maxRounds):	?>
<h2>Rounds</h2>
<ul>
	<li>
		<a href="<?=General::frameworkLink('tournament/round/') ?>">
			Set up round <?=$nextRound ?>
		</a>
	</li>
</ul>
<?php	endif;	?>


<?php	if (count($gamesComplete)):	?>
<h2>Games that need full results entering</h2>
<ul>
<?php		foreach($gamesComplete AS $g):	?>
	<li>
		<a href="<?=General::frameworkLink('game/complete/'.$g['RoundNum'].'/'.$g['TableNum'].'/') ?>">
			Round <?=$g['RoundNum'] ?>, Table <?=$g['TableNum'] ?>
		</a>
	</li>
<?php		endforeach;	?>
</ul>
<?php	endif;	?>

<h2>Standings</h2>

<table class="borders">
	<tbody>
		<tr>
			<th colspan="3">Current Standings</th>
		</tr>
		<?=showlinks('By Surname', 'tournament/standings/last/') ?>
		<?=showlinks('By First Name', 'tournament/standings/first/') ?>
		<tr>
			<th colspan="3">Score Category Standings</th>
		</tr>
<?php	foreach($categories AS $c):	?>
		<?=showlinks($c[1], 'tournament/scoring/'.$c[0].'/') ?>
<?php	endforeach;	?>
		<tr>
			<th colspan="3">Wonders Standings</th>
		</tr>
		<?=showlinks('Wonders', 'tournament/wonders-standings/');	?>
	</tbody>
</table>

<?php	endif;	// playerCount ?>

<?php
	function showlinks($name, $url)
	{
?>
		<tr>
			<td><?=$name ?></td>
			<td>
				<a href="<?=General::frameworkLink($url) ?>">
					View Here
				</a>
			</td>
			<td>
				<a target="display"
				   href="<?=General::frameworkLink($url) ?>">
					Open in Display Window
				</a>
			</td>
		</tr>
<?php
	}
?>



<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>
