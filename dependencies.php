<?php
namespace Yuma;
use Yuma\HTML\Link;
use Yuma\HTML\Scripts;
//include "php-browser-detection/src/BrowserDetection.php";
	Link::style(Array(
		'name' => 'fontawesome@6.2.1',
		'href' => "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css",
		'version' => getlastmod(),//date(),//filemtime('js/Element.js'),
		//'show_comment' => true,
		//'rel' => "stylesheet",
		)
	);
/*	Link::style(Array(
		'name' => 'fontawesome@4.7.0',
		'href' => "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css",
		'version' => getlastmod(),//date(),//filemtime('js/Element.js'),
		//'show_comment' => true,
		//'rel' => "stylesheet",
		)
	);*/
/*	Link::style(Array(
		//'name' => 'bootstrap@5.0.1',
		//'href' => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css",
		//'integrity' => "sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x",
		'name' => 'bootstrap@5.0.2',
		'href' => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css",
		'version' => getlastmod(),//date(),//filemtime('js/Element.js'),
		//'show_comment' => true,
		//'rel' => "stylesheet",
		'integrity' => "sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC",
		'crossorigin' => "anonymous" )
	);*/
	Link::style(Array(
		'name' => 'bootstrap@5.1.3',
		'href' => "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css",
		'version' => getlastmod(),//date(),//filemtime('js/Element.js'),
		//'rel' => "stylesheet",
		'integrity' => "sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3",
		'crossorigin' => "anonymous" )
	);
	Link::style(Array(
		'name' => 'simple-datatables@latest',
		'href' => "https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css",
		'version' => getlastmod(),//date(),//filemtime('js/Element.js'),
		//'rel' => "stylesheet",
		'crossorigin' => "anonymous" )
	);
	$jsVars = Array(
		'BASE' => BASE,
		'CHARSET' => CHARSET,
		//'WASajaxURL' => $url. 'getAjax.php',
		'ajaxURL' => BASE. 'getAjax.php',
		'nonce' => getServerValue('UNIQUE_ID'),
		'requestToken' => $_SESSION['requestToken'],
		'LANG' => Array(
			'multiselectBtnPh' => t('_multiselectBtnPh'),
			'selectAll' => t('_Select All')
		),
		'reCaptcha' => Array(
			'v3' => Captcha::v3_sitekey,
			'v2_invisible' => Captcha::v2_invisible_sitekey,
			'v2_checkbox' => Captcha::v2_checkbox_sitekey,
		),
	);
	$scr = new Scripts;
	$scr->enqueueInline(Array( 'name' => 'globaljs', 'code' => 'var jsVar = '. json_encode($jsVars), 'nonce' => getServerValue('UNIQUE_ID'), 'put_in_header' => true, 'show_comment' => false ));
/*	Scripts::getInstance()->enqueue(Array(
		'name' => 'compatible',
		'src' => BASE. "js/compatible.js",
		'version' => filemtime('js/compatible.js'),
		'crossorigin' => "anonymous", 'put_in_header' => true )
	);*/
	$scr->enqueue(Array(
		'name' => 'simple-datatables@latest',
		'src' => "https://cdn.jsdelivr.net/npm/simple-datatables@latest",
		'version' => getlastmod(),
		//'crossorigin' => "anonymous",
		'put_in_header' => true )
	);
	$scr->enqueue(Array(
		'name' => 'ddr-ecma5-1.2.1-min',
		'src' => BASE. "js/ddr-ecma5-1.2.1-min.js",
		'version' => filemtime('js/ddr-ecma5-1.2.1-min.js'),
		'crossorigin' => "anonymous", 'put_in_header' => true )
	);
/*	Scripts::getInstance()->enqueue(Array(
		'name' => 'dynamicListener.min',
		'src' => BASE. "js/dynamicListener.min.js",
		'version' => filemtime('js/dynamicListener.min.js'),
		'crossorigin' => "anonymous", 'put_in_header' => true )
	);*/
	$scr->enqueue(Array(
		'name' => 'smoothscroll.min',
		'src' => BASE. "js/smoothscroll.min.js",
		'version' => filemtime('js/smoothscroll.min.js'),
		'crossorigin' => "anonymous", 'put_in_header' => true )
	);
	$scr->enqueue(Array(
		//'name' => 'bootstrap@5.0.1',
		//'src' => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js",
		//'integrity' => "sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT",
		'name' => 'bootstrap@5.0.2',
		'src' => "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js",
		'version' => getlastmod(),//date(),//filemtime('js/Element.js'),
		//'integrity' => "sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM",
		'crossorigin' => "anonymous", 'put_in_header' => true )
	);
	$scr->enqueue(Array( 'name' => 'addEvent', 'src' => BASE. 'js/addEvent.js', 'version' => 'file', /*'put_in_header' => true,*/ 'show_comment' => false ));
	$scr->enqueue(Array( 'name' => 'addClass', 'src' => BASE. 'js/addClass.js', 'version' => 'file', /*'put_in_header' => true,*/ 'show_comment' => false ));
	$scr->enqueue(Array( 'name' => 'scroll-progress', 'src' => BASE. 'js/scroll-progress.js', 'version' => filemtime('js/scroll-progress.js') ));
	$scr->enqueue(Array( 'name' => 'common', 'src' => BASE. 'js/common.js', 'version' => filemtime('js/common.js') ));
	$scr->enqueue(Array( 'name' => 'invalid', 'src' => BASE. 'js/invalid.js', 'version' => filemtime('js/invalid.js') ));
	$scr->enqueue(Array( 'name' => 'fields', 'src' => BASE. 'js/fields.js', 'version' => filemtime('js/fields.js') ));
/*?>
	<div class="scroll-progress"></div>*/
