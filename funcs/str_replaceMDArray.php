<?php
namespace Yuma;

function str_replace_json($search, $replace, $subject){
	return json_decode( str_replace($search, $replace, json_encode($subject)), true );
}

function preg_replace_json($search, $replace, $subject){
	$json = json_encode($subject);
//	echo 'preg_replace_json: looking for '. $search. ' replacing with '. $replace. ' in '.$json. "<br>\n";
	return json_decode( preg_replace($search, $replace, $json), true );
}
