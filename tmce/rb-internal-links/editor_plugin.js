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
				var button = '<a id="mce_editor_0_rbinternallinks" href="javascript:tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceAnarchyMediaInsert\');" onclick="tinyMCE.execInstanceCommand(\'{$editor_id}\',\'mceRBInternalLinkInsert\');return false;" onmousedown="return false;" class="mceButtonNormal" target="_self"><img src="{$pluginurl}/images/icon.gif" title="RB Internal Links"></a>';
				return button;
		}
		return "";
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