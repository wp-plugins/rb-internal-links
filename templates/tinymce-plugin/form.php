<ul class="box-list"><li class="content">

<h2>Insert</h2>

<table class="details" cellspacing="0">
	<tr>
		<td class="heading">Type</td>
		<td><?php echo ucfirst($type); ?></td>
	</tr>
	<tr>
		<td class="heading">Id</td>
		<td><?php echo substr($id, 0, 30); ?></td>
	</tr>
</table>

<div id="formErrors"></div>

<form class="link" onsubmit="submitForm(this); return false;">
	<input type="hidden" name="linkId" value="<?php echo $id; ?>" />
	<input type="hidden" name="linkType" value="<?php echo $type; ?>" />

	<fieldset>
		<label>Link text</label>
		<input type="text" name="linkText" style="width: 100%" value="<?php echo $content; ?>" />
	</fieldset>
	
	<a id="showAdvanced">Advanced options <img src="images/down.gif" /></a>
	
	<div id="formAdvanced" style="display: none;">
		<fieldset>
			<label>Class</label>
			<input type="text" name="linkClass" />
		</fieldset>
		
		<fieldset>
			<label>Target</label>
			<select name="linkTarget">
				<option value="">Default</option>
				<option value="_blank">_blank</option>
				<option value="_parent">_parent</option>
				<option value="_self">_self</option>
				<option value="_top">_top</option>
			</select>
		</fieldset>
		
		<fieldset>
			<label>Anchor</label>
			<input type="text" name="linkAnchor" />
		</fieldset>
	</div>
	
	<fieldset class="last">
		<input type="submit" name="submit" style="float: right;" value="Insert link" />
	</fieldset>
	

</form>
</li></ul>

<script type="text/javascript">
jQuery('#showAdvanced').click(function(){
	var advanced = jQuery('#formAdvanced');
	if(advanced.is(':visible'))
	{
		advanced.slideUp();
		jQuery('#showAdvanced > img').attr('src', 'images/down.gif');
	}
	else
	{
		advanced.slideDown();
		jQuery('#showAdvanced > img').attr('src', 'images/up.gif');
	}
});
</script>
