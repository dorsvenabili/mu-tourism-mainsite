<?php
	/*
		Template Name: Confirmación
	*/
?>
<?php
define( "WP_INSTALLING", true );

/** Sets up the WordPress Environment. */
require( ABSPATH . '/wp-load.php' );

require( './wp-blog-header.php' );

do_action("activate_header");

function do_activate_header() {
	do_action("activate_wp_head");
}
add_action( 'wp_head', 'do_activate_header' );

function wpmu_activate_stylesheet() {
	?>
	<style type="text/css">
		#submit, #key { width: 90%; font-size: 24px; }
		#language { margin-top: .5em; }
		.error { background: #f66; }
		span.h3 { padding:0 8px; font-size:1.3em; font-family:'Trebuchet MS','Lucida Grande',Verdana,Arial,Sans-Serif; font-weight:700; color:#333333; }
	</style>
	<?php
}
add_action( 'wp_head', 'wpmu_activate_stylesheet' );

// Notify user of signup success.
function mecus_wpmu_signup_blog_notification($key) {
	global $current_site, $wpdb;
	
	$datos_blog = $wpdb->get_results("SELECT * FROM wp_signups WHERE activation_key = '".$key."' LIMIT 1");
	
	foreach($datos_blog as $dato_blog){

		$domain = $dato_blog->domain;
		$path = $dato_blog->path;
		$title = $dato_blog->title;
		$user = $dato_blog->user_login;
		$user_email = $dato_blog->user_email;
		$meta = $dato_blog->meta;
	}
		
	//if ( !apply_filters('wpmu_signup_blog_notification', $domain, $path, $title, $user, $user_email, $key, $meta) )
	//	return false;

	// Send email with activation link.
	if ( !is_subdomain_install() || $current_site->id != 1 )
		$activate_url = network_site_url("wp-activate.php?key=$key");
	else
		$activate_url = "http://{$domain}{$path}wp-activate.php?key=$key"; // @todo use *_url() API

	$activate_url = esc_url($activate_url);
	$admin_email = get_site_option( 'admin_email' );
	if ( $admin_email == '' )
		$admin_email = 'support@' . $_SERVER['SERVER_NAME'];
	$from_name = get_site_option( 'site_name' ) == '' ? 'WordPress' : esc_html( get_site_option( 'site_name' ) );
	$message_headers = "From: \"{$from_name}\" <{$admin_email}>\n" . "Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
	$message = sprintf( apply_filters( 'wpmu_signup_blog_notification_email', __( "Mensaje para el administrador:\n\nUn nuevo blog ha sido creado en la red de blogs de Turismo de la Comunidad Valenciana.  El usuario ya ha respondido al email de confirmación de su cuenta de email. Para activar dicho blog, por favor haz click sobre el siguiente enlace:\n\n%s\n\nDespués de activarlo, el usuario recibirá un email con sus datos de usuario y contraseña. Si no desearas activar el blog, tan sólo ignora este email y en dos días se eliminarán los datos de la Base de Datos.\n\nUna vez activado el blog, no olvides configurar los plugins y los widgets por defecto. Podrás visitar el sitio en:\n\n%s" ) ), $activate_url, esc_url( "http://{$domain}{$path}" ), $key );
	// TODO: Don't hard code activation link.
	$subject = sprintf( apply_filters( 'wpmu_signup_blog_notification_subject', __( '[%1s] Activate %2s' ) ), $from_name, esc_url( 'http://' . $domain . $path ) );
	wp_mail($admin_email, $subject, $message, $message_headers);
	
	
	
	return true;
}


get_header();
?>

<?php
	$key = !empty($_GET['key']) ? $_GET['key'] : '';
	
?>


<div id="content" class="widecolumn">
	<?php if ( empty($key) ) { ?>

		<h2><?php _e('Activation Key Required') ?></h2>
		<form name="activateform" id="activateform" method="post" action="<?php echo network_site_url('wp-activate.php'); ?>">
			<p>
			    <label for="key"><?php _e('Activation Key:') ?></label>
			    <br /><input type="text" name="key" id="key" value="" size="50" />
			</p>
			<p class="submit">
			    <input id="submit" type="submit" name="Submit" class="submit" value="<?php esc_attr_e('Activate') ?>" />
			</p>
		</form>

	<?php } else {

		mecus_wpmu_signup_blog_notification($key);
		
		?>
		<h2><?php _e('Tu petición ya ha sido enviada.'); ?></h2>
		<p>Tu email de confirmación ya ha sido enviado al administrador del sitio. En los próximos días recibirás un email aceptando o denegando tu solicitud. En caso de ser aceptada, dicho email contendrá tus datos de usuario y claves para acceder y empezar a escribir en tu nuevo blog.</p>
		<p>Un saludo del equipo de Blogs de Turismo de la Comunitat Valenciana</p>
		
				
	<?php } ?>
	
</div>

<script type="text/javascript">
	var key_input = document.getElementById('key');
	key_input && key_input.focus();
</script>
<?php get_footer(); ?>