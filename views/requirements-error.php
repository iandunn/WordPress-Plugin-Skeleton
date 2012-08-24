<p><?php echo WPPS_NAME; ?> error: Your environment doesn't meet all of the system requirements listed below.</p>

<ul class="ul-disc">
	<li><strong>PHP <?php echo WPPS_REQUIRED_PHP_VERSION; ?>+</strong> <em>(You're running version <?php echo PHP_VERSION; ?>)</em></li>
	<li><strong>WordPress <?php echo WPPS_REQUIRED_WP_VERSION; ?>+</strong> <em>(You're running version <?php echo esc_html( $wp_version ); ?>)</em></li>
	<?php //<li><strong>Plugin XYZ</strong> activated</em></li> ?>
</ul>

<p>If you need to <a href="http://codex.wordpress.org/Switching_to_PHP5">upgrade to PHP 5</a> you can ask your hosting company for assistance, and if you need help upgrading WordPress you can refer to <a href="http://codex.wordpress.org/Upgrading_WordPress">the Codex</a>.</p>