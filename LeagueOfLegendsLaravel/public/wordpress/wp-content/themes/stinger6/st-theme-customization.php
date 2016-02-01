<?php

function st_customize_register( $wp_customize ) {

	$wp_customize->add_section( 'st_logo_image',
		array(
			'title' => 'ロゴ画像',
			'priority' => 10,
		) );

	$wp_customize->add_setting( 'st_logo_image',
		array(
			'default' => '',
			'type' => 'option',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'esc_url_raw',
		) );

	$wp_customize->add_control( new WP_Customize_Image_Control(
		$wp_customize,
		'logo_Image',
		array(
			'label' => '画像',
			'section' => 'st_logo_image',
			'settings' => 'st_logo_image',
		)
	) );

if ( isset( $GLOBALS["stdata1"] ) && $GLOBALS["stdata1"] === 'yes' ) {

		// Color
		$wp_customize->add_section( 'st_menu_customize',
			array(
				'title' => __( '基本色（キーカラー）', 'st' ),
				'priority' => 30,
			) );

		$wp_customize->add_setting( 'st_menu_logocolor',
			array(
			'default' => '#1a1a1a',
			'sanitize_callback' => 'sanitize_hex_color',
			'sanitize_js_callback' => 'maybe_hash_hex_color',
			) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'st_menu_logocolor', array(
			'label' => __( 'タイトル色など', 'st' ),
			'section' => 'st_menu_customize',
			'settings' => 'st_menu_logocolor',
		) ) );

		$wp_customize->add_setting( 'st_menu_bgcolor', 			
			array(
			'default' => '#f3f3f3',
			'sanitize_callback' => 'sanitize_hex_color',
			'sanitize_js_callback' => 'maybe_hash_hex_color',
			) );

		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'st_menu_bgcolor', array(
			'label' => __( '吹き出し背景色', 'st' ),
			'section' => 'st_menu_customize',
			'settings' => 'st_menu_bgcolor',
		) ) );

		$wp_customize->add_setting( 'st_menu_color', 
			array(
			'default' => '#1a1a1a',
			'sanitize_callback' => 'sanitize_hex_color',
			'sanitize_js_callback' => 'maybe_hash_hex_color',
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'st_menu_color', array(
			'label' => __( '吹き出し内の文字色（H2）', 'st' ),
			'section' => 'st_menu_customize',
			'settings' => 'st_menu_color',
		) ) );

		$wp_customize->add_setting( 'st_rss_color', 
			array(
			'default' => '#f3f3f3',
			'sanitize_callback' => 'sanitize_hex_color',
			'sanitize_js_callback' => 'maybe_hash_hex_color',
			) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'st_rss_color', array(
			'label' => __( 'RSS（購読する）ボタン', 'st' ),
			'section' => 'st_menu_customize',
			'settings' => 'st_rss_color',
		) ) );
	}
}

	add_action( 'customize_register', 'st_customize_register' );


if ( isset( $GLOBALS["stdata1"] ) && $GLOBALS["stdata1"] === 'yes' ) {

	function st_customize_css() {
		//初期カラー
		$menu_color = get_theme_mod( 'st_menu_color', '#1a1a1a' );
		$menu_bgcolor = get_theme_mod( 'st_menu_bgcolor', '#f3f3f3' );
		$menu_logocolor = get_theme_mod( 'st_menu_logocolor', '#1a1a1a' );
		$menu_rsscolor = get_theme_mod( 'st_rss_color', '#87BF31' );
		?>

		<style type="text/css">
			/*グループ1
			------------------------------------------------------------*/
			/*ブログタイトル*/

			header .sitename a {
				color: <?php echo $menu_logocolor;
?>;
			}

			/* メニュー */
			nav li a {
				color: <?php echo $menu_logocolor;
?>;
			}

			/*キャプション */

			header h1 {
				color: <?php echo $menu_logocolor;
?>;
			}

			header .descr {
				color: <?php echo $menu_logocolor;
?>;
			}

			/* アコーディオン */
			#s-navi dt.trigger .op {
				color: <?php echo $menu_logocolor;
?>;
			}

			.acordion_tree li a {
				color: <?php echo $menu_logocolor;
?>;
			}

			/* サイド見出し */
			aside h4 {
				color: <?php echo $menu_logocolor;
?>;
			}

			/* フッター文字 */
			#footer, #footer .copy {
				color: <?php echo $menu_logocolor;
?>;
			}

			/*グループ2
			------------------------------------------------------------*/
			/* 中見出し */
			h2 {
				background: <?php echo $menu_bgcolor;
?>;
				color: <?php echo $menu_color;
?>;
			}

			h2:after {
				border-top: 10px solid <?php echo $menu_bgcolor;
?>;
			}

			h2:before {
				border-top: 10px solid <?php echo $menu_bgcolor;
?>;
			}

			/*小見出し*/
			.post h3 {
				border-bottom: 1px <?php echo $menu_bgcolor;
?> dotted;
			}

			/* 記事タイトル下の線 */
			.blogbox {
				border-top-color: <?php echo $menu_bgcolor;
?>;
				border-bottom-color: <?php echo $menu_bgcolor;
?>;
			}

			/*グループ4
			------------------------------------------------------------*/
			/* RSSボタン */
			.rssbox a {
				background-color: <?php echo $menu_rsscolor;
?>;
			}
		</style>
		<?php
	}

	add_action( 'wp_head', 'st_customize_css' );
}
