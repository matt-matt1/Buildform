/**
 * Transform a standard select HTML element (and its options) into a div and spans (a list of spans)
 * - an editable list that can be easily styled.  If the list options with optgroups exceeds
 * the limit then each optgroup is shrunk and can be opened / closed to reveal the options inside.
 */
//class myCustomSelect {
//}
function CustomSelect(select, config)
{
	var props = {								// default properties
		useSelectWidth: true,					// uses the width of the to be hidden select for the replacements
		limit: 20,								// number of select entries before the select is transformed
		groupCloseMsg: 'Press/click to close',	// Title (message) - displayed on cursor hover, over group span when group is open
		groupOpenMsg: 'Press/click to open',	// Title (message) - displayed on cursor hover, over group span when group is closed
	};
/*const GROUP_CLOSE_MSG = 'Press/click to close';
const GROUP_OPEN_MSG = 'Press/click to open';*/
//var HIGHEST_Z_INDEX = 0;
//const viewHeight = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0);
/*
export class CustomSelect extends HTMLElement {
	public var selected;
}
customElements.define("custom-select", CustomSelect);
*/
//const CustomSelect = {};
/*
CustomSelect.prototype.doneResponse = function(response, request)
{
	with (this)
	{
	}
}
*/
	for (var p in props) {		// merge given config with default properties
		this[p] = (typeof config === "undefined" || typeof config[p] === undefined || typeof config[p] === "undefined") ? props[p] : config[p];
	}
	if (!this.select)
		this.select = select;
	//reduceSelect(el, limit);
	this.init();		// initialise the transformation
}

/**
 * If outside limit, this will go through each child (option or options inside optgroup)
 * and convert them. At the same time establishes event to hook
 */
CustomSelect.prototype.init = function()
{
	var that = this;
	//with (this) {
		buildCustomSelect();
		var all = select.querySelectorAll('*');
		var i;
		for (i = 0; i<select.children.length; i++) {
			const childRoot = select.children[i];
			if (childRoot.nodeName.toUpperCase() === "OPTGROUP" && all.length > limit) {
				processOptgroup(childRoot);
			} else /*if (childRoot.nodeName.toUpperCase() === "OPTION") || childRoot.nodeName.toUpperCase() === "OPTGROUP"*/ {
				addEvent(processOption(childRoot), 'click', function(){		// set an event for when an option-like item is clicked
					if (this.disabled)
						return;
					//clickedItem(this, select, that.div, that.trigger);
					clickedItem(this, select, div, trigger);
				});
			}
		}
		addEvent(trigger, 'click', function(event){		// set an event for when a select-like list is clicked
			if (event.defaultPrevented) return;
			shiftListToSelected();
			const wasOpen = hasClass(this.parentElement, 'opened');
			closeAll(/*event*/);
			if (!wasOpen)
				addClass(this.parentElement, 'opened');
///			event.stopPropagation();
		});
		addEvent(document.body, 'click', function(event){		// set an event for when a click is detected anywhere
		//addEvent(document, 'click', function(event){		// set an event for when a click is detected anywhere
			event = event || window.event;
			var target = event.target || event.srcElement;
			if (that.trigger.contains(target))
				return false;
			openCloseGroup(event);
		});
		const groups = document.querySelectorAll('.custom-select-optgroup-trigger');	// set initial (mouse-hover) title for each optgroup-like list
		var i;
		for (i = 0; i<groups.length; i++) {
			//groups[i].title = (hasClass(groups[i].parentElement, 'opened')) ? (GROUP_CLOSE_MSG || 'Press/click to close') : (GROUP_OPEN_MSG || 'Press/click to open');
			groups[i].title = (hasClass(groups[i].parentElement, 'opened')) ? (groupCloseMsg || 'Press/click to close') : (groupOpenMsg || 'Press/click to open');
			addEvent(groups[i], 'click', function(event){		// set an event for when a click is detected anywhere
				openCloseGroup(event);
			});
		}
	//}
}

/**
 * Establishes the main elements for the new list
 */
