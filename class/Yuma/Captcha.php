<?php

namespace Yuma;

class Captcha
{
	const v3_sitekey = '6LeUXf0cAAAAAOlNEXfg8jDd90dVx8QklLb0XqKX';
	const v3_secretkey = '6LeUXf0cAAAAABN8FTCn0gyL8N37vOLOxBubIanp';
	const v2_invisible_sitekey = '6Lc5mf0cAAAAAK9VKzcVuzBAqd0Cwidn1t_e8Gaf';
	const v2_invisible_secretkey = '6Lc5mf0cAAAAAJ5GCWKdaoRwqAmgNOWs4ZrGFI-x';
	const v2_checkbox_sitekey = '6LfDUf0cAAAAAGEYK77qKuzJw6zlzjot5SvbThm2';
	const v2_checkbox_secretkey = '6LfDUf0cAAAAAAJENBjFqXvz3SlCHupATJYd7--R';

	public function render($type)
	{
	}

	/**
	 * Loading reCAPTCHA
	 * grecaptcha.ready() to wrap API calls,
	 * https://developers.google.com/recaptcha/docs/loading
	 */
	public function loadAPIforv3($asAsync=false, $doPrepare=false, $args='')
	{
		$out = '<script '. (isset($asAsync) && $asAsync ? 'async' : ''). 'src="https://www.google.com/recaptcha/api.js'. $args. '"></script>';
		if ($doPrepare) {
			$out .= "\n".
				'<script>'. "\n\t".
					'if(typeof grecaptcha === "undefined") {'. "\n\t\t".
						'grecaptcha = {};'. "\n\t".
					'}'. "\n\t".
					'grecaptcha.ready = function(cb){'. "\n\t\t".
						'if(typeof grecaptcha === "undefined") {'. "\n\t\t\t".
							'const c = "___grecaptcha_cfg";'. "\n\t\t\t".
							'window[c] = window[c] || {};'. "\n\t\t\t".
							'(window[c]["fns"] = window[c]["fns"]||[]).push(cb);'. "\n\t\t".
						'} else {'. "\n\t\t\t".
							'cb();'. "\n\t\t".
						'}'. "\n\t".
					'}'. "\n".
				'</script>';
		}
		return $out;
	}

	/**
	 * Loading reCAPTCHA
	 * Use the wrapper prepared above
	 * https://developers.google.com/recaptcha/docs/loading
	 */
	public function placePrepare($sitekey)
	{
		return ''.
			'grecaptcha.ready(function(){'. "\n\t".
				'grecaptcha.render("container", {'. "\n\t\t".
					'sitekey: "'. $sitekey. '"'. "\n\t".
				'});'. "\n".
			'});';
	}

	/**
	 * Loading reCAPTCHA
	 * sites that use the v2 API may find it useful to use the onload callback; the onload callback is executed when reCAPTCHA finishes loading. The onload callback should be defined before loading the reCAPTCHA script.
	 * https://developers.google.com/recaptcha/docs/loading
	 */
	public function jsOnloadV2API()
	{
		return ''.
			'<script>'. "\n\t".
				'const onloadCallback = function() {'. "\n\t\t".
					'console.log("reCAPTCHA has loaded!");'. "\n\t\t".
					'grecaptcha.reset();'. "\n\t".
				'};'. "\n".
			'</script>'. "\n".
			'<script async src="https://www.google.com/recaptcha/api.js?onload=onloadCallback”></script>';
	}

	/**
	 * Loading reCAPTCHA
	 * Including the following resource hints in the <head> of the document will reduce the amount of time that it takes to deliver the resources used by reCAPTCHA. The preconnect resource hint instructs the browser to establish an early connection with a third-party origin.
	 * https://developers.google.com/recaptcha/docs/loading
	 */
	public function resourceHints()
	{
		return '<link rel="preconnect" href="https://www.google.com">'. "\n".
			'<link rel="preconnect" href="https://www.gstatic.com" crossorigin>';
	}

	/**
	 * reCAPTCHA v3
	 * Automatically bind the challenge to a button
	 * https://developers.google.com/recaptcha/docs/v3
	 */
	public function autoBindV3_step1_loadAPI()
	{
		return $this->loadAPIforv3();
	}

	/**
	 * reCAPTCHA v3
	 * Automatically bind the challenge to a button
	 * https://developers.google.com/recaptcha/docs/v3
	 */
	public function autoBindV3_step2_CallBack($formID, $token)
	{
		return ''.
			'<script>'. "\n\t".
				'function onSubmit('. $token. ') {'. "\n\t\t".
					'document.getElementById("'. $formID. '").submit();'. "\n\t".
				'}'. "\n".
			'</script>';
	}

	/**
	 * reCAPTCHA v3
	 * Automatically bind the challenge to a button
	 * https://developers.google.com/recaptcha/docs/v3
	 */
	public function autoBindV3_step3_buttonClass()
	{
		return 'g-recaptcha';
	}

	/**
	 * reCAPTCHA v3
	 * Automatically bind the challenge to a button
	 * https://developers.google.com/recaptcha/docs/v3
	 */
	public function autoBindV3_step3_buttonExtra($sitekey, $jsCBfunc='onSubmit', $jsAction='submit')
	{
		/*<button class="g-recaptcha" */
		return '"data-sitekey="'. $sitekey. '" data-callback="'. $jsCBfunc. '" data-action="'. $jsAction. '"';
		/*>Submit</button>*/
	}

	public function autoRenderV2CheckboxInForm($sitekey)
	{
		?><div class="g-recaptcha" data-sitekey="<?=$sitekey?>"></div><?php
	}

	/**
	 * reCAPTCHA v3
	 * Programmatically invoke the challenge
	 * https://developers.google.com/recaptcha/docs/v3
	 */
	public function invokeV3_step1($sitekey)
	{
		return $this->loadAPIforv3(false, false, '?render='. $sitekey);
	//	return '<script src="https://www.google.com/recaptcha/api.js?render=reCAPTCHA_site_key"></script>';
	}

	/**
	 * reCAPTCHA v3
	 * Programmatically invoke the challenge
	 * https://developers.google.com/recaptcha/docs/v3
	 */
	public function invokeV3_step2($sitekey, $jsAction='submit', $token='')
	{
		return ''.
			'<script>'. "\n".
			'function onClick(e) {'. "\n\t".
				'e.preventDefault();'. "\n\t".
				'grecaptcha.ready(function() {'. "\n\t\t".
					'grecaptcha.execute("'. $sitekey. '", {action: "'. $jsAction. '"}).then(function(token) {'. "\n\t\t\t".
						'// Add your logic to submit to your backend server here.'. "\n\t\t".
					'});'. "\n\t".
				'});'. "\n".
			'}'. "\n".
			'</script>';
	}

	/**
	 * reCAPTCHA v3
	 */
	public function sampleResponceV3()
	{
		return ''.
			'{'.
			'"success": true|false,			// whether this request was a valid reCAPTCHA token for your site'.
			'"score": number,				// the score for this request (0.0 - 1.0)'.
			'"action": string,				// the action name for this request (important to verify)'.
			'"challenge_ts": timestamp,		// timestamp of the challenge load (ISO format yyyy-MM-dd\'T\'HH:mm:ssZZ).'.
			'"hostname": string,			// the hostname of the site where the reCAPTCHA was solved.'.
			'"error-codes": [...]			// optional.'.
			'}';
	}

}
