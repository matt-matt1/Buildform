<?php
namespace Yuma;
function header2( string $header, bool $replace = true, int $response_code = 0 ) {
	if( !defined('PRINT_HEADERS') || !PRINT_HEADERS) {
		header( $header, $replace, $response_code);
	} else { 
		echo "$header". " (replace=$replace, response_code=$response_code)\n";
	}
}