CustomSelect.prototype.buildCustomSelect = function()//(mySel)
{
	with (this)
	{
		addClass(select, "custom-select");
		var div = document.createElement('DIV');
		div.className = 'custom-select-visible';
//		mySel.div = div;
		this.div = div;
		if (select.className)
			div.className += ' '+ select.className;
	/*	if (select.title)
			div.title = select.title;
		div.id = select.id;*/
		div.name = select.name || select.id;
		const attrs = select.attributes;
		for (var i=0; i<attrs.length; i++) {
	/*		if (attrs[i] === 'name')
				div.name = select.name || select.id;*/
			if (attrs[i].name === 'class')
				continue;
	/*		if (attrs[i] === 'id')
				continue;*/
	/*		if (attrs[i].startsWith('data')) {*/
				div.setAttribute(attrs[i].name, attrs[i].value);
	/*		}*/
		}
	/*	div.value = select.value;*/
		var trigger = document.createElement('SPAN');
		if (select.attributes && typeof select.attributes.placeholder !== "undefined")
			trigger.innerHTML = select.getAttribute('placeholder');
			//trig.innerHTML = select.getAttribute('placeholder');//placeholder || select.value;
		trigger.className = 'custom-select-trigger';
//		mySel.trigger = trigger;
		this.trigger = trigger;
			trigger.style.width = select.clientWidth + 'px';
	//	console.log('trigger.style.width = '+ trigger.style.width);
//		console.log('CustomSelect::buildCustomSelect select width = '+ select.clientWidth + 'px');
		div.appendChild(trigger);
		var list = document.createElement('DIV');
		list.className = 'custom-select-options';
//		mySel.list = list;
		this.list = list;
			list.style.width = select.clientWidth + 'px';
	//	console.log('list.style.width = '+ list.style.width);
		div.appendChild(list);
		var wrap = document.createElement('DIV');
		wrap.className = 'custom-select-wrapper';
//		mySel.wrapper = wrap;
		this.wrapper = wrap;
	//	wrap.style.zIndex = HIGHEST_Z_INDEX;
	//	const select = modal.querySelectorAll('.custom-select-wrapper');
	//	for (var i=0; i<dis.length; i++) {
	//		select[i].style.zIndex = HIGHEST_Z_INDEX;
	//	}
		if (select.parentElement)
			select.parentElement.replaceChild(wrap, select);
		wrap.appendChild(select);
		wrap.appendChild(div);
		addClass(select, 'hide');
		select.id += '_';
	}
}

/**
 * Closes all select-like lists
 */
CustomSelect.prototype.closeAll = function(/*event*/)
{
//	event = event || window.event;
//	const target = event.target || event.srcElement;
//			event.stopImmediatePropagation();
	var selects = document.querySelectorAll('div.custom-select.opened');		// all open selects
	for (var i=0; i<selects.length; i++) {
		removeClass(selects[i], 'opened');		// mark as closed
	}
}

/**
 * Performs the processes when an item is selected
 */
CustomSelect.prototype.clickedItem = function(el, select, parent, trigger)
{
	if (el.dataset && el.dataset.value)
		if (select.value === el.dataset.value)		// don't proceed if same item is selected
			return false;
		select.value = el.dataset.value;		// set the select-value as the seletected option-data-value
	//const all = el.parentElement.children;
	const all = el.parentsWithClass("custom-select-wrapper")[0].children[1].children[1].children;//.querySelectorAll('.selected');
	for (var i=0; i<all.length; i++) {		// clear all previously selected
		var j=0;
		while (hasClass(all[i], 'custom-select-optgroup') && j++<1001) {
			const childs = all[i].children[1].children;
			for (var k=0; k<childs.length; k++) {
				if (!childs[k].disabled)
					removeClass(childs[k], 'selected');		// remove select status for optgroup children, if not disabled
			}
		}
		if (!all[i].disabled)
			removeClass(all[i], 'selected');		// remove select status for children, if not disabled
	}
	addClass(el, 'selected');		// mark element as selected
	el.selected = true;				// mark with flag
	//removeClass(parent, 'opened');
	removeClass(this.div, 'opened');		// close this list (div of options-like spans)
	if (el.innerHTML) {
		trigger.innerHTML = el.innerHTML;		// display new value in trigger span
		var customEvent;		// send event (for others to capture) of selection
/*		if (CustomEvent) {
			//customEvent = new CustomEvent("custom-select.selected", { detail: el.innerText, bubbles: true, cancelable: false })
			customEvent = new CustomEvent("custom-select.selected", { detail: el, bubbles: true, cancelable: false })
			//customEvent = new CustomEvent("custom-select.selected", { selected: el, bubbles: true, cancelable: false })
		} else*/ if (Event) {
			customEvent = new Event("custom-select.selected", { bubbles: true, cancelable: false });
		} else if (document.createEvent) {
			customEvent = document.createEvent("Event");
			customEvent.initEvent("custom-select.selected", true, false);
		} else {
//			console.log('Cannot create "custom-select.selected" event on "'+ el.id+ '"');
			return;
		}
		//HTMLElement.prototype.selected = null;
		trigger.selected = el;		// insert selected elememt into trigger properties
		trigger.dispatchEvent(customEvent);
//		el.dispatchEvent(customEvent);
	}
}

