<?php
/**
 * WordPress の基本設定
 *
 * このファイルは、インストール時に wp-config.php 作成ウィザードが利用します。
 * ウィザードを介さずにこのファイルを "wp-config.php" という名前でコピーして
 * 直接編集して値を入力してもかまいません。
 *
 * このファイルは、以下の設定を含みます。
 *
 * * MySQL 設定
 * * 秘密鍵
 * * データベーステーブル接頭辞
 * * ABSPATH
 *
 * @link http://wpdocs.sourceforge.jp/wp-config.php_%E3%81%AE%E7%B7%A8%E9%9B%86
 *
 * @package WordPress
 */

// 注意: 
// Windows の "メモ帳" でこのファイルを編集しないでください !
// 問題なく使えるテキストエディタ
// (http://wpdocs.sourceforge.jp/Codex:%E8%AB%87%E8%A9%B1%E5%AE%A4 参照)
// を使用し、必ず UTF-8 の BOM なし (UTF-8N) で保存してください。

// ** MySQL 設定 - この情報はホスティング先から入手してください。 ** //
/** WordPress のためのデータベース名 */
define('DB_NAME', 'programmingBlog');

/** MySQL データベースのユーザー名 */
define('DB_USER', 'root');

/** MySQL データベースのパスワード */
define('DB_PASSWORD', 'root');

/** MySQL のホスト名 */
define('DB_HOST', 'localhost');

/** データベースのテーブルを作成する際のデータベースの文字セット */
define('DB_CHARSET', 'utf8mb4');

/** データベースの照合順序 (ほとんどの場合変更する必要はありません) */
define('DB_COLLATE', '');

/**#@+
 * 認証用ユニークキー
 *
 * それぞれを異なるユニーク (一意) な文字列に変更してください。
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org の秘密鍵サービス} で自動生成することもできます。
 * 後でいつでも変更して、既存のすべての cookie を無効にできます。これにより、すべてのユーザーを強制的に再ログインさせることになります。
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '@VO~~97n;<8|A?hLu3!$oKK_Q#qSUk-YwQwFmF^cZlnnZ$r1Wr$Q3Mgr]+SCXd=o');
define('SECURE_AUTH_KEY',  '3+SPd%A]~]U:;6.KI5)@7XD pHL4Q3#F58Fa#z7mi<FVh~<(arVQ-|/,SI*#`!C;');
define('LOGGED_IN_KEY',    'U{9ZsU~if!)2r<2oO -|EE^V~[/pR8!qhK8e0++R+CJ.q=56U>-sd7|F!,AW#sF7');
define('NONCE_KEY',        'Dp!U<F@$QEr1An<V-PBfgG|6cB+e.JNZ39Pmw:KK=&g>BxBsV|t3HQhL )``M{9@');
define('AUTH_SALT',        't<Q]jKV%cifv8Af@vw .zW|6+}v>W.5wNO9F(z2Z%%bT@qy$UUr/Sl/{z6e-x9@o');
define('SECURE_AUTH_SALT', '~[}p|/D0oQ-6[n?@>z|w7;!!bw-@7q-Etw&:iK4(!&:V4F%Hr%#S/@(Gf%!kp*KB');
define('LOGGED_IN_SALT',   'Br5dmdYIY[xO&m/|W{QWXLro c{NMkbBB|icb-AN~H;;5`)0:Wak+M$^:~ZX)pM<');
define('NONCE_SALT',       'JfBYb@~UHO?T|]Rm: |{4/,nj|{K)j@7H?v%N]G?<&Qe]d; t-Ri~@cs,x_V[?&)');

/**#@-*/

/**
 * WordPress データベーステーブルの接頭辞
 *
 * それぞれにユニーク (一意) な接頭辞を与えることで一つのデータベースに複数の WordPress を
 * インストールすることができます。半角英数字と下線のみを使用してください。
 */
$table_prefix  = 'wp_';

/**
 * 開発者へ: WordPress デバッグモード
 *
 * この値を true にすると、開発中に注意 (notice) を表示します。
 * テーマおよびプラグインの開発者には、その開発環境においてこの WP_DEBUG を使用することを強く推奨します。
 *
 * その他のデバッグに利用できる定数については Codex をご覧ください。
 *
 * @link http://wpdocs.osdn.jp/WordPress%E3%81%A7%E3%81%AE%E3%83%87%E3%83%90%E3%83%83%E3%82%B0
 */
define('WP_DEBUG', false);

/* 編集が必要なのはここまでです ! WordPress でブログをお楽しみください。 */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
