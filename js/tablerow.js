	/**
	 * Launch load when the document loads
	 * ----------------------------
	 */
	/*if (typeof window.addEvent === "function") {*/
		addEvent(window, 'load', load_tablerow);
/*	} else {
//		alert('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
		console.error('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
	}*/

/**
 * Highlights/unhighlights the table row to be deleted
 */
function onClickSelectRowCheckbox()
{
	const rows = $('tr.checkbox');
	for(i=0; i<rows.length; i++)
	{
		addEvent(rows[i], 'click', function(event){
			if (event.target.type !== 'checkbox') {
				$('input[type="checkbox"]', this).checked = !($('input[type="checkbox"]', this).checked)
			}
/*			addClass(this.parentElement.parentElement, 'table-danger')*/
		});
	}
}

function checkAllRows()
{
	var rows = $('.check-all');
	if (typeof rows.length === 'undefined') {
		rows = [rows]
	}
	for(i=0; i<rows.length; i++) {
		addEvent(rows[i], 'click', function(e){
			//e = e || window.event;
			//var target = e.target || e.srcElement;
			var checks = $('.tbody td .checkbox', parentOfType(this, 'table'))
			for(i=0; i<checks.length; i++) {
				checks[i].checked = !(checks[i].checked)
			}
		});
	}
}

function fixPaginationBarHeight()
{
	var pag = $('.pagination.sticky');
	for(i=0; i<pag.length; i++) {
		try {
			var ta = pag[i].previousElementSibling;
			ta.style.bottom = pag[i].getBoundingClientRect().height + 'px';
		} catch (e) {
		}
	}
}

function submitButtonOnclick()
{
	const submits = $('button[type="submit"]');
	for(i=0; i<submits.length; i++) {
		addEvent(submits[i], 'click', function(e){
			clickedSubmitButton(e);
		});
	}
}

function clickedSubmitButton(e)
{
	e = e || window.event;
	var target = e.target || e.srcElement;

//	var url = window.location.href;

	const urlParams = new URLSearchParams(window.location.search);
	var base = document;
	if (typeof target.form !== "undefined") {
		base = target.form;
	}
	var names = $('[name][value]', base);
	if (typeof names === "undefined") {
		return;
	}
	if (typeof names.length === "undefined") {
		names = [names];
	}
	for (var i=0; i<names.length; i++) {
		if (typeof names[i].name !== "undefined" && typeof names[i].value !== "undefined") {
			urlParams.set(names[i].name, names[i].value);
		}
	}
	alert (window.location.href + ' : ' + urlParams);
	//urlParams.set('order', 'date');
	window.location.search = urlParams;
	//window.location.href = url;
}

function load_tablerow()
{
	addClass($('#businesses').parentsWithClass('tab-pane')[0], 'show');
/*
//	onClickSelectRowCheckbox();
	checkAllRows();
	fixPaginationBarHeight();
//	submitButtonOnclick();
*/
}
