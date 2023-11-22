function onloadCallback()
{
	console.log("reCAPTCHA has loaded!");
	grecaptcha.reset();
}
/* for reCAPTCHA v2:
<script async src="https://www.google.com/recaptcha/api.js?onload=onloadCallback”></script>
-- OR --
<script async src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit” defer></script>
*/
/*
// Usage
grecaptcha.ready(function(){
	if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.render !== 'undefined') {
		grecaptcha.render("container", {
			sitekey: "ABC-123"
		});
	}
});
*/
