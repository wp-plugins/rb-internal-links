<ul class="box-list">
	<?php if(!empty($topCategory)): ?>
		<li onclick="itemClick(__boxID__, this); loadForm('<?php echo $type; ?>', '<?php echo $topCategory->term_id; ?>');" class="item"><div><?php _e($topCategory->name); ?></div></li>
	<?php endif; ?>
	
	<?php foreach($categories AS $category): ?>
		<li onclick="itemClick(__boxID__, this); loadCategory('<?php echo $type; ?>', '<?php echo $category->term_id; ?>', '<?php echo $post_type; ?>');"><div><?php _e($category->name); ?></div></li>
	<?php endforeach; ?>
	
	<?php include($path . '/templates/tinymce-plugin/items.php'); ?>	
</ul>
