<?php

namespace Yuma;

class Cookie
{

	public function getFromClient($name)
	{
		$spt = new Scripts();
		$spt->enqueue(Array( 'name' => 'Cookie', 'src' => BASE. 'js/Cookie.js', 'version' => filemtime('js/Cookie.js') ));
		$spt->enqueue(Array( 'name' => 'MyAjax', 'src' => BASE. 'js/MyAjax.js', 'version' => filemtime('js/MyAjax.js') ));
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
	}
}
