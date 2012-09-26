<p>Meta box introduction / user instructions</p>

<table id="<?php esc_attr_e( WordPressPluginSkeleton::PREFIX ); ?>example-box" class="form-table">
	<tbody>
		<tr>
			<th><label for="<?php esc_attr_e( WordPressPluginSkeleton::PREFIX ); ?>example-box-field">Example Box Field:</label></th>
			<td>
				<input id="<?php esc_attr_e( WordPressPluginSkeleton::PREFIX ); ?>example-box-field" name="<?php esc_attr_e( WordPressPluginSkeleton::PREFIX ); ?>example-box-field" type="text" class="regular-text" value="<?php esc_attr_e( $exampleBoxField ); ?>" />
			</td>
		</tr>
	</tbody>
</table>