<?php 

get_header();
?>				

<?php
    global $switched;
    
    //Mostramos el artículo destacado del blog que hayan seleccionado en el panel de administración: Artículo destacado Opciones, encaso de no haber ninguno, por defecto dejamos el de buceo.
    $mostrar_ult_art_o_destacado = get_option('valencia_modalidad');
    $blog_del_articulo_destacado = get_option('valencia_blog');
    
    $id_del_blog = cargar_id_del_blog_con_ultima_noticia($mostrar_ult_art_o_destacado, $blog_del_articulo_destacado);
    
    switch_to_blog($id_del_blog);
?> 
				
				<div id="main" class="left-align">
					<div id="container">
						
						<div id="content">
							<div class="navigation skip" id="nav-above">
								<div class="nav-previous"><a href="#"><span class="meta-nav">←</span> Older posts</a></div>
								<div class="nav-next"></div>
							</div><!-- end nav-above -->
							
							<?php 
								//Si vamos a mostrar el último artículo de la red, no se debe mostrar el sticky post, sólo el último
								if($mostrar_ult_art_o_destacado == 0):
									$args = array("post__not_in" =>get_option("sticky_posts"), 'posts_per_page' => 1);
								else:
									$args = array('posts_per_page' => 1,'post__in'  => get_option('sticky_posts'),'caller_get_posts' => 1);
								endif;
									query_posts($args);
							?>
							<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
							
							<div class="post" id="post">
								<?php if(is_sticky()): 
											echo '<h2 class="tit-dest">Artículo destacado</h2>'; 
									else: 
											echo '<h2 class="tit-ult">Último artículo</h2>'; 
									endif;
								?>
								
								<div id="left-post-content">

									<div class="entry-meta">
										
										<div class="comments">
											<a title="Comentarios" href="<?php the_permalink(); ?>#comments"><span class="comments-link"><?php comments_number('0', '1', '%'); ?></span></a>
										</div><!-- end comments -->
										
										<div id="author">
											<div class="author-photo">
												<?php userphoto_the_author_thumbnail(); ?>
											</div><!-- end author-photo -->
											<span rel="bookmark" title="fecha" href="<?php echo get_month_link('', ''); ?>" class="date-post">
												<span class="entry-date"><?php $date = get_the_date(); echo strtolower($date); ?></span>
											</span>
											<span class="meta-sep">Escrito por</span> <span class="author vcard"><?php the_author_posts_link(); ?></span>
										</div><!-- end author -->
										<br class="clear" />
										
									</div><!-- end entry-meta -->
									
									<div class="entry-content">
										
										<div id="attachment_8" class="wp-caption alignright" style="width: 200px;">
											<a href="<?php the_permalink(); ?>" title="Teclas">
												<?php the_post_thumbnail('medium'); ?>
											</a>
											<p class="wp-caption-text">
												<?php //Ahora vamos a mostrar la leyenda de la imagen si la tiene
															 $post_thumbnail_id = get_post_thumbnail_id();
															 $post_thumbanil = get_post($post_thumbnail_id);
															 echo $post_thumbanil->post_excerpt;
												?>
											</p>
										</div>
										
										
										<div id="entrada">
		
											<h3 class="entry-title">
												<a rel="bookmark" title="Permalink" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
											</h3>
											
											<?php
												remove_filter('get_the_excerpt', 'wp_trim_excerpt');
												add_filter('get_the_excerpt', 'excerpt_50');
												echo get_the_excerpt();
												remove_filter('get_the_excerpt', 'excerpt_50');
												add_filter('get_the_excerpt', 'wp_trim_excerpt');
											?>
																					
											<div class="read-more-div"><p class="read-more"><a href="<?php the_permalink(); ?>" title="Leer más">leer más</a></p></div>
											
											<br />
											
											<div class="entry-utility">
												<span class="cat-links">
													<span class="entry-utility-prep entry-utility-prep-cat-links">Categorías: </span><?php the_category(', '); ?>
												</span>
												<br />
												<span class="tag-links">
													<span class="entry-utility-prep entry-utility-prep-tag-links">Temas: </span><?php the_tags(' ',', ',' '); ?>
												</span>
											</div><!-- end entry-utility -->
										
										</div><!-- end entrada -->
										
										<?php show_social_icons(); ?>
									</div><!-- end entry-content -->
									
					<?php endwhile; ?>
					
								
								</div><!-- end left-post-content -->
								

<?php restore_current_blog(); ?>							
								
<?php get_sidebar(); ?>
<?php get_footer(); ?>