/**
 * Form the elements (divs and spans) for the options (children) of the select's optgroup
 */
CustomSelect.prototype.processOptgroup = function(childRoot)
{
	with (this) {
		var divg = document.createElement('DIV');
		divg.className = 'custom-select-optgroup';
		var trigg = document.createElement('SPAN');
		trigg.innerHTML = childRoot.label;
//		trigg.title = 'Toggle opened/closed';
		trigg.className = 'custom-select-optgroup-trigger xcustom-select-trigger';
//		trigg.style.width = select.clientWidth + 'px';
		divg.appendChild(trigg);
		var listg = document.createElement('DIV');
//		listg.style.width = select.clientWidth + 'px';
		list.className = 'custom-select-optgroup-options custom-select-options';
		divg.appendChild(listg);
		list.appendChild(divg);
		for (var j=0; j<childRoot.children.length; j++) {		// each child or option
			const child = childRoot.children[j];
			var opt = document.createElement('SPAN');
			opt.className = 'custom-select-optgroup-option xcustom-select-option hide';
//			opt.style.width = select.clientWidth + 'px';
			opt.dataset.value = child.value;
			for (var k=0; k<child.attributes.length; k++) {		// copy each attribute
				opt.setAttribute(child.attributes[k].name, child.attributes[k].value);
			}
			if (child.className && child.className != '') {
				opt.className += ' '+ child.className;
			}
			if (childRoot.title)
				opt.title = childRoot.title;
			if (child && child.selected)
				trigger.innerHTML = child.innerHTML;
			opt.dataset.value = child.value;
			opt.innerHTML = child.innerHTML;
			listg.appendChild(opt);
			addEvent(opt, 'click', function(event){		// set event for selecting an option-like element within an optgroup-like element
				event = event || window.event;
				var target = event.target || event.srcElement;
				//clickedItem(child, select, divg. trig);
				//clickedItem(event.target, select, divg, trigger);
				clickedItem(target, select, div, trigger);
/*				if (this.dataset && this.dataset.value)
					select.value = this.dataset.value;
				const all = this.parentElement.children;
				for (var i=0; i<all.length; i++) {
					removeClass(all[i], 'selected');
				}
				addClass(this, 'selected');
				this.selected = true;
				removeClass(divg, 'opened');
				if (this.innerHTML) {
					trig.innerHTML = this.innerHTML;
					if (CustomEvent)
						const event = new CustomEvent("change", { detail: this.innerText, bubbles: true, cancelable: false })
					else if (Event)
						const event = new Event("change", { bubbles: true, cancelable: false });
					else if (document.createEvent) {
						const event = document.createEvent("Event");
						event.initEvent("change", true, false);
					} else {
						console.log('Cannot create "change" event on "'+ this.id+ '"');
						return;
					}
					this.dispatchEvent(event);
				}*/
			});
		}
//		return opt;
	}
}

/**
 * Form the elements (span and div of spans) for each child (option)
 */
CustomSelect.prototype.processOption = function(childRoot)
{
	with (this) {
		var opt = document.createElement('SPAN');
		opt.className = 'custom-select-option';
//		opt.style.width = select.clientWidth + 'px';
		if (childRoot.className && childRoot.className != '') {
			opt.className += ' '+ childRoot.className;
		}
		if (childRoot.disabled) {
			opt.disabled = true;
			//opt.className += ' disabled';
			addClass(opt, 'disabled');
		}
		if (opt.selected && !opt.disabled)
			trigger.innerHTML = opt.innerHTML;
		if (childRoot.title)
			opt.title = childRoot.title;
		opt.dataset.value = childRoot.value;
		opt.innerHTML = childRoot.innerHTML;
//		if (!select.value)
//			select.value = '';
		if (opt.dataset.value == select.value) {
//			clickedItem(opt, select, div, trig);
			addClass(opt, 'selected');		// set the initial class
			opt.selected = true;
//			removeClass(div, 'opened');
//			if (this.innerHTML)
				trigger.innerHTML = opt.innerHTML;
		}
		for (var k=0; k<childRoot.attributes.length; k++) {		// copy each attribute
			opt.setAttribute(childRoot.attributes[k].name, childRoot.attributes[k].value);
		}
		list.appendChild(opt);
		return opt;
	}
}

