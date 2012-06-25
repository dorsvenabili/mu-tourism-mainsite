<?php

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):

function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();

	// Your changeable header business starts here
	define( 'HEADER_TEXTCOLOR', '' );
	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 198 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 940 pixels wide by 198 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyten_admin_header_style(), below.
	add_custom_image_header( '', 'twentyten_admin_header_style' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'berries' => array(
			'url' => '%s/images/headers/berries.jpg',
			'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Berries', 'twentyten' )
		),
		'cherryblossom' => array(
			'url' => '%s/images/headers/cherryblossoms.jpg',
			'thumbnail_url' => '%s/images/headers/cherryblossoms-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Cherry Blossoms', 'twentyten' )
		),
		'concave' => array(
			'url' => '%s/images/headers/concave.jpg',
			'thumbnail_url' => '%s/images/headers/concave-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Concave', 'twentyten' )
		),
		'fern' => array(
			'url' => '%s/images/headers/fern.jpg',
			'thumbnail_url' => '%s/images/headers/fern-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Fern', 'twentyten' )
		),
		'forestfloor' => array(
			'url' => '%s/images/headers/forestfloor.jpg',
			'thumbnail_url' => '%s/images/headers/forestfloor-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Forest Floor', 'twentyten' )
		),
		'inkwell' => array(
			'url' => '%s/images/headers/inkwell.jpg',
			'thumbnail_url' => '%s/images/headers/inkwell-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Inkwell', 'twentyten' )
		),
		'path' => array(
			'url' => '%s/images/headers/path.jpg',
			'thumbnail_url' => '%s/images/headers/path-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Path', 'twentyten' )
		),
		'sunset' => array(
			'url' => '%s/images/headers/sunset.jpg',
			'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Sunset', 'twentyten' )
		)
	) );
}
endif;

