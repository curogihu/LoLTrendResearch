<?php
// 直接アクセスを禁止
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if (locate_template('/st-kanri.php') !== '') {
require_once locate_template('/st-kanri.php');
}


if (locate_template('/st-theme-customization.php') !== '') {
require_once locate_template('/st-theme-customization.php');
}

if ( !function_exists( 'st_after_setup_theme' ) ) {
	/**
	 * テーマの初期設定
	 */
	function st_after_setup_theme() {
		add_theme_support( 'title-tag' );
	}

	add_action( 'after_setup_theme', 'st_after_setup_theme' );
}

if ( !function_exists( 'st_register_jq_script' ) ) {
	/**
	 * jQueryの読み込み
	 */
	function st_register_jq_script() {
		wp_deregister_script( 'jquery' );

		wp_enqueue_script(
			'jquery',
			'//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js',
			array(),
			'1.11.3',
			false
		);
	}

	add_action( 'wp_enqueue_scripts', 'st_register_jq_script' );
}

if (!function_exists('st_auto_post_slug')) {
	/**
	 * WordPress の投稿スラッグを自動的に生成する
	 */
	function st_auto_post_slug( $slug, $post_ID, $post_status, $post_type ) {
		if ( preg_match( '/(%[0-9a-f]{2})+/', $slug ) ) {
			$slug = utf8_uri_encode( $post_type ) . '-' . $post_ID;
		}

		return $slug;
	}

	if ( isset($GLOBALS['stdata4']) && $GLOBALS['stdata4'] === 'yes' ) {
		add_filter( 'wp_unique_post_slug', 'st_auto_post_slug', 10, 4 );
	}
}

// カスタム背景
$custom_bgcolor_defaults = array(
	'default-color' => '#f2f2f2',
);
add_theme_support( 'custom-background', $custom_bgcolor_defaults );

// カスタムヘッダー
$custom_header = array(
	'random-default' => false,
	'width' => 1060,
	'height' => 400,
	'flex-height' => true,
	'flex-width' => false,
	'default-text-color' => '',
	'header-text' => false,
	'uploads' => true,
	'default-image' => get_stylesheet_directory_uri() . '/images/af.png',
);
add_theme_support( 'custom-header', $custom_header );

if (!function_exists('st_custom_excerpt_length')) {
	/**
	 * 抜粋の長さを変更する
	 */
    function st_custom_excerpt_length($length) {
	    return 40;
    }

	add_filter( 'excerpt_length', 'st_custom_excerpt_length', 999 );
}

// 文末文字を変更する
if ( !function_exists( 'st_custom_excerpt_more' ) ) {
	function st_custom_excerpt_more( $more ) {
		return ' ... ';
	}

	add_filter( 'excerpt_more', 'st_custom_excerpt_more' );
}

if ( !function_exists( 'st_wrap_h3' ) ) {
	/**
	 * h3にチェックマークのアイコン
	 */
	function st_wrap_h3( $the_content ) {
			$the_content = preg_replace(
				'!(<h3(?:\s+[^>]*)?>)(.+?)(</h3>)!is',
				'$1<i class="fa fa-check-circle"></i><span style="margin-left:15px;">$2</span>$3',
				$the_content
			);

		return $the_content;
	}

	if ( isset($GLOBALS['stdata3']) && $GLOBALS['stdata3'] === 'yes' ) {
		add_filter( 'the_content', 'st_wrap_h3' );
	}
}

if ( !function_exists( 'st_in_hatena' ) ) {
	/**
	 * はてなマークのアイコン挿入
	 */
	function st_in_hatena( $the_content ) {
			$the_content = preg_replace(
				'!(<p class="hatenamark(?:\s+[^>]*)?">)(.+?)(</p>)!is',
				'$1<i class="fa fa-question-circle"></i><span style="margin-left:10px;">$2</span>$3',
				$the_content
			);

		return $the_content;
	}


		add_filter( 'the_content', 'st_in_hatena' );

}

if ( !function_exists( 'st_in_check' ) ) {
	/**
	 * チェックマークのアイコン挿入
	 */
	function st_in_check( $the_content ) {
			$the_content = preg_replace(
				'!(<p class="checkmark(?:\s+[^>]*)?">)(.+?)(</p>)!is',
				'$1<i class="fa fa-check-circle"></i><span style="margin-left:10px;">$2</span>$3',
				$the_content
			);

		return $the_content;
	}


		add_filter( 'the_content', 'st_in_check' );

}

