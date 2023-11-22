<?php
namespace Yuma;
/**
 * Applies either the multi-byte or standard string function
 * eg.
 * do_mbstr('substr', $string, o, 4);
 * first parameter is a string of the standard string function name
 * the remaining parameters are the same as the function call
 */
function do_mbstr()
{
	$pre = 'do_mbstr() ';
	if (!mb_check_encoding(func_get_arg(1), CHARSET)) {
		$log = 'string is not '. CHARSET;
		$trace = debug_backtrace();
		$log .= ' (' . $trace[1]['file'] .
			' #' . $trace[1]['line']. ')';
					try {
						$logger = new Logger;
						$logger->log($pre. $log);
					} catch (Exception $e) {
						error_log ($pre. $log);
					}
/*		(is_callable(array('Error', 'log'))) &&
			Error::log( $pre. $log ) ||
			error_log( $pre. $log );*/
		//error_log('do_mbstr() string is not '. CHARSET);
	}
	//error_log('do_mbstr() func_num_args()='. func_num_args());
	switch(func_get_arg(0))
	{
	case 'substr':
		if (ini_get('mbstring.http_output')) {
			//return mb_substr($str, $start, $length);
			//if (null !== func_get_arg(3)) {
			if (func_num_args() > 3) {
				return mb_substr(func_get_arg(1), func_get_arg(2), func_get_arg(3));
			} else {
				return mb_substr(func_get_arg(1), func_get_arg(2));
			}
		} else {
			//return substr($str, $start, $length);
			//return substr(func_get_arg(1), func_get_arg(2), func_get_arg(3));
			//if (null !== func_get_arg(3)) {
			if (func_num_args() > 3) {
				return substr(func_get_arg(1), func_get_arg(2), func_get_arg(3));
			} else {
				return substr(func_get_arg(1), func_get_arg(2));
			}
		}
		break;
	case 'strstr':
		if (ini_get('mbstring.http_output')) {
			return mb_strstr(func_get_arg(1), func_get_arg(2), func_get_arg(3));
		} else {
			return strstr(func_get_arg(1), func_get_arg(2), func_get_arg(3));
		}
		break;
	case 'strtr':
		// https://www.php.net/manual/en/function.strtr.php
		if (ini_get('mbstring.http_output')) {
			if (is_array(func_get_arg(2))) {
				$from = array_map('utf8_decode', func_get_arg(2));
 				$from = array_map('utf8_decode', array_flip (func_get_arg(2)));
 				return utf8_encode (strtr (utf8_decode (func_get_arg(1)), array_flip (func_get_arg(2))));
 			}
 			return utf8_encode (strtr (utf8_decode (func_get_arg(1)), utf8_decode(func_get_arg(2)), utf8_decode (func_get_arg(3))));
		} else {
			return strtr(func_get_arg(1), func_get_arg(2), func_get_arg(3));
		}
		break;
	case 'strlen':
		if (ini_get('mbstring.http_output')) {
			return mb_strlen(func_get_arg(1));
		} else {
			return strlen(func_get_arg(1));
		}
		break;
	case 'strspn':
/*		if (ini_get('mbstring.http_output')) {
			if (func_num_args() > 3) {
				return mb_strspn(func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			} else {
				return mb_strspn(func_get_arg(1), func_get_arg(2));
			}
			$log = 'strspn is not defined for multi-byte';
			(is_callable(array('Error', 'log'))) &&
				Error::log( $pre. $log ) ||
				error_log( $pre. $log );
		} else {*/
			if (func_num_args() > 3) {
				return strspn(func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
			} else {
				return strspn(func_get_arg(1), func_get_arg(2));
			}
/*		}*/
		break;
	case 'str_pad':
		if (ini_get('mbstring.http_output')) {
			return str_pad(func_get_arg(1), strlen(func_get_arg(1))-mb_strlen(func_get_arg(1)/*, $encoding*/)+func_get_arg(2), func_get_arg(3), func_get_arg(4));
		} else {
			return str_pad(func_get_arg(1), func_get_arg(2), func_get_arg(3), func_get_arg(4));
		}
		break;
	case 'trim':
		if (ini_get('mbstring.http_output')) {
			$trim_chars = (null !== func_get_arg(2)) ? func_get_arg(2) : "\s";
			return preg_replace('/^['. $trim_chars. ']*(?U)(.*)['. $trim_chars. ']*$/u', '\\1', func_get_arg(1));
		} else {
			return trim(func_get_arg(1), func_get_arg(2));
		}
		break;
	case 'str_replace':
		if (ini_get('mbstring.http_output')) {
			return implode(func_get_arg(1), 0, func_get_arg(3)). func_get_arg(2). mb_substr(func_get_arg(1), func_get_arg(3)+func_get_arg(4));
		} else {
			return str_replace(func_get_arg(1), func_get_arg(2), func_get_arg(3)/*, func_get_arg(4)*/);
		}
		break;
	case 'parse_url':
		if (ini_get('mbstring.http_output')) {
/*			$enc_url = preg_replace_callback( '%[^:/@?&=#]+%usD', function ($matches)
				{
					return urlencode($matches[0]);
				}, $url
			);
			$parts = parse_url($enc_url);
			if ($parts === false) {
				throw new \InvalidArgumentException('Malformed URL: ' . $url);
			}
			foreach($parts as $name => $value) {
				$parts[$name] = urldecode($value);
			}
			return $parts;
 */
			$encodedUrl = preg_replace('%[^:/?#&=\.]+%usDe', 'urlencode(\'$0\')', func_get_arg(1));
			$components = parse_url(trim($encodedUrl));
			foreach ($components as &$component)
				$component = urldecode($component);
			return $components;
		} else {
			return parse_url(func_get_arg(1));
		}
		break;
	default:
		//if (ini_get('mbstring.http_output') && is_callable('mb_'. func_get_arg([0]) {
		if (ini_get('mbstring.http_output') && function_exists('mb_'. func_get_arg([0]))) {
			$args = func_get_args();
			array_shift($args);
			return call_user_func_array('mb_'. func_get_arg(0), $args);
		} else {
			$args = func_get_args();
			array_shift($args);
			return call_user_func_array(func_get_arg(0), $args);
		}
		break;
	}
}
/*function mb_substr_replace($string, $replacement, $start, $lengith){
	return mb_substr($string, 0, $start).$replacement.mb_substr($string, $start+$length);
}*/
function mb_str_replace($needle, $replacement, $haystack) {
	return implode($replacement, mb_split($needle, $haystack));
}
function parseUrl(string $url) {
	$r  = "^(?:(?P<scheme>\w+)://)?";
	$r .= "(?:(?P<login>\w+):(?P<pass>\w+)@)?";
	$r .= "(?P<host>(?:(?P<subdomain>[\w\.]+)\.)?" . "(?P<domain>\w+\.(?P<extension>\w+)))";
	$r .= "(?::(?P<port>\d+))?";
	$r .= "(?P<path>[\w/]*/(?P<file>\w+(?:\.\w+)?)?)?";
	$r .= "(?:\?(?P<arg>[\w=&]+))?";
	$r .= "(?:#(?P<anchor>\w+))?";
	$r = "!$r!";												 // Delimiters
	
	preg_match ( $r, $url, $out );
	
	return $out;
}
function normaliza (string $string) {
	// https://www.php.net/manual/en/function.strtr.php
	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	$string = utf8_decode($string);
	$string = strtr($string, utf8_decode($a), $b);
	$string = strtolower($string);
	return utf8_encode($string);
}
function mb_rawurlencode (string $url)
{
	$encoded = '';
	$length = mb_strlen($url);
	for ($i=0; $i<$length; $i++) {
		$encoded .= '%'. wordwrap (bin2hex (mb_substr ($url, $i, 1) ), 2, '%', true);
	}
	return $encoded;
}
