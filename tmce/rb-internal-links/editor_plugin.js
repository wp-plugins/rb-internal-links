var TinyMCE_RBInternalPlugin = {
	getInfo : function() {
		return {
			longname : 'RBInternalPlugin',
			author : 'Cohen',
			authorurl : 'http://www.blograndom.com',
			infourl : '',
			version : "1.0"
		};
	},
	getControlHTML : function(cn) {
		switch (cn) {
			case "rbinternallinks":
				var button = '<a id="mce_editor_0_rbinternallinks" href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceRBInternalLinkInsert\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceRBInternalLinkInsert\');return false;" onmousedown="return false;" class="mceButtonNormal" target="_self"><img src="{$pluginurl}/images/icon.gif" title="RB Internal Links"></a>';
				return button;
		}
		return "";
	},
	cleanup : function(type, content) {
		switch (type) {

			case "insert_to_editor":
				var startPos = 0;
				
				// Parse all <!--intlink * --> tags and replace them with images
				while ((startPos = content.indexOf('<!--intlink', startPos)) != -1) {
					var endPos = content.indexOf('-->', startPos) + 3;
					// Insert code
					var linkCode = content.substring(startPos+11, endPos-3);
					// Find link text
					var textStartPos = linkCode.indexOf('text="', 0);
					var textEndPos = linkCode.indexOf('"', textStartPos+6);
					var linkText = linkCode.substring(textStartPos+6, textEndPos);
					// Get the rest of the content
					var contentAfter = content.substring(endPos);
					// Put it all together
					content = content.substring(0, startPos);
					content += '<span class="rbIntLinkText">';
					content += linkText;
					content += '<!--pintlink' + linkCode + '-->';
					content += '</span>';
					content += contentAfter;

					startPos++;
				}
				var startPos = 0;

				break;

			case "get_from_editor":
				// Parse all parsed internal links back to <!--intlink * -->
				var startPos = -1;
				while ((startPos = content.indexOf('<span class="rbIntLinkText">', startPos+1)) != -1) {
					var endPos = content.indexOf('--></span>', startPos);
						
						nContent = content.substring(0, startPos);
						chunkAfter = content.substring(endPos+10);
						
						if((codeStartPos = content.indexOf('<!--pintlink', startPos+28)) != -1){ // start position + span code
							codeEndPos = endPos; // end position
							linkCode = content.substring(codeStartPos+12, codeEndPos);
						}else
							linkCode = 'error';
						
						nContent += '<!--intlink' + linkCode + '-->';
						
						nContent += chunkAfter;
						content = nContent;
						
				}

				break;
		}

		// Pass through to next handler in chain
		return content;
	},
	initInstance : function(inst) {
		tinyMCE.importCSS(inst.getDoc(), rbinternal_url + 'styles.css');
	},
	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			case "mceRBInternalLinkInsert":
			
				var selectedHTML = tinyMCE.getInstanceById(editor_id).selection.getSelectedHTML();
				
				var template = new Array();

				template['file']   = rbinternal_url + 'tmce_plugin.php?linktext=' + escape(selectedHTML);
				template['width']  = 355;
				template['height'] = 400;

				// Language specific width and height addons
				template['width']  += tinyMCE.getLang('lang_advimage_delta_width', 0);
				template['height'] += tinyMCE.getLang('lang_advimage_delta_height', 0);

				var inst = tinyMCE.getInstanceById(editor_id);
				var elm = inst.getFocusElement();

				if (elm != null && tinyMCE.getAttrib(elm, 'class').indexOf('mceItem') != -1)
					return true;

				tinyMCE.openWindow(template, {editor_id : editor_id, inline : "yes"});

			return true;
		}
		return false;
	}
};
tinyMCE.addPlugin("rbinternallinks", TinyMCE_RBInternalPlugin);