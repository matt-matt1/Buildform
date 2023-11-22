<?php
namespace Yuma;
function getRequestValue( $fieldName, $filter=FILTER_DEFAULT, $options=0, $defaultValue=null )
{
	if( filter_input( INPUT_SERVER, "REQUEST_METHOD") == "POST" ) {
		$val = filter_input( INPUT_POST, $fieldName, $filter, $options );
		return ($val) ? $val : (isset($_POST[$fieldName]) ? $_POST[$fieldName] : $defaultValue);
	} else {
		$val = filter_input( INPUT_GET, $fieldName, $filter, $options );
		return ($val) ? $val : (isset($_GET[$fieldName]) ? $_GET[$fieldName] : $defaultValue);
	}
}
function hasPrefix( string $db ) {
	//return strpos( $db, $GLOBALS['data']['PREFIX'] ) === 0;
	return strpos( $db, PREFIX ) === 0;
}
function addPrefix( string $db ) {
	if (hasPrefix($db)) {
		return false;
	}
	//return $GLOBALS['data']['PREFIX']. $db;
	return PREFIX. $db;
}
function delPrefix( string $db ) {
	if (!hasPrefix($db)) {
		return false;
	}
	//return substr( $db, strlen($GLOBALS['data']['PREFIX']));
	return do_mbstr('substr', $db, (do_mbstr('strlen', PREFIX)));
}

function showRequest($cookie) {
/*	if (defined('DEBUG_SHOW_COOKIES') && DEBUG_SHOW_COOKIES) {
		$cookie = getServerValue('HTTP_COOKIE');
		if (empty($cookie))
			$cookie = filter_input_array(INPUT_COOKIE, FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);
		if (empty($cookie))
			$cookie = $_COOKIE;
	}*/
?>
	<div class-"container">
		<div class="request">
			<div>POST: <?php echo print_r($_POST, true); ?></div>
			<div>GET: <?php echo print_r($_GET, true); ?></div>
			<div>REQ.: <?php echo print_r($_REQUEST, true); ?></div>
<?php	if (defined('DEBUG_SHOW_SESSION') && DEBUG_SHOW_SESSION) {
?>			<div>SESS.: <?php echo print_r($_SESSION, true); ?></div>
<?php	}
		if (defined('DEBUG_SHOW_COOKIES') && DEBUG_SHOW_COOKIES) {
?>			<div>COOKIE: <?php echo print_r($cookie, true); ?></div>
<?php	}
?>
		</div>
	</div>
<?php
}

function notFound( string $file='', bool $showPage=true ) {
	//header ($_SERV['SERVER_PROTOCOL']. ' 404 Not Found');
	header (getServerValue('SERVER_PROTOCOL'). ' 404 Not Found');
	if ($showPage)
	{
		//$arr = Array( 'name' => "home", 'href' => BASE. "css/style.css" );
		$arr = Array( 'name' => "home", 'href' => BASE. "css/mine.css" );
		//if (file_exists("css/style.css") && filemtime("css/style.css"))
		if (file_exists("css/mine.css") && filemtime("css/mine.css"))
		{
			//$arr['version'] = filemtime("css/style.css");
			$arr['version'] = filemtime("css/mine.css");
		}
		Link::style($arr);
		echo makeHead( t("Welcome to BuildForm") ), '<div class="container">', "\n";
		$note = new Note(Array(
			'message' => sprintf( t('Cannot find "%s"'), $file ),
			'type' => Note::error,
			'details' => t('_Try: /databases or /database/add or /database/<~database name~> [?remove or /table/add or /table/<~table name~> [?remove or /columns or /column/add or /column/<~column name~>]]'),
			//'type' => 'error',
			//'fatal' => true
		));
		echo $note->display(), '</div>', "\n";
	}
	//exit();
}
function dquote($value) {
	$value = str_ireplace( "&#39;", "", trim( $value, ' \'"`' ) );
	return str_ireplace('""', '"', "\"{$value}\"");
}

function squote($value) {
	$value = str_ireplace( "&#39;", "", trim( $value, ' \'"`' ) );
	return str_ireplace("''", "'", "'{$value}'");
}
/**/
function enquote($value) {
	$value = str_ireplace( "&#39;", "", trim( $value, ' \'"`' ) );
	//return preg_replace( '/[^a-zA-Z0-9_-]/s', '_', $value );
	return '`' . str_ireplace('`', '', $value) . '`';
}
/**/
if (!function_exists('str_ends_with')) {
	/**
	 * Polyfill for `str_ends_with()` function added in PHP 8.0.
	 *
	 * Performs a case-sensitive check indicating if
	 * the haystack ends with needle.
	 *
	 * @param string $haystack The string to search in.
	 * @param string $needle   The substring to search for in the `$haystack`.
	 * @return bool True if `$haystack` ends with `$needle`, otherwise false.
	 */
	function str_ends_with($haystack, $needle) {
		if ('' === $haystack) {
			return '' === $needle;
		}
		$len = strlen($needle);

		return substr($haystack, -$len, $len) === $needle;
	}
}

