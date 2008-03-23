(function() {	// Load plugin specific language pack	//tinymce.PluginManager.requireLangPack('example');	tinymce.create('tinymce.plugins.rbinternallinks', {		/**		 * Initializes the plugin, this will be executed after the plugin has been created.		 * This call is done before the editor instance has finished it's initialization so use the onInit event		 * of the editor instance to intercept that event.		 *		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.		 * @param {string} url Absolute URL to where the plugin is located.		 */		init : function(ed, url) {			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');			ed.addCommand('rbinternallinks', function() {				ed.windowManager.open({					file : url + '../../../tmce_plugin.php?linktext=' + escape(tinyMCE.activeEditor.selection.getContent()),					width : 355,					height : 400,					inline : 1				}, {					plugin_url : url // Plugin absolute URL				});			});			// Register example button			ed.addButton('rbinternallinks', {				title : 'RB Internal Links',				cmd : 'rbinternallinks',				image : url + '/images/icon.gif'			});			// Add a node change handler, selects the button in the UI when a image is selected			ed.onNodeChange.add(function(ed, cm, n) {				//cm.setActive('example', n.nodeName == 'IMG');			});
			
			// Replace morebreak with images
			ed.onBeforeSetContent.add(function(ed, o) {
				
				var startPos = 0;
				
				// Parse all <!--intlink * --> tags and replace them with images
				while ((startPos = o.content.indexOf('<!--intlink', startPos)) != -1) {
					var endPos = o.content.indexOf('-->', startPos) + 3;
					// Insert code
					var linkCode = o.content.substring(startPos+11, endPos-3);
					// Find link text
					var textStartPos = linkCode.indexOf('text="', 0);
					var textEndPos = linkCode.indexOf('"', textStartPos+6);
					var linkText = linkCode.substring(textStartPos+6, textEndPos);
					// Get the rest of the content
					var contentAfter = o.content.substring(endPos);
					// Put it all together
					o.content = o.content.substring(0, startPos);
					o.content += '<span class="rbIntLinkText">';
					o.content += linkText;
					o.content += '<!--pintlink' + linkCode + '-->';
					o.content += '</span>';
					o.content += contentAfter;

					startPos++;
				}
				var startPos = 0;

			});

			// Replace images with morebreak
			ed.onPostProcess.add(function(ed, o) {
				if (o.get){
					// Parse all parsed internal links back to <!--intlink * -->
					var startPos = -1;
					var rbinternal_i=0;
					while ((startPos = o.content.indexOf('<span class="rbIntLinkText">', startPos+1)) != -1) {
						rbinternal_i=rbinternal_i+1;
						var endPos = o.content.indexOf('--></span>', startPos);
							
							nContent = o.content.substring(0, startPos);
							chunkAfter = o.content.substring(endPos+10);
							
							if((codeStartPos = o.content.indexOf('<!--pintlink', startPos+28)) != -1){ // start position + span code
								codeEndPos = endPos; // end position
								linkCode = o.content.substring(codeStartPos+12, codeEndPos);
							}else
								linkCode = 'error';
							
							nContent += '<!--intlink' + linkCode + '-->';
							
							nContent += chunkAfter;
							o.content = nContent;
					
						if(rbinternal_i > 500){ alert('RB Internal Links: Javascript seems to be stuck in a loop, exiting processing.'); break; }
							
					}
				}
			});		},		/**		 * Creates control instances based in the incomming name. This method is normally not		 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons		 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this		 * method can be used to create those.		 *		 * @param {String} n Name of the control to create.		 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.		 * @return {tinymce.ui.Control} New control instance or null if no control was created.		 */		createControl : function(n, cm) {			return null;		},
		/**		 * Returns information about the plugin as a name/value array.		 * The current keys are longname, author, authorurl, infourl and version.		 *		 * @return {Object} Name/value array containing information about the plugin.		 */		getInfo : function() {			return {				longname : 'RB Internal Links',				author : 'Cohen',				authorurl : 'http://www.blograndom.com',				infourl : 'http://www.blograndom.com/blog/extras/rb-internal-links-plugin/',				version : "1.0"			};		}	});	// Register plugin	tinymce.PluginManager.add('rbinternallinks', tinymce.plugins.rbinternallinks);})();