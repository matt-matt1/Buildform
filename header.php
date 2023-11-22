<?php
namespace Yuma;
use Yuma\HTML\Doc;
use Yuma\HTML\Link;
use Yuma\HTML\Meta;
use Yuma\HTML\Breadcrumbs;
	defined('ABS_PATH') || (header("HTTP/1.1 403 Forbidden") & die('403.14 - Directory listing denied.'));
	header('X-XSS-Protection "1; mode=block"');
	$lng = new Lang();
//	$lng->getClientLanguage();
//	$lang = $lng->getCode();
	$doc = new Doc();
	//if ($doc->inHTML('lang="'. $lang. '"')) {
	if ($doc->inHTML('lang="'. $lng->getLanguage(). '"')) {
	try {
		$logger = new Logger;
		$logger->log('Doc::inHTML - could not set opening html tag');
	} catch (Exception $e) {
		error_log ('Doc::inHTML - could not set opening html tag');
	}
/*		if (is_callable(array('Error', 'log'))) {
			Error::log( "Doc::inHTML - could not set opening html tag\n" );
		} else {
			error_log( "Doc::inHTML - could not set opening html tag\n" );
		}*/
	}
	//Link::style( "home", "css/home.css", filemtime("css/home.css") );
	$meta = new Meta();
	if (defined('CHARSET')) {
	//if (!empty($GLOBALS['data']['CHARSET'])) {
		$meta->add('charutf8',	'charset="'. CHARSET. '"');
		//Meta::add('charutf8',	'charset="'. $GLOBALS['data']['CHARSET']. '"');
		$meta->add('text',		'http-equiv',	"content-type",		"text/html; charset=". CHARSET);
		//Meta::add('text',		'http-equiv',	"content-type",		"text/html; charset=". $GLOBALS['data']['CHARSET']);
	} else {
		$meta->add('charutf8',	'charset="utf-8"');
		$meta->add('text',		'http-equiv',	"content-type",		"text/html; charset=utf-8");
	}
	$meta->add('edge',		'http-equiv',	"X-UA-Compatible",	"ie=edge");
	//Meta::getInstance()->add('initview1',	'name',			'viewport',			"initial-scale=1.0");
	$meta->add('initview1',	'name',			'viewport',			"width=device-width, initial-scale=1.0");
	//Meta::add('viewport',	'name',			"viewport",			"width=device-width, initial-scale=1");
if (function_exists('getServerValue')) {
    $url = htmlentities(getServerValue('REQUEST_SCHEME'));
} else {
    $url = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : '';
}
	if (empty($url)) {
		$https = (function_exists('getServerValue') ? htmlentities(getServerValue('HTTPS')) : isset($_SERVER['HTTPS'])) ? $_SERVER['HTTPS'] : '';
		$url = 'http'. (null !== $https && $https && strtoupper($https) != 'OFF' ? 's' : '');
	}
	define('URL_SCHEME', $url);
	$url .= '://'. getServerValue('HTTP_HOST');
	define('ABS_URL', $url);
	define('BASEURL', $url. BASE);
	//$GLOBALS['data']['BASEURL'] = $url;
	foreach ( glob_recursive( __DIR__. "/css/", "*.css") as $filename ) {
		//echo "{$filename}\n";
		//Link::getInstance()->styleFromFilename( $filename );
		Link::styleFromFilename( $filename );
	}
	//Breadcrumbs::setWrap('ul class="breadcrumbs breadcrumbs-triangle xcustom-icons" itemscope itemtype="https://schema.org/BreadcrumbList"');
	//Breadcrumbs::setWrap('ul class="breadcrumbs breadcrumbs-triangle" itemscope itemtype="https://schema.org/BreadcrumbList"');
	//Breadcrumbs::setWrap('ol class="breadcrumbs breadcrumbs--one" itemscope itemtype="https://schema.org/BreadcrumbList"');
	//Breadcrumbs::setWrap('ol class="breadcrumbs breadcrumb-arrow" itemscope itemtype="https://schema.org/BreadcrumbList"');
	//Breadcrumbs::setWrap('ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList"');
	Breadcrumbs::setWrap('ol class="breadcrumbs breadcrumb wizard" itemscope itemtype="https://schema.org/BreadcrumbList"');
	//Breadcrumbs::setWrap('ol class="breadcrumb breadcrumb-custom xbreadcrumbs xbreadcrumbs-triangle xcustom-icons" itemscope itemtype="https://schema.org/BreadcrumbList"');
	//Breadcrumbs::setWrap('ol class="breadcrumb breadcrumb-custom" itemscope itemtype="https://schema.org/BreadcrumbList"');
	//Breadcrumbs::setWrap('ol class="breadcrumb breadcrumb-arrow" itemscope itemtype="https://schema.org/BreadcrumbList"');
	//Breadcrumbs::setWrap('ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList"');
//	Breadcrumbs::setWrap('nav class="xbreadcrumbs ribbon ribbon--mlpha" role="navigation" aria-label="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList"');
/*	foreach ( Styles::getInstance()->getAll() as $s ) {
		echo $s;
	}*/
