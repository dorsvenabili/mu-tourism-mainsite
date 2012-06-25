<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
							
									<div class="entry-meta-listado">
										
										<div class="comments">
											<span class="comments-link"><a title="Comentarios" href="<?php the_permalink(); ?>#comments"><?php comments_number('0', '1', '%'); ?></a></span>
										</div><!-- end comments -->
										
																				<div id="author">
											<div class="author-photo">
												<?php //userphoto_the_author_thumbnail(); ?>
											</div><!-- end author-photo -->
											<span rel="bookmark" title="fecha" href="<?php echo get_month_link('', ''); ?>" class="date-post">
												<span class="entry-date"><?php $date = get_the_date(); echo strtolower($date); ?></span>
											</span>
											<!-- <span class="meta-sep">Escrito por</span> <span class="author vcard"><?php //the_author_posts_link(); ?></span> -->
										</div><!-- end author -->

										<br class="clear" />
										
									</div><!-- end entry-meta -->
									
									<div class="entry-content">
										
										<div id="attachment_8" class="wp-caption alignright" style="width: 550px;">
											<?php the_post_thumbnail(); ?>
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
											
											<?php the_excerpt('leer más'); ?>
											
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
<br class="clear" />