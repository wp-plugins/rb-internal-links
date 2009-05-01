<ul class="box-list">
	<li class="content">
		<h2><?php _e('Search posts'); ?></h2>
		<form id="postSearch" onsubmit="itemClick(__boxID__, this); performSearch(this); return false;">
			<input type="text" name="q" value="" />
			<input type="submit" name="submit" value="<?php _e('Search'); ?>" />
		</form>
	</li>
</ul>
