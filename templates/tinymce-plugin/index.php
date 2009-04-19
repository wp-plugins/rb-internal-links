<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>RB Internal Linker</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<style>
		body{
			margin: 0;
			padding: 0;
			border: 0;
			width: 710px;
			font-family: arial, tahoma, verdana;
			font-size: 12px;
		}
		h2{
			margin: 0 0 5px 0;
			font-size: 12px;
			padding: 0 0 4px 0;
			border-bottom: 1px #BAC3CA solid;
		}
		a{
			color: #00457A;
		}
		#container{
			position: absolute;
			top: 0;
			left: 25px;
			width: 650px;
			height: 300px;
			overflow: hidden;
		}
		#boxes{
			position: relative;
			float: left;
			height: 300px;
			width: 5000px;
		}
		.box{
			position: relative;
			float: left;
			width: 215px;
			height: 300px;
			border-right: 1px #c0c0c0 solid;
			overflow: auto;
		}
		.last{
			border-right: 0px;
		}
		.arrow{
			position: absolute;
			left: 0;
			top: 0;
			height: 300px;
			width: 25px;
			background-color: #003366;
			color: #ffffff;
			font-size: 20px;
			text-align: center;
			line-height: 300px;
			cursor: pointer;
		}
		.right{
			left: 695px;
		}
		
		ul.box-list{
			list-style: none;
			padding: 0;
			margin: 0;
		}
		ul.box-list li{
			padding: 4px;
			background:url('images/list-bg.jpg');
			background-repeat: repeat-x;
			border-bottom: 1px #dcdcdc solid;
			color: #404040;
			cursor: pointer;
		}
		ul.box-list li.content{
			cursor: default;
		}
		ul.box-list li.item{
			background:url('images/list-bg-item.jpg');
			background-repeat: repeat-x;
		}
		ul.box-list li:hover, ul.box-list li.active{
			background:url('images/list-bg-hover.jpg');
			background-repeat: repeat-x;
		}
		div.details{
			margin: 10px;
		}
		table.details{
			font-size: 12px;
			width: 180px;
			text-align: left;
			margin: 0 0 10px 0;
			padding: 0;
		}
		table.details td.heading
		{
			font-weight: bold;
			width: 30px;
		}
		table.details td{
			border: 1px #dedede solid;
		}
		#formErrors{
			color: #ff0000;
		}
		form.link{
			margin-top: 5px;
		}
		fieldset{
			padding: 0;
			margin: 0 0 5px 0;
			border: 0;
		}
		label{
			margin: 0 0 2px 0;
			padding: 0;
		}
		input, select{
			clear: left;
			width: 100%;
		}
		fieldset.last{
			background-color: #E2EBF3;
			padding: 2px;
			margin: 0px;
		}		
		#showAdvanced{
			display: block;
			margin: 10px 0;
		}
		
		#formAdvanced{
			margin: 0 0 5px 0;
			padding: 0;
		}
		#theDarkness{
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0,0,0,0.5);
		}
		#ajaxLoading{
			position: absolute;
			top: 50%;
			left: 50%;
			background:url('images/ajax-loader.gif');
			width: 220px;
			height: 19px;
			margin: -10px 0 0 -110px;
		}
	</style>
	<?php wp_enqueue_script('tinymce-popup', '/wp-includes/js/tinymce/tiny_mce_popup.js'); ?>
	<?php wp_enqueue_script('jquery'); ?>
	<?php wp_head(); ?> 
	<script type="text/javascript" src="js/box.js"></script>
	<script type="text/javascript">	
		jQuery(document).ready(function(){
			loadStart();
			// remove the tinymce plugin css
			var allLinks = document.getElementsByTagName("link");
			allLinks[allLinks.length-1].parentNode.removeChild(allLinks[allLinks.length-1]);
			allLinks[allLinks.length-1].parentNode.removeChild(allLinks[allLinks.length-1]);
		});
		
		var editorContent = "<?php echo $content; ?>";
		
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
			var html = loadHtml('form', 'type=' + type + '&id=' + id + '&content=' + editorContent);
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
			jQuery.getJSON('tinymce-plugin.php', 'action=link&' + data, function(result){
				if(result.status == 'error')
					jQuery('#formErrors').text(result.message);
				else
				{
					tinyMCEPopup.execCommand("mceInsertContent", false, result.code);
					tinyMCEPopup.close();
				}
			});
		}
		
	</script>	
</head>
<body>
	<div class="arrow" onclick="moveBoxes(-1);">&laquo;</div>

	<div id="container">
		<div id="boxes">
						
		</div>
	</div>
	
	<div class="arrow right" onclick="moveBoxes(1);">&raquo;</div>

	<div id="theDarkness">
		<div id="ajaxLoading"></div>
	</div>
</body>
</html>
