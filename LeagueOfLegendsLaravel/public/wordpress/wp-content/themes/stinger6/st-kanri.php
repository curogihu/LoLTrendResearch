<?php

// 直接アクセスを禁止
if ( !defined( 'ABSPATH' ) ) {
     exit;
}


// 管理画面ページ

$stdata1 = get_option( 'st-data1' );
$stdata2 = get_option( 'st-data2' );
$stdata3 = get_option( 'st-data3' );
$stdata4 = get_option( 'st-data4' );
$stdata5 = get_option( 'st-data5' ); //サムネイルの有無
$stdata6 = get_option( 'st-data6' );
$stdata7 = get_option( 'st-data7' );
$stdata8 = get_option( 'st-data8' );
$stdata9 = get_option( 'st-data9' );
$stdata10 = get_option( 'st-data10' );
//$stdata11 = get_option( 'st-data11' );
$stdata12 = get_option( 'st-data12' );
$stdata13 = get_option( 'st-data13' );
$stdata14 = get_option( 'st-data14' );
$stdata15 = get_option( 'st-data15' ); //カテゴリーのindex
$stdata16 = get_option( 'st-data16' ); //RSSボタン
$stdata17 = get_option( 'st-data17' ); //著者情報
$stdata18 = get_option( 'st-data18' ); //下層ページのヘッダー
$stdata19 = get_option( 'st-data19' ); //投稿記事のテキスト選択
$stdata20 = get_option( 'st-data20' ); //お知らせ欄
$stdata21 = get_option( 'st-data21' ); //お知らせ欄カテゴリID
$stdata22 = get_option( 'st-data22' ); //お知らせ欄表示件数
$stdata23 = get_option( 'st-data23' ); //アクセス解析タグ
$stdata24 = get_option( 'st-data24' ); //隠すクラス
$stdata25 = get_option( 'st-data25' ); //Twitterアカウント
$stdata26 = get_option( 'st-data26' ); //ファビコンURL
$stdata27 = get_option( 'st-data27' ); //appletouchicon
$stdata28 = get_option( 'st-data28' ); //お知らせに表示するカテゴリID


add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
     add_object_page(
          __( 'STINGER管理', 'my-custom-admin' ),
          __( 'STINGER管理', 'my-custom-admin' ),
          'manage_options',
          'my-custom-admin',
          'my_custom_admin'
     );

     add_submenu_page(
	  'my-custom-admin',
          __( 'STINGER管理リセット', 'my-custom-admin' ),
          __( 'STINGER管理リセット', 'my-custom-admin' ),
          'manage_options',
          'my-submenu',
          'my_submenu'
     );
}

	

