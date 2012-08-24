<p>Meta box introduction / user instructions</p>

<table id="<?php echo WordPressPluginSkeleton::PREFIX; ?>example-box">
	<tbody>
		<tr>
			<th><label for="<?php echo WordPressPluginSkeleton::PREFIX; ?>example-box-field">Example Box Field:</label></th>
			<td>
				<input id="<?php echo WordPressPluginSkeleton::PREFIX; ?>example-box-field" name="<?php echo WordPressPluginSkeleton::PREFIX; ?>example-box-field" type="text" class="regular-text" value="<?php echo $exampleBoxField; ?>" />
			</td>
		</tr>
	</tbody>
</table>