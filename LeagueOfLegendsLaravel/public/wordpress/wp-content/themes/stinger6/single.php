<?php get_header(); ?>

<div id="content" class="clearfix">
	<div id="contentInner">
		<main <?php st_text_copyck(); ?>>
			<article>
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<!--ぱんくず -->
					<div id="breadcrumb">
						<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
							<a href="<?php echo home_url(); ?>" itemprop="url"> <span itemprop="title">ホーム</span>
							</a> &gt; </div>
						<?php $postcat = get_the_category(); ?>
						<?php $catid = $postcat[0]->cat_ID; ?>
						<?php $allcats = array( $catid ); ?>
						<?php
						while ( !$catid == 0 ) {
							$mycat = get_category( $catid );
							$catid = $mycat->parent;
							array_push( $allcats, $catid );
						}
						array_pop( $allcats );
						$allcats = array_reverse( $allcats );
						?>
						<?php foreach ( $allcats as $catid ): ?>
							<div itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
								<a href="<?php echo get_category_link( $catid ); ?>" itemprop="url">
									<span itemprop="title"><?php echo esc_html( get_cat_name( $catid ) ); ?></span> </a> &gt; </div>
						<?php endforeach; ?>
					</div>
					<!--/ ぱんくず -->

					<!--ループ開始 -->
					<?php if (have_posts()) : while (have_posts()) :
					the_post(); ?>
					<h1 class="entry-title">
						<?php the_title(); //タイトル ?>
					</h1>

					<div class="blogbox <?php st_hidden_class(); ?>">
						<p><span class="kdate"><i class="fa fa-calendar"></i>&nbsp;
                <time class="entry-date date updated" datetime="<?php the_time(DATE_W3C); ?>">
	                <?php the_time( 'Y/m/d' ); ?>
                </time>
                &nbsp;
								<?php if ( $mtime = st_get_mtime( 'Y/m/d' ) ) {
									echo ' <i class="fa fa-repeat"></i>&nbsp; ', $mtime;
								} ?>
                </span></p>
					</div>

					<?php the_content(); //本文 ?>

					<?php if ( function_exists( 'dynamic_sidebar' ) && dynamic_sidebar( 5 ) ) : else : ?>
					<?php endif; ?>

					<?php wp_link_pages(); ?>


					<p class="tagst"><i class="fa fa-tags"></i>&nbsp;-
						<?php the_category( ', ' ) ?>
						<?php the_tags( '', ', ' ); ?>
					</p>
					<aside>
						<div style="padding:20px 0px;">
							<?php get_template_part( 'ad' ); //アドセンス読み込み ?>
							<?php if ( st_is_mobile() ) { //スマホの場合 ?>
							<?php } else { //PCの場合 ?>
								<div class="smanone" style="padding-top:10px;">
									<?php get_template_part( 'ad' ); //アドセンス読み込み ?>
								</div>
							<?php } ?>
						</div>
						<?php get_template_part( 'sns' ); //ソーシャルボタン読み込み ?>

						<p class="author">
						<?php
						if ( isset($GLOBALS['stdata17']) && $GLOBALS['stdata17'] === 'yes' ) { ?>
							執筆者：<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a>
						<?php } ?>
						</p>

						<?php endwhile; else: ?>
							<p>記事がありません</p>
						<?php endif; ?>
						<!--ループ終了-->
						<?php
						if ( ( $GLOBALS["stdata6"] ) === '' ) { ?>
							<?php if ( comments_open() || get_comments_number() ) {
								comments_template();
							} ?>
						<?php } ?>
						<!--関連記事-->
						<h4 class="point"><i class="fa fa-th-list"></i>&nbsp; 関連記事</h4>
						<?php get_template_part( 'kanren' ); ?>

						<!--ページナビ-->
						<div class="p-navi clearfix">
							<dl>
								<?php
								$prev_post = get_previous_post();
								if ( !empty( $prev_post ) ): ?>
									<dt>PREV</dt>
									<dd>
										<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"><?php echo $prev_post->post_title; ?></a>
									</dd>
								<?php endif; ?>
								<?php
								$next_post = get_next_post();
								if ( !empty( $next_post ) ): ?>
									<dt>NEXT</dt>
									<dd>
										<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"><?php echo $next_post->post_title; ?></a>
									</dd>
								<?php endif; ?>
							</dl>
						</div>
					</aside>
				</div>
				<!--/post-->
			</article>
		</main>
	</div>
	<!-- /#contentInner -->
	<?php get_sidebar(); ?>
</div>
<!--/#content -->
<?php get_footer(); ?>
