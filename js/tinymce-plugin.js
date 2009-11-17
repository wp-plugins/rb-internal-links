jQuery(document).ready(function(){
	loadStart();
	// remove the tinymce plugin css
	var allLinks = document.getElementsByTagName("link");
	allLinks[allLinks.length-1].parentNode.removeChild(allLinks[allLinks.length-1]);
	allLinks[allLinks.length-1].parentNode.removeChild(allLinks[allLinks.length-1]);
});
		
function loadHtml(action, params)
{
	if(params == undefined)
		params = '';
		
	jQuery('body').css('cursor', 'wait');
	jQuery('#theDarkness').show();
		
	var html = jQuery.ajax({
		type: 'POST',
		url: 'tinymce-plugin.php',
		data: 'action=' + action + ((params != undefined)? '&' + params : ''),
		async: false
	}).responseText;
	
	jQuery('body').css('cursor', 'default');
	jQuery('#theDarkness').hide();
	
	return html;
}

function loadStart()
{
	var start = new box();
	var html = loadHtml('start');
	start.content(html);
}

function loadType(type)
{
	var b = new box();
	var html = loadHtml('type', 'type=' + type);
	b.content(html);
}

function loadCategory(type, parent)
{
	var b = new box();
	var html = loadHtml('type', 'type=' + type + '&parent=' + parent);
	b.content(html);
}

function loadForm(type, id)
{
	var b = new box();
	var html = loadHtml('form', 'type=' + type + '&id=' + id);
	b.content(html);
}

function performSearch(form)
{
	q = jQuery(form).serialize();
	var html = loadHtml('search', q);
	var b = new box();
	b.content(html);			
}

function submitForm(form)
{
	data = jQuery(form).serialize();
	jQuery.post(
		'tinymce-plugin.php',
		'action=link&' + data,
		function(result){
			if(result.status == 'error')
				jQuery('#formErrors').text(result.message);
			else
			{
				tinyMCEPopup.execCommand("mceInsertContent", false, result.code);
				tinyMCEPopup.close();
			}
		},
		"json"
	);
}
