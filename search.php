<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

		<div id="main" class="left-align">
					<div id="container">
						
						<div id="content">
							
							<div class="post" id="post">

								<div id="left-post-content">
									

<?php if ( have_posts() ) : ?>
				<h2 class="titulo-archivo">
					<?php printf( __( 'Resultados obtenidos sobre: %s', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?>
				</h2>
				
				<?php
				/* Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called loop-search.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'archive' );
				?>
<?php else : ?>
				<h2 class="titulo-archivo">
					<?php _e( 'Sin resultados', 'twentyten' ); ?>
				</h2>
						
					<div class="entry-content">
						<div id="entrada">
							<p><?php _e( 'Lo sentimos, pero no hemos obtenido resultados para su criterio de búsqueda. Por favor, inténtelo de nuevo con diferentes palabras.', 'twentyten' ); ?></p>
						</div>
						<div class="social" style="margin-bottom:0;">
							<ul>
								<li>								
									<?php get_search_form(); ?>
								</li>
							</ul>
							
						</div><!-- end social -->
					</div>
				</div>
				
				
<?php endif; ?>
								</div><!-- end left-post-content -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
