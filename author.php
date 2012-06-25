<?php
/**
 * The template for displaying Author Archive pages.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
global $switched;

get_header(); ?>
		
<?php
	/* Queue the first post, that way we know who
	 * the author is when we try to get their name,
	 * URL, description, avatar, etc.
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
?>
				<div id="main" class="left-align">
					<div id="container">
						
						<div id="content">
							<div class="navigation skip" id="nav-above">
								<div class="nav-previous"><?php previous_post_link('<strong>%link</strong>'); ?></div>
								<div class="nav-next"><?php next_post_link('<strong>%link</strong>'); ?> </div>
							</div><!-- end nav-above -->
							
							<div class="post" id="post">

								<div id="left-post-content">
									<h2 class="page-title author titulo-archivo"><?php printf( __( 'Artículos de: %s', 'twentyten' ), "<span class='vcard'>" . strtolower(get_the_author()) . "</span>" ); ?></h2>

									<?php
										// If a user has filled out their description, show a bio on their entries.
										if ( get_the_author_meta( 'description' ) ) : ?>
											<div id="entry-author-info">
												<div id="author-avatar">
													<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyten_author_bio_avatar_size', 60 ) ); ?>
												</div><!-- #author-avatar -->
												<div id="author-description">
													<h2><?php printf( __( 'Acerca de %s', 'twentyten' ), get_the_author() ); ?></h2>
													<?php the_author_meta( 'description' ); ?>
												</div><!-- #author-description	-->
											</div><!-- #entry-author-info -->
											<br class="clear" />
									<?php endif; ?>
<?php
$user_id = 1;
$user_blogs = get_blogs_of_user( $user_id );

foreach ($user_blogs as $user_blog) {
	  
	if($user_blog->userblog_id != 1){
		switch_to_blog($user_blog->userblog_id);
	
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		echo 'pagina:'.$paged;
		
		 //query_posts('posts_per_page=8&paged='.$paged);
	
		//$args = array('posts_per_page' => 10, 'author' => get_the_author_meta( 'ID' ), 'paged' => $paged );
		query_posts('posts_per_page=10&author='.get_the_author_meta( 'ID' ).'&paged=1');
	
	
		if ( have_posts() )
			the_post();
?>

				<div class="navigation skip" id="nav-above">
					<div class="nav-previous"><?php previous_post_link('<strong>%link</strong>'); ?></div>
					<div class="nav-next"><?php next_post_link('<strong>%link</strong>'); ?> </div>
				</div><!-- end nav-above -->
									
									<?php
										/* Since we called the_post() above, we need to
	 									* rewind the loop back to the beginning that way
	 									* we can run the loop properly, in full.
	 									*/
										rewind_posts();

										/* Run the loop for the author archive page to output the authors posts
	 									* If you want to overload this in a child theme then include a file
	 									* called loop-author.php and that will be used instead.
	 									*/
	 									get_template_part( 'loop', 'author' );
									?>
									
											
							
						
	<?php restore_current_blog(); 
	}//End if($user_blog->userblog_id)	
}//End foreach ($user_blogs AS $user_blog) ?>

								<div id="div-paginacion-archivo">
									<div class="separator-h">
										<hr />
									</div><!-- end separator-h -->
							
									<div id="div-paginacion-archive"><?php wp_pagenavi(); ?></div>
								</div>	

</div><!-- end left-post-content -->				
															
<?php get_sidebar(); ?>
<?php get_footer(); ?>