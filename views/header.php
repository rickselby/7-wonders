<!DOCTYPE html>
<html>
	<head>
		<title>7 Wonders Tournament</title>
		<script src="js/jquery-2.1.1.min.js"></script>
		<script src="js/shared.js"></script>
<?php	if (isset($js)):	?>
		<script src="js/<?=$js ?>"></script>
<?php	endif;	?>
		<link rel="stylesheet" type="text/css" href="style/general.css" media="screen" />
	</head>
	<body <?=isset($bodyClass) ? 'class="'.$bodyClass.'"' : '' ?>>
