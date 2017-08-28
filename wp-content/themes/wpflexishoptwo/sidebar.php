<aside id="sidebar" class="sidebar">
  <?php if ( is_active_sidebar(prima_get_sidebar('sidebar')) ) : ?> 
    <?php prima_dynamic_sidebar( 'sidebar' ); ?>
  <?php else : ?>
    <div class="widget widget-container widget-sidebar widget_text">
      <h3><?php echo prima_dynamic_sidebar_name('sidebar'); ?></h3>
      <div class="textwidget">
      <?php printf(__('This is <strong>"%1$s"</strong> widget area. Visit your <a href="%2$s">Widgets Page</a> to add new widget to this area.', 'primathemes'), prima_dynamic_sidebar_name('sidebar'), admin_url('widgets.php')); ?>
      </div>
    </div>
  <?php endif; ?>
</aside>
