<?php	include('views'.DIRECTORY_SEPARATOR.'header.php');	?>

<?php	if (isset($message)): ?><?=$message; ?><?php	endif;	?>

<h1><?=($rounds == $maxRounds ? 'Final' : 'Current') ?> Standings</h1>

<script type="text/javascript">
	generateTable = true;
	rounds = <?=$rounds ?>;
	standings = [];
<?php	foreach($standings AS $k => $s):	?>
	standings[<?=$k ?>] = <?=json_encode($s) ?>;
<?php	endforeach;	?>
</script>

<div class="footer">
	<a href="<?=General::frameworkLink('') ?>">Back to index</a>
</div>

<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>
