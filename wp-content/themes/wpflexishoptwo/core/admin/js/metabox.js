/**
 * This Scripts control Post Metabox Page
 * Dependencies: jQuery
 * Credits: WooFramework
 */

jQuery(document).ready(function(){
	
	jQuery('form#post').attr('enctype','multipart/form-data');
	jQuery('form#post').attr('encoding','multipart/form-data');
	
	 //JQUERY DATEPICKER
	jQuery('.meta_input_calendar').each(function (){
		jQuery('#' + jQuery(this).attr('id')).datepicker({showOn: 'button', buttonImage: prima.primaAdminUri + '/images/calendar.gif', buttonImageOnly: true});
	});
	
	//JQUERY TIME INPUT MASK
	jQuery('.meta_input_time').each(function (){
		jQuery('#' + jQuery(this).attr('id')).mask("99:99");
	});
	
	//JQUERY CHARACTER COUNTER
	jQuery('.prima-word-count').each(function(){ 
			var s = ''; var s2 = ''; 
			var length = jQuery(this).val().length; 
			var w_length = jQuery(this).val().split(/\b[\s,\.-:;]*/).length; 
			
			if(length != 1) { s = 's';}
			if(w_length != 1){ s2 = 's';} 
			if(jQuery(this).val() == ''){ s2 = 's'; w_length = '0';}
			
			jQuery(this).parent().find('.counter').html( length + ' character'+ s + ', ' + w_length + ' word' + s2);  

			jQuery(this).keyup(function(){  
			var s = ''; var s2 = '';
				var new_length = jQuery(this).val().length; 
				var word_length = jQuery(this).val().split(/\b[\s,\.-:;]*/).length;
				
				if(new_length != 1) { s = 's';} 
				if(word_length != 1){ s2 = 's'}  
				if(jQuery(this).val() == ''){ s2 == 's'; word_length = '0';}
				
				jQuery(this).parent().find('.counter').html( new_length + ' character' + s + ', ' + word_length + ' word' + s2);  
			});  
		});  
	
	jQuery('#prima-meta-box-normal th:first, #prima-meta-box-normal td:first').css('border','0');
	jQuery('#prima-meta-box-template th:first, #prima-meta-box-template td:first').css('border','0');
	jQuery('#prima-meta-box-seo th:first, #prima-meta-box-seo td:first').css('border','0');
	jQuery('#prima-meta-box-side th:first, #prima-meta-box-side td:first').css('border','0');
	jQuery('#prima-meta-box-general th:first, #prima-meta-box-general td:first').css('border','0');
	var val = jQuery('input#title').attr('value');
	if(val == ''){ 
	jQuery('.meta_fields .button-highlighted').after("<em class='meta_red_note'>Please add a Title before uploading a file</em>");
	};
	jQuery('.meta-radio-img-img').click(function(){
			jQuery(this).parent().find('.meta-radio-img-img').removeClass('meta-radio-img-selected');
			jQuery(this).addClass('meta-radio-img-selected');
			
		});
		jQuery('.meta-radio-img-label').hide();
		jQuery('.meta-radio-img-img').show();
		jQuery('.meta-radio-img-radio').hide();
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
        if ( $(this).parents('tr').find('th.meta_names label') ) { prima_title = $(this).parents('tr').find('th.meta_names label').text(); } // End IF Statement
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
/**
 * UploadGallery Option
 * Dependencies: jQuery, Media Upload, Thickbox
 * Credits: OptionTree
 */
(function ($) {
  uploadGalleryOption = {
    init: function () {
      var formfield,
          formID,
          btnContent = true;
      // On Click
      $('.upload_gallery_button').live("click", function () {
        formfield = $(this).prev('input').attr('id');
        formID = $(this).attr('rel');
        // Display a custom title for each Thickbox popup.
        var prima_title = '';
        if ( $(this).parents('tr').find('th.meta_names label') ) { prima_title = $(this).parents('tr').find('th.meta_names label').text(); } // End IF Statement
        tb_show( prima_title, 'media-upload.php?post_id='+formID+'&type=image&amp;is_primathemesgallery=1&amp;TB_iframe=1');
        return false;
      });
    }
  };
  $(document).ready(function () {
	  uploadGalleryOption.init();
  })
})(jQuery);
