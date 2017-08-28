jQuery(document).ready(function(){

	 
	jQuery('#primary').ev_addClose({trigger : '.mobile_cat_menu'});
	jQuery('#topnavright').ev_addClose({trigger : '.mobile-nav-btn'});
	
	
	jQuery('.ev_faq_li a').ev_scrollTo();
	jQuery('.ev_faq_top').ev_scrollToTop();

});

(function($){

	$.fn.ev_scrollTo = function(options){
	
		jQuery(this).each( function() {
			var $this = $(this);
			
			jQuery($this).click(function(){
				$scroll_to_id = $this.attr('href');
				
				jQuery('html, body').animate(
					{
						scrollTop: jQuery($scroll_to_id).offset().top - 200
					}, {
							duration:500,
							easing : 'easeInQuart',
							complete: function(){
								jQuery($scroll_to_id).addClass('is_focus').delay(1000).queue(function(next){
									$($scroll_to_id).removeClass("is_focus");
									next();
								});
							}
					}
				);	

				return false;
			});
		})
	}

}(jQuery));

(function($){

	$.fn.ev_scrollToTop = function(options){
	
		jQuery(this).each( function() {
			var $this = $(this);
			
			jQuery($this).click(function(){

				jQuery('html, body').animate({
					scrollTop: 0
				}, {	
				
					easing : 'easeInQuart',
					duration : 500,
					complete: function(){
						
					}
				});		
				
				return false;
			
			});
		})
	}

}(jQuery));

(function($) {

    $.fn.ev_addClose = function(options) {
		
		var	defaults = {  
					trigger         : false,
				},  
				settings = $.extend({}, defaults, options);  
		  
		jQuery(this).each( function() {
			var $this = $(this);
			
			if(!jQuery($this).hasClass('is-closed')){
				jQuery($this).addClass('is-closed');
			}		
				
			jQuery(settings.trigger).click(function(){
				
				if(jQuery($this).hasClass('is-closed')){
					jQuery($this).removeClass('is-closed');
					jQuery($this).addClass('is-opened');
				}else{
					jQuery($this).addClass('is-closed');
					jQuery($this).removeClass('is-opened');		
				}
			
				return false;
			})		
		
			
		});

    }

}(jQuery));