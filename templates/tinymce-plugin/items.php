<?php if(!empty($items)) foreach($items AS $item): ?>
	<li onclick="itemClick(__boxID__, this); loadForm('<?php echo $type; ?>', '<?php echo $item->ID; ?>');" class="item">
		<img src="images/calendar_icon.gif" class="icon" title="Posted: <?php echo $item->post_date; ?>" />
		<div class="title"><?php _e($item->post_title); ?></div>
	</li>
<?php endforeach; ?>
