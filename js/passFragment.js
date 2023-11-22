if (typeof done_once === 'undefined') {
	do_once();
}
addEvent(window, 'click', do_once);
function do_once() {
	done_once = true;
	let url = window.location.href;
	//if (url.indexOf('#') > -1){
	const frag = window.location.hash;
	if (frag == '') return;
	if (url.indexOf('?') > -1){
		url += '&frag='+ frag
	} else {
		url += '?frag='+ frag
	}
	window.location.href = url;
}
