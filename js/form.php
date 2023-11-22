	/**
	 * Launch load when the document loads
	 * ----------------------------
	 */
	/*if (typeof window.addEvent === "function") {*/
		addEvent(window, 'load', load_form);
/*	} else {
//		alert('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
		console.error('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
	}*/

function load_form() {
	/* exact elements */
//	const ccen = document.getElementById('ccen');
//	const crename = document.getElementById('crename');
//	addEvent(ccen, 'change', toggleCrename);
//	addEvent(window, 'load', toggleCrename);
	//toggleCrename('');
//	const cdef = document.getElementById('cdef');
	//const cdef_label = document.getElementById('cdef_label');
//	const cdef2 = document.getElementById('cdef2');
//	addEvent(cdef, 'change', toggleCdef2);
	/* apply to each disabled input element */
	const tags = document.querySelectorAll('[data-tag]');
	for(i=0; i<tags.length; i++) {
		addEvent(tags[i], 'click', addTagToMain);
	}
}

function addTagToMain(e) {
	console.log(this);
	colsole.log(e.target);
	//var a = document.createElement();
}

function toggleCrename(e) {
	crename.disabled = !this.checked;
}

function toggleCdef2(e) {
	//console.log(this);
	//cdef2.disabled = !(this.value == "define");
	if ((this.value == "define")) {
		cdef2.disabled = false;
		cdef2.focus();
	} else {
		cdef2.disabled = true;
	}
	//cdef_label.htmlFor = !(this.value == "define") ? 'cdef_label' : 'cdef';
	//if (this.value == "define") {
		//alert(this);
		//console.log(this)
	//}
	//cdef2.disabled = !this.checked;
}

function toggleDisableWithCheckbox() {
	//crename.disabled = !this.checked;
	//alert(e);
	document.getElementById(this.dataset.dest).disabled = !this.checked;
}

function toggleDisableWithSelect() {
	var dest = document.getElementById(this.dataset.dest);
	if ((this.value == "define")) {
		//cdef2.disabled = false;
		dest.disabled = false;
		//cdef2.focus();
		dest.focus();
	} else {
		//cdef2.disabled = true;
		dest.disabled = true;
	}
	//alert(e);
}
