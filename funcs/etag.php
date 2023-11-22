<?php
namespace Yuma;

// insired by https://www.php.net/manual/en/function.header.php

function etag($file) {
	$last_modified_time = filemtime ($file);
	$etag = md5_file ($file);
	
	header ('Last-Modified: '. gmdate ('D, d M Y H:i:s', $last_modified_time) .' GMT');
	header ("Etag: $etag");
	
	if (function_exists('getServerValue')) {
		if (@strtotime (getServerValue('HTTP_IF_MODIFIED_SINCE')) == $last_modified_time || trim (getServerValue('HTTP_IF_NONE_MATCH')) == $etag) {
			header (getServerValue('SERVER_PROTOCOL'). ' 304 Not Modified');	// header("HTTP/1.1 304 Not Modified");
		}
	} else {
		if (@strtotime ($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $last_modified_time || trim ($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) {
			header ($_SERVER["SERVER_PROTOCOL"]. ' 304 Not Modified');	// header("HTTP/1.1 304 Not Modified");
		}
	}
	exit;
}
