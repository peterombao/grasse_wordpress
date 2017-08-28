<?php
if( is_singular() ) return;

global $wp_query;

if( $wp_query->max_num_pages <= 1 ) return;

$prev_link = get_previous_posts_link( __( '&larr; Previous Page', 'primathemes' ) );
$prev_link = $prev_link ? '<div class="alignleft">' . $prev_link . '</div>' : '';

$next_link = get_next_posts_link( __( 'Next Page &rarr;', 'primathemes' ) );
$next_link = $next_link ? '<div class="alignright">' . $next_link . '</div>' : '';
?>

<?php if ( $prev_link || $next_link ) : ?>
  <nav id="nav-prevnext" class="navigation clearfix">
	<h3 class="assistive-text"><?php _e( 'Post navigation', 'primathemes' ); ?></h3>
	<?php echo $prev_link; ?>
	<?php echo $next_link; ?>
  </nav>
<?php endif; ?>