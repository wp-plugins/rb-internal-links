<?php if(!empty($items)) foreach($items AS $item): ?>
	<li onclick="itemClick(__boxID__, this); loadForm('<?php echo $type; ?>', '<?php echo $item->ID; ?>');" class="item">
		<img src="<?php echo WP_PLUGIN_URL; ?>/rb-internal-links/images/calendar_icon.gif" class="icon" title="Posted: <?php echo $item->post_date; ?>" />
		<div class="title"><?php _e(empty($item->post_title)? 'Untitled' : $item->post_title); ?></div>
	</li>
<?php endforeach; ?>
