<?php
//$l = new Login;
//$usr = $l->getUser();
$usr = $loggedin;
//echo 'user<pre>'. print_r($usr, true). '</pre>';
//$u = new Users;
if (!isset($usr) || !is_array($usr) /*|| !$usr instanceof Users*/) {
	echo "no valid user loggedin\n";
} else {
?>
<div class="container">
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
?>
			<div class="row">
				<div class="col-2"><b><?=$kk?></b></div>
				<div class="col-auto"><?=print_r($v, true)?></div>
			</div>
<?php
	}
?>
		</div>
	</div>
</div>
<div class="container" style="justify-content:flex-start">
	<p id="nav" data-style="clear:both">
		<!--a href="<?=getServerValue('REQUEST_URI')?>">&larr;</a-->
		<a style="font-size:2em;text-decoration:none" title="<?=t('_back')?>" href="<?=getServerValue('REDIRECT_URL')?>">&larr;</a>
	</p>
</div>
<div class="clear"></div>
<?php
}
