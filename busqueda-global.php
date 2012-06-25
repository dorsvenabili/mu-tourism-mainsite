<?php
/*
	Template Name: Búsqueda global
 */
 ?>
<?php get_header(); ?>


				<div id="main" class="left-align">
					<div id="container">
						
						<div id="content">
							<div class="navigation skip" id="nav-above">
								<div class="nav-previous"><a href="#"><span class="meta-nav">←</span> Older posts</a></div>
								<div class="nav-next"></div>
							</div><!-- end nav-above -->
						<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

							
							<div class="post" id="post">
								<div id="left-post-content-globalsearch">

											<h2 class="titulo-archivo">
												<a rel="bookmark" title="Permalink" href="<?php the_permalink(); ?>">Resultados en toda la red de blogs</a>
											</h2>
											
											<?php the_content(); ?>		

								</div><!-- end left-post-content -->




<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