if ( !function_exists( 'st_in_attention' ) ) {
	/**
	 * アテンションマークのアイコン挿入
	 */
	function st_in_attention( $the_content ) {
			$the_content = preg_replace(
				'!(<p class="attentionmark(?:\s+[^>]*)?">)(.+?)(</p>)!is',
				'$1<i class="fa fa-exclamation-triangle"></i><span style="margin-left:10px;">$2</span>$3',
				$the_content
			);

		return $the_content;
	}


		add_filter( 'the_content', 'st_in_attention' );

}

if ( !function_exists( 'st_is_mobile' ) ) {
	/**
	 * スマホ表示分岐
	 */
	function st_is_mobile() {
		$useragents = array(
			'iPhone', // iPhone
			'iPod', // iPod touch
			'Android.*Mobile', // 1.5+ Android *** Only mobile
			'Windows.*Phone', // *** Windows Phone
			'dream', // Pre 1.5 Android
			'CUPCAKE', // 1.5+ Android
			'blackberry9500', // Storm
			'blackberry9530', // Storm
			'blackberry9520', // Storm v2
			'blackberry9550', // Storm v2
			'blackberry9800', // Torch
			'webOS', // Palm Pre Experimental
			'incognito', // Other iPhone browser
			'webmate' // Other iPhone browser

		);
		$pattern = '/' . implode( '|', $useragents ) . '/i';

		return preg_match( $pattern, $_SERVER['HTTP_USER_AGENT'] );
	}
}

if ( !function_exists( 'st_if_is_pc' ) ) {
	/**
	 * ショートコード: PCで表示するコンテンツ
	 */
	function st_if_is_pc( $atts, $content = null ) {
		if ( !wp_is_mobile() ) {
			$content = do_shortcode( $content );

			return $content;
		}

		return '';
	}

	add_shortcode( 'pc', 'st_if_is_pc' );
}

if ( !function_exists( 'st_if_is_nopc' ) ) {
	/**
	 * ショートコード: PC以外で表示するコンテンツ
	 */
	function st_if_is_nopc( $atts, $content = null ) {
		if ( wp_is_mobile() ) {
			$content = do_shortcode( $content );

			return $content;
		}

		return '';
	}

	add_shortcode( 'nopc', 'st_if_is_nopc' );
}

// アイキャッチサムネイル
add_theme_support( 'post-thumbnails' );

add_image_size( 'st_thumb100', 100, 100, true );
add_image_size( 'st_thumb150', 150, 150, true );

// カスタムメニュー
add_action( 'init', 'my_custom_menus' );
function my_custom_menus() {
    register_nav_menus(
        array(
            'primary-menu' => __( 'トップ用メニュー', 'affinger' ),
            'secondary-menu' => __( 'フッター用メニュー', 'affinger' )
        )
    );
}

// RSS
add_theme_support( 'automatic-feed-links' );

// 管理画面にオリジナルのスタイルを適用
add_editor_style( 'style.css' );    // メインのCSS
add_editor_style( 'editor-style.css' );

if ( !isset( $content_width ) ) {
	$content_width = 580;
}

if ( !function_exists( 'st_custom_editor_settings' ) ) {
	function st_custom_editor_settings( $initArray ) {
		$initArray['body_id'] = 'primary';    // id の場合はこれ
		$initArray['body_class'] = 'post';    // class の場合はこれ

		return $initArray;
	}

	add_filter( 'tiny_mce_before_init', 'st_custom_editor_settings' );
}

// ヘッダーを綺麗に
if ( isset($GLOBALS['stdata2']) && $GLOBALS['stdata2'] === '' ) {
	remove_action( 'wp_head', 'feed_links_extra', 3 );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_generator' );
}

if ( !function_exists( 'st_custom_content_more_link' ) ) {
	/**
	 * moreリンク
	 */
	function st_custom_content_more_link( $output ) {
		$output = preg_replace( '/#more-[\d]+/i', '', $output );

		return $output;
	}

	add_filter( 'the_content_more_link', 'st_custom_content_more_link' );
}

