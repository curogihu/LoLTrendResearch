<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>
<html class="i7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>
<html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!-->
<html <?php language_attributes(); ?>>
	<!--<![endif]-->
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" >
		<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no">
		<meta name="format-detection" content="telephone=no" >

		<?php if ( is_home() && !is_paged() ): ?>
			<meta name="robots" content="index,follow">
		<?php elseif ( is_search() or is_404() ): ?>
			<meta name="robots" content="noindex,follow">
		<?php elseif ( !is_category() && is_archive() ): ?>
			<meta name="robots" content="noindex,follow">
		<?php elseif ( is_paged() ): ?>
			<meta name="robots" content="noindex,follow">
		<?php elseif ( trim($GLOBALS["stdata9"]) !== '' &&  ($GLOBALS["stdata9"]) == $post->ID ): ?>
			<meta name="robots" content="noindex,follow">
		<?php elseif ( is_category() && trim($GLOBALS["stdata15"]) !== ''): ?>
			<meta name="robots" content="noindex,follow">
		<?php endif; ?>

		<link rel="stylesheet" href="<?php echo esc_url( get_template_directory_uri() ); ?>/css/normalize.css">
		<link rel="stylesheet" href="<?php echo esc_url( get_stylesheet_uri() ); ?>" type="text/css" media="screen" >
		<link rel="alternate" type="application/rss+xml" title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?> RSS Feed" href="<?php bloginfo( 'rss2_url' ); ?>" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" >
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

		<!--[if lt IE 9]>
		<script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
		<script src="<?php echo esc_url( get_template_directory_uri() ) ; ?>/js/html5shiv.js"></script>
		<![endif]-->
		<?php if ( is_singular() ) wp_enqueue_script( "comment-reply" ); ?>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?> >
		<?php include_once( "analyticstracking.php" ) //アナリティクスコード ?>
	
		<div id="wrapper">
			<!-- アコーディオン -->
			<nav id="s-navi" class="pcnone">
				<dl class="acordion">
					<dt class="trigger">
					<p><span class="op"><i class="fa fa-bars"></i>&nbsp; MENU</span></p>
					</dt>
					<dd class="acordion_tree">
						<?php
							$defaults = array(
							'theme_location' => 'primary-menu',
							);
						?>
						<?php wp_nav_menu( $defaults ); ?>
						<div class="clear"></div>
					</dd>
				</dl>
			</nav>
			<!-- /アコーディオン -->
			<header>
				<!-- ロゴ又はブログ名 -->
				<p class="sitename"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php if ( get_option( 'st_logo_image' ) ): //ロゴ画像がある時 ?>
							<img alt="<?php bloginfo( 'name' ); ?>" src="<?php echo esc_url( get_option( 'st_logo_image' ) ); ?>" >
						<?php else: //ロゴ画像が無い時 ?>
							<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>
						<?php endif; ?>
					</a></p>
				<!-- キャプション -->
				<?php if ( is_home() ) { ?>
					<h1 class="descr">
						<?php bloginfo( 'description' ); ?>
					</h1>
				<?php } else { ?>
					<p class="descr">
						<?php bloginfo( 'description' ); ?>
					</p>
				<?php } ?>

				<?php get_template_part( 'st-header-image' ); //カスタムヘッダー画像 ?>

				<!--
				メニュー
				-->
				<?php
					$defaults = array(
					'container' => 'nav',
					'container_class' => 'smanone clearfix',
					'theme_location' => 'primary-menu',
					);
				?>
				<?php if ( has_nav_menu( 'primary-menu' ) ) : ?>
					<?php wp_nav_menu( $defaults ); ?>
				<?php else : ?>
					<nav class="smanone clearfix">
					<?php wp_page_menu( $defaults ); ?>
					</nav>
				<?php endif; ?>

			</header>
