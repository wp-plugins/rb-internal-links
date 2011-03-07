<ul class="box-list">
	<li onclick="itemClick(__boxID__, this); loadType('post');"><?php _e('Posts', 'rb-internal-links'); ?></li>
	<li onclick="itemClick(__boxID__, this); loadType('page');"><?php _e('Pages', 'rb-internal-links'); ?></li>
	<li onclick="itemClick(__boxID__, this); loadType('category');"><?php _e('Categories', 'rb-internal-links'); ?></li>
        <li onclick="itemClick(__boxID__, this); loadType('custom');"><?php _e('Custom types', 'rb-internal-links'); ?></li>
	<li onclick="itemClick(__boxID__, this); loadType('search');"><?php _e('Search', 'rb-internal-links'); ?></li>
</ul>