if ( !function_exists( 'st_no_self_pingst' ) ) {
	/**
	 * セルフピンバック禁止
	 */
	function st_no_self_pingst( $pung ) {
		$pung[] = home_url();

		return array_unique( $pung );
	}

	apply_filters( 'get_pung', 'st_no_self_pingst' );
}

if ( !function_exists( 'st_wrap_iframe_in_div' ) ) {
	/**
	 * iframeのレスポンシブ対応
	 */
	function st_wrap_iframe_in_div( $the_content ) {
		if ( is_singular() ) {
			$the_content = preg_replace(
				'!(<\s*?iframe(?:\s+[^>]*>)?)(.*?)(</\s*?iframe\s*?>)!is',
				'<div class="youtube-container">$1$2$3</div>',
				$the_content
			);
		}

		return $the_content;
	}

	add_filter( 'the_content', 'st_wrap_iframe_in_div' );
}

if ( !function_exists( 'st_register_sidebars' ) ) {
	/**
	 * ウイジェット追加
	 */
	function st_register_sidebars() {
		register_sidebar( array(
			'id' => 'sidebar-1',
			'name' => 'サイドバーウイジェット',
			'before_widget' => '<ul><li>',
			'after_widget' => '</li></ul>',
			'before_title' => '<h4 class="menu_underh2">',
			'after_title' => '</h4>',
		) );

		register_sidebar( array(
			'id' => 'sidebar-2',
			'name' => 'スクロール広告用',
			'description' => '「テキスト」をここにドロップして内容を入力して下さい。アドセンスは禁止です。※PC以外では非表示部分',
			'before_widget' => '<ul><li>',
			'after_widget' => '</li></ul>',
			'before_title' => '<h4 class="menu_underh2" style="text-align:left;">',
			'after_title' => '</h4>',
		) );

		register_sidebar( array(
			'id' => 'sidebar-3',
			'name' => 'Googleアドセンス用336px',
			'description' => '「テキスト」をここにドロップしてコードを入力して下さい。タイトルは反映されません。',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<p style="display:none">',
			'after_title' => '</p>',
		) );

		register_sidebar( array(
			'id' => 'sidebar-4',
			'name' => 'Googleアドセンスのスマホ用300px',
			'description' => '「テキスト」をここにドロップしてコードを入力して下さい。タイトルは反映されません。',
			'before_widget' => '',
			'after_widget' => '',
			'before_title' => '<p style="display:none">',
			'after_title' => '</p>',
		) );

		register_sidebar( array(
			'id' => 'sidebar-5',
			'name' => '投稿記事の下に一括表示',
			'description' => '「テキスト」等をここにドロップしてコードを入力して下さい。タイトルは反映されません。',
			'before_widget' => '<div class="kizi-under-box">',
			'after_widget' => '</div>',
			'before_title' => '<p style="display:none">',
			'after_title' => '</p>',
		) );

		register_sidebar( array(
			'id' => 'sidebar-6',
			'name' => '固定記事の下に一括表示',
			'description' => '「テキスト」等をここにドロップしてコードを入力して下さい。タイトルは反映されません。',
			'before_widget' => '<div class="kizi-under-box">',
			'after_widget' => '</div>',
			'before_title' => '<p style="display:none">',
			'after_title' => '</p>',
		) );
	}

	add_action( 'widgets_init', 'st_register_sidebars' );
}

if ( !function_exists( 'st_get_mtime' ) ) {
	/**
	 * 更新日の追加
	 */
	function st_get_mtime( $format ) {
		$mtime = (int) get_the_modified_time( 'Ymd' );
		$ptime = (int) get_the_time( 'Ymd' );

		if ( $ptime > $mtime ) {
			return get_the_time( $format );
		} elseif ( $ptime === $mtime ) {
			return null;
		} else {
			return get_the_modified_time( $format );
		}
	}
}

if ( !function_exists( 'st_rss_feed_copyright' ) ) {
	/**
	 * RSSに著作権
	 */
	function st_rss_feed_copyright( $content ) {
		$content = $content . '<p>Copyright &copy; ' . esc_html( date( 'Y' ) ) .
		           ' <a href="' . esc_url( home_url() ) . '">' .
		           apply_filters( 'bloginfo', get_bloginfo( 'name' ), 'name' ) .
		           '</a> All Rights Reserved.</p>';

		return $content;
	}

	add_filter( 'the_excerpt_rss', 'st_rss_feed_copyright' );
	add_filter( 'the_content_feed', 'st_rss_feed_copyright' );
}

