if (typeof grecaptcha === 'undefined') {
	grecaptcha = {};
}
grecaptcha.ready = function(cb){
	if (typeof grecaptcha === 'undefined') {
		const c = '___grecaptcha_cfg';
		window[c] = window[c] || {};
		(window[c]['fns'] = window[c]['fns']||[]).push(cb);
	} else {
		cb();
	}
}
/*
// Usage
grecaptcha.ready(function(){
	//if (typeof grecaptcha !== 'undefined' && typeof grecaptcha.render !== 'undefined') {
		grecaptcha.render("container", {
			sitekey: jsVar.reCaptcha.v3_sitekey
		});
	//}
});
*/
