  <?php $postclass = is_singular() ? '' : 'postblog'; ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class($postclass); ?> >
	<div class="entry">

	  <h2 class="posttitle"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
	  
	  <?php if ( 'post' == get_post_type() ) : ?>
	  <p class="postmeta"><small>
		<?php 
		  printf( __( 'Posted <span class="metadate">on %1$s</span> <span class="metaauthor">by %2$s</span>', 'primathemes' ), get_the_date(), '<a class="url fn n" href="'.get_author_posts_url( get_the_author_meta( 'ID' ) ).'" title="">'.get_the_author_meta( 'display_name' ).'</a>' );
		  if ( comments_open() && ! post_password_required() ) :
			echo '<span class="metacomment"> / ';
			comments_popup_link( __( 'No comments', 'primathemes' ), __( 'One comment', 'primathemes' ), __( '% comment', 'primathemes' ) );
			echo '</span>';
		  endif; 
          edit_post_link( __( 'Edit', 'primathemes' ), ' / ', '' );
		?>
	  </small></p>
	  <?php endif; ?>

	  <div class="postcontent">
	    <?php prima_image( array ( 'width' => 700, 'height' => 275, 'image_class' => 'featuredimage', 'link_to_post' => true ) ); ?>
		<?php the_excerpt(); ?>
	  </div>

	</div>
  </article>