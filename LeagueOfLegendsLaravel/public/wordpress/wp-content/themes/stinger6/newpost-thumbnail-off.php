<div id="kanren">
	<?php
	$args = array(
		'posts_per_page' => 5,
	);
	$st_query = new WP_Query( $args );
	?>
	<?php if ( $st_query->have_posts() ): ?>
		<?php while ( $st_query->have_posts() ) : $st_query->the_post(); ?>
			
		<div class="no-thumbitiran">
			<h5><a href="<?php the_permalink(); ?>">
			<?php the_title(); ?>
			</a></h5>

			<div class="smanone">
				<?php the_excerpt(); //スマートフォンには表示しない抜粋文 ?>
			</div>
		</div>

		<?php endwhile; ?>
	<?php else: ?>
		<p>新しい記事はありません</p>
	<?php endif; ?>
	<?php wp_reset_postdata(); ?>
</div>