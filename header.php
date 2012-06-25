<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 * We filter the output of wp_title() a bit -- see
	 * twentyten_filter_wp_title() in functions.php.
	 */
	wp_title( '|', true, 'right' );

	?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
		<!--[if lt IE 7]>
			<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js" type="text/javascript"></script>
		<![endif]--> 

		<!--[if lt IE 8]>
			<script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE8.js" type="text/javascript"></script>
		<![endif]--> 
<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/favicon.ico" type="image/x-icon" /> 
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	
	wp_head();
?>

<?php 
	$url_blog_actual = get_bloginfo('url'); 
	$url_blog_principal = explode('/',$url_blog_actual);
?>
</head>

	<body>
		<div id="fondo">
			<div id="wrapper">
				<div id="header" class="left-align">
					<div id="mainnav">
						<div class="head-box">
							<ul class="mainnavegation">
								<li><a href="<?php bloginfo('home'); ?>" title="Inicio" accesskey="0" <?php if (is_home()) echo 'class=" selected"'; ?>>Inicio</a></li>
								<li><a href="http://<?php echo $url_blog_principal[2]; ?>/" title="Inicio" accesskey="0">Red de blogs</a></li>
								<li><a href="<?php bloginfo('home'); ?>/contacto/" title="Contacto" accesskey="1"<?php if (is_page('28')) echo 'class="selected"'; ?>>Contacto</a></li>
								<li><a href="<?php bloginfo('home'); ?>/acerca-de/" title="Acerca de" accesskey="2"<?php if (is_page('2')) echo 'class="selected"'; ?>>Acerca de</a></li>
							</ul>
						</div><!-- end head-box -->
						
						<div class="head-box fright">
							<ul class="metanav">
								<?php								
									if ( is_active_sidebar( 'second-header-widget-area' ) ) : // Nothing here by default and design
										dynamic_sidebar( 'second-header-widget-area' );	
									else:
									endif;								
								?>
								
							</ul>
						</div><!-- end head-box -->
						
						<br class="clear" />
					</div><!-- end mainnav -->
					<h1 id="logo"><a href="<?php bloginfo('home'); ?>" title="Al inicio" accesskey="0">Blogs Comunitat Valenciana</a></h1>
				</div><!-- end header -->