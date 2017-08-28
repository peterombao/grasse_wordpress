<?php
function prima_productsearch( $id = 'searchform', $post_type = 'product' ) {
?>
	<form role="search" method="get" id="<?php echo $id ?>" action="<?php echo esc_url( home_url() ); ?>">
		<div>
			<label class="screen-reader-text" for="s"><?php _e('Search for:', 'primathemes'); ?></label>
			<input type="text" value="<?php the_search_query(); ?>" name="s" class="searchinput" placeholder="<?php _e('Search', 'primathemes'); ?>" />
			<input type="submit" class="searchsubmit" value="<?php _e('Search', 'primathemes'); ?>" />
			<input type="hidden" name="post_type" value="<?php echo $post_type ?>" />
		</div>
	</form>
<?php
}

add_action('wp_footer', 'home_footer_scripts'); 
function home_footer_scripts(){
	//$theme_url = get_bloginfo('template_url');
	//wp_enqueue_script('responsive-slider', $theme_url. '/js/responsive-slider.js',array( 'jquery' ));
	//echo "<script type='text/javascript' src='{$theme_url}/js/responsive-slider.js'></scirpt>";
}

function prima_producttax_image( $args = array() ) {
	echo prima_get_producttax_image( $args );
}

function prima_get_producttax_image( $args = array() ) {
	$defaults = array(
		'term_id' => false,
		'link_to' => false,
		'size' => false,
		'width' => 150,
		'height' => 150,
		'crop' => true,
		'default_image' => false,
		'image_class' => false,
		'output' => 'image',
		'before' => '',
		'after' => '',
		'link_to_post' => false,
		'meta_key' => false,
		'attachment' => false,
		'the_post_thumbnail' => false,
	);
	$args = wp_parse_args( $args, $defaults );
	if ( !$args['term_id'] )
		return false;
	$args['image_id'] = get_woocommerce_term_meta( $args['term_id'], 'thumbnail_id', true );
	if ( !$args['image_id'] )
		return false;
	return prima_get_image( $args );
}

if ( !is_admin() || defined('DOING_AJAX') ) :
if ( class_exists( 'WC_Query' ) ) :
class Prima_Attr_Query extends WC_Query {
	function pre_get_posts( $q ) {
	    if ( !is_main_query() || !$q->is_tax ) 
			return;
		$term = get_queried_object();
		if ( !isset($term->taxonomy) ) 
			return;
		if ( strpos($term->taxonomy, 'pa_') !== 0 ) 
			return;
	    $this->product_query( $q );
	    add_action('wp', array( &$this, 'get_products_in_view' ), 2);
	    remove_filter( 'pre_get_posts', array( &$this, 'pre_get_posts') );
	}
}
$Prima_Attr_Query = new Prima_Attr_Query();
endif;
endif;