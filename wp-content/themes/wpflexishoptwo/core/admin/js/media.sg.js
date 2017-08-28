(
	function(){
	
		var url = '';
	
		tinymce.create(
			"tinymce.plugins.PrimaShortcodesMedia",
			{
				init: function(d,e) {
					url = e;
				},
				createControl:function(d,e)
				{
				
					if(d=="prima_shortcodes_media_button"){
					
						d=e.createMenuButton( "prima_shortcodes_media_button",{
							title: "Insert Media Shortcode",
							image: url + '/images/media.png',
							icons: false
							});
							
							var a=this;d.onRenderMenu.add(function(c,b){
								
								a.addImmediate(b,"Vimeo","[prima_vimeo id=\"30153918\" width=\"700\" height=\"386\"]");
								a.addImmediate(b,"Youtube","[prima_youtube id=\"chTkQgQKotA\" width=\"700\" height=\"386\"]");
								a.addImmediate(b,"Audio (MP3)","[prima_audio width=\"640\" mp3=\"YOUR_MP3_URL\"]");
								a.addImmediate(b,"Video (MP4)","[prima_video width=\"636\" height=\"360\" poster=\"\" m4v=\"YOUR_MP4_URL\"]");
								
							});
						return d
					
					} // End IF Statement
					
					return null
				},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
				
			}
		);
		
		tinymce.PluginManager.add( "PrimaShortcodesMedia", tinymce.plugins.PrimaShortcodesMedia);
	}
)();