if ( !function_exists( 'st_showads' ) ) {
	/**
	 * アドセンス
	 */
	function st_showads() {
		ob_start();

		get_template_part( 'st-ad' );

		$ads = ob_get_clean();

		return $ads;
	}

	add_shortcode( 'adsense', 'st_showads' );
}

if ( !function_exists( 'st_stchildlink' ) ) {
	/**
	 * 固定ページで子ページのリンクやタイトル、抜粋を一覧表示させるショートコード
	 */
	function st_stchildlink() {
		global $post;

		$args = array(
			'post_parent' => $post->ID,
			'post_type' => 'page'
		);

		$subpages = new WP_query( $args );

		if ( $subpages->have_posts() ) {
			$output = '<aside class="pagelist-box"><h4 class="kanrenh4">【関連コンテンツ】</h4><ul class="st-childlink">';

			while ( $subpages->have_posts() ) {
				$subpages->the_post();
				$output .= '<li><strong><a href="' . esc_url( apply_filters( 'the_permalink', get_permalink() ) ) . '">' .
				           get_the_title() .
				           '</a></strong>' .
				           '<p>' . apply_filters( 'the_excerpt', get_the_excerpt() ) . '</p></li>';
			}

			$output .= '</ul></aside>';
		} else {
			$output = '';
		}

		wp_reset_postdata();

		return $output;
	}

	add_shortcode( 'stchildlink', 'st_stchildlink' );
}

if ( !function_exists( 'st_shortcode_tp' ) ) {
	/**
	 * テーマ内の画像を読み込む
	 */
	function st_shortcode_tp( $atts, $content = '' ) {
		return get_stylesheet_directory_uri() . '/' . ltrim($content, '/\\');
	}

	add_shortcode( 'tp', 'st_shortcode_tp' );
}

if ( !function_exists( 'st_hidden_class' ) ) {
	/**
	 * 表示しないクラス名を出力
	 */
	function st_hidden_class() {
		if ( isset($GLOBALS['stdata24']) && $GLOBALS['stdata24'] === 'yes' ) {
		$hiedeclass = 'st-hide';
		}else{
		$hiedeclass = '';
		}
		
	echo esc_attr( $hiedeclass );
	}
}

if ( !function_exists( 'st_headerwide_class' ) ) {
	/**
	 * ヘッダー画像をワイドにするクラス名を出力
	 */
	function st_headerwide_class() {
		if ( isset($GLOBALS['stdata29']) && $GLOBALS['stdata29'] === 'yes' ) {
			$headerwide = '-wide';
		}else{
			$headerwide = '';
		}
		
	echo esc_attr( $headerwide );
	}
}

if ( !function_exists( 'st_text_copyck' ) ) {
	/**
	 * テキスト選択
	 */
	function st_text_copyck() {
		global $wp_query;
		if( !is_404() && !is_search() ){
			$postID = $wp_query->post->ID;
			$textcopyck1 = get_post_meta( $postID, 'textcopyck', true );
		}else{
		$textcopyck1 = '';
		}
		
	if ( isset( $textcopyck1 ) && $textcopyck1 === 'yes' ){
		$st_textcopyck = '';
	} else {
		if ( isset($GLOBALS['stdata19']) && $GLOBALS['stdata19'] === 'yes' ) {
			$st_textcopyck = 'oncontextmenu="return false" style="-webkit-touch-callout:none; -webkit-user-select:none;" onSelectStart="return false;" onMouseDown="return false;" style="-moz-user-select: none; -khtml-user-select: none; user-select: none;"';
		} else {
			$st_textcopyck = '';
		}
	}
		echo $st_textcopyck ;
	}
}

