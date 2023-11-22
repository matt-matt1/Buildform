/**
 * Adds a new record to the database-table
 */
	/**
	 * Launch load when the document loads
	 * ----------------------------
	 */
	/*if (typeof window.addEvent === "function") {*/
		addEvent(window, 'load', load_addRecord);
/*	} else {
//		alert('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
		console.error('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
	}*/

function loadRecord() {
	//duplicate_row();
	const btn = document.getElementById('add-column-btn');
	addEvent(btn, 'click', duplicate_row);
}

function duplicate_row(e) {
	//const oldRow = document.getElementsByClassName('last-row');
	if (typeof document.querySelector('.last-row') === 'undefined')
		return;
	const oldRow = document.querySelector('.last-row');
	var newRow = oldRow.cloneNode(true);
	removeClass(oldRow, 'last-row');
	oldRow.id = '';
	const oldnum = oldRow.dataset.row;
	const newnum = (parseInt(oldnum) + 1).toString();
	if (!newRow.dataset || !newRow.dataset.row)
		return;
	newRow.dataset.row = newnum;//newRow.dataset.row + 1;
	delete newRow.dataset.col;
	const all = newRow.getElementsByTagName('*');
	var j, l;
	for (j = -1, l = all.length; ++j < l;) {
		var el = all[j];
		if (el.name) {
			var matches = el.name.match(/([^\d]+)([\d]+)([^\d]*)/);
			if (matches[1])
				el.name = matches[1];
			if (matches[2])
				el.name += (parseInt(matches[2]) + 1).toString();
			if (matches[3])
				el.name += matches[3];
/*			var end = (el.name.substr(el.name.length-1) == ']') ? '[]' : '';
			var count = el.name.match(/\d+/);
			if (null !== count)
				el.name = el.name.substr(0, count.index) + (++count[0]) + end;//newnum;
			*/
		}
		if (el.id) {
			var end = (el.id.substr(el.id.length-1) == ']') ? '[]' : '';
			var count = el.id.match(/\d+/);
			if (null !== count)
				el.id = el.id.substr(0, count.index) + (++count[0]) + end;//newnum;
		}
		if (el.dataset && el.dataset.placeholder) {
			//el.dataset.placeholder = '';
			//el.removeAttribute('data-placeholder');
			delete el.dataset.placeholder;
		}
		if (el.dataset && el.dataset.row) {
			el.dataset.row = newnum;
		}
		if (el.value && el.nodeName.toUpperCase() === "SELECT") {
			el.selectedIndex = 0;
		} else if (el.value && el.nodeName.toUpperCase() !== "OPTION") {
			el.value = '';
/*			el.removeAttribute('value');
			if (el.placeholder) {
				//el.placeholder = '';
				el.removeAttribute('placeholder');
			}*/
		}
		if (el.title) {
			//el.title = '';
			el.removeAttribute('title');
		}
	}	// each child element
	oldRow.parentNode.appendChild(newRow);
	newRow.querySelector('input').focus();
	startDisabled(newRow);
	const endis = newRow.querySelectorAll('.endis');
	for (var j = -1, l = endis.length; ++j < l;) {
		//setEnDisAction(endis[j], newnum);
		addEventEnDis(endis[j].parentElement, newnum);
	}
	e.preventDefault();
}
