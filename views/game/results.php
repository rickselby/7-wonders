<?php
include('views'.DIRECTORY_SEPARATOR.'header.php');	?>

<h1>Game Results</h1>

<h2>Round <?=$round ?>, Table <?=$table ?></h2>

<?php	if (isset($message)): ?><?=$message; ?><?php	endif;	?>

<form method="post" action="<?=General::frameworkLink('game/results/') ?>">
	<input type="hidden" name="round" value="<?=$round ?>" />
	<input type="hidden" name="table" value="<?=$table ?>" />
	<table class="borders centre">
		<thead>
			<tr>
				<th>Seat</th>
				<th>Player</th>
				<th>Rank</th>
				<th>Game Points</th>
			</tr>
		</thead>
		<tbody>
<?php	foreach($results AS $r):	?>
			<tr>
				<td><?=$r['SeatNum'] ?></td>
				<td><?=$r['FullName'] ?></td>
				<td>
					<select name="rank[<?=$r['PlayerID'] ?>]">
						<?=HTML::optionList(array_merge([''], range(1,count($results)))) ?>
					</select>
				</td>
				<td>
					<input type="text" name="TempTotalPts[<?=$r['PlayerID'] ?>]"
						   size="2" />
				</td>
			</tr>
<?php	endforeach;	?>
			<tr>
				<td colspan="5" class="submit">
					<input type="submit" value="Set Results" />
				</td>
			</tr>
		</tbody>
	</table>
</form>

<p>You can add the detailed results after setting the ranks</p>

<div class="footer">
	<a href="<?=General::frameworkLink('') ?>">Back to index</a>
</div>

<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>
