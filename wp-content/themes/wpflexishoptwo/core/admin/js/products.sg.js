(
	function(){
	
		var url = '';
	
		tinymce.create(
			"tinymce.plugins.PrimaShortcodesProducts",
			{
				init: function(d,e) {
					url = e;
				},
				createControl:function(d,e)
				{
				
					if(d=="prima_shortcodes_products_button"){
					
						d=e.createMenuButton( "prima_shortcodes_products_button",{
							title: "Insert Products Shortcode",
							image: url + '/images/products.png',
							icons: false
							});
							
							var a=this;d.onRenderMenu.add(function(c,b){
								
								c=b.addMenu({title:"Recent Products"});
									a.addImmediate(c,"Recent Products",'[prima_products title="Recent Products" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Recent Products (Custom Image Size)",'[prima_products title="Recent Products" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Recent Products (Complete Options)",'[prima_products title="Recent Products" numbers="4" columns="4" orderby="date" order="desc" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
								c=b.addMenu({title:"Random Products"});
									a.addImmediate(c,"Random Products",'[prima_products title="Random Products" numbers="4" columns="4" orderby="rand"]<br/><br/>');
									a.addImmediate(c,"Random Products (Custom Image Size)",'[prima_products title="Random Products" numbers="4" columns="4" orderby="rand" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Random Products (Complete Options)",'[prima_products title="Random Products" numbers="4" columns="4" orderby="rand" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
								c=b.addMenu({title:"Best Sellers Products"});
									a.addImmediate(c,"Best Sellers Products",'[prima_bestsellers_products title="Best Sellers Products" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Best Sellers Products (Custom Image Size)",'[prima_bestsellers_products title="Best Sellers Products" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Best Sellers Products (Complete Options)",'[prima_bestsellers_products title="Best Sellers Products" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
								c=b.addMenu({title:"Featured Products"});
									a.addImmediate(c,"Featured Products",'[prima_featured_products title="Featured Products" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Featured Products (Custom Image Size)",'[prima_featured_products title="Featured Products" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Featured Products (Complete Options)",'[prima_featured_products title="Featured Products" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
								c=b.addMenu({title:"On Sale Products"});
									a.addImmediate(c,"On Sale Products",'[prima_onsale_products title="On Sale Products" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"On Sale Products (Custom Image Size)",'[prima_onsale_products title="On Sale Products" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"On Sale Products (Complete Options)",'[prima_onsale_products title="On Sale Products" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
								c=b.addMenu({title:"Products In A Category"});
									a.addImmediate(c,"Products In A Category",'[prima_products_in_category title="Products In A Category" category="replace_with_category_slug" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Products In A Category (Custom Image Size)",'[prima_products_in_category title="Products In A Category" category="replace_with_category_slug" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Products In A Category (Complete Options)",'[prima_products_in_category title="Products In A Category" category="replace_with_category_slug" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
								c=b.addMenu({title:"Products In A Tag"});
									a.addImmediate(c,"Products In A Tag",'[prima_products_in_tag title="Products In A Tag" category="replace_with_tag_slug" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Products In A Tag (Custom Image Size)",'[prima_products_in_tag title="Products In A Tag" category="replace_with_tag_slug" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Products In A Tag (Complete Options)",'[prima_products_in_tag title="Products In A Tag" category="replace_with_tag_slug" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
								c=b.addMenu({title:"Products With IDs"});
									a.addImmediate(c,"Products With IDs",'[prima_products_with_ids title="Products With IDs" ids="1,2,3,4" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Products With IDs (Custom Image Size)",'[prima_products_with_ids title="Products With IDs" ids="1,2,3,4" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Products With IDs (Complete Options)",'[prima_products_with_ids title="Products With IDs" ids="1,2,3,4" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
								c=b.addMenu({title:"Products With SKUs"});
									a.addImmediate(c,"Products With SKUs",'[prima_products_with_skus title="Products With SKUs" skus="SKU01,SKU02,SKU03,SKU04" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Products With SKUs (Custom Image Size)",'[prima_products_with_skus title="Products With SKUs" skus="SKU01,SKU02,SKU03,SKU04" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Products With SKUs (Complete Options)",'[prima_products_with_skus title="Products With SKUs" skus="SKU01,SKU02,SKU03,SKU04" numbers="4" columns="4" image_width="150" image_height="150" image_crop="yes" product_saleflash="yes" product_title="yes" product_price="yes" product_button="no"]<br/><br/>');
									
								b.addSeparator();
								
								c=b.addMenu({title:"Product Categories"});
									a.addImmediate(c,"Product Categories",'[prima_product_categories title="Product Categories" numbers="" columns="4"]<br/><br/>');
									a.addImmediate(c,"Product Categories (show 4 only)",'[prima_product_categories title="Product Categories" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Product Categories (Custom Image Size)",'[prima_product_categories title="Product Categories" numbers="" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Product Categories (Complete Options)",'[prima_product_categories title="Product Categories" numbers="" columns="4" image_width="150" image_height="150" image_crop="yes" show_title="yes" show_count="yes"]<br/><br/>');
									a.addImmediate(c,"Top Product Categories",'[prima_product_categories title="Top Product Categories" parent="0" numbers="" columns="4"]<br/><br/>');
									a.addImmediate(c,"Child Product Categories (Parent ID=1)",'[prima_product_categories title="Child Product Categories (Parent ID=1)" parent="1" numbers="" columns="4"]<br/><br/>');
									
								c=b.addMenu({title:"Product Attributes"});
									a.addImmediate(c,"Product Attributes",'[prima_product_attributes title="Product Attributes" attribute="replace_with_attribute_slug" numbers="" columns="4"]<br/><br/>');
									a.addImmediate(c,"Product Attributes (show 4 only)",'[prima_product_attributes title="Product Attributes" attribute="replace_with_attribute_slug" numbers="4" columns="4"]<br/><br/>');
									a.addImmediate(c,"Product Attributes (Custom Image Size)",'[prima_product_attributes title="Product Attributes" attribute="replace_with_attribute_slug" numbers="" columns="4" image_width="150" image_height="150" image_crop="yes"]<br/><br/>');
									a.addImmediate(c,"Product Attributes (Complete Options)",'[prima_product_attributes title="Product Attributes" attribute="replace_with_attribute_slug" numbers="" columns="4" image_width="150" image_height="150" image_crop="yes" show_title="yes" show_count="yes"]<br/><br/>');
									
							});
						return d
					
					} // End IF Statement
					
					return null
				},
		
				addImmediate:function(d,e,a){d.add({title:e,onclick:function(){tinyMCE.activeEditor.execCommand( "mceInsertContent",false,a)}})}
				
			}
		);
		
		tinymce.PluginManager.add( "PrimaShortcodesProducts", tinymce.plugins.PrimaShortcodesProducts);
	}
)();