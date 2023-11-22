<?php

namespace Yuma;
use Yuma\HTML\Field;
	$l = new LoginUser;
	$usr = get_object_vars($l->getUser());
//	echo 'user<pre>'. print_r($usr, true). '</pre>';
	if (!isset($usr) || !is_array($usr)) {
		echo "no valid user logged in\n";
	} else {
?><div class="container">
	<div class="panel">
		<div class="pane">
<?php
		foreach ($usr as $k1 => $v) {					// from https://stackoverflow.com/questions/27075698/converting-php-string-to-title-case
			$k = str_replace(array('_'), ' ', $k1);		// ^
			$kk = '';									// ^
			$pattern = '#([;,-./ X])#';					// ^
			$array = preg_split($pattern, $k, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);	// ^
			foreach($array as $key => $val)				// ^
				$kk .= ucwords(strtolower($val));		// ONLY THIS IS REQUIRED : USE $k INSTEAD OF $val
			$kk = str_replace(array('_'), ' ', $kk);	// ^
?>			<div class="row">
				<div class="col-2"><b><?=$kk?></b></div>
				<div class="col-auto"><?=print_r($v, true)?></div>
			</div>
<?php
		}
	echo Field::out(array(
			'id' => 'remember',
			'type' => 'select',
			'noLabel' => true,
//			'label' => 'Remember',
			'optional' => true,
			'showOptional' => false,
			'options' => array(
				0 => t('_no_cookie'),
				1 => t('_1hr'),
		/*		2 => sprintf( t('_%d_hrs'), 2),
				3 => sprintf( t('_%d_hrs'), 3),
				4 => sprintf( t('_%d_hrs'), 4),
				5 => sprintf( t('_%d_hrs'), 5),
				10 => sprintf( t('_%d_hrs'), 10),
				24 => sprintf( t('_%d_hrs'), 24),
				48 => sprintf( t('_%d_hrs'), 48),*/
				24 => t('_1day'),
				7*24 => t('_1wk'),
				30*24 => sprintf( t('_%d_dys'), 30),
				365*24 => t('_1yr'),
			),
			'placeholder' => t('_remember'),
			'default_' => t('_remember_hint'),
			'defaultExtra' => 'style="margin:0 1em;font-size:14px"',
			'inputWrapClass' => 'input col-6 xcol-md-2 xcol-sm-2 xcol-xs-1',
			'value' => isset($_POST['remember']) ? htmlspecialchars($_POST['remember']) : '',
	));
?>		</div>
	</div>
</div>
<div class="container noflex">
	<p data-id="nav">
		<a class="double-size" title="<?=t('_back')?>" href="<?=getServerValue('REDIRECT_URL')?>">&larr;</a>
	</p>
</div>
<div class="clear"></div>
<?php
	}
	// TODO: re-remember me on this device
			//setrawcookie('LoginToken', encode_cookie_value($raw), time() + 3600 * $length, '/');	// htmlspecialchars

