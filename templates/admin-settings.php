<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><?php echo self::$pluginName; ?> settings</h2>
	
	<?php if(isset($codeUpdateSuccess)): ?>
		<div class="updated"><p><strong>Old code update process complete. Converted <?php echo $codeUpdateSuccess; ?> links.</strong></p></div>
	<?php endif; ?>
	
	<?php if(isset($updateSuccess)): ?>
		<div class="updated"><p><strong>Settings saved.</strong></p></div>
	<?php endif; ?>

	<form method="post" action="<?php echo self::getCurrentPage(); ?>">
	<input type="hidden" name="rbinternal_submit" value="1" />
	<table class="form-table">
	<tr valign="top">
	<th scope="row">Enable WYSIWYG plugin</th>
	<td>
		<fieldset>
			<label for="enable_wysiwyg">
				<input name="tinymce" type="checkbox" id="enable_wysiwyg" value="1" <?php if($options['tinymce']): ?>checked="checked"<?php endif; ?> />
				Yes
			</label>
		</fieldset>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="return_param">Return to shortcode</label></th>
	<td>
		<select name="return_param" id="return_param">
			<option value='id' <?php if($options['return_param'] == 'id'): ?> selected="selected"<?php endif; ?>>Post Id</option>
			<option value='slug' <?php if($options['return_param'] == 'slug'): ?> selected="selected"<?php endif; ?>>Post slug</option>
		</select>
	</td>
	</tr>

	</table>
	
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Save Changes" />
	</p>
	
	</form>
	
	
	<h3>Convert old internal link code</h3>
	<p>Used rb internal links before and wishing your old link code working with the new version? Just run this conversion process.<br/>
	<strong>Please backup your database first, just in case!</strong></p>
	
	<form method="post" action="<?php echo self::getCurrentPage(); ?>">
	<input type="hidden" name="rbinternal_update_code" value="1" />
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="Convert code" />
	</p>
	</form>
	
</div>
