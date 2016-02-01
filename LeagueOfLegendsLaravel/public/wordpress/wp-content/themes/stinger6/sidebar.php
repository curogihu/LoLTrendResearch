<div id="side">
	<aside>
		<?php if ( is_404() ) { ?>
		<?php } else { ?>
			<div class="ad">
				<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 4 ) ) : else : //アドセンス ?>
				<?php endif; ?>
			</div>
		<?php } ?>
		
		<?php if ( $GLOBALS["stdata16"] === '' ) { ?>
			<!-- RSSボタンです -->
			<div class="rssbox">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>/?feed=rss2"><i class="fa fa-rss-square"></i>&nbsp;購読する</a></div>
			<!-- RSSボタンここまで -->
		<?php } ?>

		<?php get_template_part( 'newpost' ); //最近のエントリ ?>

		<div id="mybox">
			<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 1 ) ) : else : //サイドウイジェット読み込み ?>
			<?php endif; ?>
		</div>

		<div id="scrollad">
			<?php get_template_part( 'scroll-ad' ); //追尾式広告 ?>
		</div>
	</aside>
</div>
<!-- /#side -->
