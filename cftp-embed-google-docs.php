<?php 
/*
Plugin Name: Embed Google Docs & Maps
Plugin URI:  https://github.com/cftp/embed-google-docs
Description: Easily embed Google Docs and Google Maps into your post content by pasting the URL on its own line.
Version:     1.1
Author:      Code for the People
Author URI:  http://codeforthepeople.com/ 

--------------------------------------------------------

This is a plugin version of the patch at http://core.trac.wordpress.org/ticket/23622
written by Samuel Wood (otto42).

--------------------------------------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

 
function cftp_embed_handler_googledrive( $matches, $attr, $url, $rawattr ) {
	if ( !empty($rawattr['width']) && !empty($rawattr['height']) ) {
		$width  = (int) $rawattr['width'];
		$height = (int) $rawattr['height'];
	} else {
		list( $width, $height ) = wp_expand_dimensions( 425, 344, $attr['width'], $attr['height'] );
	}

	$extra = '';
	if ( $matches[1] == 'spreadsheet' ) {
		$url .= '&widget=true';
	} elseif ( $matches[1] == 'document' ) {
		$url .= '?embedded=true';
	} elseif ( $matches[1] == 'presentation' ) {
		$url = str_replace( '/pub', '/embed', $url);
		$extra = 'allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true"';
	}

	return "<iframe width='{$width}' height='{$height}' frameborder='0' src='{$url}' {$extra}></iframe>";
}

function cftp_embed_handler_googlemaps( $matches, $attr, $url, $rawattr ) {
	if ( !empty($rawattr['width']) && !empty($rawattr['height']) ) {
		$width  = (int) $rawattr['width'];
		$height = (int) $rawattr['height'];
	} else {
		list( $width, $height ) = wp_expand_dimensions( 425, 326, $attr['width'], $attr['height'] );
	}
	return "<iframe width='{$width}' height='{$height}' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='{$url}&output=embed'></iframe>";
}

function init_cftp_embed_handler_google() {
	wp_embed_register_handler( 'cftpgoogledocs', '#https?://docs.google.(com|co\.uk)/(document|spreadsheet|presentation)/.*#i', 'cftp_embed_handler_googledrive' );
	wp_embed_register_handler( 'cftpgooglemaps', '#https?://maps.google.(com|co\.uk)/(maps)?.+#i', 'cftp_embed_handler_googlemaps' );
}

add_action( 'init', 'init_cftp_embed_handler_google' );
