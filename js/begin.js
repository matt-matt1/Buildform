	/**
	 * Launch load when the document loads
	 * ----------------------------
	 */
	/*if (typeof window.addEvent === "function") {*/
		addEvent(window, 'load', load);
/*	} else {
//		alert('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
		console.error('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
	}*/

function load() {
	const dbname = document.querySelector('#dbname');
	addEvent(dbname, 'input', verify);
}

function verify(e) {
	for(i=0; i<takenDBNames.length; i++) {
		if (this.value === takenDBNames[i]) {
			addClass(dbname, 'error');
			break;
		} else {
			removeClass(dbname, 'error');
		}
	}
}
