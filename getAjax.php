<?php
//	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
/**
 *
 * @package 
 * @subpackage Administration
 *
 * @link 
 */
/**
 * Executing Ajax process.
 *
 * @since 2.1.0
 */
define( 'DOING_AJAX', true );
//require "config.php";
require_once "begin.php";
require "setdb.php";
require "dohead.php";
header( 'X-AJAX-response: true' );

header( 'Access-Control-Allow-Origin: *' );
header( 'Access-Control-Allow-Methods: GET, PUT, POST, HEAD, DELETE, OPTIONS');
header( 'Access-Control-Allow-Headers: Content-Type' );
/** Allow for cross-domain requests (from the front end). */
//header( 'Access-Control-Allow-Origin: ' . $origin );
header( 'Access-Control-Allow-Credentials: true' );
if ( 'OPTIONS' === $_SERVER['REQUEST_METHOD'] ) {
	exit;
}

header( 'X-Robots-Tag: noindex' );

// Require a valid action parameter.
/*if ( empty( $_REQUEST['action'] ) || ! is_scalar( $_REQUEST['action'] ) ) {
	exit();//wp_die( '0', 400 );
}*/

header( 'X-Content-Type-Options: nosniff' );
header( 'Expires: Wed, 11 Jan 1984 05:00:00 GMT' );
header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
/*
$json = json_decode(file_get_contents("php://input"));
if (isset($json) && !empty($json))
{
	header( 'Content-Type: application/json; charset=' . CHARSET );
	exit($json):
}*/
//header( 'Content-Type: text/html; charset=' . CHARSET );
//header( 'Content-Type: text/html; charset=UTF-8' );
//header( "Content-Type: application/x-www-form-urlencoded; charset=". $GLOBALS['CHARSET'] );
$cont = 'Content-Type: application/x-www-form-urlencoded';
if (defined('CHARSET') && CHARSET)
{
	$cont .= '; charset='. CHARSET;
}
header($cont);
/*
$core_actions_get = array(
	'fetch-list',
	'ajax-tag-search',
	'-compression-test',
	'imgedit-preview',
	'oembed-cache',
	'autocomplete-user',
	'dashboard-widgets',
	'logged-in',
	'rest-nonce',
);

$core_actions_post = array(
	'oembed-cache',
	'image-editor',
	'delete-comment',
	'delete-tag',
	'delete-link',
	'delete-meta',
	'delete-post',
	'trash-post',
	'untrash-post',
	'delete-page',
	'dim-comment',
	'add-link-category',
	'add-tag',
	'get-tagcloud',
	'get-comments',
	'replyto-comment',
	'edit-comment',
	'add-menu-item',
	'add-meta',
	'add-user',
	'closed-postboxes',
	'hidden-columns',
	'update-welcome-panel',
	'menu-get-metabox',
	'wp-link-ajax',
	'menu-locations-save',
	'menu-quick-search',
	'meta-box-order',
	'get-permalink',
	'sample-permalink',
	'inline-save',
	'inline-save-tax',
	'find_posts',
	'widgets-order',
	'save-widget',
	'delete-inactive-widgets',
	'set-post-thumbnail',
	'date_format',
	'time_format',
	'-remove-post-lock',
	'dismiss--pointer',
	'upload-attachment',
	'get-attachment',
	'query-attachments',
	'save-attachment',
	'save-attachment-compat',
	'send-link-to-editor',
	'send-attachment-to-editor',
	'save-attachment-order',
	'media-create-image-subsizes',
	'heartbeat',
	'get-revision-diffs',
	'save-user-color-scheme',
	'update-widget',
	'query-themes',
	'parse-embed',
	'set-attachment-thumbnail',
	'parse-media-shortcode',
	'destroy-sessions',
	'install-plugin',
	'update-plugin',
	'crop-image',
	'generate-password',
	'save-wporg-username',
	'delete-plugin',
	'search-plugins',
	'search-install-plugins',
	'activate-plugin',
	'update-theme',
	'delete-theme',
	'install-theme',
	'get-post-thumbnail-html',
	'get-community-events',
	'edit-theme-plugin-file',
	'-privacy-export-personal-data',
	'-privacy-erase-personal-data',
	'health-check-site-status-result',
	'health-check-dotorg-communication',
	'health-check-is-in-debug-mode',
	'health-check-background-updates',
	'health-check-loopback-requests',
	'health-check-get-sizes',
	'toggle-auto-updates',
	'send-password-reset',
);

// Deprecated.
$core_actions_post_deprecated = array(
	'-fullscreen-save-post',
	'press-this-save-post',
	'press-this-add-category',
	'health-check-dotorg-communication',
	'health-check-is-in-debug-mode',
	'health-check-background-updates',
	'health-check-loopback-requests',
);

$core_actions_post = array_merge( $core_actions_post, $core_actions_post_deprecated );
 */
$_REQUEST = array_merge((array)$_POST, (array)$_GET);
if (!isset($_REQUEST['action'])) {
//if (!isset($_POST['action']) && !isset($_GET['action'])) {
	echo json_encode('AJAX Error: must send "action" variable');// (REQUEST: '. implode(', ', $_REQUEST). ')');
	exit();
}
$action = $_REQUEST['action'];
if ( !empty( $_GET['action'] ) /*&& !empty( $_GET['request'] )*//*&& in_array( $_GET['action'], $core_actions_get, true )*/ ) {
	$_GET['action'] = null;
	unset($_GET['action']);
	$data = array(
		'action' => $action,
		'method' => 'GET',
		'data' => $_GET,
		'nonce' => $_GET['nonce']
	);
}

if ( !empty( $_POST['action'] ) /*&& !empty( $_POST['request'] )*//*&& in_array( $_POST['action'], $core_actions_post, true )*/ ) {
	$_POST['action'] = null;
	unset($_POST['action']);
	$data = array(
		'action' => $action,
		'method' => 'POST',
		'data' => $_POST,
	);
}
if (isset($_GET['nonce']))
	$data['nonce'] = $_GET['nonce'];
if (is_callable("ajax_{$action}")) {
	$data['response'] = do_action("ajax_{$action}", $data['data']);
}
//echo json_encode( print_r($data, true) );
echo json_encode( $data, JSON_HEX_TAG | JSON_UNESCAPED_SLASHES );

//nopriv_generate-password();
//nopriv_heartbeat();

exit();
