<?php
/**
 * The template for displaying Comments.
 *
 * @package WordPress
 * @subpackage WPFlexiShop_Two
 * @since WP FlexiShop Two 1.0
 */
?>

<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'primathemes' ); ?></p>
	<?php return; ?>
<?php endif; ?>
	
<?php if ( comments_open() &&  post_type_supports( get_post_type(), 'comments' ) ) : ?>

    <section id="comments">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="horizontalheading"><span>
			<?php
				printf( _n( 'One comment', '%1$s comments', get_comments_number(), 'primathemes' ),
					number_format_i18n( get_comments_number() ) );
			?>
		</span></h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above">
			<h3 class="assistive-text"><?php _e( 'Comment navigation', 'primathemes' ); ?></h3>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'primathemes' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'primathemes' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

		<ol class="commentlist">
			<?php wp_list_comments(); ?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below">
			<h3 class="assistive-text"><?php _e( 'Comment navigation', 'primathemes' ); ?></h3>
			<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'primathemes' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'primathemes' ) ); ?></div>
		</nav>
		<?php endif; // check for comment navigation ?>

	<?php endif; ?>

	<?php comment_form(); ?>

	</section><!-- #comments -->

<?php endif; ?>
