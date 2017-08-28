(
	function(){
	
		var url = '';
	
		tinymce.create(
			"tinymce.plugins.PrimaShortcodesTypography",
			{
				init: function(d,e) {
					url = e;
				},
				createControl:function(d,e)
				{
				
					if(d=="prima_shortcodes_typography_button"){
					
						d=e.createMenuButton( "prima_shortcodes_typography_button",{
							title: "Insert Typography Shortcode",
							image: url + '/images/text.png',
							icons: false
							});
							
							var a=this;d.onRenderMenu.add(function(c,b){
								
								a.addImmediate(b,"Heading", "[heading]Your heading text[/heading]");

								c=b.addMenu({title:"Box"});
									a.addImmediate(c,"Simple Box","[box] Please insert your content here [/box]");
									a.addImmediate(c,"Blue Box","[box color=\"blue\"] Please insert your content here [/box]");
									a.addImmediate(c,"Red Blox","[box color=\"red\"] Please insert your content here [/box]");
									a.addImmediate(c,"Green Box","[box color=\"green\"] Please insert your content here [/box]");
									a.addImmediate(c,"Yellow Box","[box color=\"yellow\"] Please insert your content here [/box]");
									a.addImmediate(c,"Left Floated Box","[box float=\"left\"] Please insert your content here [/box]");
									a.addImmediate(c,"Right Floated Box","[box float=\"right\"] Please insert your content here [/box]");
									a.addImmediate(c,"Box With \"Heart\" Icon","[box icon=\"heart\"] Please insert your content here [/box]");
								
								// b.addSeparator();
								
								c=b.addMenu({title:"Quote"});
									a.addImmediate(c,"Simple Quote","[quote] Please insert your content here [/quote]");
									a.addImmediate(c,"Left Floated Quote","[quote float=\"left\"] Please insert your content here [/quote]");
									a.addImmediate(c,"Right Floated Quote","[quote float=\"right\"] Please insert your content here [/quote]");
									a.addImmediate(c,"Boxed Quote","[quote style=\"boxed\"] Please insert your content here [/quote]");
									a.addImmediate(c,"Left Floated Boxed Quote","[quote style=\"boxed\" float=\"left\"] Please insert your content here [/quote]");
									a.addImmediate(c,"Right Floated Boxed Quote","[quote style=\"boxed\" float=\"right\"] Please insert your content here [/quote]");

								// b.addSeparator();
								
								c=b.addMenu({title:"Button"});
									a.addImmediate(c,"Simple Button","[button link=\"#\" ] Button Text [/button]");
									a.addImmediate(c,"Blue Button","[button link=\"#\" color=\"blue\"] Button Text [/button]");
									a.addImmediate(c,"Red Button","[button link=\"#\" color=\"red\"] Button Text [/button]");
									a.addImmediate(c,"Green Button","[button link=\"#\" color=\"green\"] Button Text [/button]");
									a.addImmediate(c,"Yellow Button","[button link=\"#\" color=\"yellow\"] Button Text [/button]");
									a.addImmediate(c,"Black Button","[button link=\"#\" color=\"black\"] Button Text [/button]");
									a.addImmediate(c,"Button With \"Heart\" Icon","[button link=\"#\" icon=\"heart\"] Button Text [/button]");
								
								// b.addSeparator();
								
								c=b.addMenu({title:"Tabs"});
									a.addImmediate(c,"2 Tabs Content","[tabs]<br/>[tab title=\"First Tab\"]<br/><br/>First Tab content goes here.<br/><br/>[/tab]<br/>[tab title=\"Second Tab\"]<br/><br/>Second Tab content goes here.<br/><br/>[/tab]<br/>[/tabs]");
									a.addImmediate(c,"3 Tabs Content","[tabs]<br/>[tab title=\"First Tab\"]<br/><br/>First Tab content goes here.<br/><br/>[/tab]<br/>[tab title=\"Second Tab\"]<br/><br/>Second Tab content goes here.<br/><br/>[/tab]<br/>[tab title=\"Third Tab\"]<br/><br/>Third Tab content goes here.<br/><br/>[/tab]<br/>[/tabs]");
									a.addImmediate(c,"4 Tabs Content","[tabs]<br/>[tab title=\"First Tab\"]<br/><br/>First Tab content goes here.<br/><br/>[/tab]<br/>[tab title=\"Second Tab\"]<br/><br/>Second Tab content goes here.<br/><br/>[/tab]<br/>[tab title=\"Third Tab\"]<br/><br/>Third Tab content goes here.<br/><br/>[/tab]<br/>[tab title=\"Fourth Tab\"]<br/><br/>Fourth Tab content goes here.<br/><br/>[/tab]<br/>[/tabs]");
								
								// b.addSeparator();
								
								a.addImmediate(b,"Toggle", "[toggle title=\"Toggle Title\"] <br/><br/>Please insert your content here<br/><br/> [/toggle]");
								a.addImmediate(b,"Dropcap", "[dropcap]T[/dropcap]his is an example of a dropcap text.");
								a.addImmediate(b,"Highlight", "You could edit this to put [highlight]important information[/highlight] on this page.");

							});
						return d
					
					} // End IF Statement
					
					return null
				},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
				
			}
		);
		
		tinymce.PluginManager.add( "PrimaShortcodesTypography", tinymce.plugins.PrimaShortcodesTypography);
	}
)();