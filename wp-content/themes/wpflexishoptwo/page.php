<?php 

get_header(); 
$title = get_the_title();
$template_url = get_bloginfo('template_url');
$content = apply_filters('the_content', $post->post_content); 

?>
	<div class="page-header">
		<div class="container">
			<div class="row">
			
				<div class="col-md-12">
				
					<h1 class="page-title product-title"><?php echo $title ?></h1>	
					
				</div>
			
			</div>
		</div>
	</div>
	
	
	<div class="container" id="main-container">
	
		
		
		<div class="row">
		
			<div class="<?php echo woocommerce_get_page_id( 'cart' ) != $post->ID ? 'col-md-9' : 'col-md-12'; ?>">
				
				<div class="<?php echo woocommerce_get_page_id( 'cart' ) != $post->ID ? 'paper' : '' ?>">
				
					<div class="container">

						<div class="row">
							
							<div class="col-md-12">
							
								<?php echo $content ?>
							
							</div>
						
						</div>
					
					</div>
				
				</div>			
			</div>
			
			<?php if(woocommerce_get_page_id( 'cart' ) != $post->ID): ?>
			
				<div class="col-md-3 product-sidebar">
					
					<div class="paper">
					<div class="container">
						<div class="row">
							
							<div class="col-md-12">				
								<?php dynamic_sidebar( 'primary' ); ?>
							</div>
							
						</div>
					</div>
					</div>
				</div>
			
			<?php endif; ?>
		</div>

	
	</div>
	
	
<?php get_footer(); ?>