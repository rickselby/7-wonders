<?php
$js = 'complete.js';
include('views'.DIRECTORY_SEPARATOR.'header.php');	?>

<?php	if (isset($message)): ?><?=$message; ?><?php	endif;	?>

<h1>Full Results</h1>

<h2>Round <?=$round ?>, Table <?=$table ?></h2>

<form method="post" action="<?=General::frameworkLink('game/complete/') ?>">
	<input type="hidden" name="round" value="<?=$round ?>" />
	<input type="hidden" name="table" value="<?=$table ?>" />
	<table class="borders centre">
		<thead>
			<tr>
				<th>Player</th>
<?php	foreach($results AS $r):	?>
				<th><?=$r['FullName'] ?></th>
<?php	endforeach; ?>
			</tr>
			<tr>
				<th>Seat</th>
<?php	foreach($results AS $r):	?>
				<th><?=$r['SeatNum'] ?></th>
<?php	endforeach; ?>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>Wonder</th>
<?php	foreach($results AS $r):	?>
				<td>
					<?=$r['WonderName'] ?>
				</td>
<?php	endforeach; ?>
			</tr>
			<tr class="rowSum">
				<th>Military</th>
<?php	foreach($results AS $r):	?>
				<td>
					<input class="rowSum" type="text" size="2"
						   name="MilitaryPts[<?=$r['PlayerID'] ?>]"
						   value="<?=$r['MilitaryPts'] ?>" />
				</td>
<?php	endforeach; ?>
			</tr>
			<tr class="rowSum">
				<th>Treasury</th>
<?php	foreach($results AS $r):	?>
				<td>
					<input class="rowSum" type="text" size="2"
						   name="MoneyPts[<?=$r['PlayerID'] ?>]"
						   value="<?=$r['MoneyPts'] ?>" />
				</td>
<?php	endforeach; ?>
			</tr>
			<tr class="rowSum">
				<th>Wonder</th>
<?php	foreach($results AS $r):	?>
				<td>
					<input class="rowSum" type="text" size="2"
						   name="WonderPts[<?=$r['PlayerID'] ?>]"
						   value="<?=$r['WonderPts'] ?>" />
				</td>
<?php	endforeach; ?>
			</tr>
			<tr class="rowSum">
				<th>Civilian</th>
<?php	foreach($results AS $r):	?>
				<td>
					<input class="rowSum" type="text" size="2"
						   name="CivilPts[<?=$r['PlayerID'] ?>]"
						   value="<?=$r['CivilPts'] ?>" />
				</td>
<?php	endforeach; ?>
			</tr>
			<tr class="rowSum">
				<th>Trading</th>
<?php	foreach($results AS $r):	?>
				<td>
					<input class="rowSum" type="text" size="2"
						   name="CommercePts[<?=$r['PlayerID'] ?>]"
						   value="<?=$r['CommercePts'] ?>" />
				</td>
<?php	endforeach; ?>
			</tr>
			<tr class="rowSum">
				<th>Guilds</th>
<?php	foreach($results AS $r):	?>
				<td>
					<input class="rowSum" type="text" size="2"
						   name="GuildsPts[<?=$r['PlayerID'] ?>]"
						   value="<?=$r['GuildsPts'] ?>" />
				</td>
<?php	endforeach; ?>
			</tr>
			<tr class="rowSum">
				<th>Science</th>
<?php	foreach($results AS $r):	?>
				<td>
					<input class="rowSum" type="text" size="2"
						   name="SciencePts[<?=$r['PlayerID'] ?>]"
						   value="<?=$r['SciencePts'] ?>" />
				</td>
<?php	endforeach; ?>
			</tr>
			<tr>
				<th>Total</th>
<?php	foreach($results AS $r):	?>
				<td class="totalCell"></td>
<?php	endforeach; ?>
			</tr>
			<tr>
				<th>Total Entered</th>
<?php	foreach($results AS $r):	?>
				<td><?=$r['TempTotalPts'] ?></td>
<?php	endforeach; ?>
			</tr>
			<tr>
				<th>Rank</th>
<?php	foreach($results AS $r):	?>
				<td>
					<select name="rank[<?=$r['PlayerID'] ?>]">
						<?=HTML::optionList(
								array_merge([''], range(1,count($results))),
								NULL, NULL,
								$r['Rank']) ?>
					</select>
</td>
<?php	endforeach; ?>
			</tr>
			<tr>
				<td colspan="<?=count($results) + 1 ?>" class="submit">
					Mark results as complete: <input type="checkbox" name="Complete" />
					<input type="submit" value="Set Results" />
				</td>
			</tr>
		</tbody>
	</table>
</form>

<div class="footer">
	<a href="<?=General::frameworkLink('') ?>">Back to index</a>
</div>


<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>


<hr />

<h2>Testing: Autofill boxes</h2>
<input type="button" onclick="javascript:fillBoxes()" value="Fill Boxes" />
