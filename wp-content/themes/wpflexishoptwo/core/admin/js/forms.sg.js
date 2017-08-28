(
	function(){
	
		var url = '';
	
		tinymce.create(
			"tinymce.plugins.PrimaShortcodesForms",
			{
				init: function(d,e) {
					url = e;
				},
				createControl:function(d,e)
				{
				
					if(d=="prima_shortcodes_forms_button"){
					
						d=e.createMenuButton( "prima_shortcodes_forms_button",{
							title: "Insert Forms Shortcode",
							image: url + '/images/forms.png',
							icons: false
							});
							
							var a=this;d.onRenderMenu.add(function(c,b){
								
								a.addImmediate(b,"Contact Form", "[prima_contact_form email=\"youremail@yourdomain.com\" subject=\"Message via the contact form\" button_text=\"Submit\"]");
								a.addImmediate(b,"Search Form", "[search_form]");
								a.addImmediate(b,"Feedburner Form", "[feedburner_form id=\"primathemes\" ]");

							});
						return d
					
					} // End IF Statement
					
					return null
				},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
				
			}
		);
		
		tinymce.PluginManager.add( "PrimaShortcodesForms", tinymce.plugins.PrimaShortcodesForms);
	}
)();