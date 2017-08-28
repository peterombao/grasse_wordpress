(function() {
	tinymce.create('tinymce.plugins.PrimaShortcodesSlider', {
	
		init : function(ed, url) {

			ed.addCommand('prima_shortcodes_slider_button', function() {
				ed.windowManager.open({
					file : url + '/slider.window.php',
					width : 450,
					height : 300,
					inline : 1
				}, {
					plugin_url : url
				});
			});

			ed.addButton('prima_shortcodes_slider_button', {
				title : 'Add a slider shortcode',
				cmd : 'prima_shortcodes_slider_button',
				image : url + '/images/slider.png'
			});

			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('prima_shortcodes_slider_button', n.nodeName == 'IMG');
			});
		},

		createControl : function(n, cm) {
			return null;
		},
	});

	tinymce.PluginManager.add('PrimaShortcodesSlider', tinymce.plugins.PrimaShortcodesSlider);
	
})();


