<footer id="footer">
<?php
$defaults = array(
	'theme_location'  => 'secondary-menu',
	'container'       => 'div',
	'container_class' => 'footermenubox clearfix ',
	'menu_class'      => 'footermenust',
	'depth'           => 1,
);
//wp_nav_menu( $defaults );
?>
<!--
	<h3>
		<?php if ( is_home() or is_front_page() ) { ?>
			<?php bloginfo( 'name' ); ?>
		<?php } else { ?>
			<?php st_wp_title( '' ); ?>
		<?php } ?>
	</h3>
-->

<!--
	<p>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'description' ); ?></a>
	</p>
-->

	<p class="copy">Copyright&copy;
		<?php bloginfo( 'name' ); ?>
		,
		<?php echo date( 'Y' ); ?>
		All Rights Reserved.</p>
</footer>
</div>
<!-- /#wrapper -->
<!-- ページトップへ戻る -->
<div id="page-top"><a href="#wrapper" class="fa fa-angle-up"></a></div>
<!-- ページトップへ戻る　終わり -->
<?php wp_enqueue_script( 'base', get_template_directory_uri() . '/js/base.js', array() ); ?>

<?php if ( st_is_mobile() ) { //PCのみ追尾広告のjs読み込み ?>
<?php } else { ?>
	<?php wp_enqueue_script( 'scroll', get_template_directory_uri() . '/js/scroll.js', array() ); ?>
<?php } ?>

<?php wp_footer(); ?>
</body></html>
