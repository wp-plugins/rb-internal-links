=== RB Internal Links ===
Contributors: cohen
Donate link: http://www.blograndom.com
Tags: links, posts, slugs, permalinks
Requires at least: 2.1
Tested up to: 2.3
Stable tag: 0.11

Link to internal posts and pages in your blog in a similar to style to wiki. No need to remember full URLs, post ids or slugs. Wysiwyg plugin to help find previous posts and pages. 

== Description ==

An easy way to link to other posts and articles in your blog. As well as quickly typing in the link tag there is a tinymce wysiwyg 
plugin that allows you to search for posts for category.

I saw a need for this plugin and thought I'd give it a go. If you would like to help improve this plugin please contact me via my blog.

== Installation ==

1. Upload the "rb-internal-links" folder to your wp-content/plugins/ directory.
2. Activate the plug-in
3. Browse to Options > RB Internal Links to take a look at preferences
4. Start internally linking

== Usage ==

For more information visit http://www.blograndom.com/blog/extras/rb-internal-link-plugin/

To manually link a page:

	At the place you would like the link to appear write ( ignoring the ticks - i.e. ` ):
		`{{post id="post-slug" text="link text"}}` OR `{{post id="post-id" text="link text"}}`
	...exchanging post-slug or post-id for the post or page slug/id you would like to link to.

	Note:
	- text is the text or html you would like to put within the anchor tag and should be ok with anything other than { or ".
	- you can specify a class for the link using ... `class="my_class"` ...
	- you can specify a target for the link using ... `target="_new"` ...
	
Using the tinymce wysiwyg editor:

	Make sure you've ticked "enable wysiwyg editor" on the plugin preferences page.
	A new icon will appear on the wysiwyg toolbar, it looks like a page with a link over it
	Clicking on the icon will pop-up a toolbox
	Select the category the post you need is in or alternatively, choose pages for a list of pages
	Click on the post or page you wish to link to
	Fill in the optional properties for the link
	Click "Insert Link"

== Change Log ==

v0.11
- Updating some spelling mistakes
- Added debug information for missing wysiwyg icon