/*?>
	<script>*/
/*
	url: 'saveForm.php',
	url: 'getAjax.php?action=getwh',
	data: JSON.stringify({"action": "test", "json": {"width": dims.width, "height": dims.height}}),
	headers: [{"Content-Type": "aapplication/json; charset={$charset}"}],
	responseHeaders: function(headers) {
		if (headers)
		{
			console.log('response headers:');
			try {
				for (const key in headers)
					if (headers.hasOwnProperty(key))
					{
						var str = key;
						if (headers[key])
							str += ' -> \t\t\t'+ headers[key];
						console.log(str);
					}
			} catch (err) {
				const vals = Object.values(headers);
				for (i=0; i<vals.length; i++)
					if (headers.hasOwnProperty(key))
					{
						var str = key;
						if (headers[key])
							str += ' -> \t\t\t'+ headers[key];
						console.log(str);
					}
			}
			console.log('----');
//			const arr = headers.entries();
//			for (i=0; i<arr.length; i++)
//				console.log(arr[i]);
			//console.log(headers);
		}
	},
	onTimeout: function(event, request) {
		console.log('xhr timed-out (after got '+ event.loaded+ 'bytes)');
	},
	beforeSend: function(event, request) {
		console.log('data sending is imenient...');
	},
	afterSend: function(event, request) {
		console.log('...data has been sent');
	},
	timeout: 2,
	responseAs: 'json',
	username: 'me',
	password: '',
		forceMIME: 'application/json',

	Async: false,
	log: true
	onSuccess: function(response, rqst) {
//		console.log('transfered. Response: ('+ typeof response+ ')');
		console.log(instanceof response);//XMLHttpRequestProgressEvent
//		if (response && response.length > 0)
//			console.log(response);
	},
 */
	//$charset = $GLOBALS['data']['CHARSET'];
	$charset = CHARSET;
	$getWHo = <<<EOSo
var serializeObject = function (obj)
{
	var serialized = [];
	for (var key in obj) {
		if (obj.hasOwnProperty(key)) {
			serialized.push(encodeURIComponent(key) + '=' + encodeURIComponent(obj[key]));
		}
	}
	return serialized.join('&');
};
var dims = Elem.getWindowDims();
var ajax = new MyAjax("getAjax.php", {
	method: 'POST',
/*	url: 'getAjax.php',*/
	data: 'action=test&width='+ dims.width+ '&height='+ dims.height,
	onSuccess: function(response, rqst) {
		console.log('transfered. Response: ('+ typeof response+ ')');
		if (response && response.length > 0)
			console.log(response);
	},
	onError: function(request) {
		console.error('Ajax returned an error ('+ request.status+ ') '+ request.statusText);
	},

		onProgress: function(progress, request) {
console.log('progress: '+ progress.loaded);
},
		onAbort: function(event, request) {
console.log('request was aborted');
},
});
ajax.makeRequest();
EOSo;
/*	$getWH = <<<EOS
var dims = Elem.getWindowDims();
var ajax = new MyAjax({
	method: 'POST',
	url: 'getAjax.php',
	data: {width: dims.width, height: dims.height},
	log: true
});
ajax.onError();
ajax.onSuccess(function(response, rqst) {
	//console.log('transfered. Response:');
	console.log('transfered. Response: ('+ typeof response+ ')');
	//console.log(typeof response);
	console.log(instanceof response);//XMLHttpRequestProgressEvent
	console.log(response);
});
ajax.makeRequest();
EOS;*/
	/*
	//Scripts::getInstance()->enqueueInline(Array( 'name' => 'getWH', 'code' => $getWH, 'put_in_header' => true, 'show_comment' => false ));
	Scripts::getInstance()->enqueueInline(Array( 'name' => 'getWHo', 'code' => $getWHo, 'put_in_header' => true, 'show_comment' => false ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'Element', 'src' => BASE. 'js/Element.js', 'version' => filemtime('js/Element.js'), 'put_in_header' => true ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'MyAjax', 'src' => BASE. 'js/MyAjax.js', 'version' => filemtime('js/MyAjax.js'), 'put_in_header' => true ));
	Scripts::getInstance()->enqueue(Array( 'name' => 'addEvent', 'src' => BASE. 'js/addEvent.js', 'version' => filemtime('js/addEvent.js'), 'put_in_header' => true ));
	//	Scripts::getInstance()->enqueue(Array( 'name' => 'ajax_simple2', 'src' => BASE. 'js/ajax_simple2.js', 'version' => filemtime('js/ajax_simple2.js') ));*/
//	Scripts::getInstance()->enqueueInline(Array( 'name' => 'globaljs', 'code' => 'var jsVar = {BASE: "'. BASE. '", CHARSET: "'. $GLOBALS['data']['CHARSET']. '", ajaxURL: "'. $url. 'getAjax.php", nonce: "'. getServerValue('UNIQUE_ID'). '"}', 'put_in_header' => true, 'show_comment' => false ));
/*</script>
<?php*/
	require_once('dependencies.php');
