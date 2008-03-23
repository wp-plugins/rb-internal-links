(function() {
			
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
			});
