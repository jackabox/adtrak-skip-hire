<div id="adtrak-settings" class="wrap">
    <h1>Skip Hire</h1>
	<p>Options and helpers for the Skip Hire plugin.</p>

	<?php settings_errors(); ?>

	<div class="adtrak-theme-left-col">
    	<h2>Options</h2>

    	<?php $active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'ash_general_page'; ?>

    	<h2 class="nav-tab-wrapper">
        	<?php

            foreach ($this->sections as $section) {
                $tab = $active_tab == $section['page'] ? 'nav-tab-active' : '';
                $options = get_option($section['page']);
                $feed_enabled = empty($options['ash_enabled']) ? '' : 'feed-enabled';
                echo '<a href="?page=' . $this->settings_slug . '&amp;tab=' . $section['page'] . '" class="nav-tab ' . $tab . ' ' . $feed_enabled . '">' . $section['title'] . '</a>';
            }
        	?>
    	</h2>

    	<form method="post" action="options.php" class="nav-tab-content">
        	<?php
        	settings_fields ($active_tab);
        	do_settings_sections ($active_tab);

        	submit_button();
        	?>
    	</form>
	</div>
	<div class="adtrak-theme-right-col">

        <h3>Help</h3>

        <div class="nav-tab-content">
            <p>The plugin contains a three shortcodes to call in the required features. Some will be automatically generated, others may require manual input. These are <pre>[ash_postcode_form] <br>[ash_booking_form] <br>[ash_booking_confirmation]</pre></p>
            <p>If you need any help contact us at staff.development@adtrak.co.uk</p>
        </div>
	</div>
</div><!-- /.wrap -->
