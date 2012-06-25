<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
			
<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>		
				<div id="main" class="left-align">
					<div id="container">
						
						<div id="content">
							<div class="navigation skip" id="nav-above">
								<div class="nav-previous"><?php previous_post_link('<strong>%link</strong>'); ?></div>
								<div class="nav-next"><?php next_post_link('<strong>%link</strong>'); ?> </div>
							</div><!-- end nav-above -->
							
							<div class="post" id="post">

								<div id="left-post-content">
									<h2 class="titulo-archivo">
										<?php if ( is_day() ) : ?>
											<?php printf( __( 'Artículos del: <span>%s</span>', 'twentyten' ), strtolower(get_the_date()) ); ?>
										<?php elseif ( is_month() ) : ?>
											<?php printf( __( 'Artículos de: <span>%s</span>', 'twentyten' ), strtolower(get_the_date('F Y')) ); ?>
										<?php elseif ( is_year() ) : ?>
											<?php printf( __( 'Artículos de: <span>%s</span>', 'twentyten' ), get_the_date('Y') ); ?>
										<?php else : ?>
											<?php _e( 'Blog Archives', 'twentyten' ); ?>
										<?php endif; ?>
									</h2>
									<?php
										/* Since we called the_post() above, we need to
	 									* rewind the loop back to the beginning that way
	 									* we can run the loop properly, in full.
	 									*/
										rewind_posts();

										/* Run the loop for the archives page to output the posts.
	 									* If you want to overload this in a child theme then include a file
	 									* called loop-archives.php and that will be used instead.
	 									*/
				 						get_template_part( 'loop', 'archive' );
									?>
									
								<div id="div-paginacion-archivo">
									<div class="separator-h">
										<hr />
									</div><!-- end separator-h -->
							
									<div id="div-paginacion-archive"><?php wp_pagenavi(); ?></div>
								</div>
							</div><!-- end left-post-content -->
															
<?php get_sidebar(); ?>
<?php get_footer(); ?>