<h3 class="tit-related-news">Noticias relacionadas</h3>
	
<?php if ($related_query->have_posts()):?>
	
	<?php $i = 0; ?>
	
	<?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
	
		<?php if($i == 0): ?>
			<div class="minibox f-left">
			<p><a href="<?php the_permalink(); ?>" title="Foto de la entrada" class="img-rpa"><?php the_post_thumbnail('thumbnail'); ?></a><br class="clear" /></p>
			<h4><a href="<?php the_permalink(); ?>" title="Título de la entrada"><?php the_title(); ?></a></h4>
			<p class="read-more"><a href="<?php the_permalink(); ?>" title="Leer más">leer más</a></p>
			<br class="clear" />
			</div><!-- end minibox -->
	
		<?php elseif($i == 1): ?>
			<div class="minibox">
			<p><a href="<?php the_permalink(); ?>" title="Foto de la entrada" class="img-rpa"><?php the_post_thumbnail('thumbnail'); ?></a><br class="clear" /></p>
			<h4><a href="<?php the_permalink(); ?>" title="Título de la entrada"><?php the_title(); ?></a></h4>
			<p class="read-more"><a href="<?php the_permalink(); ?>" title="Leer más">leer más</a></p>
			<br class="clear" />
			</div><!-- end minibox -->
	
		<?php elseif($i == 2): ?>
			<div class="minibox last-r">
			<p><a href="<?php the_permalink(); ?>" title="Foto de la entrada" class="img-rpa"><?php the_post_thumbnail('thumbnail'); ?></a><br class="clear" /></p>
			<h4><a href="<?php the_permalink(); ?>" title="Título de la entrada"><?php the_title(); ?></a></h4>
			<p class="read-more"><a href="<?php the_permalink(); ?>" title="Leer más">leer más</a></p>
			<br class="clear" />
			</div><!-- end minibox -->
		<?php endif; ?>
		
		<?php $i++; ?>
	
	<?php endwhile; ?>

<?php else: ?>

	<p><?php echo 'No hay artículos relacionados';?></p>

<?php endif; ?>