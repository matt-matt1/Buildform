<?php
namespace Yuma;
	use Yuma\Database\Store;

defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	echo makeHead(array( 'page_title' => t('_sett_desc'), 'body_id' => 'sett', 'body_class' => "", 'page_description' => "", 'page_posttitle' => "", 'page_pretitle' => "BuildForm | " ));
//	Scripts::getInstance()->enqueue(Array( 'name' => 'form', 'src' => BASE. 'js/form.js', 'version' => filemtime('js/form.js') ));

//require_once 'funcs/forms.php';
$values = array();	// values to be passed to HTML form
$store = new Store();
/*$values['ABS_PATH'] = array(
	'id' => 'ABS_PATH',
	'value' => $store->load('ABS_PATH', defined('ABS_PATH') ? ABS_PATH : NULL),	// prime storage with default defined values if not already set
	'input_class' => 'input col-9',	//col-lg-3 
	'label_class' => 'right'
);*/
$default_field = array('label_wrap_class' => 'label col-sm-12 col-xs-12 col-lg-3', 'input_wrap_class' => 'input col-xs-12 xcol-sm-12 col-3');
$values['CHARSET'] = array('id' => 'CHARSET', 'value' => $store->load('CHARSET', defined('CHARSET') ? CHARSET : NULL), /*'type' => 'text',*/ 'extra' => 'autofocus');
$values['SHOW_OTHER_DBS'] = array('id' => 'SHOW_OTHER_DBS', 'value' => $store->load('SHOW_OTHER_DBS', defined('SHOW_OTHER_DBS') ? SHOW_OTHER_DBS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_wrap_class' => 'label col-md-3', 'label_class' => 'right', 'input_wrap_class' => 'input col-auto', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['SHOW_OG_TAGS'] = array('id' => 'SHOW_OG_TAGS', 'value' => $store->load('SHOW_OG_TAGS', defined('SHOW_OG_TAGS') ? SHOW_OG_TAGS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_wrap_class' => 'label col-md-3', 'label_class' => 'right', 'input_wrap_class' => 'input col-auto', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['SHOW_TWITTER_TAGS'] = array('id' => 'SHOW_TWITTER_TAGS', 'value' => $store->load('SHOW_TWITTER_TAGS', defined('SHOW_TWITTER_TAGS') ? SHOW_TWITTER_TAGS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_wrap_class' => 'label col-md-3', 'label_class' => 'right', 'input_wrap_class' => 'input col-auto', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['SHOW_ARTICLE_TAGS'] = array('id' => 'SHOW_ARTICLE_TAGS', 'value' => $store->load('SHOW_ARTICLE_TAGS', defined('SHOW_ARTICLE_TAGS') ? SHOW_ARTICLE_TAGS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_wrap_class' => 'label col-md-3', 'label_class' => 'right', 'input_wrap_class' => 'input col-auto', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['PREFIX'] = array('id' => 'PREFIX', 'value' => $store->load('PREFIX', defined('PREFIX') ? PREFIX : NULL)/*, 'type' => 'text'*/);
$values['RESERVED'] = array('id' => 'RESERVED', 'value' => $store->load('RESERVED', defined('RESERVED') ? RESERVED : NULL), 'row_class' => "row mb-2 g-2 row-cols-2 textarea5", 'type' => 'textarea', 'input_wrap_class' => 'input col-md-9', 'label_title' => t('_database names that cannot be used'));
$values['ADD_DB'] = array('id' => 'ADD_DB', 'value' => $store->load('ADD_DB', defined('ADD_DB') ? ADD_DB : NULL)/*, 'type' => 'text'*/);
$values['ADD_TBL'] = array('id' => 'ADD_TBL', 'value' => $store->load('ADD_TBL', defined('ADD_TBL') ? ADD_TBL : NULL)/*, 'type' => 'text'*/);
$values['ADD_COL'] = array('id' => 'ADD_COL', 'value' => $store->load('ADD_COL', defined('ADD_COL') ? ADD_COL : NULL)/*, 'type' => 'text'*/);
$values['ADD_FRM'] = array('id' => 'ADD_FRM', 'value' => $store->load('ADD_FRM', defined('ADD_FRM') ? ADD_FRM : NULL)/*, 'type' => 'text'*/);
$values['ADD_ROW'] = array('id' => 'ADD_ROW', 'value' => $store->load('ADD_ROW', defined('ADD_ROW') ? ADD_ROW : NULL)/*, 'type' => 'text'*/);
$values['NOTIFY_CREATE_DB'] = array('id' => 'NOTIFY_CREATE_DB', 'value' => $store->load('NOTIFY_CREATE_DB', defined('NOTIFY_CREATE_DB') ? NOTIFY_CREATE_DB : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['NOTIFY_DELETE_DB'] = array('id' => 'NOTIFY_DELETE_DB', 'value' => $store->load('NOTIFY_DELETE_DB', defined('NOTIFY_DELETE_DB') ? NOTIFY_DELETE_DB : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['NOTIFY_CREATE_TABLE'] = array('id' => 'NOTIFY_CREATE_TABLE', 'value' => $store->load('NOTIFY_CREATE_TABLE', defined('NOTIFY_CREATE_TABLE') ? NOTIFY_CREATE_TABLE : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['NOTIFY_DELETE_TABLE'] = array('id' => 'NOTIFY_DELETE_TABLE', 'value' => $store->load('NOTIFY_DELETE_TABLE', defined('NOTIFY_DELETE_TABLE') ? NOTIFY_DELETE_TABLE : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['NOTIFY_CREATE_RECORD'] = array('id' => 'NOTIFY_CREATE_RECORD', 'value' => $store->load('NOTIFY_CREATE_RECORD', defined('NOTIFY_CREATE_RECORD') ? NOTIFY_CREATE_RECORD : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['NOTIFY_DELETE_RECORD'] = array('id' => 'NOTIFY_DELETE_RECORD', 'value' => $store->load('NOTIFY_DELETE_RECORD', defined('NOTIFY_DELETE_RECORD') ? NOTIFY_DELETE_RECORD : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['NOTIFY_CREATE_COLUMN'] = array('id' => 'NOTIFY_CREATE_COLUMN', 'value' => $store->load('NOTIFY_CREATE_COLUMN', defined('NOTIFY_CREATE_COLUMN') ? NOTIFY_CREATE_COLUMN : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['NOTIFY_DELETE_COLUMN'] = array('id' => 'NOTIFY_DELETE_COLUMN', 'value' => $store->load('NOTIFY_DELETE_COLUMN', defined('NOTIFY_DELETE_COLUMN') ? NOTIFY_DELETE_COLUMN : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['DEBUG'] = array(
	'HEAD_PLACEHOLDERS' => array('id' => 'HEAD_PLACEHOLDERS', 'value' => $store->load('HEAD_PLACEHOLDERS', defined('DEBUG') && isset(\DEBUG['HEAD_PLACEHOLDERS']) ? DEBUG['HEAD_PLACEHOLDERS'] : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_wrap_class' => 'label col-md-3', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox'),
	'SHOW_REQUEST' => array('id' => 'SHOW_REQUEST', 'value' => $store->load('SHOW_REQUEST', defined('DEBUG') && isset(\DEBUG['SHOW_REQUEST']) ? DEBUG['SHOW_REQUEST'] : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_wrap_class' => 'label col-md-3', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox'),
);
$values['DEBUG_SHOW_REQ'] = array('id' => 'DEBUG_SHOW_REQ', 'value' => $store->load('DEBUG_SHOW_REQ', defined('DEBUG_SHOW_REQ') ? DEBUG_SHOW_REQ : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['DEBUG_SHOW_HEAD_COMMENTS'] = array('id' => 'DEBUG_SHOW_HEAD_COMMENTS', 'value' => $store->load('DEBUG_SHOW_HEAD_COMMENTS', defined('DEBUG_SHOW_HEAD_COMMENTS') ? DEBUG_SHOW_HEAD_COMMENTS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['DEBUG_SHOW_HTTP_VARS'] = array('id' => 'DEBUG_SHOW_HTTP_VARS', 'value' => $store->load('DEBUG_SHOW_HTTP_VARS', defined('DEBUG_SHOW_HTTP_VARS') ? \DEBUG_SHOW_HTTP_VARS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['DEBUG_HIDE_HEAD_METAS'] = array('id' => 'DEBUG_HIDE_HEAD_METAS', 'value' => $store->load('DEBUG_HIDE_HEAD_METAS', defined('DEBUG_HIDE_HEAD_METAS') ? DEBUG_HIDE_HEAD_METAS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['DEBUG_HIDE_HEAD_STYLES'] = array('id' => 'DEBUG_HIDE_HEAD_STYLES', 'value' => $store->load('DEBUG_HIDE_HEAD_STYLES', defined('DEBUG_HIDE_HEAD_STYLES') ? DEBUG_HIDE_HEAD_STYLES : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['DEBUG_HIDE_HEAD_SCRIPTS'] = array('id' => 'DEBUG_HIDE_HEAD_SCRIPTS', 'value' => $store->load('DEBUG_HIDE_HEAD_SCRIPTS', defined('DEBUG_HIDE_HEAD_SCRIPTS') ? DEBUG_HIDE_HEAD_SCRIPTS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['PREVENT_DELETE_DB_IF_HAS_TABLES'] = array('id' => 'PREVENT_DELETE_DB_IF_HAS_TABLES', 'value' => $store->load('PREVENT_DELETE_DB_IF_HAS_TABLES', defined('PREVENT_DELETE_DB_IF_HAS_TABLES') ? PREVENT_DELETE_DB_IF_HAS_TABLES : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['PREVENT_DELETE_TABLE_IF_HAS_ROWS'] = array('id' => 'PREVENT_DELETE_TABLE_IF_HAS_ROWS', 'value' => $store->load('PREVENT_DELETE_TABLE_IF_HAS_ROWS', defined('PREVENT_DELETE_TABLE_IF_HAS_ROWS') ? PREVENT_DELETE_TABLE_IF_HAS_ROWS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['PREVENT_DELETE_FORM_IF_HAS_ROWS'] = array('id' => 'PREVENT_DELETE_FORM_IF_HAS_ROWS', 'value' => $store->load('PREVENT_DELETE_FORM_IF_HAS_ROWS', defined('PREVENT_DELETE_FORM_IF_HAS_ROWS') ? PREVENT_DELETE_FORM_IF_HAS_ROWS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['PREVENT_DELETE_TABLE_IF_HAS_COLUMNS'] = array('id' => 'PREVENT_DELETE_TABLE_IF_HAS_COLUMNS', 'value' => $store->load('PREVENT_DELETE_TABLE_IF_HAS_COLUMNS', defined('PREVENT_DELETE_TABLE_IF_HAS_COLUMNS') ? PREVENT_DELETE_TABLE_IF_HAS_COLUMNS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['PREVENT_DELETE_FORM_IF_HAS_COLUMNS'] = array('id' => 'PREVENT_DELETE_FORM_IF_HAS_COLUMNS', 'value' => $store->load('PREVENT_DELETE_FORM_IF_HAS_COLUMNS', defined('PREVENT_DELETE_FORM_IF_HAS_COLUMNS') ? PREVENT_DELETE_FORM_IF_HAS_COLUMNS : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['DB_HOST'] = array('id' => 'DB_HOST', 'value' => $store->load('DB_HOST', defined('DB_HOST') ? DB_HOST : NULL), 'input_wrap_class' => 'input col-md-9');
$values['DB_NAME'] = array('id' => 'DB_NAME', 'value' => $store->load('DB_NAME', defined('DB_NAME') ? DB_NAME : NULL), 'type' => 'text');
$values['DB_USER'] = array('id' => 'DB_USER', 'value' => $store->load('DB_USER', defined('DB_USER') ? DB_USER : NULL), 'type' => 'text', 'extra' => 'autocomplete="off"');
//$values['DB_PASS'] = array('id' => 'DB_PASS', 'value' => ($store->load('DB_PASS', defined('DB_PASS') ? DB_PASS : NULL)) ? '*****' : '', 'type' => 'text'/*'password'*//*'email'*/, 'extra' => 'data-password-input="true"', 'canshow' => true);
$values['DB_PASS'] = array('id' => 'DB_PASS', 'value' => $store->load('DB_PASS', defined('DB_PASS') ? DB_PASS : NULL), 'type' => 'text'/*'password'*//*'email'*/, 'extra' => 'data-password-input="true"', 'canshow' => true);
$values['DB_PORT'] = array('id' => 'DB_PORT', 'value' => $store->load('DB_PORT', defined('DB_PORT') ? \DB_PORT : NULL), 'placeholder' => '3306', 'type' => 'number');
$values['DB_TYPE'] = array('id' => 'DB_TYPE', 'value' => $store->load('DB_TYPE', defined('DB_TYPE') ? DB_TYPE : NULL), 'type' => 'select'/*, 'default' => DB_TYPE*/);
$values['DB_SOCK'] = array('id' => 'DB_SOCK', 'value' => $store->load('DB_SOCK', defined('DB_SOCK') ? \DB_SOCK : NULL), 'row_class' => 'row g-2 mb-4 form-switch', 'label_class' => 'right', 'input_class' => 'big-check', 'type' => 'checkbox');
$values['DB_TIME'] = array('id' => 'DB_TIME', 'value' => $store->load('DB_TIME', defined('DB_TIME') ? DB_TIME : NULL), 'type' => 'number');
$values['DB_CHAR'] = array('id' => 'DB_CHAR', 'value' => $store->load('DB_CHAR', defined('DB_CHAR') ? DB_CHAR : NULL), 'type' => 'text');
//$values['DB_OPTS'] = array('id' => 'DB_OPTS', 'value' => unserialize($store->load('DB_OPTS', defined('DB_OPTS') ? DB_OPTS : NULL)), 'default_class' => 'right', 'row_class' => "row mb-2 g-2 row-cols-2 textarea5");
//$values['DB_OPTS'] = array('id' => 'DB_OPTS', 'value' => $store->load('DB_OPTS', defined('DB_OPTS') ? DB_OPTS : NULL), 'default_class' => 'right', 'row_class' => "row mb-2 g-2 row-cols-2 textarea5");
$values['DB_OPTS'] = array('id' => 'DB_OPTS', 'value' => $store->load('DB_OPTS', isset($GLOBALS['DB_OPTS']) ? $GLOBALS['DB_OPTS'] : NULL), 'default_class' => 'right', 'row_class' => "row mb-2 g-2 row-cols-2 xtextarea5", 'type' => 'textarea', 'input_wrap_class' => 'input col-md-9');
//	$body = t('_Really reset all form values to their defaults');
//	$title = t('_Do you really want to do this?');
//	$href = BASE. 'settings';
$values['RESET'] = array('id' => 'RESET', 'type' => 'submit'/*'type' => 'button'*/, 'value' => t('_Reset_all'), 'title' => t('_RESET'), /*'row_class' => "row xmb-2 xg-2",*/ 'input_class' => 'danger symbol modal-trigger', /*'input_wrap_class' => 'submit xinput xcol-3',*/ 'label_wrap_class' => 'hide', 'extra' => 'data-bs-toggle="modal" data-bs-target="#myModal" data-title="'. t('_Do you really want to do this?').
	'" data-body="'. t('_Really reset all form values to their defaults') .'" href="'. BASE. 'settings'. '" data-lpignore="true"');
//$values['RESET'] = array('id' => 'RESET', 'type' => 'submit'/*'button'*/, 'value' => t('_Reset_all'), 'row_class' => "row xmb-2 xg-2", 'input_class' => 'danger', 'input_wrap_class' => 'submit xinput xcol-md-3', 'label_wrap_class' => 'hide', 'extra' => 'data-bs-toggle="modal" data-bs-target="#myModal" data-name="'. t('_RESET'). ' class="symbol modal-trigger" onclick="return confirm(\''. t('Do you really want to do this?'). '\');"');

if (isset($_POST['RESET']) && !empty($_POST['RESET']) || 
	isset($_GET['reset']) /*&& !empty($_GET['reset'])*/)
{
/*	$note = new Note(Array(
		'type' => Note::warning,
		'canIgnore' => true,
		'message' => sprintf( t("_Cannot create form %s"), $form ),
		'details' => implode(' - ', (array)$e->errorInfo). "<br>\nQuery: {$qry}",
	));
	echo $note->display();
*/
	$values['CHARSET']['value'] = defined('CHARSET') ? CHARSET : NULL;
	$values['SHOW_OTHER_DBS']['value'] = defined('SHOW_OTHER_DBS') ? SHOW_OTHER_DBS : NULL;
	$values['SHOW_OG_TAGS']['value'] = defined('SHOW_OG_TAGS') ? SHOW_OG_TAGS : NULL;
	$values['SHOW_TWITTER_TAGS']['value'] = defined('SHOW_TWITTER_TAGS') ? SHOW_TWITTER_TAGS : NULL;
	$values['SHOW_ARTICLE_TAGS']['value'] = defined('SHOW_ARTICLE_TAGS') ? SHOW_ARTICLE_TAGS : NULL;
	$values['PREFIX']['value'] = defined('PREFIX') ? PREFIX : NULL;
	$values['RESERVED']['value'] = defined('RESERVED') ? RESERVED : NULL;
	$values['ADD_DB']['value'] = defined('ADD_DB') ? ADD_DB : NULL;
	$values['ADD_TBL']['value'] = defined('ADD_TBL') ? ADD_TBL : NULL;
	$values['ADD_COL']['value'] = defined('ADD_COL') ? ADD_COL : NULL;
	$values['ADD_FRM']['value'] = defined('ADD_FRM') ? ADD_FRM : NULL;
	$values['ADD_ROW']['value'] = defined('ADD_ROW') ? ADD_ROW : NULL;
	$values['NOTIFY_CREATE_DB']['value'] = defined('NOTIFY_CREATE_DB') ? NOTIFY_CREATE_DB : NULL;
	$values['NOTIFY_DELETE_DB']['value'] = defined('NOTIFY_DELETE_DB') ? NOTIFY_DELETE_DB : NULL;
	$values['NOTIFY_CREATE_TABLE']['value'] = defined('NOTIFY_CREATE_TABLE') ? NOTIFY_CREATE_TABLE : NULL;
	$values['NOTIFY_DELETE_TABLE']['value'] = defined('NOTIFY_DELETE_TABLE') ? NOTIFY_DELETE_TABLE : NULL;
	$values['NOTIFY_CREATE_RECORD']['value'] = defined('NOTIFY_CREATE_RECORD') ? NOTIFY_CREATE_RECORD : NULL;
	$values['NOTIFY_DELETE_RECORD']['value'] = defined('NOTIFY_DELETE_RECORD') ? NOTIFY_DELETE_RECORD : NULL;
	$values['NOTIFY_CREATE_COLUMN']['value'] = defined('NOTIFY_CREATE_COLUMN') ? NOTIFY_CREATE_COLUMN : NULL;
	$values['NOTIFY_DELETE_COLUMN']['value'] = defined('NOTIFY_DELETE_COLUMN') ? NOTIFY_DELETE_COLUMN : NULL;
	$values['DEBUG']['HEAD_PLACEHOLDERS']['value'] = defined('DEBUG') && isset(\DEBUG['HEAD_PLACEHOLDERS']) ? DEBUG['HEAD_PLACEHOLDERS'] : NULL;
	$values['DEBUG']['SHOW_REQUEST']['value'] = defined('DEBUG') && isset(\DEBUG['SHOW_REQUEST']) ? DEBUG['SHOW_REQUEST'] : NULL;
	$values['DEBUG_SHOW_REQ']['value'] = defined('DEBUG_SHOW_REQ') ? DEBUG_SHOW_REQ : NULL;
	$values['DEBUG_SHOW_HEAD_COMMENTS']['value'] = defined('DEBUG_SHOW_HEAD_COMMENTS') ? DEBUG_SHOW_HEAD_COMMENTS : NULL;
	$values['DEBUG_SHOW_HTTP_VARS']['value'] = defined('DEBUG_SHOW_HTTP_VARS') ? \DEBUG_SHOW_HTTP_VARS : NULL;
	$values['DEBUG_HIDE_HEAD_METAS']['value'] = defined('DEBUG_HIDE_HEAD_METAS') ? DEBUG_HIDE_HEAD_METAS : NULL;
	$values['DEBUG_HIDE_HEAD_STYLES']['value'] = defined('DEBUG_HIDE_HEAD_STYLES') ? DEBUG_HIDE_HEAD_STYLES : NULL;
	$values['DEBUG_HIDE_HEAD_SCRIPTS']['value'] = defined('DEBUG_HIDE_HEAD_SCRIPTS') ? DEBUG_HIDE_HEAD_SCRIPTS : NULL;
	$values['PREVENT_DELETE_DB_IF_HAS_TABLES']['value'] = defined('PREVENT_DELETE_DB_IF_HAS_TABLES') ? PREVENT_DELETE_DB_IF_HAS_TABLES : NULL;
	$values['PREVENT_DELETE_TABLE_IF_HAS_ROWS']['value'] = defined('PREVENT_DELETE_TABLE_IF_HAS_ROWS') ? PREVENT_DELETE_TABLE_IF_HAS_ROWS : NULL;
	$values['PREVENT_DELETE_FORM_IF_HAS_ROWS']['value'] = defined('PREVENT_DELETE_FORM_IF_HAS_ROWS') ? PREVENT_DELETE_FORM_IF_HAS_ROWS : NULL;
	$values['PREVENT_DELETE_TABLE_IF_HAS_COLUMNS']['value'] = defined('PREVENT_DELETE_TABLE_IF_HAS_COLUMNS') ? PREVENT_DELETE_TABLE_IF_HAS_COLUMNS : NULL;
	$values['PREVENT_DELETE_FORM_IF_HAS_COLUMNS']['value'] = defined('PREVENT_DELETE_FORM_IF_HAS_COLUMNS') ? PREVENT_DELETE_FORM_IF_HAS_COLUMNS : NULL;
	$values['DB_HOST']['value'] = defined('DB_HOST') ? DB_HOST : NULL;
	$values['DB_NAME']['value'] = defined('DB_NAME') ? DB_NAME : NULL;
	$values['DB_USER']['value'] = defined('DB_USER') ? DB_USER : NULL;
	$values['DB_PASS']['value'] = defined('DB_PASS') ? DB_PASS : NULL;
	$values['DB_PORT']['value'] = defined('DB_PORT') ? \DB_PORT : NULL;
	$values['DB_TYPE']['value'] = defined('DB_TYPE') ? DB_TYPE : NULL;
	$values['DB_SOCK']['value'] = defined('DB_SOCK') ? \DB_SOCK : NULL;
	$values['DB_TIME']['value'] = defined('DB_TIME') ? DB_TIME : NULL;
	$values['DB_CHAR']['value'] = defined('DB_CHAR') ? DB_CHAR : NULL;
	$values['DB_OPTS']['value'] = isset($GLOBALS['DB_OPTS']) ? $GLOBALS['DB_OPTS'] : NULL;
} else if (isset($_POST['submit']) && !empty($_POST['submit']))
{
	unset($_POST['submit']);	// remove 'submit' from POST
	$_POST['DEBUG'] = null;	// remove 'DEBUG' from POST
	unset($_POST['DEBUG']);	// remove 'DEBUG' from POST
//		echo 'posted:<pre>'. print_r($_POST, true). '</pre>';
	foreach($values as $k => $v)	// each form input value
	{
		$dont = array('DEBUG');
		if (in_array($k, $dont))
			continue;
		if (isset($values[$k]['row_class']) && $values[$k]['type'] == 'checkbox'/*strpos($values[$k]['row_class'], 'form-switch') !== false*/)
		{
			//is a checkbox
			$values[$k]['value'] = (isset($_POST[$k]) && $_POST[$k] && $_POST[$k] !== 'off') ? true : false;
			//echo $k. ' is a checkbox: '. ($values[$k]['value'] ? 'on' : 'off');
			$store->set($k, (isset($_POST[$k]) && $_POST[$k] && $_POST[$k]) ? true : false);
		} elseif (0 && isset($values[$k]['row_class']) && $values[$k]['type'] == 'textarea' /*strpos($values[$k]['row_class'], 'textarea') !== false*/ /*&& isset($_POST[$k]) && $_POST[$k]*/)
		{
			//is a textarea
			//echo $k. ' is a textarea: '. print_r($values[$k]['value'], true). ';';
			//$val = explode(PHP_EOL, $_POST[$k]);
			$val = preg_split('/[^A-Z-0-9a-z_]/', $_POST[$k]);
			//$val = preg_split('/[\S]*/', $_POST[$k]);
			echo $k. ' is a textarea: '. urlencode($_POST[$k]). ' - '. serialize($val/*$_POST[$k]*/). ' ;  ';
			//$values[$k]['value'] = unserialize((isset($_POST[$k]) && $_POST[$k] / *&& $_POST[$k] !== 'off'* /) ? $_POST[$k] : $store->get($k));
			$values[$k]['value'] = /*isset($_POST[$k]) && empty($_POST[$k]) ?*/ serialize(trim($_POST[$k])) /*: array()*/;
		} elseif (isset($_POST[$k]) && $_POST[$k])
		{
			$values[$k]['value'] = $_POST[$k];
			$store->set($k, $_POST[$k]);
		}
/*		if (!isset($v['value']))
			echo 'setting default for '. $k. ';  ';
		else
			$store->set($k, isset($_POST[$k]) ? $_POST[$k] : $v['value']);*/
	}
//		echo 'values:<pre>'. print_r($values, true). '</pre>';
}
?>
	<!-- begin page contents -->
<!--	<div class="scroll-progress"></div>-->
	<a id="top"></a>
	<div class="container">
		<div class="panel">
<?php
/*****************************************************************************
 * logic
 */
//	enScript( Array( 'name' => 'addEvent', 'src' => BASE. 'js/addEvent.js', 'version' => filemtime('js/addEvent.js'), 'show_comment' => true ));
//	enScript( Array( 'name' => 'addClass', 'src' => BASE. 'js/addClass.js', 'version' => filemtime('js/addClass.js'), 'show_comment' => true ));
//	Scripts::getInstance()->enqueue(Array( 'name' => 'settings', 'src' => BASE. 'js/settings.js', 'version' => filemtime('js/settings.js') ));
//	Scripts::getInstance()->enqueue(Array( 'name' => 'scrolltop', 'src' => BASE. 'js/scrolltop.js', 'version' => filemtime('js/scrolltop.js') ));
//	Scripts::getInstance()->enqueue(Array( 'name' => 'validate', 'src' => BASE. 'js/validate.js', 'version' => filemtime('js/validate.js') ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'trigmodal', 'src' => BASE. 'js/trigmodal.js', 'version' => filemtime('js/trigmodal.js') ));
	//Scripts::getInstance()->enqueue(Array( 'name' => 'fields', 'src' => BASE. 'js/fields.js', 'version' => filemtime('js/fields.js') ));
	function breadscrumbsLine2()
	{
		$menu = new Menu(array(
			new Button(array(
	/*			'text' => '',
				'slug' => '',
				'class' => '',
				'indents' => 0,
				'id' => 'debug',
				'wrap' => '',
	 */
				'text' => t('_general_txt'),
				'title' => t('_general'),
				'slug' => '#general',
			)),
			new Button(Array(
				'text' => t('_notify_txt'),
				'title' => t('_notify'),
				'slug' => '#notify',
			)),
			new Button(Array(
				'text' => t('_debug_txt'),
				'title' => t('_debug'),
				'slug' => '#debug',
			)),
			new Button(Array(
				'text' => t('_showhide_txt'),
				'title' => t('_showhide'),
				'slug' => '#showhide',
			)),
			new Button(Array(
				'text' => t('_prevention_txt'),
				'title' => t('_prevention'),
				'slug' => '#prevention',
			)),
			new Button(Array(
				'text' => t('_database_txt'),
				'title' => t('_database'),
				'slug' => '#database',
			)),
		));
		$menu->vertical = false;
		$menu->classSet(['align-end', 'xinner-nav', 'breadcrumbs2', 'xbreadcrumb']);
?>
				<nav class="navbar navbar-expand-lg navbar-light bg-light">
					<div class="container-fluid">
						<nav class="breadcrumbs" aria-label="breadcrumb">
<?php
		echo $menu->HTML();
?>
						</nav>
					</div>
				</nav>
<?php
	}

	function display_form($values)
	{
?>
			<div id="pane1" class="pane">
<?php	echo Breadcrumbs::here();	?>
<?php
		breadscrumbsLine2();
?>
				<h1>
					<span class="xthis-page"><?=t('_Settings')?></span>
				</h1>
				<form method="post" id="settings" name="settings" class="needs-validation">
					<div class="container-fluid">
<!--					<div id="debug" class="mb-3 fieldset-surround">-->
					<fieldset class="mb-2">
						<legend><?=t('_general')?>
						</legend>
<!--						<div class="row g-2 mb-2 xform-row row-cols-1 row-cols-lg-2">
<?//=textField($values['ABS_PATH'])?>
						</div>-->
						<!-- ABS_PATH = <?=ABS_PATH?> -->
						<div class="row g-2 mb-3">
<?=Field::out($values['CHARSET'])?>
<?=Field::out($values['PREFIX'])?>
						</div>
<?=Field::out($values['SHOW_OTHER_DBS'])?>
<?=Field::out($values['SHOW_OG_TAGS'])?>
<?=Field::out($values['SHOW_TWITTER_TAGS'])?>
<?=Field::out($values['SHOW_ARTICLE_TAGS'])?>
<?=Field::out($values['RESERVED'])?>
						<div class="row g-2 mb-2 row-cols-2 custom-control material-switch">
<?=Field::out($values['ADD_DB'])?>
<?=Field::out($values['ADD_TBL'])?>
						</div>
						<div class="row g-2 mb-2 row-cols-2">
<?=Field::out($values['ADD_COL'])?>
<?=Field::out($values['ADD_FRM'])?>
						</div>
						<div class="row g-2 mb-2 row-cols-2">
<?=Field::out($values['ADD_ROW'])?>
						</div>
					</fieldset>

					<div id="notify" class="mb-3 fieldset-surround">
						<fieldset class="mb-2">
							<legend><?=t('_notify')?>
							</legend>
<?=Field::out($values['NOTIFY_CREATE_DB'])?>
<?=Field::out($values['NOTIFY_DELETE_DB'])?>
<?=Field::out($values['NOTIFY_CREATE_TABLE'])?>
<?=Field::out($values['NOTIFY_DELETE_TABLE'])?>
<?=Field::out($values['NOTIFY_CREATE_RECORD'])?>
<?=Field::out($values['NOTIFY_DELETE_RECORD'])?>
<?=Field::out($values['NOTIFY_CREATE_COLUMN'])?>
<?=Field::out($values['NOTIFY_DELETE_COLUMN'])?>
						</div>
					</fieldset>
				<!--	</div>-->

					<div id="debug" class="mb-3 fieldset-surround">
						<fieldset class="mb-2">
							<legend><?=t('_debug')?>
					<!--			<span>
									<a class="top" href="#top" title="<?=t('_top')?>">
						<!- -			<i class="fa-solid fa-up"></i>- ->
									</a>
								</span>-->
							</legend>
<?=Field::out/*checkboxRow*/($values['DEBUG']['HEAD_PLACEHOLDERS'])?>
<?=Field::out/*checkboxRow*/($values['DEBUG']['SHOW_REQUEST'])?>
						</fieldset>
					</div>

					<div id="showhide" class="mb-3 fieldset-surround">
						<fieldset class="mb-2">
							<legend><?=t('_showhide')?><!--<a class="top" href="#top" title="<?=t('_top')?>"></a>--></legend>
<?=Field::out/*checkboxRow*/($values['DEBUG_SHOW_REQ'])?>
<?=Field::out/*checkboxRow*/($values['DEBUG_SHOW_HEAD_COMMENTS'])?>
<?=Field::out/*checkboxRow*/($values['DEBUG_SHOW_HTTP_VARS'])?>
<?=Field::out/*checkboxRow*/($values['DEBUG_HIDE_HEAD_METAS'])?>
<?=Field::out/*checkboxRow*/($values['DEBUG_HIDE_HEAD_STYLES'])?>
<?=Field::out/*checkboxRow*/($values['DEBUG_HIDE_HEAD_SCRIPTS'])?>
						</fieldset>
					</div>

					<div id="prevention" class="mb-3 fieldset-surround">
						<fieldset class="mb-2">
							<legend><?=t('_prevention')?><!--<a class="top" href="#top" title="<?=t('_top')?>"></a>--></legend>
<?=Field::out/*checkboxRow*/($values['PREVENT_DELETE_DB_IF_HAS_TABLES'])?>
<?=Field::out/*checkboxRow*/($values['PREVENT_DELETE_TABLE_IF_HAS_ROWS'])?>
<?=Field::out/*checkboxRow*/($values['PREVENT_DELETE_FORM_IF_HAS_ROWS'])?>
<?=Field::out/*checkboxRow*/($values['PREVENT_DELETE_TABLE_IF_HAS_COLUMNS'])?>
<?=Field::out/*checkboxRow*/($values['PREVENT_DELETE_FORM_IF_HAS_COLUMNS'])?>
						</fieldset>
					</div>

					<div id="database" class="mb-3 fieldset-surround">
						<fieldset class="mb-2 xform-row">
							<legend><?=t('_database')?><!--<a class="top" href="#top" title="<?=t('_top')?>"></a>--></legend>
							<div class="row g-2 mb-2 row-cols-2">
<?=Field::out/*textField*/($values['DB_HOST'])?>
							</div>
							<div class="row mb-2 g-2 row-cols-2">
<?=Field::out/*textField*/($values['DB_NAME'])?>
							</div>
							<div class="row mb-2 g-2 row-cols-2">
<?=Field::out/*textField*/($values['DB_USER'])?>
<!--						</div>
						<div class="row mb-2 g-2 row-cols-2">-->
<?=Field::out/*textField*/($values['DB_PASS'])?>
							</div>
							<details>
								<summary><?=t('_advanced')?></summary>
								<div class="row mb-2 g-2 row-cols-2">
<?=Field::out/*numField*/($values['DB_PORT'])?>
								</div>
								<div class="row mb-2 g-2 row-cols-2">
<?php
	if (isset($db) && is_object($db))
	{
		$cname = get_class($db);	// : get_class() expects parameter 1 to be object, unknown given
	} else
	{
		$cname = 'Db';
	}
	try
	{
		$r = new ReflectionClass($cname);	// :  Uncaught ReflectionException: Class  does not exist
		$parent = $r->getFilename();
	} catch (Exception $ex)
	{
	}
//	$values['DB_TYPE']['options'] = array();
	$options = array();
	$value = (isset($_POST['DB_TYPE'])) ? $_POST['DB_TYPE'] : $values['DB_TYPE']['value'];
	foreach ( glob_recursive( ABS_PATH. "/class/Database/", "*_Driver.php") as $filename ) {
		$text = basename( str_replace('_Driver.php', '', $filename) );
		$short = strtolower( $text );
/*		if ($value === $short)
		{
			$text .= ' ('. t('_default'). ')';
		}*/
		$options[$short] = $text;
//		$options[$text] = $short;
/*?>
											<option<?php if ($value === $short) { echo ' selected'; }?> value="<?=$short?>"><?=$natural?></option>
<?php*/
	}
		$values['DB_TYPE']['options'] = $options;
?>
<?=Field::out/*selectField*/($values['DB_TYPE'])/*
									<div class="label col-lg-3 col-9">
										<label for="DB_TYPE"><?=l('_DB_TYPE')?>:</label>
<?php
	if (isset($db) && is_object($db))
	{
		$cname = get_class($db);	// : get_class() expects parameter 1 to be object, unknown given
	} else
	{
		$cname = 'Db';
	}
	try
	{
		$r = new ReflectionClass($cname);	// :  Uncaught ReflectionException: Class  does not exist
		$parent = $r->getFilename();
//		$default = strtolower($db->getId());	// $GLOBALS['data']['DB_TYPE']
	} catch (Exception $ex)
	{
	}
	$value = (isset($_POST['DB_TYPE'])) ? $_POST['DB_TYPE'] : $values['DB_TYPE']['value'];
//	$default = (isset($default) && !empty($default)) ? $default : $values['DB_TYPE']['default'];
	//echo '<!-- '. print_r($db->driver, true). ' -->';
	//echo '<!-- '. var_dump($db, true). ' -->';
?>
									</div>
									<div class="input col-lg-3 col-9">
										<select class="form-control" id="DB_TYPE" name="DB_TYPE" value="<?=$value?>">
<?php
	if (!isset($_POST['DB_TYPE'])) {
?>
											<option selected disabled><?=l('_Select')?></option>
<?php
	}
//	print '<!-- searching: '. $values['ABS_PATH']['value']. "/class/Database/". "*_Driver.php". ' -->';
	//foreach ( glob_recursive( $values['ABS_PATH']['value']. "/class/Database/", "*_Driver.php") as $filename ) {
	foreach ( glob_recursive( ABS_PATH. "/class/Database/", "*_Driver.php") as $filename ) {
		$natural = basename( str_replace('_Driver.php', '', $filename) );
		$short = strtolower( $natural );
		if ($value === $short)
		{
			$natural .= ' ('. t('_default'). ')';
		}
//		$id = '$this->id';
?>
											<option<?php if ($value === $short) { echo ' selected'; }?> value="<?=$short?>"><?=$natural?></option>
<?php
	}
?>
										</select>
									</div>*/?>
								</div>
<?=Field::out/*checkboxRow*/($values['DB_SOCK'])?>
								<div class="row mb-3 g-2 row-cols-2">
<?=Field::out/*numField*/($values['DB_TIME'])?>
<!--						</div>
						<div class="row mb-2 row-cols-2">-->
<!--							<div class="xinput_field">-->
<?=Field::out/*textField*/($values['DB_CHAR'])?>
								</div>
<?=Field::out/*textareaRow*/($values['DB_OPTS'])?>
							</details>
						</fieldset>
					</div>	<!-- #database -->
					<div class="row g-2 mb-3">
						<div class="col-1">&nbsp;</div>
<?/*=Field::out($values['RESET'])*/?>
							<div class="input col-md-3">
								<button type="submit" data-lpignore="true" class="xform-control danger symbol modal-trigger" id="RESET" name="RESET" data-bs-toggle="modal" data-bs-target="#myModal" data-title="Do you really want to do this?" data-body="Really reset all form values to their defaults" href="/BuildForm/settings"><i class='fa-solid fa-recycle fa-2x'></i>&nbsp; All settings to default</button>
							</div>
						<div class="col-md-4">&nbsp;</div>
						<div class="input col-md-3 xsubmit">
							<!--<input type="submit" id="submit" name="submit" value="<?=l('_Save changes')?>">-->
							<button type="submit" data-lpignore="true" class="xform-control symbol" id="submit" name="submit"><i class="fa-solid fa-2x fa-download"></i>&nbsp; <?=l('_Save changes')?></button>
						</div>
<!--						<div class="col-1">&nbsp;</div>-->
					</div>
<?php	/*
	$body = t('_Really reset all form values to their defaults');
	$title = t('_Do you really want to do this?');
	$href = BASE. 'settings';
? >
					<div class="row xmb-5">
						<div class="submit xmodal-trigger">
<!--							<input type="submit" id="RESET" name="RESET" class="danger" value="<?=l('_RESET')?>">-->
							<input type="submit" id="RESET" name="RESET" value="<?=l('_Reset_all')
?>" data-bs-toggle="modal" data-bs-target="#myModal" data-xname="<?=t('_RESET')
?>" data-title="<?=$title?>" data-body="<?=$body
?>" class="danger symbol modal-trigger" href="<?=$href
?>?reset" title="<?=t('_RESET')
?>">

						</div>
					</div>
					<div class="row xmb-5">
						<div class="submit">
							<input type="submit" id="submit" name="submit" value="<?=l('_Save changes')?>">
						</div>
					</div>*/?>
				</div>	<!-- .container-fluid -->
				</form>
			</div>	<!-- #pane1 -->
<?php
	}

	display_form($values);
?>
		</div>
	</div>
	<a href="#top" id="return-to-top" class="head" title="<?=t('_top')?>"><i class="fa-solid fa-caret-up" aria-hidden="true"></i></a>
