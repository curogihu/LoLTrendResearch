<?php

// 直接アクセスを禁止
if ( !defined( 'ABSPATH' ) ) {
     exit;
}?>

<?php if (is_home() || is_front_page()) { ?>
	<div id="gazou<?php st_headerwide_class(); ?>">
		<?php if ( get_header_image() ): ?>
			<img src="<?php header_image(); ?>" alt="*" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" >
		<?php endif; ?>
	</div>

<?php } else { ?>
	<?php if ( isset($GLOBALS['stdata18']) && $GLOBALS['stdata18'] === 'yes' ) { ?>
		<div id="gazou<?php st_headerwide_class(); ?>">
			<?php if ( get_header_image() ): ?>
				<img src="<?php header_image(); ?>" alt="*" width="<?php echo HEADER_IMAGE_WIDTH; ?>" height="<?php echo HEADER_IMAGE_HEIGHT; ?>" >
			<?php endif; ?>
		</div>
	<?php } ?>
<?php } ?>

<!-- /gazou -->