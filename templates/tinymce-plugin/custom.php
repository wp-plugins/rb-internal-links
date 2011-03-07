<ul class="box-list">
    <?php foreach ($post_types as $post_type => $object): ?>
        <li onclick="itemClick(__boxID__, this); loadCustom('<?php echo $post_type; ?>');"><div><?php _e($object->label); ?></div></li>
    <?php endforeach; ?>

    <?php //include($path . '/templates/tinymce-plugin/items.php'); ?>
</ul>