// 基本管理画面
function my_custom_admin() {
     ?>

     <div class="wrap">
          <h2>STINGER基本管理画面</h2>
          <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
          <form id="my-main-form" method="post" action="">
               <?php wp_nonce_field( 'my-nonce-key', 'my-custom-admin' ); ?>
               <h3 style="padding:10px;margin-bottom:10px;border:1px solid #ccc;background-color:#f3f3f3;">デザイン・レイアウト関連設定</h3>

               <p>
			<input type="checkbox" name="st-data1" value="yes" <?php if ( $GLOBALS["stdata1"] === 'yes' ) {
				echo 'checked';
			} ?>>
                    オリジナルテーマカスタマイザーを使用する（「外観」＞「テーマ」にて基本カラーを選択できます。）</p>

               <p>
                    <input type="checkbox" name="st-data18" value="yes" <?php if ( $GLOBALS["stdata18"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    下層ページにもヘッダー画像を表示する</p>

               <p>


               <p>
                    <input type="checkbox" name="st-data16" value="yes" <?php if ( $GLOBALS["stdata16"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    RSSボタンを表示しない</p>


               <p>
                    <input type="checkbox" name="st-data3" value="yes" <?php if ( $GLOBALS["stdata3"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    H3にチェックマークを適応する</p>

               <p>
                    <input type="checkbox" name="st-data6" value="yes" <?php if ( $GLOBALS["stdata6"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    コメント欄を非表示にする</p>

               <p>
                    <input type="checkbox" name="st-data17" value="yes" <?php if ( $GLOBALS["stdata17"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    著者情報を表示する</p>

               <p>

               <p>
                    <input type="checkbox" name="st-data10" value="yes" <?php if ( $GLOBALS["stdata10"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    固定ページに子ページへのリンクを表示</p>

			<h4 style="padding:5px 10px;background-color:#dddddd;"><i class="fa fa-eye"></i>&nbsp;アイコン</h4>
		<p>メディアより画像をアップロードして「ファイルのURL」を記入して下さい。（例：http://example.com/wp-content/uploads/2015/11/favicon.ico ）</p>
		<?php if ( empty( $GLOBALS["stdata26"] ) ) {
                    echo '<P>ファビコン画像のURL：<input type="text" name="st-data26" value="" size="30" style="ime-mode:disabled;">&nbsp;※16×16px、32×32pxのマルチアイコンが推奨されます</p>';
               } else {
                    echo '<p>現在のファビコン※32pxで表示<br/><img src="' . esc_url( ( $GLOBALS["stdata26"] ) ) . '" width="32px"></p>';
                    echo '<input type="checkbox" name="stdata26kesi" value="yes">' . '削除';
               }
               ?>

		<?php if ( empty( $GLOBALS["stdata27"] ) ) {
                    echo '<P><i class="fa fa-apple"></i>&nbsp;apple-touch-icon画像のURL：<input type="text" name="st-data27" value="" size="30" style="ime-mode:disabled;">&nbsp;※png形式 152px以上推奨</p>';
               } else {
                    echo '<p><i class="fa fa-apple"></i>&nbsp;現在のapple-touch-icon※74pxで表示<br/><img src="' . esc_url( ( $GLOBALS["stdata27"] ) ) . '" width="74px"></p>';
                    echo '<input type="checkbox" name="stdata27kesi" value="yes">' . '削除';
               }
               ?>

			<h4 style="padding:5px 10px;background-color:#dddddd;"><i class="fa fa-share-alt-square"></i>&nbsp;SNS（ソーシャルボタン）</h4>

		<?php if ( empty( $GLOBALS["stdata25"] ) ) {
                    echo '<P><i class="fa fa-twitter"></i>&nbsp;Twitterアカウント@：<input type="text" name="st-data25" value="" size="30" style="ime-mode:disabled;"></p>';
               } else {
                    echo '<p><i class="fa fa-twitter"></i>&nbsp;現在のTwitterアカウント@：' . esc_attr( ( $GLOBALS["stdata25"] ) ) . '</p>';
                    echo '<input type="checkbox" name="stdata25kesi" value="yes">' . '削除';
               }
               ?>


		<p>
                    <input type="checkbox" name="st-data12" value="yes" <?php if ( $GLOBALS["stdata12"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    SNSボタンを非表示にする</p>

		<p>「<a href="//ja.wordpress.org/plugins/sns-count-cache/" target="_blank">SNS Count Cacheプラグイン</a>」を利用するとカウントが表示されます</p>

		<h4 style="padding:5px 10px;background-color:#dddddd;"><i class="fa fa-th-list"></i>&nbsp;投稿一覧</h4>

               <p>
                    <input type="checkbox" name="st-data5" value="yes" <?php if ( $GLOBALS["stdata5"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                   投稿一覧のサムネイルを表示しない</p>

               <p>

               <p>
                    <input type="checkbox" name="st-data24" value="yes" <?php if ( $GLOBALS["stdata24"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    投稿に「投稿日」「更新日」を表示しない</p>

               <p>
                    <input type="checkbox" name="st-data13" value="yes" <?php if ( $GLOBALS["stdata13"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    サイドバーの新しい投稿一覧を非表示にする</p>

		<h4 style="padding:5px 10px;background-color:#dddddd;"><i class="fa fa-newspaper-o"></i>&nbsp;お知らせの表示</h4>

               <p>
                    <input type="checkbox" name="st-data20" value="yes" <?php if ( $GLOBALS["stdata20"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                   お知らせをTOPページの一番上に表示する</p>

               <p>

               <P>お知らせタイトルに表示するカテゴリID：
                    <input type="number" pattern="^[0-9]+$" name="st-data28" value="<?php echo esc_attr( $GLOBALS["stdata28"] ); ?>">
		　　<br/>※お知らせのタイトルとして表示したいカテゴリのIDを１つだけ記入して下さい。現在は<b>「
			<?php if ( trim( $GLOBALS["stdata28"] ) !== '' ) {
				$catid = esc_attr( $GLOBALS["stdata28"] );
				$catno = get_category( $catid );
				$catname = $catno->cat_name;
			}else{
				$catname = 'お知らせ';
				}
			echo $catname;
			?>
		」</b>です。
               </p>

               <P>お知らせに表示するカテゴリ（カテゴリID※デフォルトは「0（全て）」）：
                    <input type="text" pattern="^[0-9,]+$" name="st-data21" value="<?php echo esc_attr( $GLOBALS["stdata21"] ); ?>"><br/>複数指定する場合は半角カンマ「,」で区切ってください
               </p>

               <P>お知らせに表示する件数：
                    <input type="number" name="st-data22" value="<?php echo esc_attr( $GLOBALS["stdata22"] ); ?>">
               </p>

               <h4 style="padding:5px 10px;background-color:#dddddd;"><i class="fa fa-columns"></i>&nbsp;トップページへの記事挿入</h4>

                              
               <P>トップページに固定記事を挿入（記事ID）：
                    <input type="number" name="st-data9" value="<?php echo esc_attr( $GLOBALS["stdata9"] ); ?>">
               </p>


               <h3 style="padding:10px;margin-bottom:10px;border:1px solid #ccc;background-color:#f3f3f3;">SEO関連設定</h3>

               <p>
                    <input type="checkbox" name="st-data2" value="yes" <?php if ( $GLOBALS["stdata2"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    ヘッダーソースを自動で綺麗にしない（Head Cleaner使用の場合など）</p>

               <p>
                    <input type="checkbox" name="st-data15" value="yes" <?php if ( $GLOBALS["stdata15"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                   カテゴリーをindexしない</p>


               <p>
                    <input type="checkbox" name="st-data4" value="yes" <?php if ( $GLOBALS["stdata4"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    日本語パーマリンクを自動変換する<span style="color:#ff0000;">※更新時にURLが変更されます</span></p>

               <p>
                    <input type="checkbox" name="st-data19" value="yes" <?php if ( $GLOBALS["stdata19"] === 'yes' ) {
                         echo 'checked';
                    } ?>>
                    テキスト選択不可にする※100%コピーを禁止できるわけではありません（個々の設定が優先されます。）</p>

               <p>


               <h3 style="padding:10px;margin-bottom:10px;border:1px solid #ccc;background-color:#f3f3f3;">Google連携に関する設定</h3>
               <?php if ( empty( $GLOBALS["stdata7"] ) ) {
                    echo '<P>アナリティクスコード（トラッキング ID）：UA-<input type="text" name="st-data7" value="" size="30" style="ime-mode:disabled;"></p>';
               } else {
                    echo '<p>現在のトラッキングID：UA-' . stripslashes( ( $GLOBALS["stdata7"] ) ) . '</p>';
                    echo '<input type="checkbox" name="stdata7kesi" value="yes">' . '削除';
               }
               ?>
               
		<?php if ( empty( $GLOBALS["stdata14"] ) ) {
                    echo '<P>サーチコンソールHTMLタグ：<input type="text" name="st-data14" value="" size="30" style="ime-mode:disabled;"></p>';
               } else {
                    echo '<p>現在のサーチコンソールHTMLタグ：' . stripslashes( ( $GLOBALS["stdata14"] ) ) . '</p>';
                    echo '<input type="checkbox" name="stdata14kesi" value="yes">' . '削除';
               }
               ?>

               <h3 style="padding:10px;margin-bottom:10px;border:1px solid #ccc;background-color:#f3f3f3;">その他のアクセス解析</h3>

		<p>※こちらはβ版機能です。設置後は必ず自身でも御確認下さい</p>

               <p>

		<?php if ( empty( $GLOBALS["stdata23"] ) ) {
                    echo '<P>その他アクセス解析タグ（β）：<input type="text" name="st-data23" value="" size="30" style="ime-mode:disabled;"></p>';
               } else {
                    echo '<p>現在のアクセス解析タグ：' . stripslashes( ( $GLOBALS["stdata23"] ) ) . '</p>';
                    echo '<input type="checkbox" name="stdata23kesi" value="yes">' . '削除';
               }
               ?>

               <p>
                    <input type="submit" value="<?php echo esc_attr( __( 'save',
                         'my-custom-admin' ) ); ?>" class="button button-primary button-large">
               </p>
          </form>
     </div>
<?php }

// サブ管理画面
function my_submenu() {
     ?>

     <div class="wrap">
          <h2>STINGER基本管理リセット画面</h2>
           <form id="my-main-form" method="post" action="">
               <?php wp_nonce_field( 'my-nonce-key', 'my-custom-admin' ); ?>
			<p style="color:#ff0000">※本画面は緊急用です。誤ったタグの挿入などで「AFFINGER管理画面」に不具合が生じた場合、こちらからリセットできます。</p>

               <p>
			<input type="checkbox" name="st-data-reset" value="yes" onclick="window.alert('※チェックを入れて保存するとAFFINGER管理画面のアナリティクスコード等が全てリセットされます');">
                    全てリセットする</p>

             

               <p>
                    <input type="submit" value="<?php echo esc_attr( __( 'save',
                         'my-custom-admin' ) ); ?>" class="button button-primary button-large">
               </p>
          </form>
     </div>
<?php }

//データ保存

add_action( 'admin_init', 'my_admin_init' );

function my_admin_init() {
     if ( isset( $_POST['my-custom-admin'] ) && $_POST['my-custom-admin'] ) {
          if ( check_admin_referer( 'my-nonce-key', 'my-custom-admin' ) ) {
	  if ( isset( $_POST['st-data-reset'] ) && $_POST['st-data-reset'] ) {
                    update_option( 'st-data7', '' );
                    update_option( 'st-data14', '' );
                    update_option( 'st-data23', '' );

	}else{

               if ( isset( $_POST['st-data1'] ) && $_POST['st-data1'] ) {
                    update_option( 'st-data1', $_POST['st-data1'] );
               } else {
                    update_option( 'st-data1', '' );
               }

               if ( isset( $_POST['st-data2'] ) && $_POST['st-data2'] ) {
                    update_option( 'st-data2', $_POST['st-data2'] );
               } else {
                    update_option( 'st-data2', '' );
               }

               if ( isset( $_POST['st-data3'] ) && $_POST['st-data3'] ) {
                    update_option( 'st-data3', $_POST['st-data3'] );
               } else {
                    update_option( 'st-data3', '' );
               }

               if ( isset( $_POST['st-data4'] ) && $_POST['st-data4'] ) {
                    update_option( 'st-data4', $_POST['st-data4'] );
               } else {
                    update_option( 'st-data4', '' );
               }

               if ( isset( $_POST['st-data5'] ) && $_POST['st-data5'] ) {
                    update_option( 'st-data5', $_POST['st-data5'] );
               } else {
                    update_option( 'st-data5', '' );
               }

               if ( isset( $_POST['st-data6'] ) && $_POST['st-data6'] ) {
                    update_option( 'st-data6', $_POST['st-data6'] );
               } else {
                    update_option( 'st-data6', '' );
               }

               if ( isset( $_POST['st-data7'] ) && $_POST['st-data7'] ) {
                    update_option( 'st-data7', $_POST['st-data7'] );
               } else {
                    if ( isset( $_POST['stdata7kesi'] ) && $_POST['stdata7kesi'] === 'yes' ) {
                         update_option( 'st-data7', '' );
                    }
               }

               if ( isset( $_POST['st-data8'] ) && $_POST['st-data8'] ) {
                    update_option( 'st-data8', $_POST['st-data8'] );
               } else {
                    update_option( 'st-data8', '' );
               }

               if ( isset( $_POST['st-data9'] ) && $_POST['st-data9'] ) {
                    update_option( 'st-data9', $_POST['st-data9'] );
               } else {
                    update_option( 'st-data9', '' );
               }

               if ( isset( $_POST['st-data10'] ) && $_POST['st-data10'] ) {
                    update_option( 'st-data10', $_POST['st-data10'] );
               } else {
                    update_option( 'st-data10', '' );
               }


               if ( isset( $_POST['st-data12'] ) && $_POST['st-data12'] ) {
                    update_option( 'st-data12', $_POST['st-data12'] );
               } else {
                    update_option( 'st-data12', '' );
               }

               if ( isset( $_POST['st-data13'] ) && $_POST['st-data13'] ) {
                    update_option( 'st-data13', $_POST['st-data13'] );
               } else {
                    update_option( 'st-data13', '' );
               }

               if ( isset( $_POST['st-data14'] ) && $_POST['st-data14'] ) {
                    update_option( 'st-data14', $_POST['st-data14'] );
               } else {
                    if (  isset( $_POST['stdata14kesi'] ) && $_POST['stdata14kesi'] === 'yes' ) {
                         update_option( 'st-data14', '' );
                    }
               }

               if ( isset( $_POST['st-data15'] ) && $_POST['st-data15'] ) {
                    update_option( 'st-data15', $_POST['st-data15'] );
               } else {
                    update_option( 'st-data15', '' );
               }

               if ( isset( $_POST['st-data16'] ) && $_POST['st-data16'] ) {
                    update_option( 'st-data16', $_POST['st-data16'] );
               } else {
                    update_option( 'st-data16', '' );
               }

               if ( isset( $_POST['st-data17'] ) && $_POST['st-data17'] ) {
                    update_option( 'st-data17', $_POST['st-data17'] );
               } else {
                    update_option( 'st-data17', '' );
               }

               if ( isset( $_POST['st-data18'] ) && $_POST['st-data18'] ) {
                    update_option( 'st-data18', $_POST['st-data18'] );
               } else {
                    update_option( 'st-data18', '' );
               }

               if ( isset( $_POST['st-data19'] ) && $_POST['st-data19'] ) {
                    update_option( 'st-data19', $_POST['st-data19'] );
               } else {
                    update_option( 'st-data19', '' );
               }

               if ( isset( $_POST['st-data20'] ) && $_POST['st-data20'] ) {
                    update_option( 'st-data20', $_POST['st-data20'] );
               } else {
                    update_option( 'st-data20', '' );
               }

               if ( isset( $_POST['st-data21'] ) && $_POST['st-data21'] ) {
                    update_option( 'st-data21', $_POST['st-data21'] );
               } else {
                    update_option( 'st-data21', '' );
               }

               if ( isset( $_POST['st-data22'] ) && $_POST['st-data22'] ) {
                    update_option( 'st-data22', $_POST['st-data22'] );
               } else {
                    update_option( 'st-data22', '' );
               }

               if ( isset( $_POST['st-data23'] ) && $_POST['st-data23'] ) {
                    update_option( 'st-data23', $_POST['st-data23'] );
               } else {
                    if (  isset( $_POST['stdata23kesi'] ) && $_POST['stdata23kesi'] === 'yes' ) {
                         update_option( 'st-data23', '' );
                    }
               }

               if ( isset( $_POST['st-data24'] ) && $_POST['st-data24'] ) {
                    update_option( 'st-data24', $_POST['st-data24'] );
               } else {
                    update_option( 'st-data24', '' );
               }

               if ( isset( $_POST['st-data25'] ) && $_POST['st-data25'] ) {
                    update_option( 'st-data25', $_POST['st-data25'] );
               } else {
                    if (  isset( $_POST['stdata25kesi'] ) && $_POST['stdata25kesi'] === 'yes' ) {
                         update_option( 'st-data25', '' );
                    }
               }

               if ( isset( $_POST['st-data26'] ) && $_POST['st-data26'] ) {
                    update_option( 'st-data26', $_POST['st-data26'] );
               } else {
                    if (  isset( $_POST['stdata26kesi'] ) && $_POST['stdata26kesi'] === 'yes' ) {
                         update_option( 'st-data26', '' );
                    }
               }

               if ( isset( $_POST['st-data27'] ) && $_POST['st-data27'] ) {
                    update_option( 'st-data27', $_POST['st-data27'] );
               } else {
                    if ( isset( $_POST['stdata27kesi'] ) && $_POST['stdata27kesi'] === 'yes' ) {
                         update_option( 'st-data27', '' );
                    }
               }

               if ( isset( $_POST['st-data28'] ) && $_POST['st-data28'] ) {
                    update_option( 'st-data28', $_POST['st-data28'] );
               } else {
                    update_option( 'st-data28', '' );
               }

               

	  }
	     wp_safe_redirect( menu_page_url( 'my-custom-admin', false ) );
          }
     }
}

//////////////////////////////////
// index操作
//////////////////////////////////

add_action( 'admin_menu', 'st_stmeta_robots_add_metaboxr' );
add_action( 'save_post', 'st_stmeta_robots_save_stmeta_robots' );

function st_stmeta_robots_add_metaboxr() {
          add_meta_box( 'stmeta', 'index変更', 'st_stmeta_robots_stmeta_robots_dropdown_box', 'page', 'side', 'low' );
          add_meta_box( 'stmeta', 'index変更', 'st_stmeta_robots_stmeta_robots_dropdown_box', 'post', 'side', 'low' );
}

// 挿入ページ

function st_stmeta_robots_stmeta_robots_dropdown_box() {
     global $post;
     wp_nonce_field( wp_create_nonce( __FILE__ ), 'st_stmeta_robots' );
     $stmeta_robots = $post->stmeta_robots;

     ?>
     <label for="stmeta_robots"></label>
     <select name="stmeta_robots" id="stmeta_robots">
          <option <?php if ( $stmeta_robots === "index, follow" ) {
               echo 'selected="selected"';
          } ?>>index, follow
          </option>
          <option <?php if ( $stmeta_robots === "index, nofollow" ) {
               echo 'selected="selected"';
          } ?>>index, nofollow
          </option>
          <option <?php if ( $stmeta_robots === "noindex, follow" ) {
               echo 'selected="selected"';
          } ?>>noindex, follow
          </option>
          <option <?php if ( $stmeta_robots === "noindex, nofollow" ) {
               echo 'selected="selected"';
          } ?>>noindex, nofollow
          </option>
     </select>
     <?php
}

// データベース登録

function st_stmeta_robots_save_stmeta_robots( $post_id ) {
     $my_nonce = isset( $_POST['st_stmeta_robots'] ) ? $_POST['st_stmeta_robots'] : null;
     if ( !wp_verify_nonce( $my_nonce, wp_create_nonce( __FILE__ ) ) ) {
          return $post_id;
     }
     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
          return $post_id;
     }
     if ( !current_user_can( 'edit_post', $post_id ) ) {
          return $post_id;
     }

     $data = $_POST['stmeta_robots'];

     if ( get_post_meta( $post_id, 'stmeta_robots' ) === "" ) {
          add_post_meta( $post_id, 'stmeta_robots', $data, true );
     } elseif ( $data !== get_post_meta( $post_id, 'stmeta_robots', true ) ) {
          update_post_meta( $post_id, 'stmeta_robots', $data );
     } elseif ( $data === "" ) {
          delete_post_meta( $post_id, 'stmeta_robots', get_post_meta( $post_id, 'stmeta_robots', true ) );
     }
}

// 表示

function st_stmeta_robots_add_stmeta_robots_tag() {
     global $post;
     if ( is_single() || is_page() ) {
	if ( $GLOBALS["stdata9"] === '' || ( $GLOBALS["stdata9"] !== '' && $GLOBALS["stdata9"] != $post->ID ) ) {
              $stmeta_robots = ( empty( $post->stmeta_robots ) ) ? 'index, follow' : $post->stmeta_robots;
              echo '<meta name="robots" content="' . $stmeta_robots . '" />' . "\n";
         }
     } elseif ( !is_category() && is_archive() ) {
          echo '<meta name="robots" content="noindex, follow" />' . "\n";
     } else {

     }
}

add_action( 'wp_head', 'st_stmeta_robots_add_stmeta_robots_tag' );

//////////////////////////////////
// メタキーワード
//////////////////////////////////

add_action( 'admin_menu', 'add_st_keywords' );
add_action( 'save_post', 'save_st_keywords' );

function add_st_keywords() {
          add_meta_box( 'st_keywords1', 'メタキーワード', 'insert_st_keywords', 'page', 'normal', 'high' );
          add_meta_box( 'st_keywords1', 'メタキーワード', 'insert_st_keywords', 'post', 'normal', 'high' );
}

function insert_st_keywords() {
     global $post;
     wp_nonce_field( wp_create_nonce( __FILE__ ), 'my_stkeywords' );
     $stkeywords = get_post_meta( $post->ID, 'st_keywords', true );
     $stkeywords = esc_attr( $stkeywords );
     echo '<label class="hidden" for="st_keywords">メタキーワード</label><p>複数指定する場合は半角カンマ「,」で区切ってください</p><input type="text" name="st_keywords" value="'.$stkeywords.'" />';

}

function save_st_keywords( $post_id ) {
     $my_stkeywords = isset( $_POST['my_stkeywords'] ) ? $_POST['my_stkeywords'] : null;
     if ( !wp_verify_nonce( $my_stkeywords, wp_create_nonce( __FILE__ ) ) ) {
          return $post_id;
     }

     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
          return $post_id;
     }
     if ( !current_user_can( 'edit_post', $post_id ) ) {
          return $post_id;
     }
     $data = $_POST['st_keywords'];

     if ( get_post_meta( $post_id, 'st_keywords' ) == "" ) {
          add_post_meta( $post_id, 'st_keywords', $data, true );
     } elseif ( $data != get_post_meta( $post_id, 'st_keywords', true ) ) {
          update_post_meta( $post_id, 'st_keywords', $data );
     } elseif ( $data == "" ) {
          delete_post_meta( $post_id, 'st_keywords', get_post_meta( $post_id, 'st_keywords', true ) );
     }
}

// 表示

function st_keywords_source() {
     if ( !is_home() && !is_404() && !is_search() ) {
          global $wp_query;
          $postID = $wp_query->post->ID;
          $stkeywords = get_post_meta( $postID, 'st_keywords', true );
     } else {
          $stkeywords = '';
     };
     if (isset( $stkeywords ) && trim( $stkeywords ) !== '') {
     echo '<meta name="keywords" content="' . esc_attr($stkeywords) . '">'. "\n";
     }  else {
     }
}
add_action( 'wp_head', 'st_keywords_source' );

//////////////////////////////////
// メタディスクリプション
//////////////////////////////////

add_action( 'admin_menu', 'add_st_description' );
add_action( 'save_post', 'save_st_description' );

function add_st_description() {
          add_meta_box( 'st_description1', 'メタディスクリプション', 'insert_st_description', 'page', 'normal', 'high' );
          add_meta_box( 'st_description1', 'メタディスクリプション', 'insert_st_description', 'post', 'normal', 'high' );
}

function insert_st_description() {
     global $post;
     wp_nonce_field( wp_create_nonce( __FILE__ ), 'my_stdescription' );
     $stdescription = get_post_meta( $post->ID, 'st_description', true );
     $stdescription = esc_html( $stdescription );
     echo '<label class="hidden" for="st_description">メタディスクリプション</label><p>全角120文字程度に納めましょう</p><textarea style="width:100%" rows="4" type="text" name="st_description" />'.$stdescription.'</textarea>';

}

function save_st_description( $post_id ) {
     $my_stdescription = isset( $_POST['my_stdescription'] ) ? $_POST['my_stdescription'] : null;
     if ( !wp_verify_nonce( $my_stdescription, wp_create_nonce( __FILE__ ) ) ) {
          return $post_id;
     }

     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
          return $post_id;
     }
     if ( !current_user_can( 'edit_post', $post_id ) ) {
          return $post_id;
     }
     $data = $_POST['st_description'];

     if ( get_post_meta( $post_id, 'st_description' ) == "" ) {
          add_post_meta( $post_id, 'st_description', $data, true );
     } elseif ( $data != get_post_meta( $post_id, 'st_description', true ) ) {
          update_post_meta( $post_id, 'st_description', $data );
     } elseif ( $data == "" ) {
          delete_post_meta( $post_id, 'st_description', get_post_meta( $post_id, 'st_description', true ) );
     }
}

// 表示

function st_description_source() {
     if ( !is_home() && !is_404() && !is_search()  ) {
          global $wp_query;
          $postID = $wp_query->post->ID;
          $stdescription = get_post_meta( $postID, 'st_description', true );
     } else {
          $stdescription = '';
     };
     if (isset( $stdescription ) && trim( $stdescription ) !== '') {
     echo '<meta name="description" content="' . esc_attr($stdescription) .'">'. "\n";
     }  else {
     }
}
add_action( 'wp_head', 'st_description_source' );

//////////////////////////////////
// テキストの選択
//////////////////////////////////

add_action( 'admin_menu', 'add_textcopyck' );
add_action( 'save_post', 'save_textcopyck' );

function add_textcopyck() {
          add_meta_box( 'textcopyck1', 'コピー防止', 'insert_textcopyck', 'page', 'side', 'low' );
          add_meta_box( 'textcopyck1', 'コピー防止', 'insert_textcopyck', 'post', 'side', 'low' );
}

function insert_textcopyck() {
     global $post;
     wp_nonce_field( wp_create_nonce( __FILE__ ), 'st_kanri' );
     $textcopyckmark = get_post_meta( $post->ID, 'textcopyck', true );

     if ( $textcopyckmark === 'yes' ) {
          $textcopychecked = 'checked';
     } else {
          $textcopychecked = '';
     }

     echo '<label class="hidden" for="textcopyck">コピー防止</label><input type="checkbox"  name="textcopyck" value="yes" ' . $textcopychecked . '/>設定に関わらずテキスト選択可';
    
}

function save_textcopyck( $post_id ) {
     $my_nonce = isset( $_POST['st_kanri'] ) ? $_POST['st_kanri'] : null;
     if ( !wp_verify_nonce( $my_nonce, wp_create_nonce( __FILE__ ) ) ) {
          return $post_id;
     }

     if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
          return $post_id;
     }
     if ( !current_user_can( 'edit_post', $post_id ) ) {
          return $post_id;
     }
     
     if(isset($_POST['textcopyck'])){
	 $data = $_POST['textcopyck'];
     }else{
	 $data = '';
     }

     if ( get_post_meta( $post_id, 'textcopyck' ) == "" ) {
          add_post_meta( $post_id, 'textcopyck', $data, true );
     } elseif ( $data != get_post_meta( $post_id, 'textcopyck', true ) ) {
          update_post_meta( $post_id, 'textcopyck', $data );
     } elseif ( $data == "" ) {
          delete_post_meta( $post_id, 'textcopyck', get_post_meta( $post_id, 'textcopyck', true ) );
     }
}
