<?php	include('views'.DIRECTORY_SEPARATOR.'header.php');	?>

<?php	if (isset($message)): ?><?=$message; ?><?php	endif;	?>

<h1>Wonders Standings</h1>

<table class="borders centre">
	<thead>
		<tr>
			<th rowspan="2">Rank</th>
			<th rowspan="2">Wonder</th>
			<th rowspan="2">Points</th>
			<th rowspan="2">Games</th>
<?php	if ($maxRank != 0):	?>
			<th colspan="<?=$maxRank ?>">Ranks</th>
<?php	endif;	?>
			<th rowspan="2">Average Points</th>
		</tr>
		<tr>
<?php	for ($r = 1; $r <= $maxRank; $r++):	?>
			<th><?=$r ?></th>
<?php	endfor;	?>
		</tr>
	</thead>
	<tbody>
<?php	$lastRankNum = -1;	foreach($standings AS $k => $s):	?>
		<tr>
			<td>
<?php	if ($lastRankNum === $s['RankStr']): ?>
				=
<?php	else:	?>
				<?=$k+1 ?>
<?php	endif;	?>
			</td>
			<td><?=$s['WonderName'] ?></td>
			<td><?=$s['TotalPoints'] ?></td>
			<td><?=$s['Games'] ?></td>
<?php	for ($r = 1; $r <= $maxRank; $r++):	?>
			<td><?=$s['Ranks'][$r] ?></td>
<?php	endfor;	?>

			<td class="points"><?=round($s['AveragePoints'], 2) ?></td>
		</tr>
<?php	$lastRankNum = $s['RankStr'];	endforeach;	?>
	</tbody>
</table>

<div class="footer">
	<a href="<?=General::frameworkLink('') ?>">Back to index</a>
</div>

<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>
