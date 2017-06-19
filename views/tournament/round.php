<?php
$js = 'round.js';
include('views'.DIRECTORY_SEPARATOR.'header.php');
?>

<?php	if (isset($incomplete) && $incomplete):	?>

<h1>ERROR</h1>
<p>
	Cannot set up a new round until the current round is complete.
</p>

<?php	else:	?>

<h1>Set up Round <?=$nextRound ?></h1>

<form method="post" action="<?=General::frameworkLink('tournament/round/') ?>">

	<fieldset>
		<legend>Round Details</legend>

		<div>
			Total players: <?=$playerCount ?>
		</div>

		<div>
			Wonders in use: <?=count($wonders) ?>
		</div>

		<p>
			This assigns each player to an appropriate table, depending on the
			wonder they will be playing in this round. Higher-ranked players
			will be on higher tables than lower-ranked players.
		</p>

		<div>
			<input type="submit" value="Add Round" />
		</div>
	</fieldset>
</form>
<?php	endif;	?>

<div class="footer">
	<a href="<?=General::frameworkLink('') ?>">Back to index</a>
</div>

<script type="text/javascript">
	playerCount = <?=$playerCount ?>;
</script>

<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>
