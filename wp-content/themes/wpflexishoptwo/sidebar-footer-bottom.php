
  <div id="footerbottom" class="clearfix">
	<div class="margin clearfix">
	
	<?php if( (int)prima_get_setting('footer_bottom_layout') >= 10 ) : ?>
	  <div class="footerbottom1">
	  <?php if ( is_active_sidebar( 'footer-bottom-1' ) ) : ?> 
		<?php dynamic_sidebar( 'footer-bottom-1' ); ?>
	  <?php else : ?>
		<div class="widget widget-container widget_text">
		  <h3 class="widget-title"><?php _e('Footer Bottom #1', 'primathemes'); ?></h3>
		  <div class="textwidget">
		  <p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), __('Footer Bottom #1', 'primathemes'), admin_url('widgets.php')); ?></p>
		  </div>
		</div>
	  <?php endif; ?>
	  &nbsp; <!-- spacer -->
	  </div>
	<?php endif; ?>
	
	<?php if( (int)prima_get_setting('footer_bottom_layout') >= 20 ) : ?>
	  <div class="footerbottom2">
	  <?php if ( is_active_sidebar( 'footer-bottom-2' ) ) : ?> 
		<?php dynamic_sidebar( 'footer-bottom-2' ); ?>
	  <?php else : ?>
		<div class="widget widget-container widget_text">
		  <h3 class="widget-title"><?php _e('Footer Bottom #2', 'primathemes'); ?></h3>
		  <div class="textwidget">
		  <p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), __('Footer Bottom #2', 'primathemes'), admin_url('widgets.php')); ?></p>
		  </div>
		</div>
	  <?php endif; ?>
	  &nbsp; <!-- spacer -->
	  </div>
	<?php endif; ?>
	
	<?php if( (int)prima_get_setting('footer_bottom_layout') >= 30 ) : ?>
	  <div class="footerbottom3">
	  <?php if ( is_active_sidebar( 'footer-bottom-3' ) ) : ?> 
		<?php dynamic_sidebar( 'footer-bottom-3' ); ?>
	  <?php else : ?>
		<div class="widget widget-container widget_text">
		  <h3 class="widget-title"><?php _e('Footer Bottom #3', 'primathemes'); ?></h3>
		  <div class="textwidget">
		  <p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), __('Footer Bottom #3', 'primathemes'), admin_url('widgets.php')); ?></p>
		  </div>
		</div>
	  <?php endif; ?>
	  &nbsp; <!-- spacer -->
	  </div>
	<?php endif; ?>
	
	<?php if( (int)prima_get_setting('footer_bottom_layout') >= 40 ) : ?>
	  <div class="footerbottom4">
	  <?php if ( is_active_sidebar( 'footer-bottom-4' ) ) : ?> 
		<?php dynamic_sidebar( 'footer-bottom-4' ); ?>
	  <?php else : ?>
		<div class="widget widget-container widget_text">
		  <h3 class="widget-title"><?php _e('Footer Bottom #4', 'primathemes'); ?></h3>
		  <div class="textwidget">
		  <p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), __('Footer Bottom #4', 'primathemes'), admin_url('widgets.php')); ?></p>
		  </div>
		</div>
	  <?php endif; ?>
	  &nbsp; <!-- spacer -->
	  </div>
	<?php endif; ?>
	
	</div>
  </div>
  