/**
 * Opens or closes the optgroup-like div. Marks the element to open with the opened flag (or remove the flag to close)
 */
CustomSelect.prototype.openCloseGroup = function(event)
{
	with (this) {
		event = event || window.event;
		const target = event.target || event.srcElement;
		if (hasClass(target, 'custom-select-optgroup-trigger')) {
			if (event.defaultPrevented) return;
///			event.stopImmediatePropagation();
			const element = target.parentElement;
			var open;
			if (hasClass(element, 'opened')) {	// close group
				removeClass(element, 'opened');
				open = false;
				target.title = groupOpenMsg || 'Press/click to open';
			} else {
				addClass(element, 'opened');	// open group
				open = true;
				target.title = groupCloseMsg || 'Press/click to close';
			}
			const childs = target.nextElementSibling.children;
			for (var i=0; i<childs.length; i++) {
				if (open/*hasClass(childs[i], 'hide')*/) {
					removeClass(childs[i], 'hide');
				} else {
					addClass(childs[i], 'hide');
				}
			}
		}
		//else if ((target.className !== "custom-select-trigger" || element.className === 'opened') && target.className !== 'custom-select-option') {
		else if (target.className !== "custom-select-trigger" && target.className !== 'custom-select-option') {
			if (event.defaultPrevented) return;
///			event.stopImmediatePropagation();
			closeAll(/*event*/);
		}
	}
}

/**
 * Moves the select-like list to specific position
 */
CustomSelect.prototype.shiftListToSelected = function()
{
	with (this) {
		var selected = list.querySelector('.selected');		// get the selected item
		var add = 0;
		for (var i=0; i<1000, i<list.children.length; i++) {		// loop (maximum of 1000 times) each list item
			if (hasClass(list.children[i], 'custom-select-optgroup')) {
				const subs = list.children[i].children[1].children;//https://rasp.local/BuildForm/database/bf_aaaa/table/Bbbb/columns
				for (var j=0; j<subs.length; j++) {		// each child within optgroup-like element
					if (hasClass(subs[j], 'selected'))
						break;
				}
				add += j + 1;
				break;
			}
			else if (hasClass(list.children[i], 'selected'))
				break;
		}
		const optHeight = list.children[0].clientHeight;		// height of first item
		const num = i + add;		// number of item before selected item
		//const above = Math.min(optHeight * num, event.clientY);
		//const above = (typeof event === "undefined") ? optHeight * num : Math.min(optHeight * num, event.getBoundingClientRect().top);
		var above;
		//if (!event)
		if (typeof event === "undefined")
			above = Math.min(optHeight * num, wrapper.getBoundingClientRect().top);
		else {
	/*		event = event ? event : window.event;
			if (typeof event.target == 'undefined') {
				var target = event.srcElement;
			} else {
				var target = event.target;
			}
			if (target.nodeType==3)
				target = element.parentNode;
	*/
			if (!event)		// also uses mouse position, if an event
				event = window.event;
			if (event.currentTarget)
				target = event.currentTarget;
			else {
				target = event.srcElement;
				while (target.onmousedown === null)
					target = target.parentNode;
			}
			if (target.nodeType == 3)
				target = target.parentNode;
	
			above = Math.min(optHeight * num, target.getBoundingClientRect().top);		// calculates height of position or top of item withever is smaller
		}
		const space = -above + 'px';		// calculates in pixels
		//console.log('shiftListToSelected above = '+ spaceAbove);
		list.style.top = space;		// applies the set vertical position for the list
	//	console.log('shiftListToSelected top = '+ mySel.list.style.top+ ', has '+ num+ ' items above');
	/*	
		const numChildren = mySel.list.children.length;
		var est = optHeight * numChildren;
		var opened = mySel.list.querySelectorAll('.opened');
		for (var i=0; i<opened.length; i++) {
			est += opened[i].children[1].chilodren.length * optHeight;
		}
		console.log('shiftListToSelected estimated height = '+ est);
	*/
	}
}
