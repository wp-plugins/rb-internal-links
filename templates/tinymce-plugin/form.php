<ul class="box-list"><li class="content">

<h2><?php _e('Insert', 'rb-internal-links'); ?></h2>

<table class="details" cellspacing="0">
	<tr>
		<td class="heading"><?php _e('Type', 'rb-internal-links'); ?></td>
		<td><?php echo ucfirst($type); ?></td>
	</tr>
	<tr>
		<td class="heading"><?php _e('Id', 'rb-internal-links'); ?></td>
		<td><?php echo substr($id, 0, 30); ?></td>
	</tr>
</table>

<div id="formErrors"></div>

<form class="link" onsubmit="submitForm(this); return false;">
	<input type="hidden" name="linkId" value="<?php echo $id; ?>" />
	<input type="hidden" name="linkType" value="<?php echo $type; ?>" />

	<fieldset>
		<label><?php _e('Link text', 'rb-internal-links'); ?></label>
		<input type="text" name="linkText" id="linkText" value="" />
	</fieldset>
	
	<a id="showAdvanced"><?php _e('Advanced options', 'rb-internal-links'); ?> <img src="<?php echo WP_PLUGIN_URL; ?>/rb-internal-links/images/down.gif" /></a>
	
	<div id="formAdvanced" style="display: none;">
		<fieldset>
			<label><?php _e('Class', 'rb-internal-links'); ?></label>
			<input type="text" name="linkClass" />
		</fieldset>
		
		<fieldset>
			<label><?php _e('Target', 'rb-internal-links'); ?></label>
			<select name="linkTarget">
				<option value=""><?php _e('Default', 'rb-internal-links'); ?></option>
				<option value="_blank">_blank</option>
				<option value="_parent">_parent</option>
				<option value="_self">_self</option>
				<option value="_top">_top</option>
			</select>
		</fieldset>
		
		<fieldset>
			<label><?php _e('Anchor', 'rb-internal-links'); ?></label>
			<input type="text" name="linkAnchor" />
		</fieldset>
	</div>
	
	<fieldset class="last">
		<input type="submit" name="submit" value="<?php _e('Insert link', 'rb-internal-links'); ?>" />
	</fieldset>
	

</form>
</li></ul>

<script type="text/javascript">

jQuery(function(){
	jQuery('#linkText').val(tinyMCEPopup.editor.selection.getContent({format : 'text'}));	
});

jQuery('#showAdvanced').click(function(){
	var advanced = jQuery('#formAdvanced');
	if(advanced.is(':visible'))
	{
		advanced.slideUp();
		jQuery('#showAdvanced > img').attr('src', '<?php echo WP_PLUGIN_URL; ?>/rb-internal-links/images/down.gif');
	}
	else
	{
		advanced.slideDown();
		jQuery('#showAdvanced > img').attr('src', '<?php echo WP_PLUGIN_URL; ?>/rb-internal-links/images/up.gif');
	}
});
</script>
