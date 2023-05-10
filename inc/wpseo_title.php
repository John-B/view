<?php
add_filter( 'wpseo_title', 'gv_page_title' );
function gv_page_title( $title ) {
	// var_dump( $title );
	switch ( $title ) {
		case 'Grants Archive | Good Ventures':
			$title = 'Our Portfolio | Good Ventures';
			break;
		case 'Press Archive | Good Ventures':
			$title = 'Press | Good Ventures';
			break;
	}
	return $title;
}
