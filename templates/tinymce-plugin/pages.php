<ul class="box-list">
	<?php if($topPage): ?>
		<li onclick="itemClick(__boxID__, this); loadForm('<?php echo $type; ?>', '<?php echo $topPage->ID; ?>');" class="item"><?php echo $topPage->post_title; ?></li>
	<?php endif; ?>
	
	<?php foreach($pages AS $page): ?>
		<li onclick="itemClick(__boxID__, this); loadCategory('<?php echo $type; ?>', '<?php echo $page->ID; ?>');"><?php echo $page->post_title; ?></li>
	<?php endforeach; ?>	
</ul>
