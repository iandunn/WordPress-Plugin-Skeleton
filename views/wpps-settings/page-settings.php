<div class="wrap"> 
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><?php esc_html_e( WPPS_NAME ); ?> Settings</h2> 

	<form method="post" action="options.php">
		<?php settings_fields( WordPressPluginSkeleton::PREFIX . 'settings' ); ?>
		<?php do_settings_sections( WordPressPluginSkeleton::PREFIX . 'settings' ); ?>

		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>"  />
		</p>
	</form>
</div> <!-- .wrap -->