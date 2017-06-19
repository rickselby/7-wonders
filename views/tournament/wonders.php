<?php
$js = 'wonders.js';
include('views'.DIRECTORY_SEPARATOR.'header.php');
?>

<?php	if (isset($message)): ?><?=$message; ?><?php	endif;	?>

<h1>Wonders for Tournament</h1>

<form method="post" action="<?=General::frameworkLink('tournament/wonders/') ?>">
	<fieldset>
		<legend>Wonders to use</legend>
		<div>
			<select multiple="multiple" name="wonders[]" id="wonders"
					size="<?=count($wonders) ?>">
				<?=HTML::keyedOptionList($wonders) ?>
			</select>
		</div>
		<div>
			Randomly select <input type="text" size="2" value="5" id="randAmount" /> Wonders
			<input type="button" id="random" value="Select" />
		</div>

		<hr />
		<p>
			This selects the wonders that will be used throughout the tournament.
			Once done, it cannot be changed.
		</p>
		<input type="submit" value="Set Wonders" />

	</fieldset>
</form>


<?php	include('views'.DIRECTORY_SEPARATOR.'footer.php');	?>
