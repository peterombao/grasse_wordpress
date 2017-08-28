(
	function(){
	
		var url = '';
	
		tinymce.create(
			"tinymce.plugins.PrimaShortcodesColumns",
			{
				init: function(d,e) {
					url = e;
				},
				createControl:function(d,e)
				{
				
					if(d=="prima_shortcodes_columns_button"){
					
						d=e.createMenuButton( "prima_shortcodes_columns_button",{
							title: "Insert Columns Shortcode",
							image: url + '/images/columns.png',
							icons: false
							});
							
							var a=this;d.onRenderMenu.add(function(c,b){
								
								a.addImmediate(b,"2 Columns (1:1)","[column]<br/>[twocol_one] <br/><br/>First column content<br/><br/> [/twocol_one]<br/>[twocol_one_last]<br/><br/> Second column content<br/><br/> [/twocol_one_last]<br/>[/column]");
								a.addImmediate(b,"2 Columns (2:1)","[column]<br/>[threecol_two] <br/><br/>First column content<br/><br/> [/threecol_two]<br/>[threecol_one_last] <br/><br/>Second column content<br/><br/> [/threecol_one_last]<br/>[/column]");
								a.addImmediate(b,"2 Columns (1:2)","[column]<br/>[threecol_two] <br/><br/>First column content<br/><br/> [/threecol_two]<br/>[threecol_one_last] <br/><br/>Second column content<br/><br/> [/threecol_one_last]<br/>[/column]");
								a.addImmediate(b,"3 Columns (1:1:1)","[column]<br/>[threecol_one] <br/><br/>First column content<br/><br/> [/threecol_one]<br/>[threecol_one] <br/><br/>Second column content<br/><br/> [/threecol_one]<br/>[threecol_one_last] <br/><br/>Third column content<br/><br/> [/threecol_one_last]<br/>[/column]");
								a.addImmediate(b,"3 Columns (2:1:1)","[column]<br/>[fourcol_two] <br/><br/>First column content<br/><br/> [/fourcol_two]<br/>[fourcol_one] <br/><br/>Second column content<br/><br/> [/fourcol_one]<br/>[fourcol_one_last] <br/><br/>Third column content<br/><br/> [/fourcol_one_last]<br/>[/column]");
								a.addImmediate(b,"3 Columns (1:2:1)","[column]<br/>[fourcol_one] <br/><br/>First column content<br/><br/> [/fourcol_one]<br/>[fourcol_two] <br/><br/>Second column content<br/><br/> [/fourcol_two]<br/>[fourcol_one_last] <br/><br/>Third column content<br/><br/> [/fourcol_one_last]<br/>[/column]");
								a.addImmediate(b,"3 Columns (1:1:2)","[column]<br/>[fourcol_one] <br/><br/>First column content<br/><br/> [/fourcol_one]<br/>[fourcol_one] <br/><br/>Second column content<br/><br/> [/fourcol_one]<br/>[fourcol_two_last] <br/><br/>Third column content<br/><br/> [/fourcol_two_last]<br/>[/column]");
								a.addImmediate(b,"4 Columns","[column]<br/>[fourcol_one] <br/><br/>First column content<br/><br/> [/fourcol_one]<br/>[fourcol_one] <br/><br/>Second column content<br/><br/> [/fourcol_one]<br/>[fourcol_one] <br/><br/>Third column content<br/><br/> [/fourcol_one]<br/>[fourcol_one_last] <br/><br/>Fourth column content<br/><br/> [/fourcol_one_last]<br/>[/column]");
								a.addImmediate(b,"5 Columns","[column]<br/>[fivecol_one] <br/><br/>First column content<br/><br/> [/fivecol_one]<br/>[fivecol_one] <br/><br/>Second column content<br/><br/> [/fivecol_one]<br/>[fivecol_one] <br/><br/>Third column content<br/><br/> [/fivecol_one]<br/>[fivecol_one] <br/><br/>Fourth column content<br/><br/> [/fivecol_one]<br/>[fivecol_one_last] <br/><br/>Fifth column content<br/><br/> [/fivecol_one_last]<br/>[/column]");
								a.addImmediate(b,"6 Columns","[column]<br/>[sixcol_one] <br/><br/>First column content<br/><br/> [/sixcol_one]<br/>[sixcol_one] <br/><br/>Second column content<br/><br/> [/sixcol_one]<br/>[sixcol_one] <br/><br/>Third column content<br/><br/> [/sixcol_one]<br/>[sixcol_one] <br/><br/>Fourth column content<br/><br/> [/sixcol_one]<br/>[sixcol_one] <br/><br/>Fifth column content<br/><br/> [/sixcol_one]<br/>[sixcol_one_last] <br/><br/>Sixth column content<br/><br/> [/sixcol_one_last]<br/>[/column]");
							
							});
						return d
					
					} // End IF Statement
					
					return null
				},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
				
			}
		);
		
		tinymce.PluginManager.add( "PrimaShortcodesColumns", tinymce.plugins.PrimaShortcodesColumns);
	}
)();