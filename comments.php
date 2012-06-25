<div id="comments">

	<?php if ( post_password_required() ) : ?>
				<div class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'twentyten' ); ?></div>
			</div><!-- .comments -->
	<?php
		return;
	endif;
	?>

	<?php
	// You can start editing here -- including this comment!
	?>

	<?php if ( have_comments() ) : ?>
			

	<?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentyten' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyten' ) ); ?></div>
			</div>
	<?php endif; // check for comment navigation ?>

			<ol id="l-comets">
				<?php wp_list_comments( array( 'callback' => 'valencia_comment' ) ); ?>
			</ol>

	<?php if ( get_comment_pages_count() > 1 ) : // are there comments to navigate through ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentyten' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentyten' ) ); ?></div>
			</div>
	<?php endif; // check for comment navigation ?>

	<?php else : // this is displayed if there are no comments so far ?>

	<?php if ( comments_open() ) : // If comments are open, but there are no comments ?>

	<?php else : // if comments are closed ?>

		<p class="nocomments"><?php _e( 'Comments are closed.', 'twentyten' ); ?></p>

	<?php endif; ?>
	<?php endif; ?>


<!-- Aquí comienza el formulario de comentarios -->
	<br />
	<h3 class="tit-dejar-com">Comentarios</h3>
											
	<div id="post-com-w">
		<p class="info-xhtml-tags"><strong>Puedes utilizar las etiquetas más habituales de XHTML en tu comentario.</strong></p>
		<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" id="commentform">
		
			<?php if ( $user_ID ) : ?>
				<p>Estás logueado como: <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink()); ?>" title="Log out of this account">Desloguearme &raquo;</a></p>

			<?php else : ?>
			<fieldset>
				<legend class="skip">Formulario para comentar</legend>						   
													   
				<label for="name" class="skip">Nombre</label>
				<input id="name" type="text" name="author" class="input-name-c" value="<?php echo $comment_author; ?>" /> <strong>Nombre</strong><br />
													   
				<label for="email" class="skip">Email</label>
				<input id="email" type="text" name="email" class="input-mail-c" value="<?php echo $comment_author_email; ?>" /> <strong>Email</strong> (no será publicado)<br />
													   
				<label for="website" class="skip">Sitio web</label>
				<input id="website" type="text" name="website" class="input-webs-c" value="<?php echo $comment_author_url; ?>" /> <strong>Sitio web</strong><br />
				
				<?php endif; ?>
													   
				<label for="coment-area" class="skip">Comentario</label>
				<strong>Comentario</strong><br />
													   
				<textarea id="area-comentario" name="comment" rows="5"></textarea>
													   
				<br />
													   
				<label for="snd-com" class="skip">Enviar comentario</label>
				<p class="btn-snd-wrap">
					<input id="snd-com" type="submit" name="submit" value="Enviar" class="btn-snd-com" />
					<?php comment_id_fields(); ?>
				</p>

				<?php do_action('comment_form', $post->ID); ?>
			</fieldset>
		</form>
		
		<?php /*
		<h4>Previsualización</h4>
			<div id="preview-post">
				<p>...</p>
			</div><!-- end preview-post -->
			
			*/ ?>
			<p class="com-sus"><a href="#" title="Suscripción a comentarios">Suscribirse a la los comentarios</a> (recibirás un mail cada vez que alguien responda).</p>
			<br class="clear" />
		
	</div><!-- end post-com-w -->

</form>

<!-- Aquí termina el formulario de comentarios -->

</div><!-- #comments -->