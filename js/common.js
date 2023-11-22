/******
 * Common client-side function
 */

const $ = function(selector, base = document)
{
	let elements = base.querySelectorAll(selector);
	return (elements.length === 1) ? elements[0] : Array.prototype.slice.call(elements, 0);
	//return Array.prototype.slice.call(elements, 0);
}
/**
 * Loads an external file to include
 */
function includeHTML() {
/*	var el, elmnt, file, xhttp;*/
	var el = document.getElementById("include");
	if (!el)
		return;
/*	for (var i = 0; i < z.length; i++) {
	elmnt = z[i];
	file = elmnt.getAttribute("w3-include-html");*/
	var file
	if (el.dataset && el.dataset.include)
		file = jsVar.BASE + 'modals/' + el.dataset.include + '.html';
	else
		file = jsVar.BASE + 'modals/modalIn.html';
	if (file) {
		let xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4) {
				if (this.status == 200) {el.innerHTML = this.responseText;}
				if (this.status == 404) {el.innerHTML = "Page not found.";}
				if (el.dataset && el.dataset.include)
					delete el.dataset.include;
//				includeHTML();
			}
		}
		xhttp.open("GET", file, true);
		xhttp.send();
		//return;
	}
/*	}*/
}

/**
 * Convert a comma-separated string into object of key-value pairs
 */
function stringToObject( str )
{
	var obj = {};
	const pairs = str.split(',');
	for (var i=0; i<pairs.length; i++) {
		const values = pairs[i].split(':');
		obj[values[0]] = values[1];
	}
	return obj;
}

/**
 * Returns the parent element that is of nodeType type, if found
 */
function parentOfType(el, node_name, limit)
{
	if (!limit)
		limit = 1000;
	//while (el.nodeName.toUpperCase() != node_name.toUpperCase(), i++ < limit) {
	while (el.nodeName.toUpperCase() != node_name.toUpperCase() && i++ < limit) {
		el = el.parentElement;
	}
	return (i < limit) ? el : true;
}

/**
 * Cycles through each parent element until the findNode nodeName is found
 * Then returns that. Max 255 levels (parent elements)
 */
HTMLElement.prototype.parentAs = function (findNode, limit)
{
	if (!limit)
		limit = 256;
	var i=0;
	var el = this;
	while (el && i++ < limit && el.nodeName.toUpperCase() != findNode.toUpperCase()) {
		el = el.parentElement;
	}
	return el;
}

/**
 * Cycles through each parent element until the parent with the given className is found
 * Then returns that. Max 255 levels (parent elements)
 */
HTMLElement.prototype.parentsWithClass = function (className)
{
	var i=0;
	var el = this;
	var found = Array();
	for (var i=0; i<256; i++) {
		if (null === el)
			break;
		if (hasClass(el, className))
			found.push(el);
		el = el.parentElement;
	}
	return found;
}

/**
 * Scroll the window to have the element (selector) in the view-port
 */
function scrollIntoWindow(selector, offset = 0) {
	try {
		//window.scroll(0, document.querySelector(selector).offsetTop - offset);
		window.scroll({
			top: ($(selector).offsetTop - offset) || 0,
			behavior: 'smooth'
		});
	} catch(err) {
		selector.scrollIntoView({behavior: 'smooth'});
	}
}

/**
 * Retrieves a list from the database via AJAX
 */
//var businesses = [];
function dbQuery(sql, onSuccess, onError, log)
{
	var myajax = new MyAjax(jsVar.ajaxURL, {
		method: 'POST',
		onSuccess: function(res, xhr){
			onSuccess(res, xhr);
		},
		onError: function(xhr, error){
			onError(xhr, error);
		},
		data: 'action=dbqry&qry=' + sql,
		log: (log),
	});
	myajax.makeRequest();
}

addEvent(window, 'load', load_common);		// do after the window loads
function load_common() {		// only do these operation if javascript is functional
	//const hides = document.querySelectorAll('.js-hide');		// hides all elements with this class
	const hides = $('.js-hide');		// hides all elements with this class
	for (var i=0; i<hides.length; i++) {
		addClass(hides[i], 'hide');
	}
	//const ens = document.querySelectorAll('.js-enable');		// enables all elements with this class
	const ens = $('.js-enable');		// enables all elements with this class
	for (var i=0; i<ens.length; i++) {
		ens[i].disabled = false;
	}
	document.body.className = document.body.className.replace('no-js','js');
/*	var pws = $('.password-input');		// sets all password inputs as type password
	if (typeof pws !== 'undefined' && typeof pws.length === 'undefined') {
		pws = [pws];
	}
	for (var i=0; i<pws.length; i++) {
		pws[i].type = 'password';
	}*/
}
