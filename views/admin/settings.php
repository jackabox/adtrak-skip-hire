<div id="<?php echo $this->settings_slug; ?>" class="wrap">
    <h1>Skip Hire</h1>

    <?php settings_errors(); ?>

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
</div><!-- /.wrap -->
