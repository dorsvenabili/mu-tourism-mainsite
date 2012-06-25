<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage Turismo valencia
 * @since Twenty Ten 1.0
 */
?>
				</div><!-- end main -->
			</div><!-- end wrapper -->
			<div id="footer">
				<div class="cbf left-align">
					<div class="fboxes">
						<div class="footer-box last-r">
							<ul class="ulf-s">
								<?php								
									if ( is_active_sidebar( 'third-footer-widget-area' ) ) : // Nothing here by default and design
										dynamic_sidebar( 'third-footer-widget-area' );	
									else:
									endif;								
								?>
															
							</ul>
						</div><!-- end footer-box -->
								
						<div class="footer-box">
							<ul>
								<?php								
									if ( is_active_sidebar( 'second-footer-widget-area' ) ) : // Nothing here by default and design
										dynamic_sidebar( 'second-footer-widget-area' );	
									else:
									endif;								
								?>
								
							</ul>
						</div><!-- end footer-box -->
						
						<div class="footer-box">
							<ul>
								<?php								
									if ( is_active_sidebar( 'first-footer-widget-area' ) ) : // Nothing here by default and design
										dynamic_sidebar( 'first-footer-widget-area' );	
									else:
									endif;								
								?>
															
							</ul>
						</div><!-- end footer-box -->
					</div><!-- end fboxes -->
					<br class="clear" />
				</div><!-- end cbf -->
				<div id="bottom-footer">
					<div class="cbf">
						<p class="europa-link"><a href="http://www.comunitatvalenciana.com/fondo-europeo-de-desarrollo-regional-feder/0"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/FEDER-neg_hori_cast.png" /></a></p>
						<p><em>© Consellería de Turisme, 2010. Todos los derechos reservados.</em></p>
						
						<ul class="web-credits">
							<li><a href="http://www.w3.org/WAI/WCAG1AA-Conformance" target="new" title="Accesibilidad"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/sello-aa.gif" alt="Sello" width="80" height="15" /></a></li>
							<li><a href="http://jigsaw.w3.org/css-validator/check" target="new" title="CSS"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/sello-css.gif" alt="Sello" width="80" height="15" /></a></li>
							<li><a href="http://validator.w3.org/check?uri=referer" target="new" title="XHTML"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/sello-xhtml.gif" alt="Sello" width="80" height="15" /></a></li>
						</ul>
						<br class="clear" />
					</div><!-- end cbf -->
				</div><!-- end bottom-footer -->
				
			</div><!-- end footer -->
		</div><!-- end fondo -->

<?php
	/* Always have wp_footer() just before the closing </body>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to reference JavaScript files.
	 */

	wp_footer();
?>
</body>
</html>
