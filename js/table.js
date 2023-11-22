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

/**
 * Highlights/unhighlights the table row to be deleted
 */
function onHoverDeleteRow()
{
	const delrow = document.querySelectorAll('a.symbol.delrow');
	for(i=0; i<delrow.length; i++)
	{
		const drow = delrow[i];
		addEvent(drow, 'mouseover', function(){
/*			const row = this.parentElement.parentElement;
			addClass(row, 'table-danger')*/
			addClass(this.parentElement.parentElement, 'table-danger')
		});
		addEvent(drow, 'mouseout', function(){
/*			const row = this.parentElement.parentElement;
			removeClass(row, 'table-danger')*/
			removeClass(this.parentElement.parentElement, 'table-danger')
		});
	}
}

function load() {
	onHoverDeleteRow();
}
