<?php if ( isset($GLOBALS["stdata20"]) && $GLOBALS["stdata20"] === 'yes' ) {
	if ( trim( $GLOBALS["stdata21"] ) !== '' ) {
		$newsid = esc_attr( $GLOBALS["stdata21"] );
	} else {
		$newsid = 0 ;
	}
	if ( trim( $GLOBALS["stdata22"] ) !== '' ) {
		$newspost = esc_attr( $GLOBALS["stdata22"] );
	} else {
		$newspost = 5 ;
	}

	if ( trim( $GLOBALS["stdata28"] ) !== '' ) {
		$catid = esc_attr( $GLOBALS["stdata28"] );
		$catno = get_category( $catid );
		$catname = $catno->cat_name;
	}else{
		$catname = 'お知らせ';
	}
 
	the_date();?>

	<div id="topnews-box" class="clearfix">
		<div class="rss-bar"><span class="news-ca"><a href="<?php bloginfo('rss2_url'); ?>"><i class="fa fa-rss-square"></i></a>&nbsp;<?php echo $catname; ?></span></div>
	<div>
	<?php $news_posts = get_posts('numberposts=' . $newspost . '&category=' . $newsid . '&order=desc'); ?>
		<?php foreach($news_posts as $post): ?>
		<dl>
			<dt>
				<span><?php the_time('Y.m.d'); ?></span>
			</dt>
			<dd>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
			</dd>
		</dl>
	<?php endforeach; ?>
	</div></div>
<?php }?>