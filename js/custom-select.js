const GROUP_CLOSE_MSG = 'Press/click to close';
const GROUP_OPEN_MSG = 'Press/click to open';
//var HIGHEST_Z_INDEX = 0;
//const viewHeight = Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0);
/*
export class CustomSelect extends HTMLElement {
	public var selected;
}
customElements.define("custom-select", CustomSelect);
*/
const CustomSelect = {};

/**
 * Transform the select element (and its options) into a div and span (and a list of spans)
 * - an editable list that can be easily styled.  If the list options with optgroups exceed
 * the limit each optgroup is shrunk and can be opened / closed to reveal the options.
 */
function reduceSelect(select, limit)
{
	if (typeof select === "string"){// !== "object"
		select = document.getElementById(select);
	}
/*	if (!HIGHEST_Z_INDEX) {
		var elements = document.getElementsByTagName("*");
		for (var i = 0; i < elements.length - 1; i++) {
			if (parseInt(elements[i].style.zIndex) > HIGHEST_Z_INDEX) {
				HIGHEST_Z_INDEX = parseInt(elements[i].style.zIndex);
		    }
		}
	}*/
	if (!select)
		return;
	const instance = Date.now();
	//let car = cars.find(car => car.color === "red")
	var mySel = {};
	CustomSelect[instance] = mySel;
	mySel.select = select;
	buildCustomSelect(mySel);
	var all = select.querySelectorAll('*');
	//with (mySel) {
		if (!limit)
			limit = 20;
		//console.log('select '+ select.id+ ' has '+ all.length+ ' options.');
		var i;
		for (i = 0; i<select.children.length; i++) {
			const childRoot = select.children[i];
			if (childRoot.nodeName.toUpperCase() === "OPTGROUP" && all.length > limit) {
				processOptgroup(mySel, childRoot);
			} else /*if (childRoot.nodeName.toUpperCase() === "OPTION") || childRoot.nodeName.toUpperCase() === "OPTGROUP"*/ {
				addEvent(processOption(mySel, childRoot), 'click', function(){
					if (this.disabled)
						return;
					clickedItem(this, select, mySel.div, mySel.trigger);
				});
			}
		}
	/*	const first = document.querySelectorAll(".custom-select-option:first-of-type");
		addEvent(first, 'hover', function(){
			addClass(this.parentElement, 'custom-select-option-hover');
		});
		addEvent(first, 'blur', function(){
			removeClass(this.parentElement, 'custom-select-option-hover');
		});*/
		addEvent(trigger, 'click', function(event){
			shiftListToSelected(mySel);
			const wasOpen = hasClass(this.parentElement, 'opened');
			closeAll(/*event*/);
			/*if (hasClass(div, 'opened'))
				removeClass(div, 'opened');
			else*/
				//addClass(div, 'opened');
			if (!wasOpen)
				addClass(this.parentElement, 'opened');
			event.stopPropagation();
		});
		addEvent(document.body, 'click', function(event){
			openCloseGroup(event);
		});
		const groups = document.querySelectorAll('.custom-select-optgroup-trigger');	// set initial title
		for (var i=0; i<groups.length; i++) {
			groups[i].title = (hasClass(groups[i].parentElement, 'opened')) ? (GROUP_CLOSE_MSG || 'Press/click to close') : (GROUP_OPEN_MSG || 'Press/click to open');
		}
	//}
	//console.log('vhewport height = '+ viewHeight);
}

function buildCustomSelect(mySel)
{
	const select = mySel.select;
	addClass(select, "custom-select");
	var div = document.createElement('DIV');
	div.className = 'custom-select-visible';
	mySel.div = div;
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
	mySel.trigger = trigger;
		trigger.style.width = select.clientWidth + 'px';
//	console.log('trigger.style.width = '+ trigger.style.width);
	console.log('CustomSelect::buildCustomSelect select width = '+ select.clientWidth + 'px');
	div.appendChild(trigger);
	var list = document.createElement('DIV');
	list.className = 'custom-select-options';
	mySel.list = list;
		list.style.width = select.clientWidth + 'px';
//	console.log('list.style.width = '+ list.style.width);
	div.appendChild(list);
	var wrap = document.createElement('DIV');
	wrap.className = 'custom-select-wrapper';
	mySel.wrapper = wrap;
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

function closeAll(/*event*/)
{
//	event = event || window.event;
//	const target = event.target || event.srcElement;
//			event.stopImmediatePropagation();
	var selects = document.querySelectorAll('div.custom-select.opened');
	for (var i=0; i<selects.length; i++) {
		removeClass(selects[i], 'opened');
	}
}

function clickedItem(el, select, parent, trigger)
{
	if (el.dataset && el.dataset.value)
		select.value = el.dataset.value;
	//const all = el.parentElement.children;
	const all = el.parentsWithClass("custom-select-wrapper")[0].children[1].children[1].children;//.querySelectorAll('.selected');
	for (var i=0; i<all.length; i++) {
		var j=0;
		while (hasClass(all[i], 'custom-select-optgroup') && j++<1001) {
			const childs = all[i].children[1].children;
			for (var k=0; k<childs.length; k++) {
				if (!childs[k].disabled)
					removeClass(childs[k], 'selected');
			}
		}
		if (!all[i].disabled)
			removeClass(all[i], 'selected');
	}
	addClass(el, 'selected');
	el.selected = true;
	removeClass(parent, 'opened');
	if (el.innerHTML) {
		trigger.innerHTML = el.innerHTML;
		var customEvent;
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
			console.log('Cannot create "custom-select.selected" event on "'+ el.id+ '"');
			return;
		}
		//HTMLElement.prototype.selected = null;
		trigger.selected = el;
		trigger.dispatchEvent(customEvent);
//		el.dispatchEvent(customEvent);
	}
}

