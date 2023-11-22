/******
 * Handle bootstrap modal events
 */
const modal = {};
addEvent(window, 'load', load_trigmodal);

/**
 * Dispatch a function (modalAction_<button value>), if exists,
 * when a modal button is pressed
 */
function clickModalBtn(event)
{
	event = event || window.event;
	const target = event.target || event.srcTarget;
	if (!event || !event.target || !event.target.value)
		return;
	if (typeof window['modalAction_'+ event.target.value] === "function")
		window['modalAction_'+ event.target.value](event.target);
	var btnum = 0;
	if (event.target.parentNode)
		btnum = event.target.parentNode.querySelectorAll('button:not(.temphide)').length;
	if (btnum === 2 && event.target.value != 'cancel' && typeof window['modalAction_NotCancel'] === "function")
		window['modalAction_NotCancel'](event.target);
	if (btnum === 2 && event.target.value != 'cancel' && typeof window['modalAction_NotCancel'] !== "function" && event.target.dataset && event.target.dataset.url)
		window.location.href = event.target.dataset.url;
}

/**
 * Perform after modal is closed
 */
function closeModal(event)
{
//	var myModalEl = document.getElementById('myModal')
	if (modal.element.getAttribute('aria-hidden').toLowerCase() !== "true")
		return;
	if (!modal.footer)
		modal.footer = modal.element.querySelector('.modal-footer');
	const btns = modal.footer.children;
	for (var i=0; i<btns.length; i++) {
		var cls = btns[i].className.split(' ');
		for (var j=0; j<cls.length; j++) {
			if (cls[j] === 'tempbtn')
				btns[i].remove();
				//btns[i].style.display = 'none';
		}
		if (typeof btns[i] !== "undefined" && typeof btns[i].style !== "undefined" && /*typeof btns[i].style.display !== "undefined" &&*/ btns[i].style.display === 'none' && hasClass(btns[i], 'temphide')) {
			btns[i].style.display = "";
			removeClass(btns[i], 'temphide');
		}
	}
}

/**
 * Sets the modal insides before it's shown
 */
function triggerModal(param)//{mytitle,mybody,url,btns})
{
	const title = document.getElementById('myModalLabel');//class = modal-title');	// modal title
	//document.body.style.overflow = "hidden";	// freeze scrolling
	if (typeof title !== "undefined" && title)	// if a valid modal title
	{
		//const modal = title.parentElement.parentElement
//		const modal = document.getElementById('myModal');
		const dialog = modal.element.querySelector(".modal-dialog");
		const content = dialog.querySelector(".modal-content");
		const header = content.querySelector(".modal-header");
//		const title = header.querySelector(".modal-title");
		const body = content.querySelector(".modal-body");
		if (!modal.footer)
			modal.footer = modal.element.querySelector('.modal-footer');
		var targetTitle = title;
		if (typeof title.parentElement.nextElementSibling.querySelector('span') !== "undefined")
			targetTitle = title.querySelector('span');	// get the title span element
		if (targetTitle)
		{
			targetTitle.innerHTML = param.title;	// set the title
			if (title && title.parentElement && title.parentElement.nextElementSibling /*&& title.parentElement.nextElementSibling.querySelector('span')*/)
			{
				var targetBody = modal.element.querySelector(".modal-body");//title.parentElement.nextElementSibling;
				//if (typeof title.parentElement.nextElementSibling.querySelector('span') !== "undefined")
				//if (title.parentElement.nextElementSibling.querySelector('span'))
				//	targetBody = title.parentElement.nextElementSibling.querySelector('span').innerText = param.body;
				if (content.querySelector('.modal-body > span'))
					targetBody = content.querySelector('.modal-body > span');
				if (targetBody)
					targetBody.innerHTML = param.body;	// set the message body
			}
		}
		var proceed = title.parentElement.parentElement.querySelector('.modal-footer [value="proceed"]');
		if (!proceed)
			proceed = modal.footer.querySelector('.btn-primary');
		if (!proceed)
			proceed = modal.footer.querySelector('button:not(.btn-secondary)');
		if (proceed && param.url)
			proceed.dataset.url = param.url;
		if (param.btn) {
			if (param.btn.del) {
				var i;
				for (i = 0; i<param.btn.del.length; i++) {
					const parts = param.btn.del[i].split(':');
					for (var j=0; j<modal.footer.children.length; j++)
						for (var k=0; k<modal.footer.children[j].attributes.length; k++)
							if (parts[0] === modal.footer.children[j].attributes[k].name && parts[1] === modal.footer.children[j].attributes[k].value) {
								modal.footer.children[j].style.display = 'none';
								addClass(modal.footer.children[j], 'temphide');
								break;
							}
				}
			}
			if (param.btn.add) {
				for (var i=0; i<param.btn.add.length; i++) {
					obj = stringToObject(param.btn.add[i]);
					var btn = document.createElement((obj.node) ? obj.node.toUpperCase() : 'BUTTON');
					addClass(btn, 'tempbtn');
					for (const key in obj) {
						if (obj.hasOwnProperty(key)) {
							if (key == 'class')
								btn.className += ' '+ obj['class'];
							else if (key === 'innerText')
								btn.innerText = obj[key];
							else if (key !== 'node' && key !== 'position')
								btn.setAttribute(key, obj[key]);
						}
					}
//					if (typeof window['modalStarted_'+ btn.value] === "function")
//						window['modalStarted_'+ btn.value](modal);
					if (obj.position === 'start')
						modal.footer.insertChild(btn, modal.footer.fistChild);
					else
						modal.footer.appendChild(btn);
				}
//				if (typeof window['modalShown'] === "function")
//					window['modalShown'](modal.element);
			}
		}
		if (param.dialog) {
			for (var i=0; i<param.dialog.length; i++) {
				addClass(dialog, param.dialog[i]);
			}
		} else
			dialog.className = "modal-dialog";
		if (param.style) {
			for (var i=0; i<param.style.length; i++) {
				obj = stringToObject(param.style[i]);
				for (const key in obj) {
					if (obj.hasOwnProperty(key)) {
						dialog.style[key] = obj[key];
					}
				}
			}
		}
	}
}
/*function doAction(url)
{
	console.log('doing modal action: '+ url);
}*/

