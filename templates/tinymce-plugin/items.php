<?php if(!empty($items)) foreach($items AS $item): ?>
	<li onclick="itemClick(__boxID__, this); loadForm('<?php echo $type; ?>', '<?php echo $item->ID; ?>');" class="item">
		<img src="images/calendar_icon.gif" title="Posted: <?php echo $item->post_date; ?>" />
		<?php echo $item->post_title; ?>
	</li>
<?php endforeach; ?>
