<div class="wrap">
	<form method="post" action="options.php">
		<?php
		settings_fields('donations_settings_section');
		do_settings_sections(Settings_Service::SETTINGS_PAGE);
		submit_button();
		?>
	</form>
</div>