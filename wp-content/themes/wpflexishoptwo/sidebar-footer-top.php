
  <div id="footertop" class="clearfix">
	<div class="margin clearfix">
	
	<?php if( (int)prima_get_setting('footer_top_layout') >= 10 ) : ?>
	  <div class="footertop1">
	  <?php if ( is_active_sidebar( 'footer-top-1' ) ) : ?> 
		<?php dynamic_sidebar( 'footer-top-1' ); ?>
	  <?php else : ?>
		<div class="widget widget-container widget_text">
		  <h3 class="widget-title"><?php _e('Footer Top #1', 'primathemes'); ?></h3>
		  <div class="textwidget">
		  <p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), __('Footer Top #1', 'primathemes'), admin_url('widgets.php')); ?></p>
		  </div>
		</div>
	  <?php endif; ?>
	  &nbsp; <!-- spacer -->
	  </div>
	<?php endif; ?>
	
	<?php if( (int)prima_get_setting('footer_top_layout') >= 20 ) : ?>
	  <div class="footertop2">
	  <?php if ( is_active_sidebar( 'footer-top-2' ) ) : ?> 
		<?php dynamic_sidebar( 'footer-top-2' ); ?>
	  <?php else : ?>
		<div class="widget widget-container widget_text">
		  <h3 class="widget-title"><?php _e('Footer Top #2', 'primathemes'); ?></h3>
		  <div class="textwidget">
		  <p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), __('Footer Top #2', 'primathemes'), admin_url('widgets.php')); ?></p>
		  </div>
		</div>
	  <?php endif; ?>
	  &nbsp; <!-- spacer -->
	  </div>
	<?php endif; ?>
	
	<?php if( (int)prima_get_setting('footer_top_layout') >= 30 ) : ?>
	  <div class="footertop3">
	  <?php if ( is_active_sidebar( 'footer-top-3' ) ) : ?> 
		<?php dynamic_sidebar( 'footer-top-3' ); ?>
	  <?php else : ?>
		<div class="widget widget-container widget_text">
		  <h3 class="widget-title"><?php _e('Footer Top #3', 'primathemes'); ?></h3>
		  <div class="textwidget">
		  <p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), __('Footer Top #3', 'primathemes'), admin_url('widgets.php')); ?></p>
		  </div>
		</div>
	  <?php endif; ?>
	  &nbsp; <!-- spacer -->
	  </div>
	<?php endif; ?>
	
	<?php if( (int)prima_get_setting('footer_top_layout') >= 40 ) : ?>
	  <div class="footertop4">
	  <?php if ( is_active_sidebar( 'footer-top-4' ) ) : ?> 
		<?php dynamic_sidebar( 'footer-top-4' ); ?>
	  <?php else : ?>
		<div class="widget widget-container widget_text">
		  <h3 class="widget-title"><?php _e('Footer Top #4', 'primathemes'); ?></h3>
		  <div class="textwidget">
		  <p><?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), __('Footer Top #4', 'primathemes'), admin_url('widgets.php')); ?></p>
		  </div>
		</div>
	  <?php endif; ?>
	  &nbsp; <!-- spacer -->
	  </div>
	<?php endif; ?>
	
	</div>
  </div>
  
