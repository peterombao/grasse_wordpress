/**
 * This Scripts control Theme Settings Page
 * Dependencies: jQuery
 * Credits: GenesisFramework, WooFramework, OptionTree, Options Framework
 */
jQuery(document).ready( function($) {
    var isUnsaved = false, $prima_settings = $('#prima-settings');
	$('.button-reset').click(function() {
        return prima_confirm(prima.warnReset);
    })
    // Add dirty flag when we change an option
    $('input, select', $prima_settings).change(function() {
        isUnsaved = true;
    })
    // Remove dirty flag when we save options
    $('form', $prima_settings).submit (function() {
       isUnsaved = false; 
    });
    // Give warning if value changed and we try to leave the page before saving.
    $(window).bind('beforeunload', function(){
        if (isUnsaved) {
            return prima.warnUnsaved;
        }
    });
	var flip = 0;
	jQuery('#expand_options').click(function(){
		if(flip == 0){
			flip = 1;
			jQuery('#prima_container #prima-nav').hide();
			jQuery('#prima_container #prima-content').width(785);
			jQuery('#prima_container .group').add('#prima_container .group h2').show();
			jQuery(this).text('[-]');
		} else {
			flip = 0;
			jQuery('#prima_container #prima-nav').show();
			jQuery('#prima_container #prima-content').width(595);
			jQuery('#prima_container .group').add('#prima_container .group h2').hide();
			jQuery('#prima_container .group:first').show();
			jQuery('#prima_container #prima-nav li').removeClass('current');
			jQuery('#prima_container #prima-nav li:first').addClass('current');
			jQuery(this).text('[+]');
		}
	});
	jQuery('.group').hide();
	jQuery('.group:first').fadeIn();
	jQuery('.group .collapsed').each(function(){
		jQuery(this).find('input:checked').parent().parent().parent().nextAll().each( 
			function(){
				if (jQuery(this).hasClass('last')) {
					jQuery(this).removeClass('hidden');
					return false;
				}
				jQuery(this).filter('.hidden').removeClass('hidden');
			});
	});
	jQuery('.group .collapsed input:checkbox').click(unhideHidden);
	function unhideHidden(){
		if (jQuery(this).attr('checked')) {
			jQuery(this).parent().parent().parent().nextAll().removeClass('hidden');
		}
		else {
			jQuery(this).parent().parent().parent().nextAll().each( 
				function(){
					if (jQuery(this).filter('.last').length) {
						jQuery(this).addClass('hidden');
						return false;
					}
					jQuery(this).addClass('hidden');
				});
				
		}
	}
	// jQuery DatePicker
	jQuery('.prima-input-calendar').each(function (){
		jQuery('#' + jQuery(this).attr('id')).datepicker({showOn: 'button', buttonImage: prima.primaAdminUri + '/images/calendar.gif', buttonImageOnly: true});
	});
	jQuery('.prima-radio-img-img').click(function(){
		jQuery(this).parent().parent().find('.prima-radio-img-img').removeClass('prima-radio-img-selected');
		jQuery(this).addClass('prima-radio-img-selected');
		
	});
	jQuery('.prima-radio-img-label').hide();
	jQuery('.prima-radio-img-img').show();
	jQuery('.prima-radio-img-radio').hide();
	jQuery('#prima-nav li:first').addClass('current');
	jQuery('#prima-nav li a').click(function(evt){
		jQuery('#prima-nav li').removeClass('current');
		jQuery(this).parent().addClass('current');
		var clicked_group = jQuery(this).attr('href');
		jQuery('.group').hide();
		jQuery(clicked_group).fadeIn();
		evt.preventDefault();
	});
	jQuery('select.prima-typography-unit').change(function(){
		var val = jQuery(this).val();
		var parent = jQuery(this).parent();
		var name = parent.find('.prima-typography-size-px').attr('name');
		if(name == ''){ var name = parent.find('.prima-typography-size-em').attr('name'); } 
		if(val == 'px'){ 
			parent.find('.prima-typography-size-em').hide().removeAttr('name');
			parent.find('.prima-typography-size-px').show().attr('name',name);
		}
		else if(val == 'em'){
			parent.find('.prima-typography-size-em').show().attr('name',name);
			parent.find('.prima-typography-size-px').hide().removeAttr('name');
		}
		
	});
	// jQuery Time Input Mask
	jQuery('.prima-input-time').each(function (){
		jQuery('#' + jQuery(this).attr('id')).mask("99:99");
	});
});
/**
 * Confirmation
 * Dependencies: 
 */
