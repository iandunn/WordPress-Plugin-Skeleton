<?php
/*
 * Basic Section
 */
?>

<?php if( $field[ 'label_for' ] == WordPressPluginSkeleton::PREFIX . 'field-example1' ) : ?>
	
	<input id="<?php esc_attr_e( WordPressPluginSkeleton::PREFIX .'settings[basic][field-example1]' ); ?>" name="<?php esc_attr_e( WordPressPluginSkeleton::PREFIX .'settings[basic][field-example1]' ); ?>" class="regular-text" value="<?php esc_attr_e( self::$settings[ 'basic' ][ 'field-example1' ] ); ?>" />
	<span class="example"> Example value</span>

<?php endif; ?>


<?php
/*
 * Advanced Section
 */
?>

<?php if( $field[ 'label_for' ] == WordPressPluginSkeleton::PREFIX . 'field-example2' ) : ?>
	
	<textarea id="<?php esc_attr_e( WordPressPluginSkeleton::PREFIX .'settings[advanced][field-example2]' ); ?>" name="<?php esc_attr_e( WordPressPluginSkeleton::PREFIX .'settings[advanced][field-example2]' ); ?>" class="large-text" /><?php echo esc_textarea( self::$settings[ 'advanced' ][ 'field-example2' ] ); ?></textarea>
	<p class="description">This is an example of a longer explanation.</p>
	
<?php elseif( $field[ 'label_for' ] == WordPressPluginSkeleton::PREFIX . 'field-example3' ) : ?>
	
	<p>Another example</p>
	
<?php endif; ?>