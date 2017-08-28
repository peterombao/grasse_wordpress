<?php
/**
 * The template for displaying Product Taxonomy Archive pages.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */
global $wp_query;

$curr_id = get_the_ID();
$is_single = is_single($curr_id);
if($is_single){ //single page

	$post_term = wp_get_post_terms( $curr_id, 'product_cat'); 
	$taxonomy_base = isset($post_term[0]->taxonomy) ? $post_term[0]->taxonomy : null;
	$search_key = isset($post_term[0]->slug) ? $post_term[0]->slug : null;
}else{
	$taxonomy_base = $wp_query->query_vars['taxonomy'];
	$search_key = get_query_var($taxonomy_base);
}

$term = get_term_by( 'slug', $search_key, $taxonomy_base);

$post_type = 'product';
$valid_tax = array(	
							'pa_cap' => array('name' => 'pa_cap', 'label' => 'Caps'), 
							'pa_size' => array('name' => 'pa_size', 'label' => 'Sizes'),
							'pa_size-ml' => array('name' => 'pa_size-ml', 'label' => 'Size(ml)'),
							'pa_size-g' => array('name' => 'pa_size-g', 'label' => 'Size(g)')
						); //term slug
							
require_once(PRIMA_DIR.'/functions/ev_tax_selectbox_init.php');
$tax_select_box = ev_tax_selectbox_init($valid_tax);

if(isset($_GET['action'])){
	if($_GET['action'] == 'filter'){
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		
		$arr = array();
		foreach($valid_tax as $valid_tax_key => $valid_tax_val){

			if(isset($_GET[$valid_tax_val['name']])){
				if(trim($_GET[$valid_tax_val['name']]) != ''){
					$arr[] =array('taxonomy' => $valid_tax_val['name'], 'terms' => $_GET[$valid_tax_val['name']], 'field' => 'slug');
				}
			}
			
		}
	
		$arr[] =array('taxonomy' => $term->taxonomy, 'terms' =>$term->slug, 'field' => 'slug');
		
		$filter_args = array(
			'post_type' => $post_type,
			//'paged' => $paged,
			'tax_query' => $arr,
			'post_status' => 'publish'
		);
		

		$fileter_query = new WP_Query( $filter_args );
	}
}

//get_header(); ?>
  
    <div class="content-wrap clearfix">
  
	<div id="content" class="clearfix">
		
		<form method="GET" action="<?php echo  get_term_link( $term->slug, $taxonomy_base ); ?>" class="form_inline">
			
			<?php echo $tax_select_box; ?>
			<div class="form_controls">
				<input type="hidden" name="action" value="filter" />
				<input type="submit" value="Filter" name="submit" class="button"/>
				<?php if(isset($_GET['submit'])): ?>
					<a class="button mute_button" href="<?php echo  get_term_link( $term->slug, $taxonomy_base ); ?>">Reset</a>
				<?php endif; ?>
			</div>
			
		</form>
		
		<div class="clearfix"></div>
	
		<?php if(isset($_GET['action'])): ?>
		
			<?php if($_GET['action'] == 'filter'): ?>
		
				<?php query_posts($filter_args); ?>
				
				<?php get_template_part( 'ev_filter_loop', 'ev_filter_loop' ); ?>
				
			<?php endif; ?>
			
		<?php else: ?>
			<?php do_action('woocommerce_before_main_content'); ?>

				<?php woocommerce_get_template_part( 'loop', 'shop' ); ?>

				<?php do_action('woocommerce_pagination'); ?>

			<?php do_action('woocommerce_after_main_content'); ?>
		<?php endif; ?>
	</div>
	
	<?php //prima_sidebar(); ?>
	
	</div>
	
	<?php// prima_sidebar( 'mini' ); ?>
	


<?php// get_footer(); ?>