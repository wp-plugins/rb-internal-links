<div class="wrap">
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><?php echo self::$pluginName; ?> <?php _e('settings'); ?></h2>
	
	<?php if(isset($codeUpdateSuccess)): ?>
		<div class="updated"><p><strong><?php printf(__('Old code update process complete. Converted %d links'), $codeUpdateSuccess); ?>,</strong></p></div>
	<?php endif; ?>
	
	<?php if(isset($updateSuccess)): ?>
		<div class="updated"><p><strong><?php _e('Settings saved'); ?>.</strong></p></div>
	<?php endif; ?>

	<form method="post" action="<?php echo self::getCurrentPage(); ?>">
	<input type="hidden" name="rbinternal_submit" value="1" />
	
	<h3>General</h3>
	<table class="form-table">
	<tr valign="top">
	<th scope="row"><?php _e('Enable WYSIWYG plugin'); ?></th>
	<td>
		<fieldset>
			<label for="enable_wysiwyg">
				<input name="tinymce" type="checkbox" id="enable_wysiwyg" value="1" <?php if($options['tinymce']): ?>checked="checked"<?php endif; ?> />
				<?php _e('Yes'); ?>
			</label>
		</fieldset>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="return_param"><?php _e('Return to shortcode'); ?></label></th>
	<td>
		<select name="return_param" id="return_param">
			<option value='id' <?php if($options['return_param'] == 'id'): ?> selected="selected"<?php endif; ?>><?php _e('Post ID'); ?></option>
			<option value='slug' <?php if($options['return_param'] == 'slug'): ?> selected="selected"<?php endif; ?>><?php _e('Post slug'); ?></option>
		</select>
	</td>
	</tr>
	<tr valign="top">
	<th scope="row"><label for="default_text"><?php _e('Default text for link if not specified'); ?></label></th>
	<td>
		<select name="default_text" id="default_text">
			<option value='url' <?php if($options['default_text'] == 'url'): ?> selected="selected"<?php endif; ?>><?php _e('URL'); ?></option>
			<option value='title' <?php if($options['default_text'] == 'title'): ?> selected="selected"<?php endif; ?>><?php _e('Post title'); ?></option>
		</select>
	</td>
	</tr>
	</table>
	
	<h3><?php _e('Advanced'); ?></h3>
	<table class="form-table">
	<tr valign="top">
	<th scope="row"><label for="code_prefix"><?php _e('Surround shortcode with'); ?></label></th>
	<td>
		<input type="text" name="code_prefix" value="<?php echo $options['code_prefix']; ?>" /> [intlink id= ...] <input type="text" name="code_suffix" value="<?php echo $options['code_suffix']; ?>" />
	</td>
	</tr>
	</table>
	
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
	</p>
	
	</form>
	
	
	<h3><?php _e('Convert old internal link code'); ?></h3>
	<p><?php _e('Used rb internal links before and wishing your old link code worked with the new version? Just run this conversion process.'); ?><br/>
	<strong><?php _e('Please backup your database first, just in case!'); ?></strong></p>
	
	<form method="post" action="<?php echo self::getCurrentPage(); ?>">
	<input type="hidden" name="rbinternal_update_code" value="1" />
	<p class="submit">
		<input type="submit" name="Submit" class="button-primary" value="<?php _e('Convert code'); ?>" />
	</p>
	</form>
	
</div>
