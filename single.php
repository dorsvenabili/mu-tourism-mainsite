<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
								
				<div id="main" class="left-align">
					<div id="container">
						
						<div id="content">
							<div class="navigation skip" id="nav-above">
								<div class="nav-previous"><a href="#"><span class="meta-nav">←</span> Older posts</a></div>
								<div class="nav-next"></div>
							</div><!-- end nav-above -->
							
							<div class="post" id="post">
								
								<div id="left-post-content">
	
							<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
							
									<div class="entry-meta">
										
										<div class="comments">
											<span class="comments-link"><a title="Comentarios" href="<?php the_permalink(); ?>#comments"><?php comments_number('0', '1', '%'); ?></a></span>
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
										
										<div id="attachment_8" class="wp-caption alignright" style="width: 550px;">
											<?php the_post_thumbnail('large'); ?>
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
												<?php the_title(); ?>
											</h3>
											
											<?php the_content('leer más'); ?>
											
											<div class="entry-utility-single">
												<span class="cat-links">
													<span class="entry-utility-prep entry-utility-prep-cat-links">Categorías: </span><?php the_category(', '); ?>
												</span>
												<br />
												<span class="tag-links">
													<span class="entry-utility-prep entry-utility-prep-tag-links">Temas:</span><?php the_tags(' ',', ',' '); ?>
												</span>
											</div><!-- end entry-utility -->
										
										</div><!-- end entrada -->
										
										<?php show_social_icons(); ?>
									</div><!-- end entry-content -->
<?php endwhile; ?>
									<br class="clear" />
								
									<div id="more-content" class="l-post-c">
										<div class="lmc">
											
											<div class="separator-h">
												<hr />
											</div><!-- end separator-h -->
											
											
											<?php 
													//Entradas Relacionadas
													related_posts(); 
											?>
											
											<br class="clear" />
											
											<div class="separator-h">
												<hr />
											</div><!-- end separator-h -->
											
											<h3 class="tit-coments">Comentarios</h3>
											<?php comments_template( '', true ); ?>
											

											<?php /*?>
											<ol id="l-comets">
												<li>
													<span class="n-com">01</span>
													<span class="data-com">23 08 2010 a las 12:33</span>
													<span class="name-com"> | <a href="#" title="Al autor">Ricardo</a></span>

													<div class="comentario">
														<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>
													</div><!-- end comentario -->
													<br class="clear" />
												</li>
												<li>
													<span class="n-com">02</span>
													<span class="data-com">23 08 2010 a las 12:33</span>
													<span class="name-com"> | <a href="#" title="Al autor">Ricardo</a></span>

													<div class="comentario com-autor">
														<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>
													</div><!-- end comentario -->
													<br class="clear" />
												</li>
												<li>
													<span class="n-com">03</span>
													<span class="data-com">23 08 2010 a las 12:33</span>
													<span class="name-com"> | <a href="#" title="Al autor">Ricardo</a></span>

													<div class="comentario">
														<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>
													</div><!-- end comentario -->
													<br class="clear" />
												</li>
												<li>
													<span class="n-com">04</span>
													<span class="data-com">23 08 2010 a las 12:33</span>
													<span class="name-com"> | <a href="#" title="Al autor">Ricardo</a></span>

													<div class="comentario">
														<p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters.</p>
													</div><!-- end comentario -->
													<br class="clear" />
												</li>
											</ol>
											
											<div class="separator-h">
												<hr />
											</div><!-- end separator-h -->
											<h3 class="tit-dejar-com">Comentarios</h3>
											
											<div id="post-com-w">
												<p class="info-xhtml-tags"><strong>XHTML</strong>: Puedes utilizar estas etiquetas: A, ABBR, ACRONYM, B, BLOCKQUOTE, CITE, CODE, DEL, EM, I, Q, STRIKE, STRONG, IMG.</p>
												<form>
													<fieldset>
													   <legend class="skip">Formulario para comentar</legend>						   
													   
													   <label for="name" class="skip">Nombre</label>
													   <input id="name" type="text" name="name" class="input-name-c" /> <strong>Nombre</strong><br />
													   
													   <label for="email" class="skip">Email</label>
													   <input id="email" type="text" name="email" class="input-mail-c" /> <strong>Email</strong> (no será publicado)<br />
													   
													   <label for="website" class="skip">Sitio web</label>
													   <input id="website" type="text" name="website" class="input-webs-c" /> <strong>Sitio web</strong><br />
													   
													   <label for="coment-area" class="skip">Comentario</label>
													  	<strong>Comentario</strong><br />
													   
													   <textarea id="area-comentario" rows="5">ss</textarea>
													   
													   <br />
													   
													   <label for="snd-com" class="skip">Enviar comentario</label>
													   <p class="btn-snd-wrap">
													   	<input id="snd-com" type="button" name="snd-com" value="Enviar" class="btn-snd-com" />
													   </p>
													</fieldset>
												</form>
												<h4>Previsualización</h4>
												<div id="preview-post">
													<p>...</p>
												</div><!-- end preview-post -->
												<p class="com-sus"><a href="#" title="Suscripción a comentarios">Suscribirse a la los comentarios</a> (recibirás un mail cada vez que alguien responda).</p>
												<br class="clear" />
											</div><!-- end post-com-w -->
											*/ ?>
											
										</div><!-- end lmc -->
									</div><!-- end more-content -->
								
								</div><!-- end left-post-content -->								
<?php get_sidebar(); ?>
<?php get_footer(); ?>