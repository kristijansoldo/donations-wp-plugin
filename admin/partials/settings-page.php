<?php

/**
 * Settings page template.
 *
 * Available variables:
 *
 * @var     $settings                 Settings
 *
 **/
?>

<div class="wrap">
	<form method="post" action="options.php">
		<?php
		    $settings->get_form();
		?>
	</form>
</div>