if (!function_exists('st_icon_head')) {
	/**
	 * ファビコンとAppleTouchIcon登録
	 */
	function st_icon_head() {
		if ( trim( $GLOBALS["stdata26"] ) !== '' ) {
		$fabiconurl = esc_url( $GLOBALS["stdata26"] );
		echo '<link rel="shortcut icon" href="'.$fabiconurl.'" >'."\n";
		}
		if ( trim( $GLOBALS["stdata27"] ) !== '' ) {
		$appletouchiconurl = esc_url( $GLOBALS["stdata27"] );
		echo '<link rel="apple-touch-icon-precomposed" href="'.$appletouchiconurl.'" />'."\n";
		}

	}
	add_action('wp_head', 'st_icon_head');
}


if (!function_exists('st_satikoadds_head')) {
	/**
	 * サーチコンソール登録
	 */
	function st_satikoadds_head() {
		if ( trim( $GLOBALS["stdata14"] ) !== '' ) {
		$satiko = esc_attr( $GLOBALS["stdata14"] );
		echo '<meta name="google-site-verification" content="'.$satiko.'"/>'."\n";
		}
	}
	add_action('wp_head', 'st_satikoadds_head');
}

if ( !function_exists( 'st_kaiseki_footer ' ) ) {
     /**
     * アクセス解析を出力
     */
     function st_kaiseki_footer() {
          if ( trim( $GLOBALS["stdata23"] ) !== '' ) {
          $kaiseki = stripslashes ( $GLOBALS["stdata23"] );
          echo $kaiseki ."\n";
          }
	}
	add_action( 'wp_footer', 'st_kaiseki_footer', 1 );
}



if ( !function_exists( 'st_add_author_filter' ) ) {
     /**
     * ユーザーで絞込を表示
     */
	function st_add_author_filter() {
		global $post_type;
		if ( $post_type == 'post' ) {
			wp_dropdown_users( array('show_option_all' => 'すべてのユーザー', 'name' => 'author') );
		}
	}
	add_action( 'restrict_manage_posts', 'st_add_author_filter' );
}

//////////////////////////////////
// TinyMCE
//////////////////////////////////
if (!function_exists('st_tiny_mce_before_init')) {
	/**
	 * オリジナルタグ登録
	 */
	function st_tiny_mce_before_init( $init_array ) {
	//書式プルダウンメニューのカスタマイズ
	$init_array['block_formats'] = '段落=p;見出し2=h2;見出し3=h3;見出し4=h4;見出し5=h5;見出し6=h6';
	$init_array['fontsize_formats'] = '8px 10px 12px 14px 16px 18px';
	//自作クラスをプルダウンメニューで追加
	$style_formats = array (
		array( 'title' => '太字', 'inline' => 'span', 'classes' => 'huto' ),
		array( 'title' => '太字（赤）', 'inline' => 'span', 'classes' => 'hutoaka' ),
		array( 'title' => '大文字', 'block' => 'p', 'classes' => 'oomozi' ),
		array( 'title' => 'h2風', 'block' => 'p', 'classes' => 'h2fuu' ),
		array( 'title' => 'はてな', 'block' => 'p', 'classes' => 'hatenamark' ),
		array( 'title' => '注意', 'block' => 'p', 'classes' => 'attentionmark' ),
		array( 'title' => 'チェックマーク', 'block' => 'p', 'classes' => 'checkmark' ),
		array( 'title' => '黄色ボックス', 'block' => 'div', 'classes' => 'yellowbox' , 'wrapper' => true ),
		array( 'title' => '薄赤ボックス', 'block' => 'div', 'classes' => 'redbox' , 'wrapper' => true ),
		array( 'title' => 'グレーボックス', 'block' => 'div', 'classes' => 'graybox' , 'wrapper' => true ),
		array( 'title' => '引用風ボックス', 'block' => 'div', 'classes' => 'inyoumodoki' , 'wrapper' => true ),
		);
	$init_array['style_formats'] = json_encode( $style_formats );
	$init['style_formats_merge'] = false;
	return $init_array;
	}
	add_filter( 'tiny_mce_before_init', 'st_tiny_mce_before_init' );
}

