	/**
	 * Launch load when the document loads
	 * ----------------------------
	 */
	/*if (typeof window.addEvent === "function") {*/
		addEvent(window, 'load', load_fields);
/*	} else {
//		alert('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
		console.error('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
	}*/

/**
 * Switch field's eye icon (if present) to show or hide contents - such as a password field
 */
function toggleEye(eye)
{
	if (hasClass(eye, 'fa-eye-slash')) {
		addClass(eye, 'fa-eye');
		removeClass(eye, 'fa-eye-slash');
		addClass(eye.previousElementSibling, 'eyeShow');
	} else {
		addClass(eye, 'fa-eye-slash');
		removeClass(eye, 'fa-eye');
		removeClass(eye.previousElementSibling, 'eyeShow');
	}
	eye.previousElementSibling.focus();
	eye.previousElementSibling.select();
}

/**
 * On page load
 */
function load_fields()
{
	const focui = document.querySelectorAll("[autofocus]");		// force all autofocus fiels to become focused
	for (var i=0; i<focui.length; i++) {
		focui[i].focus();
	}
	const eye = document.querySelector("#eye");		// toggle eye icon (show.hide contents)
	if (typeof addEvent === 'function')
		addEvent(eye, 'click', function(){
			toggleEye(eye);
			//this.classList.toggle("fa-eye-slash")
	//		const type = eye.parentElement.getAttribute("type") === "password" ? "text" : "password"
	//		eye.parentElement.setAttribute("type", type)
		});
	else
		eye.addEventListener("click", function(){
			toggleEye(eye);
//			this.classList.toggle("fa-eye-slash")
//			const type = eye.parentElement.getAttribute("type") === "password" ? "text" : "password"
//			eye.parentElement.setAttribute("type", type)
		});
//	const dbname = document.querySelector('#dbname');
//	addEvent(dbname, 'input', verify);
}
/*
function verify(e) {
	for(i=0; i<takenDBNames.length; i++) {
		if (this.value === takenDBNames[i]) {
			addClass(dbname, 'error');
			break;
		} else {
			removeClass(dbname, 'error');
		}
	}
}*/
