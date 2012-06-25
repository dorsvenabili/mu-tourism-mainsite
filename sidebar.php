<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
 
 //Mostramos el feed y el código de feedburner que hayan seleccionado en el panel de administración: RSS Opciones.
$url_feed_blog = get_option('valencia_rss');
$codigo_feedburner_suscrip_email = get_option('valencia_email_rss');
?>
<?php global $switched, $post; ?>

								<div id="righ-post-content">
								
									<div class="col-box">
										<h4 class="rss-box">Suscripción</h4>
										<div id="rss-box">
											<p class="bottom-sep"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-rss-big.gif" alt="Suscripción RSS - Icono RSS" width="41" height="41" /> <a href="<?php echo $url_feed_blog; ?>" title="Suscripción RSS">Suscribirse RSS / Feed</a><br class="clear" /></p>
											
										
											<p style="font-size:0.7em">Suscríbete a través de tu dirección de correo electrónico.</p>
											
											<div id="mail-sus" style="font-size:0.7em">
												<form method="post" action="http://feedburner.google.com/fb/a/mailverify" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?= $codigo_feedburner_suscrip_email; ?>' 'popupwindow', 'scrollbars=yes,width=550,height=400');return true">
													  						   
													   <label for="send" class="skip">Enviar</label>
													   <input id="send" type="text" name="email" class="inp-s-b" />
													   <input type="hidden" value="<?php echo $codigo_feedburner_suscrip_email; ?>" name="uri"/>
													   <input type="hidden" name="loc" value="es_ES"/>
													   <label for="sndbtn" class="skip">Ir a la búsqueda</label>
													   <input id="sndbtn" type="submit" name="sndbtn" value="Enviar" />
												</form>
												
											</div><!-- end mail-sus -->
										</div><!-- end rss-box -->
									</div><!-- end col-box -->
									
									<div class="col-box">
										<h4 class="tit-acerca">Acerca de...</h4>
										<div class="aut-dat">
											<?php 
												$idpag = 2;
												$acerca_de = get_post($idpag);
												
												$postslist = get_posts('post_type=page&include=2');
 												foreach ($postslist as $post) : 
    												
    												setup_postdata($post);?>
													<p>
														<?php the_post_thumbnail('thumbnail'); ?>
														<?php the_excerpt(); ?>
													</p>
													<p class="read-more"><a href="<?php bloginfo(); ?>/acerca-de/" title="Leer más">leer más</a></p>
											
												<?php endforeach; ?>
											
										</div><!-- end aut-dat -->
									</div><!-- end col-box -->
									
									<div class="banner-t1">
										<?php								
											if ( is_active_sidebar( 'publicidad-widget-area' ) ) :
												dynamic_sidebar( 'publicidad-widget-area' );	
											else:
											endif;								
										?>
									</div><!-- end banner-t1 -->
								
								</div><!-- righ-post-content -->
								<br class="clear" />
							</div><!-- end post -->
							
						</div><!-- end content -->
						
						<div class="separator-h">
							<hr />
						</div><!-- end separator-h -->
						
						<div id="more-content">
							<h3 class="lasts-post">Últimos artículos</h3>
							
							<?php								
									//Panel de administración -> Portada Principal Opciones -> Cogemos los blogs y los artículos que hayan decidido en las opciones
    								$idblog1 = get_option('valencia_bloque1');
    								$idblog2 = get_option('valencia_bloque2');
    								$idblog3 = get_option('valencia_bloque3');
    								$idblog4 = get_option('valencia_bloque4');
    								$idblog5 = get_option('valencia_bloque5');
    								$idblog6 = get_option('valencia_bloque6');
    								$idblog7 = get_option('valencia_bloque7');
    								$idblog8 = get_option('valencia_bloque8');
    								$idblog9 = get_option('valencia_bloque9');
    								
    								$ult_articulo1 = get_option('valencia_bloque1_articulo');
    								$ult_articulo2 = get_option('valencia_bloque2_articulo');
    								$ult_articulo3 = get_option('valencia_bloque3_articulo');
    								$ult_articulo4 = get_option('valencia_bloque4_articulo');
    								$ult_articulo5 = get_option('valencia_bloque5_articulo');
    								$ult_articulo6 = get_option('valencia_bloque6_articulo');
    								$ult_articulo7 = get_option('valencia_bloque7_articulo');
    								$ult_articulo8 = get_option('valencia_bloque8_articulo');
    								$ult_articulo9 = get_option('valencia_bloque9_articulo');
    								
								
								//Si meten algo en los widgets de la tercera columna, los 9 últimos artículos aparecerán en las dos primeras columnas.					
								if ( is_active_sidebar( 'primary-widget-area' ) ) : // Nothing here by default and design
									
									//Primera columna
									?><div id="columna_primera_ult_articulos"><?php									
										mostrar_articulo_portada($idblog1,$ult_articulo1);
										mostrar_articulo_portada($idblog2,$ult_articulo2);
										mostrar_articulo_portada($idblog3,$ult_articulo3);
										mostrar_articulo_portada($idblog4,$ult_articulo4);
										mostrar_articulo_portada($idblog5,$ult_articulo5);
									?></div><?php
									
									//Segunda columna
									?><div id="columna_segunda_ult_articulos"><?php
										mostrar_articulo_portada($idblog6,$ult_articulo6);
										if($ult_articulo7 != 0)	
											mostrar_articulo_portada($idblog7,$ult_articulo7);
										if($ult_articulo8 != 0)	
											mostrar_articulo_portada($idblog8,$ult_articulo8);
										if($ult_articulo9 != 0)	
											mostrar_articulo_portada($idblog9,$ult_articulo9);
									?></div><?php
									
									//La tercera columna mostrará lo que metan en los widgets
									?><div id="columna_tercera_ult_articulos"><?php									
										dynamic_sidebar( 'primary-widget-area' );	
									?></div><br class="clear"><?php
								
								
								
								//En caso de que no haya ningún widget en la tercera columna, los 9 últimos artículos se distribuirán en las 3 columnas.
								else: 
																	
									
									?><div class="fila_de_3_ult_articulos"><?php									
										mostrar_articulo_portada($idblog1,$ult_articulo1);
										mostrar_articulo_portada($idblog2,$ult_articulo2);
										mostrar_articulo_portada_tercero_fila($idblog3,$ult_articulo3);
									?></div>
									
									<div class="fila_de_3_ult_articulos"><?php
										mostrar_articulo_portada($idblog4,$ult_articulo4);
										mostrar_articulo_portada($idblog5,$ult_articulo5);
										mostrar_articulo_portada_tercero_fila($idblog6,$ult_articulo6);
									?></div>
									
									<div class="fila_de_3_ult_articulos"><?php
										if($ult_articulo7 != 0)
											mostrar_articulo_portada($idblog7,$ult_articulo7);
										if($ult_articulo8 != 0)	
											mostrar_articulo_portada($idblog8,$ult_articulo8);
										if($ult_articulo9 != 0)	
											mostrar_articulo_portada_tercero_fila($idblog9,$ult_articulo9);							
									?></div>
									
									<br class="clear">
									
								<?php endif; ?>
	
							
							<div class="separator-h">
								<hr />
							</div><!-- end separator-h -->
							
							<div class="box-content-b">
								<h4 class="tit-lmv">Lo más valorado</h4>
								<div class="content-bcb">
									<?php 
										los_mas_valorados();
									?>
								</div><!-- end content-bcb -->
							</div><!-- end box-content-b -->
												
	
							<div class="box-content-b">
								<h4 class="tit-lmc">Lo más comentado</h4>
								
								<div class="content-bcb">
								
								<?php lo_mas_comentado(); ?>
								

								</div><!-- end content-bcb -->
							</div><!-- end box-content-b -->
							
							<div class="box-content-b last-r">
								<h4 class="tit-nb">Nuestros bloggers</h4>
								<?php								
									if ( is_active_sidebar( 'nuestros-blogueros-widget-area' ) ) :
										dynamic_sidebar( 'nuestros-blogueros-widget-area' );	
									else:
									endif;								
								?>
								
							</div><!-- end box-content-b -->
							
							
							<br class="clear" />
						</div><!-- end more-content -->
						
					</div><!-- end container -->
					<div id="sidebar">
						<div id="primary" class="widget-area"></div><!-- end primary -->
					</div><!-- end sidebar -->