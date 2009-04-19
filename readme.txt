=== RB Internal Links ===
Contributors: Arron Woods
Donate link: http://www.blograndom.com/
Tags: links, posts, slugs, permalinks, shortcode
Requires at least: 2.5
Tested up to: 2.7.1
Stable tag: 2.0.5

Link to posts and pages within your blog using shortcodes, similar to a wiki or cms. No need to remember full URLs, post ids or slugs with the wysiwyg UI plugin to help find previous posts and pages.

== Description ==

An easy way to link to posts and pages in your blog. As well as quickly typing in the link tag there is a tinymce wysiwyg 
plugin that allows you to search for posts by category.

The shortcode for the plugin within the post gets converted to the current permalink for the post on the render of the page. If you change your link structure later on, you don't have to go back and edit the internal links for your blog!

Using the UI you can search through your blog posts or browse by category and get the shortcode added to the post for you!

I saw a need for this plugin and thought I'd give it a go. If you would like to help improve this plugin please contact me via my blog.

== Installation ==

1. Upload the "rb-internal-links" folder to your wp-content/plugins/ directory.
2. Activate the plug-in
3. Browse to Options > RB Internal Links to take a look at preferences
4. Start internally linking

== Usage ==

For more information visit http://www.blograndom.com/blog/extras/rb-internal-link-plugin/
And check out the forum for bugs/features: http://www.blograndom.com/links/forum/

To manually link a page:

	At the place you would like the link to appear write:
		`[intlink id="post-slug"]Link text[/intlink]
	...exchanging post-slug or post-id for the post or page slug/id you would like to link to.

	Note:
	- you can specify a class for the link using ... `class="my_class"` ...
	- you can specify a target for the link using ... `target="_new"` ...
	- you can specify an anchor to go to on the new page using ... `anchor="bottom"` ...
	
Using the tinymce wysiwyg editor:

	Make sure you've ticked "enable wysiwyg editor" on the plugin preferences page.
	A new icon will appear on the wysiwyg toolbar, it looks like a page with a link over it
	Select the text you wish to turn in to a link (optional)
	Click on the icon, a toolbox should popup
	Select the category of the post you need or alternatively choose pages for a list of pages
	Click on the post or page you wish to link to
	Fill in the optional properties for the link
	Click "Insert Link"
	
== Change Log ==
v2.0.5 (19/04/2009)
- compat.php file with json_encode function for PHP versions < 5.2
- Cursor and foreground change to notify user that next section is loading

v2.0 (22/02/2009)
- Complete rewrite, a few new features, some removed but same core functionality

v0.22 (28/12/2008)
- tinyMCE language file using new format, compatibility with wordpress version 2.7

v0.15 (27/01/2008)
- Fixed bug with visual mode insertion hiding newly inserted link
- Added anchor parameter for linking to anchors within pages

v0.14 (19/12/2007)
- Changed CDATA comment tags from /* */ to //, which should resolve issues with the tinymce icon

v0.13 (23/10/2007)
- Changed charset to utf-8 for tinymce plugin (thanks to vanco)
- Put in option for ID or slug returned to editor, default to be ID (good idea, thanks again vanco)
- Support for creating an internal link with an image
- The intlink code is now hidden in visual mode, replaced with the highlighted text
- The parsing engine has been completely rewritten for speed and scalability
- Some code changes that should help people with problems related to the wysiwyg icon not turning up
- Added filter for 'the_content_rss'

v0.12 (21/10/2007)
- Stopped selected text with quote marks in from breaking the wysiwyg plugin

v0.11  (19/10/2007)
- Updating some spelling mistakes
- Added debug information for missing wysiwyg icon
