jQuery(document).ready(function(){
	loadStart();
});
		
function loadHtml(action, params)
{
	if(params == undefined)
		params = '';
		
	jQuery('body').css('cursor', 'wait');
	jQuery('#theDarkness').show();
		
	var html = jQuery.ajax({
		type: 'POST',
		url: RbInternalLinksAjax.url,
		data: 'action=rb-internal-links-ajax&rb-internal-links-action=' + action + ((params != undefined)? '&' + params : ''),
		async: false,
		error: function(data)
		{
			jQuery('body').content('Error: ' + data);
			jQuery('#theDarkness').hide();
		}
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

function loadCategory(type, parent, post_type)
{
	var b = new box();
	var html = loadHtml('type', 'type=' + type + '&parent=' + parent + '&post_type=' + post_type);
	b.content(html);
}

function loadCustom(post_type)
{
	loadCategory('post', 0, post_type);
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
		RbInternalLinksAjax.url,
		'action=rb-internal-links-ajax&rb-internal-links-action=link&' + data,
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
