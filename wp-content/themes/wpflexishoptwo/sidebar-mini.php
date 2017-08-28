<aside id="sidebarmini" class="sidebar">
  <?php if ( is_active_sidebar(prima_get_sidebar( 'sidebarmini', 'mini' )) ) : ?> 
    <?php prima_dynamic_sidebar( 'sidebarmini', 'mini' ); ?>
  <?php else : ?>
    <div class="widget widget-container widget-sidebar widget_text">
      <h3><?php echo prima_dynamic_sidebar_name( 'sidebarmini', 'mini' ); ?></h3>
      <div class="textwidget">
      <?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), prima_dynamic_sidebar_name( 'sidebarmini', 'mini' ), admin_url('widgets.php')); ?>
      </div>
    </div>
  <?php endif; ?>
</aside>
