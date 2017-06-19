<?php	include('views'.DIRECTORY_SEPARATOR.'header.php');	?>

<?php	if (isset($message)): ?><?=$message; ?><?php	endif;	?>

<h1>Seating Chart: Round <?=$round ?></h1>

<table>
	<tbody>
		<tr id="tableLayoutCells">
<?php	foreach($tables AS $tableNum => $seats):	?>
			<td>
				<table class="borders seating">
					<thead>
						<tr>
							<th colspan="4">
								Table <?=$tableNum ?>
							</th>
						</tr>
						<tr>
							<th>Seat</th>
							<th colspan="2">Wonder</th>
							<th>Player</th>
						</tr>
					</thead>
					<tbody>
<?php		foreach($seats AS $seat => $player):	?>
						<tr>
							<td><?=$seat ?></td>
							<td><?=$player['WonderName'] ?></td>
							<td><?=$player['WonderSide'] ?></td>
							<td><?=$player['FullName'] ?></td>
						</tr>
<?php		endforeach;	?>
					</tbody>
				</table>
			</td>
<?php	endforeach;	?>
		</tr>
	</tbody>
</table>

<div class="footer">
	<a href="<?=General::frameworkLink('') ?>">Back to index</a>
</div>

<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>
