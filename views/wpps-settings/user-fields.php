<h3>WPPS User Fields</h3>

<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<label for="<?php echo WordPressPluginSkeleton::PREFIX; ?>user-example-field">Example Field</label>
		</th>
		
		<td>
			<input id="<?php echo WordPressPluginSkeleton::PREFIX; ?>user-example-field" name="<?php echo WordPressPluginSkeleton::PREFIX; ?>user-example-field" type="text" class="regular-text" value="<?php esc_attr_e( get_user_meta( $user->ID, WordPressPluginSkeleton::PREFIX . 'user-example-field', true ) ); ?>" />
			<span class="description">The user can assign content to devices that are checked.</span>
		</td>
	</tr>
</table>