/**
 * Call on every document click event
 */
function allClicks(e)
{
	e = e || window.event;
	if (!e.target || !e.target.parentElement)	// only react if target has a parent
		return;
	const target = e.target || e.srcTarget;
	const parent = e.target.parentElement;
	var modalO = { title: parent.dataset.title, body: parent.dataset.body, url: parent.href }
	if (hasClass(parent, 'modal-trigger') && parent.dataset)	// parent must have 'modal-trigger' class
	{
		if (parent.dataset.ajaxUrl) {
			var opts = {};
			if (parent.dataset.ajaxHeaders) {
				var heads = parent.dataset.ajaxHeaders.split(',');
				var headers = [];
				for (var i=0; i<heads.length; i++) {
					var header = {};
					const parts = heads[i].split(':');
					const key = parts[0];
					header[key] = parts[1];
					headers.push(header);
				}
				opts.headers = headers;
			}
			if (parent.dataset.ajaxMethod)
				opts.method = parent.dataset.ajaxMethod;
			opts.onSuccess = function(res) {
				const body = modal.element.querySelector('.modal-body');
				if (res.response)
					body.innerHTML = res.response;
				else
					if (typeof res === "object")
						body.innerHTML = JSON.stringify(res);
					else
						body.innerHTML = res;
				if (typeof window['modalShown'] === "function")
					window['modalShown'](modal.element);
				opts.onSuccess = null;
				//e.stopImmediatePropagation();
			};
			if (parent.dataset.ajaxData)
				if (parent.dataset.ajaxData.indexOf('%') !== -1) {
					opts.data = decodeURI(parent.dataset.ajaxData);
					opts.data = decodeURIComponent(opts.data);
				} else
					opts.data = parent.dataset.ajaxData;
			new MyAjax(parent.dataset.ajaxUrl, opts).makeRequest();
		}
		if ( typeof parent.dataset.modalButtonAdd !== "undefined" ) {
			modalO.btn = {};
			modalO.btn.add = parent.dataset.modalButtonAdd.split(';');
		}
		if ( typeof parent.dataset.modalStyle !== "undefined" ) {
			modalO.style = parent.dataset.modalStyle.split(',');
		}
		if ( typeof parent.dataset.modalDialogClass !== "undefined" ) {
			modalO.dialog = parent.dataset.modalDialogClass.split(' ');
		}	// data-modal-dialog-class
		if ( typeof parent.dataset.modalButtonDel !== "undefined" ) {
			if (!modalO.btn)
				modalO.btn = {};
			modalO.btn.del = parent.dataset.modalButtonDel.split(',');
		}
		triggerModal( modalO );
	}
	else if (hasClass(target, 'modal-trigger') && target.dataset)
	{
		//if (parent.dataset.title && parent.dataset.body)
			triggerModal( target.dataset.title, target.dataset.body, target.href );
	}
	else if (target.nodeName.toUpperCase() === 'BUTTON' && hasClass(parent, 'modal-footer'))
	{
		if (e.defaultPrevented) return;
		//document.body.style.overflow = "";	// unfreeze scrolling
		if (target.dataset)
		{
			if (target.dataset.url)
				location.replace(target.dataset.url);
			if (target.dataset.slug)
				window.location.href = window.location.href + ((slug.indexOf('?') === 0 || slug.indexOf('&') === 0) ? '' : '?') + target.dataset.slug;
			//doAction(target.dataset.action);
		}
	}
///	e.stopImmediatePropagation();
}

/**
 * Performed when the window is loaded
 */
function load_trigmodal()
{
	addEvent(document.body, 'click', allClicks);
//	includeHTML();
	modal.element = document.getElementById('myModal')
	modal.instance = new bootstrap.Modal(modal.element/*, options*/);
	modal.static = bootstrap.Modal.getInstance(modal.element)
	if (!modal.footer)
		modal.footer = modal.element.querySelector('.modal-footer');
	addEvent(modal.element, 'hidden.bs.modal', function (event) {
		closeModal(event);
	})
	addEvent(modal.footer, 'click', function(event){
		clickModalBtn(event);
	});
}