if ( !function_exists( 'st_tiny_mce_style_select' ) ) {
	/**
	 * TinyMCEにスタイルボタンを追加
	 *
	 * @param string[] $buttons ボタン
	 *
	 * @return string[] ボタン
	 */
	function st_tiny_mce_style_select( $buttons ) {
		$position = 2;    // 表示する位置 (1〜)

		$button = 'styleselect';

		unset( $buttons[ $button ] );

		$button_count = count( $buttons );

		// 最後
		if ( $button_count === 0 || $button_count < $position ) {
			$buttons[] = $button;

			return $buttons;
		}

		// 1番目
		if ( $position === 1 ) {
			array_unshift( $buttons, $button );

			return $buttons;
		}

		// その他
		$index   = $position - 1;
		$before  = array_slice( $buttons, 0, $index );
		$after   = array_slice( $buttons, $index );
		$buttons = array_merge( $before, array( $button ), $after );

		return $buttons;
	}

	// mce_buttons_1 = 1行目, mce_buttons_2 = 2行目, ...
	add_filter( 'mce_buttons_2', 'st_tiny_mce_style_select' );
}

if (!function_exists('st_add_orignal_quicktags')) {
	/**
	 * オリジナルクイックタグ登録
	 */
	function st_add_orignal_quicktags() {
		if ( wp_script_is( 'quicktags' ) ) { ?>
			<script type="text/javascript">
				QTags.addButton( 'ed_ive', 'イベント', "onclick=\"ga('send', 'event', 'linkclick', 'click', 'hoge');\"", '' );
				QTags.addButton( 'ed_nofollow', 'nofollow', " rel=\"nofollow\"", '' );
				QTags.addButton('ed_p', 'P', '<p>', '</p>');
				QTags.addButton('ed_ads', 'アドセンス', '[adsense]', '');
				QTags.addButton('ed_yellowbox', '黄色ボックス', '<div class="yellowbox">', '</div>');
				QTags.addButton('ed_graybox', 'グレーボックス', '<div class="graybox">', '</div>');
				QTags.addButton('ed_inyoumodoki', '引用風', '<div class="inyoumodoki">', '</div>');
			</script>
			<?php
		}
	}
	add_action('admin_print_footer_scripts', 'st_add_orignal_quicktags');
}

//////////////////////////////////
// タイトル
//////////////////////////////////
if ( !function_exists( 'st_wp_title' ) ) {
	/**
	 * WP 4.3以下用の wp_title() 互換関数 (WP 4.3相当)
	 *
	 * @param string $sep 区切り文字
	 * @param bool $display 出力の有無
	 * @param string $seplocation 区切り文字の位置
	 *
	 * @return string|null タイトル
	 */
	function st_wp_title($sep = '&raquo;', $display = true, $seplocation = '' ) {
		global $wp_locale, $page, $paged;

		$m        = get_query_var( 'm' );
		$year     = get_query_var( 'year' );
		$monthnum = get_query_var( 'monthnum' );
		$day      = get_query_var( 'day' );
		$search   = get_query_var( 's' );
		$title    = '';
		$t_sep    = '%WP_TITILE_SEP%';

		// 投稿
		if ( is_single() || ( is_home() && !is_front_page() ) || ( is_page() && !is_front_page() ) ) {
			$title = single_post_title( '', false );
		}

		// 投稿タイプアーカイブ
		if ( is_post_type_archive() ) {
			$post_type = get_query_var( 'post_type' );

			if ( is_array( $post_type ) ) {
				$post_type = reset( $post_type );
			}

			$post_type_object = get_post_type_object( $post_type );

			if ( !$post_type_object->has_archive ) {
				$title = post_type_archive_title( '', false );
			}
		}

		// カテゴリー, タグ
		if ( is_category() || is_tag() ) {
			$title = single_term_title( '', false );
		}

		// タクソノミー
		if ( is_tax() ) {
			$term = get_queried_object();

			if ( $term ) {
				$tax   = get_taxonomy( $term->taxonomy );
				$title = single_term_title( $tax->labels->name . $t_sep, false );
			}
		}

		// 著者
		if ( is_author() && !is_post_type_archive() ) {
			$author = get_queried_object();

			if ( $author ) {
				$title = $author->display_name;
			}
		}

		// 投稿タイプアーカイブ (has_archive)
		if ( is_post_type_archive() && $post_type_object->has_archive ) {
			$title = post_type_archive_title( '', false );
		}

		// 月
		if ( is_archive() && !empty( $m ) ) {
			$my_year  = substr( $m, 0, 4 );
			$my_month = $wp_locale->get_month( substr( $m, 4, 2 ) );
			$my_day   = intval( substr( $m, 6, 2 ) );
			$title    = $my_year . ( $my_month ? $t_sep . $my_month : '' ) . ( $my_day ? $t_sep . $my_day : '' );
		}

		// 年
		if ( is_archive() && !empty( $year ) ) {
			$title = $year;

			if ( !empty( $monthnum ) ) {
				$title .= $t_sep . $wp_locale->get_month( $monthnum );
			}
			if ( !empty( $day ) ) {
				$title .= $t_sep . zeroise( $day, 2 );
			}
		}

		// 検索
		if ( is_search() ) {
			$title = sprintf( __( 'Search Results %1$s %2$s','affinger' ), $t_sep, strip_tags( $search ) );
		}

		// 404
		if ( is_404() ) {
			$title = __( 'Page not found','affinger' );
		}

		$prefix = '';

		if ( !empty( $title ) ) {
			$prefix = " $sep ";
		}

		$title_array = apply_filters( 'wp_title_parts', explode( $t_sep, $title ) );

		if ( $seplocation === 'right' ) {
			$title_array = array_reverse( $title_array );
			$title       = implode( " $sep ", $title_array ) . $prefix;
		} else {
			$title = $prefix . implode( " $sep ", $title_array );
		}

		// wp_head() 以外はフィルターを適用
		if ( ! did_action( 'wp_head' ) && ! doing_action( 'wp_head' ) ) {
			$title = apply_filters( 'wp_title', $title, $sep, $seplocation );
		}

		if ( $display ) {
			echo $title;
		} else {
			return $title;
		}
	}
}

