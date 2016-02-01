<?php get_header(); ?>

<div id="content" class="clearfix">
	<div id="contentInner">
		<main <?php st_text_copyck(); ?>>
			<article>
				<?php if (!is_paged()): ?>
					<?php get_template_part( 'news-st' ); //お知らせ ?>
					<?php get_template_part( 'st-topin' ); //固定記事挿入 ?>
				<?php endif; ?>

				<aside>		
					<?php get_template_part( 'itiran' ); ?>
				</aside>
				<?php get_template_part( 'sns-top' ); //ソーシャルボタン読み込み ?>
				<?php get_template_part( 'st-pagenavi' ); //ページナビ読み込み ?>
			</article>
		</main>
	</div>
	<!-- /#contentInner -->
	<?php get_sidebar(); ?>
</div>
<!-- /#content -->
<?php get_footer(); ?>