function processOptgroup(mySel, childRoot)
{
	with (mySel) {
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
		for (var j=0; j<childRoot.children.length; j++) {
			const child = childRoot.children[j];
			var opt = document.createElement('SPAN');
			opt.className = 'custom-select-optgroup-option xcustom-select-option hide';
//			opt.style.width = select.clientWidth + 'px';
			opt.dataset.value = child.value;
			for (var k=0; k<child.attributes.length; k++) {
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
			addEvent(opt, 'click', function(event){
				//clickedItem(child, select, divg. trig);
				clickedItem(event.target, select, divg, trigger);
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

function processOption(mySel, childRoot)
{
	with (mySel) {
		var opt = document.createElement('SPAN');
		opt.className = 'custom-select-option';
//		opt.style.width = select.clientWidth + 'px';
		if (childRoot.className && childRoot.className != '') {
			opt.className += ' '+ childRoot.className;
		}
		if (childRoot.disabled) {
			opt.disabled = true;
			opt.className += ' disabled';
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
			addClass(opt, 'selected');
			opt.selected = true;
//			removeClass(div, 'opened');
//			if (this.innerHTML)
				trigger.innerHTML = opt.innerHTML;
		}
		for (var k=0; k<childRoot.attributes.length; k++) {
			opt.setAttribute(childRoot.attributes[k].name, childRoot.attributes[k].value);
		}
		list.appendChild(opt);
		return opt;
	}
}

function openCloseGroup(event)
{
		event = event || window.event;
		const target = event.target || event.srcElement;
		if (hasClass(target, 'custom-select-optgroup-trigger')) {
			event.stopImmediatePropagation();
			const element = target.parentElement;
			var open;
			if (hasClass(element, 'opened')) {	// close group
				removeClass(element, 'opened');
				open = false;
				target.title = GROUP_OPEN_MSG || 'Press/click to open';
			} else {
				addClass(element, 'opened');	// open group
				open = true;
				target.title = GROUP_CLOSE_MSG || 'Press/click to close';
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
			event.stopImmediatePropagation();
			closeAll(/*event*/);
		}
}

function shiftListToSelected(mySel)
{
	var selected = mySel.list.querySelector('.selected');
	var add = 0;
	for (var i=0; i<1000, i<mySel.list.children.length; i++) {
		if (hasClass(mySel.list.children[i], 'custom-select-optgroup')) {
			const subs = mySel.list.children[i].children[1].children;//https://rasp.local/BuildForm/database/bf_aaaa/table/Bbbb/columns
			for (var j=0; j<subs.length; j++) {
				if (hasClass(subs[j], 'selected'))
					break;
			}
			add += j + 1;
			break;
		}
		else if (hasClass(mySel.list.children[i], 'selected'))
			break;
	}
	const optHeight = mySel.list.children[0].clientHeight;
	const num = i + add;
	//const above = Math.min(optHeight * num, event.clientY);
	//const above = (typeof event === "undefined") ? optHeight * num : Math.min(optHeight * num, event.getBoundingClientRect().top);
	var above;
	//if (!event)
	if (typeof event === "undefined")
		above = Math.min(optHeight * num, mySel.wrapper.getBoundingClientRect().top);
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
		if (!event)
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

		above = Math.min(optHeight * num, target.getBoundingClientRect().top);
	}
	const space = -above + 'px';
	//console.log('shiftListToSelected above = '+ spaceAbove);
	mySel.list.style.top = space;
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
