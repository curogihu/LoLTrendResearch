<?php 
	if ( isset($GLOBALS['stdata5']) && $GLOBALS['stdata5'] === 'yes' ) {
	get_template_part( 'itiran-thumbnail-off' ); 
}else{
	get_template_part( 'itiran-thumbnail-on' ); 
}

?>