if ( !function_exists( 'st_get_document_title' ) ) {
	/**
	 * タイトルを取得
	 *
	 * @return string タイトル
	 */
	function st_get_document_title() {
		global $page, $paged;

		$title     = '';
		$blog_name = get_bloginfo( 'name', 'display' );
		$page_name = '';

		if ( $paged >= 2 || $page >= 2 ) {
			$page_name = ' - ' . sprintf( '%sページ', max( $paged, $page ) );
		}

		// ホーム
		if ( is_home() ) {
			$title .= get_bloginfo( 'description', 'display' ) . ' - ' . get_bloginfo( 'name', 'display' );

			return $title;
		}

		// フロントページ
		if ( is_front_page() ) {
			$title .= $blog_name . $page_name;

			return $title;
		}

		// その他
		switch ( true ) {
			case ( is_single() ):
				$title .= st_wp_title( '|', false, 'right' );
				break;

			case ( is_page() ):
				$title .= st_wp_title( '|', false, 'right' );
				break;

			case ( is_archive() ):
				$title .= st_wp_title( '|', false, 'right' );
				break;

			case ( is_search() ):
				$title .= st_wp_title( '|', false, 'right' );
				break;

			case ( is_404() ):
				$title .= '404 |';
				break;

			default:
				break;
		}

		$title .= $blog_name . $page_name;

		return $title;
	}

	// wp_title フィルターでタイトルを置換 (WP ~4.3)
	if ( !function_exists( 'wp_get_document_title' ) ) {
		add_filter( 'wp_title', 'st_get_document_title' );
	}
}


if ( !function_exists( 'st_legacy_render_title_tag' ) ) {
	/**
	 * title要素を出力 (WP ~4.1互換)
	 */
	function st_legacy_render_title_tag() {
		echo '<' . 'title>' . st_get_document_title() . '<' . '/title>' . "\n";
	}

	// 互換関数でtitle要素を出力 (WP ~4.1)
	if ( !function_exists( '_wp_render_title_tag' ) ) {
		add_action( 'wp_head', 'st_legacy_render_title_tag', 1 );
	}
}

if ( !function_exists( 'st_get_document_title_array' ) ) {
	/**
	 * タイトルの要素を配列で取得
	 *
	 * @return string[] タイトルの要素
	 */
	function st_get_document_title_array() {
		return array( st_get_document_title() );
	}

	// document_title_parts フィルターでタイトルを置換 (WP 4.4+)
	if ( function_exists( 'wp_get_document_title' ) ) {
		add_filter( 'document_title_parts', 'st_get_document_title_array' );
	}
}