function prima_confirm(text){
	var answer=confirm(text);
	if(answer){return true;}
	else{return false;}
}
/**
 * Style Select (Replace Select text)
 * Dependencies: jQuery
 */
(function ($) {
  styleSelect = {
    init: function () {
      $('.select_wrapper').each(function () {
        $(this).prepend('<span>' + $(this).find('.prima-input option:selected').text() + '</span>');
      });
      $('.prima-input').live('change', function () {
        $(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
      });
      $('.prima-input').bind($.browser.msie ? 'click' : 'change', function(event) {
        $(this).prev('span').replaceWith('<span>' + $(this).find('option:selected').text() + '</span>');
      }); 
    }
  };
})(jQuery);
jQuery(document).ready(function() { 
	styleSelect.init();
});
/**
 * Upload Option
 * Allows window.send_to_editor to function properly using a private post_id
 * Dependencies: jQuery, Media Upload, Thickbox
 * Credits: OptionTree
 */
(function ($) {
  uploadOption = {
    init: function () {
      var formfield,
          formID,
          btnContent = true;
      // On Click
      $('.upload_button').live("click", function () {
        formfield = $(this).prev('input').attr('id');
        formID = $(this).attr('rel');
        // Display a custom title for each Thickbox popup.
        var prima_title = '';
        if ( $(this).parents('.section').find('.heading') ) { prima_title = $(this).parents('.section').find('.heading').text(); } // End IF Statement
        tb_show( prima_title, 'media-upload.php?post_id='+formID+'&type=image&amp;is_primathemes=1&amp;TB_iframe=1');
        return false;
      });
            
      window.original_send_to_editor = window.send_to_editor;
      window.send_to_editor = function(html) {
        if (formfield) {
          if ( $(html).html(html).find('img').length > 0 ) {
          	itemurl = $(html).html(html).find('img').attr('src');
          } 
          else {
          	var htmlBits = html.split("'");
          	itemurl = htmlBits[1];
          	var itemtitle = htmlBits[2];
          	itemtitle = itemtitle.replace( '>', '' );
          	itemtitle = itemtitle.replace( '</a>', '' );
          }
          var image = /(^.*\.jpg|jpeg|png|gif|ico*)/gi;
          var document = /(^.*\.pdf|doc|docx|ppt|pptx|odt*)/gi;
          var audio = /(^.*\.mp3|m4a|ogg|wav*)/gi;
          var video = /(^.*\.mp4|m4v|mov|wmv|avi|mpg|ogv|3gp|3g2*)/gi;
          if (itemurl.match(image)) {
            btnContent = '<img src="'+itemurl+'" alt="" /><a href="#" class="remove">Remove Image</a>';
          } else {
            btnContent = '<div class="no_image">'+html+'<a href="#" class="remove">Remove</a></div>';
          }
          $('#' + formfield).val(itemurl);
          $('#' + formfield).next().next('div').slideDown().html(btnContent);
          tb_remove();
        } else {
          window.original_send_to_editor(html);
        }
      }
    }
  };
  $(document).ready(function () {
	  uploadOption.init();
      // Remove Uploaded Image
      $('.remove').live('click', function(event) { 
        $(this).hide();
        $(this).parents().prev().prev('.upload').attr('value', '');
        $(this).parents('.screenshot').slideUp();
      });
  })
})(jQuery);
