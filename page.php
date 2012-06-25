<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
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
								<div id="left-post-content">
								
									<div class="entry-meta">
										<br class="clear" />
										
									</div><!-- end entry-meta -->
									
									<div class="entry-content">
										
										
										
										<div id="entrada">
		
											<h3 class="entry-title">
												<a rel="bookmark" title="Permalink" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
											
											<?php the_content(); ?>		
										</div><!-- end entrada -->
										
										<div class="social">
											<ul>
												<li><a href="#" title="Twitter"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-tt-small.gif" alt="Icono" width="16" height="16" /> <span class="skip">Twitter</span></a></span></li>
												<li><a href="#" title="Delicious"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-del-small.gif" alt="Icono" width="16" height="16" /> <span class="skip">Delicious</a></span></li>
												<li><a href="http://www.facebook.com/comunitatvalenciana" title="Facebook"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-fb-small.gif" alt="Icono" width="16" height="16" /> <span class="skip">Facebook</a></span></li>
												<li><a href="mailto:robles_dan@gva.es" title="Email"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-mail-small.gif" alt="Icono" width="16" height="16" /> <span class="skip">Email</span></a></li>
												<li><a href="http://meneame.net/submit.php?ur<?php the_permalink(); ?>" title="Menéame"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-meneame.jpg" alt="Icono" width="16" height="16" /> <span class="skip">Menéame</span></a></li>
												<li><a href="http://digg.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" title="Digg"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-digg.jpg" alt="Icono" width="16" height="16" /> <span class="skip">Digg</span></a></li>
												<li><a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" title="Stumbleupon"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-stumbleupon.jpg" alt="Icono" width="16" height="16" /> <span class="skip">Stumbleupon</span></a></li>
												<li><a href="mailto:robles_dan@gva.es" title="Email"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-tumblr.jpg" alt="Icono" width="16" height="16" /> <span class="skip">Email</span></a></li>
											</ul>
										</div><!-- end social -->
									</div><!-- end entry-content -->
								
								</div><!-- end left-post-content -->




<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
