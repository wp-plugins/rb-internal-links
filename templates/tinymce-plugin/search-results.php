<ul class="box-list">
<?php if(empty($items)): ?>
	<li><?php _e('No results found'); ?>.</li>
<?php else: ?>
	<?php include($path . '/templates/tinymce-plugin/items.php'); ?>
<?php endif; ?>
</ul>