if ( ! function_exists( 'twentyten_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since Twenty Ten 1.0
 */
function twentyten_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Makes some changes to the <title> tag, by filtering the output of wp_title().
 *
 * If we have a site description and we're viewing the home page or a blog posts
 * page (when using a static front page), then we will add the site description.
 *
 * If we're viewing a search result, then we're going to recreate the title entirely.
 * We're going to add page numbers to all titles as well, to the middle of a search
 * result title and the end of all other titles.
 *
 * The site title also gets added to all titles.
 *
 * @since Twenty Ten 1.0
 *
 * @param string $title Title generated by wp_title()
 * @param string $separator The separator passed to wp_title(). Twenty Ten uses a
 * 	vertical bar, "|", as a separator in header.php.
 * @return string The new title, ready for the <title> tag.
 */
function twentyten_filter_wp_title( $title, $separator ) {
	// Don't affect wp_title() calls in feeds.
	if ( is_feed() )
		return $title;

	// The $paged global variable contains the page number of a listing of posts.
	// The $page global variable contains the page number of a single post that is paged.
	// We'll display whichever one applies, if we're not looking at the first page.
	global $paged, $page;

	if ( is_search() ) {
		// If we're a search, let's start over:
		$title = sprintf( __( 'Search results for %s', 'twentyten' ), '"' . get_search_query() . '"' );
		// Add a page number if we're on page 2 or more:
		if ( $paged >= 2 )
			$title .= " $separator " . sprintf( __( 'Page %s', 'twentyten' ), $paged );
		// Add the site name to the end:
		$title .= " $separator " . get_bloginfo( 'name', 'display' );
		// We're done. Let's send the new title back to wp_title():
		return $title;
	}

	// Otherwise, let's start by adding the site name to the end:
	$title .= get_bloginfo( 'name', 'display' );

	// If we have a site description and we're on the home/front page, add the description:
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title .= " $separator " . $site_description;

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		$title .= " $separator " . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	// Return the new title to wp_title():
	return $title;
}
add_filter( 'wp_title', 'twentyten_filter_wp_title', 10, 2 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return __( '', 'twentyten' );
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css.
 *
 * @since Twenty Ten 1.0
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );

if ( ! function_exists( 'twentyten_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'twentyten'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function twentyten_widgets_init() {
	
	// Area 1 de la cabecera.
	register_sidebar( array(
		'name' => __( 'Enlaces de la cabecera', 'twentyten' ),
		'id' => 'second-header-widget-area',
		'description' => __( 'Zona de la cabecera donde van los enlaces: Conselleria de Turisme y Suscripción', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
	
	// Area 1 de la cabecera.
	register_sidebar( array(
		'name' => __( 'Publicidad Portada', 'twentyten' ),
		'id' => 'publicidad-widget-area',
		'description' => __( 'En este sidebar se debe poner el widget del banner de publicidad de la portada principal de la red de blogs', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
	
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Tercera columna portada', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'Si pones algún widget aquí aparecerá en la tercera columna de la portada principal', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h5 class="entry-title">',
		'after_title' => '</h5>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Bloques + Valorado y + Comentado', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	// Area 2, en el sidebar.
	register_sidebar( array(
		'name' => __( 'Nuestros blogueros', 'twentyten' ),
		'id' => 'nuestros-blogueros-widget-area',
		'description' => __( 'Bloque donde meter el widget de nuestros blogueros para mostrar en la portada de la red de blogs.', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer Columna 1 de enlaces', 'twentyten' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer Columna 2 de enlaces', 'twentyten' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );

	
	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Footer Columna 3 de enlaces', 'twentyten' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => '',
	) );
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );


/* Function that registers our widget. */
function valencia_load_widgets() {
	register_widget( 'WP_Widget_Articulo_Valencia' );
	
	do_action('widgets_init');
}

/* Add our function to the widgets_init hook. */
add_action( 'init', 'valencia_load_widgets', 1 );

/**
 * Text Valencia widget class
 */
class WP_Widget_Articulo_Valencia extends WP_Widget {

	function WP_Widget_Articulo_Valencia() {
		$widget_ops = array('classname' => 'widget_articulo_valencia', 'description' => __('Fila de últimos artículos de los blogs'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('valencia', __('Fila de últimos artículos de los blogs valencia'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		
		$idblog1 = empty( $instance['idblog1'] ) ? '' : $instance['idblog1'];
		$ult_articulo1 = empty( $instance['ult_articulo1'] ) ? '' : $instance['ult_articulo1'];
		$idblog2 = empty( $instance['idblog2'] ) ? '' : $instance['idblog2'];
		$ult_articulo2 = empty( $instance['ult_articulo2'] ) ? '' : $instance['ult_articulo2'];
		$idblog3 = empty( $instance['idblog3'] ) ? '' : $instance['idblog3'];
		$ult_articulo3 = empty( $instance['ult_articulo3'] ) ? '' : $instance['ult_articulo3'];
		
		
		echo $before_widget; 
		
		mostrar_articulo_portada($idblog1,$ult_articulo1);
		mostrar_articulo_portada($idblog2,$ult_articulo2);
		mostrar_articulo_portada_tercero_fila($idblog3,$ult_articulo3);
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['idblog1'] = $new_instance['idblog1'];
		$instance['ult_articulo1'] = $new_instance['ult_articulo1'];
		$instance['idblog2'] = $new_instance['idblog2'];
		$instance['ult_articulo2'] = $new_instance['ult_articulo2'];
		$instance['idblog3'] = $new_instance['idblog3'];
		$instance['ult_articulo3'] = $new_instance['ult_articulo3'];
		
		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'idblog1' => '', 'ult_articulo1' => '', 'idblog2' => '', 'ult_articulo2' => '', 'idblog3' => '', 'ult_articulo3' => '') );
		$idblog1 = esc_attr($instance['idblog1']);
		$ult_articulo1 = esc_attr($instance['ult_articulo1']);
		$idblog2 = esc_attr($instance['idblog2']);
		$ult_articulo2 = esc_attr($instance['ult_articulo2']);
		$idblog3 = esc_attr($instance['idblog3']);
		$ult_articulo3 = esc_attr($instance['ult_articulo3']);
?>
		<p>
			<strong>Primer artículo de la fila:</strong><br /><br />
			<label for="<?php echo $this->get_field_id('idblog1'); ?>"><?php _e('Blog del artículo:  '); ?></label>
			<select name="<?php echo $this->get_field_name('idblog1'); ?>" id="<?php echo $this->get_field_id('idblog1'); ?>">
   				<option value="2" <?php if($idblog1 == 2) echo 'selected'; ?>>Senderismo</option>
   				<option value="3" <?php if($idblog1 == 3) echo 'selected'; ?>>Buceo</option>
   				<option value="4" <?php if($idblog1 == 4) echo 'selected'; ?>>Kitesurf</option>
   				<option value="5" <?php if($idblog1 == 5) echo 'selected'; ?>>WindSurf</option>
   				<option value="6" <?php if($idblog1 == 6) echo 'selected'; ?>>Surf</option>
   				<option value="7" <?php if($idblog1 == 7) echo 'selected'; ?>>Btt</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('ult_articulo1'); ?>"><?php _e('Artículo a mostrar:'); ?></label>
			<select name="<?php echo $this->get_field_name('ult_articulo1'); ?>" id="<?php echo $this->get_field_id('ult_articulo1'); ?>">
   				<option value="1" <?php if($ult_articulo1 == 1) echo 'selected'; ?>>Último artículo</option>
   				<option value="2" <?php if($ult_articulo1 == 2) echo 'selected'; ?>>Penúltimo artículo</option>
   				<option value="3" <?php if($ult_articulo1 == 3) echo 'selected'; ?>>Antepenúltimo artículo</option>
			</select>
		</p>
		
		<p>	
			<strong>Segundo artículo de la fila:</strong><br /><br />
			<label for="<?php echo $this->get_field_id('idblog2'); ?>"><?php _e('Blog del artículo:  '); ?></label>
			<select name="<?php echo $this->get_field_name('idblog2'); ?>" id="<?php echo $this->get_field_id('idblog2'); ?>">
   				<option value="2" <?php if($idblog2 == 2) echo 'selected'; ?>>Senderismo</option>
   				<option value="3" <?php if($idblog2 == 3) echo 'selected'; ?>>Buceo</option>
   				<option value="4" <?php if($idblog2 == 4) echo 'selected'; ?>>Kitesurf</option>
   				<option value="5" <?php if($idblog2 == 5) echo 'selected'; ?>>WindSurf</option>
   				<option value="6" <?php if($idblog2 == 6) echo 'selected'; ?>>Surf</option>
   				<option value="7" <?php if($idblog2 == 7) echo 'selected'; ?>>Btt</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('ult_articulo2'); ?>"><?php _e('Artículo a mostrar:'); ?></label>
			<select name="<?php echo $this->get_field_name('ult_articulo2'); ?>" id="<?php echo $this->get_field_id('ult_articulo2'); ?>">
   				<option value="1" <?php if($ult_articulo2 == 1) echo 'selected'; ?>>Último artículo</option>
   				<option value="2" <?php if($ult_articulo2 == 2) echo 'selected'; ?>>Penúltimo artículo</option>
   				<option value="3" <?php if($ult_articulo2 == 3) echo 'selected'; ?>>Antepenúltimo artículo</option>
			</select>
		</p>

		<p>
			<strong>Tercer artículo de la fila:</strong><br /><br />
			<label for="<?php echo $this->get_field_id('idblog3'); ?>"><?php _e('Blog del artículo:  '); ?></label>
			<select name="<?php echo $this->get_field_name('idblog3'); ?>" id="<?php echo $this->get_field_id('idblog3'); ?>">
   				<option value="2" <?php if($idblog3 == 2) echo 'selected'; ?>>Senderismo</option>
   				<option value="3" <?php if($idblog3 == 3) echo 'selected'; ?>>Buceo</option>
   				<option value="4" <?php if($idblog3 == 4) echo 'selected'; ?>>Kitesurf</option>
   				<option value="5" <?php if($idblog3 == 5) echo 'selected'; ?>>WindSurf</option>
   				<option value="6" <?php if($idblog3 == 6) echo 'selected'; ?>>Surf</option>
   				<option value="7" <?php if($idblog3 == 7) echo 'selected'; ?>>Btt</option>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('ult_articulo3'); ?>"><?php _e('Artículo a mostrar:'); ?></label>
			<select name="<?php echo $this->get_field_name('ult_articulo3'); ?>" id="<?php echo $this->get_field_id('ult_articulo3'); ?>">
   				<option value="1" <?php if($ult_articulo3 == 1) echo 'selected'; ?>>Último artículo</option>
   				<option value="2" <?php if($ult_articulo3 == 2) echo 'selected'; ?>>Penúltimo artículo</option>
   				<option value="3" <?php if($ult_articulo3 == 3) echo 'selected'; ?>>Antepenúltimo artículo</option>
			</select>
		</p>

<?php
	}
}




/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post—date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'twentyten' ), get_the_author() ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


//Función que muestra los posts más valorados en la portada principal (la media de todos los blogs)
function los_mas_valorados(){
	global $wpdb, $switched;
	
	$cont = 0;
										
	$senderismo_mas_valorados = $wpdb->get_results("SELECT post_id, user_voters, user_votes 
		FROM wp_2_gdsr_data_article 
		WHERE user_votes <> 0.0
		ORDER BY user_votes DESC
		LIMIT 5
	");
	foreach($senderismo_mas_valorados as $senderismo_mas_valorado){
												
		$todos[$cont]['media'] = ($senderismo_mas_valorado->user_votes)/($senderismo_mas_valorado->user_voters);
		$todos[$cont]['post_id'] = $senderismo_mas_valorado->post_id;
		$todos[$cont]['post_blog'] = 2;
												
		$cont++;
	}
	
	$buceo_mas_valorados = $wpdb->get_results("SELECT post_id, user_voters, user_votes 
		FROM wp_3_gdsr_data_article 
		WHERE user_votes <> 0.0
		ORDER BY user_votes DESC
		LIMIT 5
	");
	foreach($buceo_mas_valorados as $buceo_mas_valorado){
												
		$todos[$cont]['media'] = ($buceo_mas_valorado->user_votes)/($buceo_mas_valorado->user_voters);
		$todos[$cont]['post_id'] = $buceo_mas_valorado->post_id;
		$todos[$cont]['post_blog'] = 3;
												
		$cont++;
	}
	
	$kitesurf_mas_valorados = $wpdb->get_results("SELECT post_id, user_voters, user_votes 
		FROM wp_4_gdsr_data_article 
		WHERE user_votes <> 0.0
		ORDER BY user_votes DESC
		LIMIT 5
	");
	foreach($kitesurf_mas_valorados as $kitesurf_mas_valorado){
												
		$todos[$cont]['media'] = ($kitesurf_mas_valorado->user_votes)/($kitesurf_mas_valorado->user_voters);
		$todos[$cont]['post_id'] = $kitesurf_mas_valorado->post_id;
		$todos[$cont]['post_blog'] = 4;										
												
		$cont++;
	}
	
	$windsurf_mas_valorados = $wpdb->get_results("SELECT post_id, user_voters, user_votes 
		FROM wp_5_gdsr_data_article 
		WHERE user_votes <> 0.0
		ORDER BY user_votes DESC
		LIMIT 5
	");
	foreach($windsurf_mas_valorados as $windsurf_mas_valorado){
												
		$todos[$cont]['media'] = ($windsurf_mas_valorado->user_votes)/($windsurf_mas_valorado->user_voters);
		$todos[$cont]['post_id'] = $windsurf_mas_valorado->post_id;
		$todos[$cont]['post_blog'] = 5;
												
		$cont++;
	}
											
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Ahora en el array $todos, se encuentran los 5 posts más valorados de cada blog
	//Sólo nos queda ordenar dicho array por el número máximo de votos
	//Ordenamos el array
	//array_multisort($todos[]['media'], SORT_NUMERIC, SORT_DESC);
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	for($i=0;$i<20;$i++){
		
		if($i < 5){
			$ordenado[$i] = $todos[$i];
		}
		else{
			$media = $todos[$i]['media'];
			$minimo_voto = minimo_voto($ordenado);
			
			if($media > $minimo_voto){
				$ordenado = nuevo_minimo($ordenado, $todos[$i], $minimo_voto);
			}
		}//endif
	}//endfor
	
	//Ya tenemos en el array $ordenado los 5 posts más valorados, pero sin ordenar, ahora vamos a ordenarlos
	$ordenado = ordenar_matriz_por_votos($ordenado);
	
	foreach($ordenado as $mas_valorado):
		
		$post_blog = $mas_valorado['post_blog'];
		$post_id = $mas_valorado['post_id'];
		$media = $mas_valorado['media'];
		
		switch_to_blog($post_blog);
			
			$post_mas_valorado = get_post($post_id);
			?>
			<h5 class="entry-title-sidebar">
				<a rel="bookmark" title="Permalink" href="<?php echo $post_mas_valorado->guid; ?>"><?php echo $post_mas_valorado->post_title; ?></a>
			</h5>
			<div class="rate">
				<?php $parte_entera = explode('.',$media); ?>
				<img src="<?php bloginfo('stylesheet_directory'); ?>/img/stars-<?php echo $parte_entera[0]; ?>.jpg" alt="Valoración: <?php echo $media; ?>" />
			</div><!-- end rate -->
			
			<?php
			
		restore_current_blog();
		
	endforeach;									
}

//Función que dada una matriz de 5 objetos, la devuelve ordenada por el campo 'media'
function ordenar_matriz_por_votos($ordenado){
	
	for($i=0;$i<5;$i++){
		$votaciones[$i] = $ordenado[$i]['media'];
		$aux[$i]['media'] = $ordenado[$i]['media'];
		$aux[$i]['post_id'] = $ordenado[$i]['post_id'];
		$aux[$i]['post_blog'] = $ordenado[$i]['post_blog'];
	}
	rsort($votaciones);
	
	$cont = 0;
	
	foreach($votaciones as $votacion){
		for($j=0;$j<5;$j++){
			if($aux[$j]['media'] == $votacion){
				$final[$cont]['media'] = $aux[$j]['media'];
				$final[$cont]['post_id'] = $aux[$j]['post_id'];
				$final[$cont]['post_blog'] = $aux[$j]['post_blog'];
				
				//Ahora eliminamos de aux el que acabamos de coger (por si hay dos votaciones con el mismo valor, que no se repita el post)
				$aux[$j]['media'] = -1;
				$aux[$j]['post_id'] = -1;
				$aux[$j]['post_blog'] = -1;
				
				$cont++;
			}
				
		}
	}
	
	return $final;
}

//Función que calcula el valor mínimo del campo 'media' en la matriz que se pasa como parámetro $ordenado
function minimo_voto($ordenado){
	
	$minimo = $ordenado[0]['media'];
	
	for($k=1;$k<5;$k++){
		if($ordenado[$k]['media'] < $minimo)
			$minimo = $ordenado[$k]['media'];
	}
	
	return $minimo;
}

//Función que dado una matriz, saca el objeto cuyo valor en el campo 'media' es igual a $minimo y en su lugar mete el objeto $media
function nuevo_minimo($ordenado, $nuevo, $minimo_voto){
	
	$i = 0;
	$enc = false;
	while(($i < 5) && (!$enc)){
		$aux = $ordenado[$i]['media'];
		if($aux == $minimo_voto){
			$ordenado[$i]['media'] = $nuevo['media'];
			$ordenado[$i]['post_id'] = $nuevo['post_id'];
			$ordenado[$i]['post_blog'] = $nuevo['post_blog'];
			
			$enc = true;
		}
		
		$i++;
	}
	
	return $ordenado;
}

//Función que muestra los cuadritos de la portada con los últimos artículos de los blogs
function mostrar_articulo_portada($idblog,$ult_penult_antepenult){ ?>
	
	<?php global $switched; ?>
	<div class="box-content">
		<?php
    			switch_to_blog($idblog);
		?> 
		<?php 
				$cont = 1;
				$args = array('posts_per_page' => $ult_penult_antepenult,'orderby'  => date,'order' => DESC);
				query_posts($args);
								
				if ( have_posts() ) while ( have_posts() ) : the_post();
								
					if($ult_penult_antepenult == $cont): ?>
								
								<h4><a href="<?php bloginfo('home'); ?>"><?php bloginfo('name');?></a></h4>
								<br class="clear" />
								<div class="cat-img">
									<a href="<?php the_permalink(); ?>" title="Imagen de la categoría">										 
										<?php 
											if(has_post_thumbnail()) {
												the_post_thumbnail('thumbnail');
											} else {
												echo '<img src="'.get_bloginfo("template_url").'/img/imagen-def-portada.jpg" />';
											}
										?>										
									</a>
								</div><!-- end cat-img -->
								<br class="clear" />
								<div class="entry-meta">
																				
										<div class="author">
											<div class="author-photo">
												<?php userphoto_the_author_thumbnail(); ?>
											</div><!-- end author-photo -->
											<span rel="bookmark" title="fecha" href="<?php echo get_month_link('', ''); ?>" class="date-post">
												<span class="entry-date"><?php $date = get_the_date(); echo strtolower($date); ?></span>
											</span>
											<span class="meta-sep">Escrito por</span> <span class="author vcard"><?php the_author_posts_link(); ?></span>
										</div><!-- end author -->
										
									</div><!-- end entry-meta -->
									
								<div class="separator-h">
									<hr />
								</div><!-- end separator-h -->
								<br class="clear" />
								<div class="last-cat-post">
									<h5 class="entry-title">
										<a rel="bookmark" title="Permalink" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h5>
									<p class="excerpt-portada"><?php 
										$text = get_the_excerpt(); 
										echo custom_excerpt($text, 25);
									?></p>
									<p class="read-more"><a href="<?php the_permalink(); ?>" title="Leer más">leer más</a></p>
								</div><!-- end last-cat-post -->
					
					<?php 
						endif; 
						$cont++;	
					?>
					
							
				<?php endwhile; ?>
				<?php restore_current_blog(); ?>						
		</div><!-- box-content -->

<?php	
}

//Función que muestra los cuadritos de la portada (los terceros de cada fila) con los últimos artículos de los blogs
function mostrar_articulo_portada_tercero_fila($idblog,$ult_penult_antepenult){ ?>
	
	<?php global $switched; ?>
	<div class="box-content last-r">
		<?php
    			switch_to_blog($idblog);
		?> 
		<?php 
				$cont = 1;
				$args = array('posts_per_page' => $ult_penult_antepenult,'orderby'  => date,'order' => DESC);
				query_posts($args);
								
				if ( have_posts() ) while ( have_posts() ) : the_post();
								
					if($ult_penult_antepenult == $cont): ?>
								
								<h4><a href="<?php bloginfo('home'); ?>"><?php bloginfo('name');?></a></h4>
								<br class="clear" />
								<div class="cat-img">
									<a href="<?php the_permalink(); ?>" title="Imagen de la categoría">
										<?php 
											if(has_post_thumbnail()) {
												the_post_thumbnail('thumbnail');
											} else {
												echo '<img src="'.get_bloginfo("template_url").'/img/imagen-def-portada.jpg" />';
											}
										?>	
									</a>
								</div><!-- end cat-img -->
								<br class="clear" />
								<div class="entry-meta">
																				
										<div class="author">
											<div class="author-photo">
												<?php userphoto_the_author_thumbnail(); ?>
											</div><!-- end author-photo -->
											<a rel="bookmark" title="fecha" href="<?php echo get_month_link('', ''); ?>" class="date-post">
												<span class="entry-date"><?php $date = get_the_date(); echo strtolower($date); ?></span>
											</a>
											<span class="meta-sep">Escrito por</span> <span class="author vcard"><?php the_author_posts_link(); ?></span>
										</div><!-- end author -->
										
									</div><!-- end entry-meta -->
									
								<div class="separator-h">
									<hr />
								</div><!-- end separator-h -->
								<br class="clear" />
								<div class="last-cat-post">
									<h5 class="entry-title">
										<a rel="bookmark" title="Permalink" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									</h5>
									<p class="excerpt-portada"><?php 
										$text = get_the_excerpt(); 
										echo custom_excerpt($text, 25);
									?></p>
									<p class="read-more"><a href="<?php the_permalink(); ?>" title="Leer más">leer más</a></p>
								</div><!-- end last-cat-post -->
							
					<?php 
						endif; 
						$cont++;	
					?>
					
							
				<?php endwhile; ?>
				<?php restore_current_blog(); ?>						
		</div><!-- box-content -->

<?php	
}


/* Function that registers our widget. */
function blogueros_load_widgets() {
	register_widget( 'WP_Widget_Nuestros_Blogueros' );
	
	do_action('widgets_init');
}

/* Add our function to the widgets_init hook. */
add_action( 'init', 'blogueros_load_widgets', 1 );

/**
 * Text Valencia widget class
 */
class WP_Widget_Nuestros_Blogueros extends WP_Widget {

	function WP_Widget_Nuestros_Blogueros() {
		$widget_ops = array('classname' => 'widget_articulo_valencia', 'description' => __('Widget para mostrar uno de Nuestros Blogueros'));
		$control_ops = array('width' => 400, 'height' => 350);
		$this->WP_Widget('blogueros', __('Nuestros blogueros'), $widget_ops, $control_ops);
	}

	function widget( $args, $instance ) {
		extract($args);
		
		$id_bloguero = empty( $instance['id_bloguero'] ) ? '' : $instance['id_bloguero'];
		$id_blog = empty( $instance['id_blog'] ) ? '' : $instance['id_blog'];
		
		echo $before_widget; 
		
		nuestros_blogueros($id_bloguero, $id_blog);
		
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['id_bloguero'] = $new_instance['id_bloguero'];
		$instance['id_blog'] = $new_instance['id_blog'];
		
		return $instance;
	}

	function form( $instance ) {
		global $wpdb;
		
		$instance = wp_parse_args( (array) $instance, array( 'id_bloguero' => '', 'id_blog' => '') );
		$id_bloguero = esc_attr($instance['id_bloguero']);
		$id_blog = esc_attr($instance['id_blog']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('id_bloguero'); ?>"><?php _e('Bloguero:  '); ?></label>
			<select name="<?php echo $this->get_field_name('id_bloguero'); ?>" id="<?php echo $this->get_field_id('id_bloguero'); ?>">
   				<?php
   					
   					$i = 0;
	
					$blogueros = $wpdb->get_results("SELECT ID, display_name, user_login 
						FROM $wpdb->users
					");
	
					foreach($blogueros as $bloguero){
												
						$bio_bloguero = $wpdb->get_var("SELECT meta_value
							FROM $wpdb->usermeta
							WHERE user_id = ".$bloguero->ID."
							AND meta_key = 'description'
						"); ?>

   						<option value="<?php echo $bloguero->ID; ?>" <?php if($id_bloguero == $bloguero->ID) echo 'selected'; ?>><?php echo $bloguero->display_name; ?></option>
   					
   				<?php } ?>
			</select>
		</p>	
		<p>
			<label for="<?php echo $this->get_field_id('id_blog'); ?>"><?php _e('Blog del que es autor: '); ?></label>
			<select name="<?php echo $this->get_field_name('id_blog'); ?>" id="<?php echo $this->get_field_id('id_blog'); ?>">
   				<?php
   					$blogs = $wpdb->get_results("SELECT blog_id FROM $wpdb->blogs WHERE blog_id <> 1 ORDER BY blog_id ASC");
	
					foreach($blogs as $blog){ 
						
						$args = "SELECT option_value FROM wp_".$blog->blog_id."_options WHERE option_name = 'blogname'";
						$nombre_blog = $wpdb->get_var($args);
						?>
   						
   						<option value="<?php echo $blog->blog_id; ?>" <?php if($id_blog == $blog->blog_id) echo 'selected'; ?>><?php echo $nombre_blog; ?></option>
   				
   				<?php } ?>
			</select>
		</p>
		
<?php
	}
}

//Función que muestra una lista de nuestros blogueros
function nuestros_blogueros($id_bloguero, $id_blog){
	global $wpdb;
	
	$datos_bloguero = $wpdb->get_results("SELECT display_name, user_login 
		FROM $wpdb->users 
		WHERE ID = ".$id_bloguero."
	");
	
	foreach($datos_bloguero as $datos){
												
		$bio_bloguero = $wpdb->get_var("SELECT meta_value
			FROM $wpdb->usermeta
			WHERE user_id = ".$id_bloguero."
			AND meta_key = 'description'
		"); 
		
		$ruta_blog = $wpdb->get_var("SELECT path
			FROM $wpdb->blogs
			WHERE blog_id = ".$id_blog."
		"); ?>
		
		<div class="content-bcb c-nb">								
			<div class="rate photo-a">
				<?php echo get_avatar( $id_bloguero, 28 ); ?>
				<h5 class="entry-title-sidebar">
					<a rel="bookmark" title="Permalink" href="<?php echo get_bloginfo('home').$ruta_blog; ?>author/<?php echo $datos->user_login; ?>/"><?php echo $datos->display_name; ?></a>
				</h5>
				<?php $bio = excerpt_28($bio_bloguero); echo $bio; ?>
				<br class="clear" />
			</div><!-- end rate -->									
		</div><!-- end content-bcb -->
		
		<?php
	}

}


//Función de nuestro excerpt personalizado de 6 palabras, sin contar código.
function excerpt_28($text) {
	
	if ( '' != $text ) {
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text, '<strong><a><p><i><em>');
		$excerpt_length = 6;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}	
	}
	
	nl2br($text);
	
	return $text;
	

}	

// Función para los más comentados	
function lo_mas_comentado(){
	global $wpdb; 
		$comentados = $wpdb->get_results("SELECT * FROM wp_3_posts WHERE 1=1 ORDER BY comment_count DESC LIMIT 1");
		foreach ($comentados as $comentado){
			echo '
			<h5 class="entry-title-sidebar">
				<a rel="bookmark" title="Permalink" href="'.$comentado->guid.'">'.$comentado->post_title.'</a>
			</h5>
			<div class="rate">
				'.excerpt_28($comentado->post_content).'
			</div><!-- end rate -->
			';

		}
		$comentados = $wpdb->get_results("SELECT * FROM wp_4_posts WHERE 1=1 ORDER BY comment_count DESC LIMIT 1");
		foreach ($comentados as $comentado){
			echo '
			<h5 class="entry-title-sidebar">
				<a rel="bookmark" title="Permalink" href="'.$comentado->guid.'">'.$comentado->post_title.'</a>
			</h5>
			<div class="rate">
				'.excerpt_28($comentado->post_content).'
			</div><!-- end rate -->
			';

		}
		$comentados = $wpdb->get_results("SELECT * FROM wp_2_posts WHERE 1=1 ORDER BY comment_count DESC LIMIT 1");
		foreach ($comentados as $comentado){
			echo '
			<h5 class="entry-title-sidebar">
				<a rel="bookmark" title="Permalink" href="'.$comentado->guid.'">'.$comentado->post_title.'</a>
			</h5>
			<div class="rate">
				'.excerpt_28($comentado->post_content).'
			</div><!-- end rate -->
			';

		}
			$comentados = $wpdb->get_results("SELECT * FROM wp_5_posts WHERE 1=1 ORDER BY comment_count DESC LIMIT 1");
		foreach ($comentados as $comentado){
			echo '
			<h5 class="entry-title-sidebar">
				<a rel="bookmark" title="Permalink" href="'.$comentado->guid.'">'.$comentado->post_title.'</a>
			</h5>
			<div class="rate">
				'.excerpt_28($comentado->post_content).'
			</div><!-- end rate -->
			';

		}

	
}

function excerpt_200($content = false) {
			global $post;
			
			if($post->post_excerpt != ''):
				$mycontent = $post->post_excerpt;
			else:
				$mycontent = $post->post_content;
			endif;

			
			$mycontent = str_replace(']]>', ']]&gt;', $mycontent);
			$mycontent = strip_tags($mycontent);
			$excerpt_length = 200;
			$words = explode(' ', $mycontent, $excerpt_length + 1);
			if(count($words) > $excerpt_length) :
				array_pop($words);
				array_push($words, '...');
				$mycontent = implode(' ', $words);
			endif;
			$mycontent = apply_filters('the_content', $mycontent);
			$mycontent = '<p>' . $mycontent . '</p>';
// Make sure to return the content
	return $mycontent;
}

function excerpt_50($content = false) {
			global $post;
			
			if($post->post_excerpt != ''):
				$mycontent = $post->post_excerpt;
			else:
				$mycontent = $post->post_content;
			endif;

			
			$mycontent = str_replace(']]>', ']]&gt;', $mycontent);
			$mycontent = strip_tags($mycontent);
			$excerpt_length = 50;
			$words = explode(' ', $mycontent, $excerpt_length + 1);
			if(count($words) > $excerpt_length) :
				array_pop($words);
				array_push($words, '...');
				$mycontent = implode(' ', $words);
			endif;
			$mycontent = apply_filters('the_content', $mycontent);
			$mycontent = '<p>' . $mycontent . '</p>';
// Make sure to return the content
	return $mycontent;
}



if ( ! function_exists( 'valencia_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function valencia_comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
   global $commentNum; ?>
  	<?php $mostrar = $commentNum+1; 
  			if ($mostrar < 10) $mostrar = '0'.$mostrar;
  	
  	?>
   <li>
   		<span class="n-com"><?php echo $mostrar; ?></span>
   		<span class="data-com"><?php comment_date(); ?></span>
		<span class="name-com"> | <?php /* ?><a href="<?php comment_author_link(); ?>" title="Al autor"><?php */ ?><?php comment_author(); ?><?php /* ?></a><?php */ ?> | <?php edit_comment_link(__('(Edit)'),'  ','') ?></span>

		<div class="comentario">
			<?php if ($comment->comment_approved == '0') : ?>
         		<em><?php _e('Your comment is awaiting moderation.') ?></em>
         		<br />
      		<?php endif; ?>
			<p> <?php comment_text() ?></p>
			
		</div><!-- end comentario -->
		<div class="reply">
         	<?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      	</div>
		<br class="clear" />
<?php $commentNum = $commentNum + 1; ?>
<?php
}
endif;

//Función de nuestro excerpt personalizado de 100 caracteres, sin contar código.
function excerpt_20($text) {
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
	
		$text = strip_tags($text, '<strong><a><p><i><em><br><br /></br>');
		$excerpt_length = 20;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}
	}
	else{
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text, '<strong><a><p><i><em><br><br /></br>');
		$excerpt_length = 20;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}
	}
	nl2br($text);
	
	return $text;
}

//Función de nuestro excerpt personalizado de 100 caracteres, sin contar código.
function custom_excerpt($text,$long) {
	global $post;
	if ( '' == $text ) {
		$text = get_the_content('');
		$text = apply_filters('the_content', $text);
		$text = str_replace(']]>', ']]&gt;', $text);
	
		$text = strip_tags($text, '<strong><a><p><i><em><br><br /></br>');
		$excerpt_length = $long;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}
	}
	else{
		$text = str_replace(']]>', ']]&gt;', $text);
		$text = strip_tags($text, '<strong><a><p><i><em><br><br /></br>');
		$excerpt_length = $long;
		$words = explode(' ', $text, $excerpt_length + 1);
		if (count($words)> $excerpt_length) {
			array_pop($words);
			array_push($words, '[...]');
			$text = implode(' ', $words);
		}
	}
	nl2br($text);
	
	return $text;
}

/*************************************************************************************************************************************************
// Menú en el panel de administración para que puedan elegir el blog del que se mostrará el sticky post o el último post
*************************************************************************************************************************************************/


function valencia_admin_head() { ?>

<?php }

// VARIABLES

$themename = "Portada principal";
$shortname = "valencia";
$manualurl = get_bloginfo('home');
$options = array();

add_option("valencia_settings",$options);

$template_path = get_bloginfo('template_directory');

$layout_path = TEMPLATEPATH . '/layouts/'; 
$layouts = array();

$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

$functions_path = TEMPLATEPATH . '/functions/';

$user_id = 1;
$user_blogs = get_blogs_of_user( $user_id );
$i=0;

//Mostramos en el select todos los blogs menos el principal y el de prueba
foreach ($user_blogs as $user_blog) {
    
    if(($user_blog->userblog_id != 1) && ($user_blog->blogname != 'Tema 1 de Valencia')){	
    	
    	$valencia_ids[$i] = $user_blog->userblog_id;
    	$valencia_blogs[$i] = $user_blog->blogname;
    
    	$i++;
    }
}

$valencia_articulo[0] = 'Último';
$valencia_articulo[1] = 'Penúltimo';
$valencia_articulo[2] = 'Antepenúltimo';


$valencia_articulo_id[0] = 1;
$valencia_articulo_id[1] = 2;
$valencia_articulo_id[2] = 3;

//Para los bloques 7, 8 y 9
$valencia_articulo_3_ultimos[0] = 'Último';
$valencia_articulo_3_ultimos[1] = 'Penúltimo';
$valencia_articulo_3_ultimos[2] = 'Antepenúltimo';
$valencia_articulo_3_ultimos[3] = 'No mostrar este bloque';


$valencia_articulo_id_3_ultimos[0] = 1;
$valencia_articulo_id_3_ultimos[1] = 2;
$valencia_articulo_id_3_ultimos[2] = 3;
$valencia_articulo_id_3_ultimos[3] = 0;

$valencia_modalidad[0] = 'Mostrar último artículo de la red';
$valencia_modalidad[1] = 'Mostrar artículo destacado';

$valencia_modalidad_ids[0] = 0;
$valencia_modalidad_ids[1] = 1;

// THESE ARE THE DIFFERENT FIELDS

$options = array (

				array(	"name" => "Opciones para el artículo destacado en la portada principal de la red de blogs de la Comunitat Valenciana",
						"type" => "heading"),
	
				array(	"name" => "Qué mostrar en portada: ",
						"desc" => "Elegir entre las opciones disponibles para mostrar en la portada principal: 1) el último artículo de la red de blogs de turismo, ó 2) el artículo destacado del blog que se elija.<br /><br />",
						"id" => $shortname."_modalidad",
						"std" => "",
						"type" => "select-deshabilitador",
						"options" => $valencia_modalidad,
						"ids" => $valencia_modalidad_ids),
						
				array(	"name" => "Blog: ",
						"desc" => "En caso de haber elegido en la opción de arriba: 'Mostrar artículo destacado',el artículo destacado de la portada principal será del blog que selecciones en esta opción. <br />En caso de que ese blog tenga algún artículo marcado como fijo, se mostrará el último de éste tipo, en otro caso se mostrará el último artículo de este blog.<br /><br /><br /><br />",
						"id" => $shortname."_blog",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids),
						
				array(	"name" => "Elige los blogs de donde se mostrarán las últimas noticias y si quieres mostrar la última, penúltima o antepenúltima noticia en cada caso.",
						"type" => "heading"),
				
				array(	"name" => "Blog bloque 1: ",
						"id" => $shortname."_bloque1",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 1: ",
						"id" => $shortname."_bloque1_articulo",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo,
						"ids" => $valencia_articulo_id)												    
				,
				
				array(	"name" => "Blog bloque 2: ",
						"id" => $shortname."_bloque2",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 2: ",
						"id" => $shortname."_bloque2_articulo",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo,
						"ids" => $valencia_articulo_id)												    
				,
				
				array(	"name" => "Blog bloque 3: ",
						"id" => $shortname."_bloque3",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 3: ",
						"id" => $shortname."_bloque3_articulo",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo,
						"ids" => $valencia_articulo_id)												    
				,
				
				array(	"name" => "Blog bloque 4: ",
						"id" => $shortname."_bloque4",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 4: ",
						"id" => $shortname."_bloque4_articulo",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo,
						"ids" => $valencia_articulo_id)												    
				,
				
				array(	"name" => "Blog bloque 5: ",
						"id" => $shortname."_bloque5",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 5: ",
						"id" => $shortname."_bloque5_articulo",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo,
						"ids" => $valencia_articulo_id)												    
				,
				
				array(	"name" => "Blog bloque 6: ",
						"id" => $shortname."_bloque6",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 6: ",
						"id" => $shortname."_bloque6_articulo",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo,
						"ids" => $valencia_articulo_id)												    
				,
				
				array(	"name" => "Blog bloque 7: ",
						"id" => $shortname."_bloque7",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 7: ",
						"id" => $shortname."_bloque7_articulo",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo_3_ultimos,
						"ids" => $valencia_articulo_id_3_ultimos)												    
				,
				
				array(	"name" => "Blog bloque 8: ",
						"id" => $shortname."_bloque8",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 8: ",
						"id" => $shortname."_bloque8_articulo",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo_3_ultimos,
						"ids" => $valencia_articulo_id_3_ultimos)												    
				,
				
				array(	"name" => "Blog bloque 9: ",
						"id" => $shortname."_bloque9",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_blogs,
						"ids" => $valencia_ids)												    
				,
				
				array(	"name" => "Artículo a mostrar en el bloque 9: ",
						"id" => $shortname."_bloque9_articulo",
						"desc" => "La numeración de los blogs va de arriba a abajo y de izquierda a derecha.",
						"std" => "Seleccione el blog:",
						"type" => "select",
						"options" => $valencia_articulo_3_ultimos,
						"ids" => $valencia_articulo_id_3_ultimos)
				,
				
				array(	"name" => "<br /><br />Opciones para editar el módulo de RSS del sidebar de este blog",
						"type" => "heading"),
	
				array(	"name" => "Enlace Suscripción: ",
						"desc" => "El enlace que pongas aquí será el del icono del RSS que hay en el sidebar.<br /><br />",
						"id" => $shortname."_rss",
						"std" => "",
						"type" => "text"),
					
					
				array(	"name" => "Código para el formulario de suscripción: ",
						"desc" => "Aquí debes poner el código de feedburner para el formulario de suscripción por email (uri).<br /><br />",
						"id" => $shortname."_email_rss",
						"std" => "",
						"type" => "text")									    
				);
				
				

// ADMIN PANEL

function valencia_add_admin() {

	 global $themename, $options;
	
	if ( $_GET['page'] == basename(__FILE__) ) {	
        if ( 'save' == $_REQUEST['action'] ) {
	
                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							update_option($up_opt, $_REQUEST[$up_opt] );
						}
					}
				}

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;						
							if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { delete_option( $up_opt ); } 
						}
					}
				}
						
				header("Location: admin.php?page=functions.php&saved=true");								
			
			die;

		} else if ( 'reset' == $_REQUEST['action'] ) {
			delete_option('sandbox_logo');
			
			header("Location: admin.php?page=functions.php&reset=true");
			die;
		}

	}

add_menu_page($themename." Opciones", $themename." Opciones", 'edit_themes', basename(__FILE__), 'valencia_page');
}


function valencia_page (){

		global $options, $themename, $manualurl;
		
		?>

<div class="wrap">

    			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">

						<h2><?php echo $themename; ?> Opciones</h2>

						<?php if ( $_REQUEST['saved'] ) { ?><div style="clear:both;height:20px;"></div><div class="warning"><?php echo $themename; ?> se ha actualizado</div><?php } ?>
						<?php if ( $_REQUEST['reset'] ) { ?><div style="clear:both;height:20px;"></div><div class="warning"><?php echo $themename; ?> se ha reseteado</div><?php } ?>						
						
						<div style="clear:both;height:20px;"></div>  			
						
						<!--START: GENERAL SETTINGS-->
     						
     						<table class="maintable">
     							
							<?php foreach ($options as $value) { ?>
	
									<?php if ( $value['type'] <> "heading" ) { ?>
	
										<tr class="mainrow">
										<td class="titledesc" style="margin: -5px 0 0 0;vertical-align:text-top;"><?php echo $value['name']; ?></td>
										<td class="forminp">
		
									<?php } ?>		 
	
									<?php
										
										switch ( $value['type'] ) {
										
										case 'select':?>
										
											<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" style="width: 300px">
	                						<?php $i=0; ?>
	                						<?php foreach ($value['options'] as $option) { ?>
	                							<?php $ids = $value['ids']; ?>
	                							<option<?php if ( get_settings( $value['id'] ) == $ids[$i]) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?> value="<?php echo $ids[$i]; ?>"><?php echo $option; ?></option>
	                							<?php $i++; ?>
	                						<?php } ?>
	            							</select><?php
		
										break;
										
										case 'select-deshabilitador':?>
										
											<select onMouseMove="if(this.value == 0){ this.form.valencia_blog.disabled = true; }else{ this.form.valencia_blog.disabled = false;}" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" style="width: 300px">
	                						<?php $i=0; ?>
	                						<?php foreach ($value['options'] as $option) { ?>
	                							<?php $ids = $value['ids']; ?>
	                							<option<?php if ( get_settings( $value['id'] ) == $ids[$i]) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?> value="<?php echo $ids[$i]; ?>"><?php echo $option; ?></option>
	                							<?php $i++; ?>
	                						<?php } ?>
	            							</select><?php
		
										break;
										
										case 'text': 
										?>
											<input size="80" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo get_settings( $value['id'] ); } else { echo $value['std']; } ?>" />
										
										<?php
										break;
										
										case "checkbox":
										
											if(get_settings($value['id'])) { $checked = "checked=\"checked\""; } else { $checked = ""; }?>
		            				
		            							<input type="checkbox" class="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> /> Actívalo si deseas mostrar los bloques 3, 6 y 9, es decir, los de la columna derecha.<?php
		
										break;
										
									
										
										case "heading":

									?>
									
										</table> 
		    							
		    									<h3 class="title"><?php echo $value['name']; ?></h3>
										
										<table class="maintable">
		
									<?php
										
										break;
										default:
										break;
									
									} ?>
	
									<?php if ( $value['type'] <> "heading" ) { ?>
	
										<?php if ( $value['type'] <> "checkbox" ) { ?><br/><br /><?php } ?><span><?php echo $value['desc']; ?></span>
										</td></tr>
	
									<?php } ?>		
									
							<?php } ?>	
							
							</table>	


							<p class="submit">
								<input name="save" type="submit" value="Guardar cambios" />    
								<input type="hidden" name="action" value="save" />
							</p>							
							
							<div style="clear:both;"></div>		
						
						<!--END: GENERAL SETTINGS-->						
             
            </form>



</div><!--wrap-->

<div style="clear:both;height:20px;"></div>
 
 <?php

};

add_action('admin_menu', 'valencia_add_admin');
add_action('admin_head', 'valencia_admin_head');

function show_social_icons(){ 

	$titulo_con_espacios = get_the_title();
	$titulo_sin_espacios = str_replace(" ", "+", $titulo_con_espacios);	
	$simbolos = array("á","é", "í", "ó", "ú", "Á", "É","Í", "Ó", "Ú", "$", "%", "&", "?", "¿", "¡", "!", "|", "@", "#", ",", ".", ":", ";", "_", "ç", "Ç", "{", "}", "´", "^", "[", "]", "*", "`", "¨", "<", ">");
	$titulo_para_tumblr = str_replace($simbolos, "", $titulo_sin_espacios);
	
	$enlace = get_permalink();
	$enlace_para_tumblr = str_replace("http://", "http%3A%2F%2F", $enlace);	
	?>
	<div class="social">
											<ul>
												<li><a href="http://twitter.com/home?status=<?php the_permalink(); ?>" title="Click para enviar a Twitter!" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-tt-small.gif" alt="Icono" width="16" height="16" /> <span class="skip">Twitter</span></a></span></li>
												<li><a href="http://delicious.com/post?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" title="Delicious" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-del-small.gif" alt="Icono" width="16" height="16" /> <span class="skip">Delicious</span></a></li>
												<li><a href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&t=<?php the_title(); ?>" target="blank" title="Facebook"><img src="<?php bloginfo('template_directory'); ?>/img/icos/ico-fb-small.gif" alt="Comparte en Facebook" width="16" height="16" /><span class="skip">Facebook</span></a></li>
												<li><a href="mailto:?subject=<?php echo get_the_title(); ?>&body=<?php echo get_permalink(); ?>" title="Email" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-mail-small.gif" alt="Icono" width="16" height="16" /> <span class="skip">Email</span></a></li>
												<li><a href="http://meneame.net/submit.php?ur<?php the_permalink(); ?>" title="Menéame" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-meneame.jpg" alt="Icono" width="16" height="16" /> <span class="skip">Menéame</span></a></li>
												<li><a href="http://digg.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" title="Digg" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-digg.jpg" alt="Icono" width="16" height="16" /> <span class="skip">Digg</span></a></li>
												<li><a href="http://www.stumbleupon.com/submit?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" title="Stumbleupon" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-stumbleupon.jpg" alt="Icono" width="16" height="16" /> <span class="skip">Stumbleupon</span></a></li>
												<li><a href="http://www.tumblr.com/share?v=3&u=<?php echo $enlace_para_tumblr; ?>&t=<?php echo $titulo_para_tumblr; ?>" title="Tumblr" target="_blank"><img src="<?php bloginfo('stylesheet_directory'); ?>/img/icos/ico-tumblr.jpg" alt="Icono" width="16" height="16" /> <span class="skip">Tumblr</span></a></li>
												<li class="add-to-any"><?php if( function_exists('ADDTOANY_SHARE_SAVE_KIT') ) { ADDTOANY_SHARE_SAVE_KIT(); } ?></li>
												
											</ul>
	</div><!-- end social --> 
<?php
}

function mes($mes)
{
  $res = "";
  if(date( 'F', mktime(0, 0, 0, $mes, 1, 2000) ) == "January")
    $res = "Enero";
  else if(date( 'F', mktime(0, 0, 0, $mes, 2, 2000) ) == "February")
    $res = "Febrero";
  else if(date( 'F', mktime(0, 0, 0, $mes, 3, 2000) ) == "March")
    $res = "Marzo";
  else if(date( 'F', mktime(0, 0, 0, $mes, 4, 2000) ) == "April")
    $res = "Abril";
  else if(date( 'F', mktime(0, 0, 0, $mes, 5, 2000) ) == "May")
    $res = "Mayo";
  else if(date( 'F', mktime(0, 0, 0, $mes, 6, 2000) ) == "June")
    $res = "Junio";
  else if(date( 'F', mktime(0, 0, 0, $mes, 7, 2000) ) == "July")
    $res = "Julio";
  else if(date( 'F', mktime(0, 0, 0, $mes, 8, 2000) ) == "August")
    $res = "Agosto";
  else if(date( 'F', mktime(0, 0, 0, $mes, 9, 2000) ) == "September")
    $res = "Septiembre";
  else if(date( 'F', mktime(0, 0, 0, $mes, 10, 2000) ) == "October")
    $res = "Octubre";
  else if(date( 'F', mktime(0, 0, 0, $mes, 11, 2000) ) == "November")
    $res = "Noviembre";
  else if(date( 'F', mktime(0, 0, 0, $mes, 12, 2000) ) == "December")
    $res = "Diciembre";
  return $res;
}


//Le ponemos que refresque la caché cada 60 seg.
add_filter( 'wp_feed_cache_transient_lifetime',
create_function( '$a', 'return 60;' ) );


function cargar_id_del_blog($mostrar_ult_art_o_destacado, $blog_del_articulo_destacado){
	global $wpdb;
	
	$id_del_blog = 3;
	//Si queremos mostrar el último artículo de la red de blogs ($mostrar_ult_art_o_destacado = 0 ), cogemos la url del último post y de la ruta sacamos el blog del que venía
    if($mostrar_ult_art_o_destacado == 0):
			
			$ruta_feed = get_bloginfo('home').'/?wpmu-feed=posts';
			// Get a SimplePie feed object from the specified feed source.
			$rss = fetch_feed($ruta_feed);
			if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
    				// Build an array of all the items, starting with element 0 (first element).
    				$rss_items = $rss->get_items(0, 1);
			endif;
					
    									
    		// Loop through each feed item and display each item as a hyperlink.
    		foreach ( $rss_items as $item ) :
    			$permalink = $item->get_permalink();
    			$fecha = $item->get_date('j F Y | g:i a');
    			$titulo = $item->get_title();
    			//echo $permalink; 
        								
        		$partes = pathinfo($permalink);
        		$dirname = $partes['dirname'];
        		$nombre_blog = explode("/", $dirname);
        		$path = '/'.$nombre_blog[3].'/';
        		$id_del_blog = $wpdb->get_var("SELECT blog_id FROM wp_blogs WHERE path = '".$path."'");
        		//echo 'ID:'.$id_del_blog;
        		?>
        										
    		<?php endforeach;
    		
    //En otro caso, significa que vamos a mostrar el artículo destacado del blog elegido
    else:
    	$id_del_blog = $blog_del_articulo_destacado;
    endif;
	
	return $id_del_blog;
}

function cargar_id_del_blog_con_ultima_noticia($mostrar_ult_art_o_destacado, $blog_del_articulo_destacado){
	global $wpdb;
	
	$id_del_blog = 3;
	//Si queremos mostrar el último artículo de la red de blogs ($mostrar_ult_art_o_destacado = 0 ), cogemos la url del último post y de la ruta sacamos el blog del que venía
    if($mostrar_ult_art_o_destacado == 0):
			
		//Cogemos la última noticia de cada blog y su fecha de publicación	
		$ult_articulo_blog_senderismo = $wpdb->get_results("SELECT ID, post_date, post_title FROM wp_2_posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
		foreach($ult_articulo_blog_senderismo as $ult_senderismo){ $senderismo = strtotime($ult_senderismo->post_date); }
		
		
		$ult_articulo_blog_buceo = $wpdb->get_results("SELECT ID, post_date, post_title FROM wp_3_posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
		foreach($ult_articulo_blog_buceo as $ult_buceo){$buceo = strtotime($ult_buceo->post_date);}
		
		
		$ult_articulo_blog_kitesurf = $wpdb->get_results("SELECT ID, post_date, post_title FROM wp_4_posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
		foreach($ult_articulo_blog_kitesurf as $ult_kitesurf){$kitesurf = strtotime($ult_kitesurf->post_date);}
		
		
		$ult_articulo_blog_windsurf = $wpdb->get_results("SELECT ID, post_date, post_title FROM wp_5_posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
		foreach($ult_articulo_blog_windsurf as $ult_windsurf){$windsurf = strtotime($ult_windsurf->post_date);}
		
		
		$ult_articulo_blog_surf = $wpdb->get_results("SELECT ID, post_date, post_title FROM wp_6_posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
		foreach($ult_articulo_blog_surf as $ult_surf){$surf = strtotime($ult_surf->post_date);}
		
		
		$ult_articulo_blog_btt = $wpdb->get_results("SELECT ID, post_date, post_title FROM wp_7_posts WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 1");
		foreach($ult_articulo_blog_btt as $ult_btt){$btt = strtotime($ult_btt->post_date);}
		
		
		//Comparamos las fechas de las seis últimas noticias (la última de cada blog) y nos quedamos con la más reciente
  		if ($senderismo-$buceo >= 0): $aux = $senderismo; $num_blog = 2; else: $aux = $buceo; $num_blog = 3; endif;
   		if ($aux-$kitesurf >= 0): else: $aux = $kitesurf; $num_blog = 4; endif;
   		if ($aux-$windsurf >= 0): else: $aux = $windsurf; $num_blog = 5; endif;
   		if ($aux-$surf >= 0): else: $aux = $surf; $num_blog = 6; endif;
   		if ($aux-$btt >= 0): else: $aux = $btt; $num_blog = 7; endif;
   		
   		$fecha = date('d/m/Y/H/i', $aux);
   		
   		//Guardamos el id del blog del artículo más reciente:
   		$id_del_blog = $num_blog;
    		
    //En otro caso, significa que vamos a mostrar el artículo destacado del blog elegido
    else:
    	$id_del_blog = $blog_del_articulo_destacado;
    endif;
	
	return $id_del_blog;
}


?>