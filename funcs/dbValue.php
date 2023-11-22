<?php
namespace Yuma;

function dbValue($key, $level=0)
{
	$val = filter_input(INPUT_POST, $key, FILTER_DEFAULT);
	if (!$val) {
		$val = filter_input(INPUT_POST, $key, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
		if (is_array($val)/*is_integer($val) || is_string($val)*/) {
			$val = $val[$level];
		}
	}
	if (is_integer($val)) {
		if (!$val && $val != 0) {
			return 0;
		}
		return $val;
	} elseif (is_string($val) && $val == 'on') {
		return true;
	} elseif (is_string($val) && $val != 'on') {
		return '\''. trim($val). '\'';
	} else {
		return '';//'\'\'';//0;//'-- unknown --';//$val;
	}
}
