/**
 * Autocomplete Class
 * Draws a list of suggestions below an input box
 * - moves focus with Arrow-down, Arrow-up, (Home, End, Page-up, Page-down)
 * - selects with Arrow-right, (Enter, Shift+Enter), mouse click
 * - clear input with Escape and removes the suggestions box
 * - filters suggestions by any character sequence being either case-sensative or case-insensative(default)
 *   either that contains the input anywhere(default) or that only starts with the input
*/
function Autocomplete(config)
{
	var defaults = {
		wrapperClass: '.autocomplete',
		suggestions: [{"id": 1, "term": "apple"}, {"id": 2, "term": "banana"}, {"id": 3, "term": "carrot"}],
		getData: function(data) {},
		searchTerm: "term",
		resultId: "id",
		element: "undefined",
		matchStartOnly: false,
		caseSensative: false,
		log: false,
		onStart: function(elm) {},
		onSelect: function(id, obj) {}
	};
	for (var d in defaults) {
		this[d] = (typeof config[d] === undefined || typeof config[d] === "undefined") ? defaults[d] : config[d];
	}
	var self = this;
	self.started = false;
	if (!self.resultId)
		self.resultId = self.searchTerm;
	if (self.element && self.element.addEventListener) {
		self.element.addEventListener("input", function(e) {	self.inputed(e)	});
		if (self.log)
			console.log('element listening for "input" on '+self.element.id);
		self.element.addEventListener("keyup", function(e) {	return self.detectKey(e)	});
		if (self.log)
			console.log('element listening for "keyup" on '+self.element.id);
	} else {
		var inputs = $(self.wrapperClass + ' input[type="text"], '+ self.wrapperClass + ' input[type="search"]');
		if (typeof inputs.length == "undefined")
			inputs = [inputs];
		for (var j=0; j<inputs.length; j++) {
			inputs[j].addEventListener("input", function(e) {	self.inputed(e)	});
			if (self.log)
				console.log('listening for "input" on '+inputs[j].id);
			inputs[j].addEventListener("keyup", function(e) {	return self.detectKey(e)	});
			if (self.log)
				console.log('listening for "keyup" on '+inputs[j].id);
		}
	}
	document.addEventListener("click", function(e) {	self.closeAllLists(e.target);	});
}


Autocomplete.prototype.inputed = function(e)
{
	var self = this;
	if (self.log)
		console.log('inputed() '+ e.data);
	this.closeAllLists();
	if (!e.target.value) {
		return false;
	}
	self.put = e.target;
	if (!self.started) {
		self.started = true;
		self.onStart(self.put);
	}
	self.currentFocus = -1;
	self.showResults();
}


Autocomplete.prototype.addActive = function()
{
	if (self.log)
		console.log('addActive()');
	if (!this.lst)
		return false;
	this.removeActive();
	if (this.currentFocus >= this.lst.children.length)
		this.currentFocus = 0;
	if (this.currentFocus < 0)
		this.currentFocus = (this.lst.children.length - 1);
	this.lst.children[this.currentFocus].classList.add("autocomplete-active");
}

Autocomplete.prototype.removeActive = function()
{
	if (self.log)
		console.log('removeActive()');
	for (var i = 0; i < this.lst.children.length; i++) {
		this.lst.children[i].classList.remove("autocomplete-active");
	}
}


Autocomplete.prototype.detectKey = function(e)
{
	var self = this;
	if (!self.lst || !self.lst.children || !self.lst.children.length)
		return false;
	e = e || window.event;
	var keyCode = e.keyCode ? e.keyCode : e.which ? e.which : e.charCode;
	var log = 'detectKey() '+ e.keyCode;
	if (self.lst && self.lst.children) {
		log += ' ; lst:'+ self.lst.children.length;
	}
	if (self.log)
		console.log(log);
	if (e.key === 'PageUp' || keyCode === 33) {
		self.currentFocus = Math.max(0, self.currentFocus - 20);
	} else if (e.key === 'PageDown' || keyCode === 34) {
		self.currentFocus = Math.min(self.lst.children.length - 1, self.currentFocus + 20);
	} else if ((e.key === 'Home' || keyCode === 36) && self.currentFocus != 0) {
		self.currentFocus = 0;
	} else if ((e.key === 'End' || keyCode === 35) && self.lst.children.length != self.currentFocus) {
		self.currentFocus = Math.min(self.lst.children.length, self.currentFocus + 1);
	} else if ((e.key === 'ArrowDown' || keyCode === 40) && self.lst.children.length != self.currentFocus) {
		self.currentFocus = Math.min(self.lst.children.length, self.currentFocus + 1);
		self.addActive();
//	} else if (e.key == '' || keyCode == 46) {	// delete
	} else if ((e.key === 'ArrowRight' || e.keyIdentifier === 'Right' || keyCode === 39 || (e.shiftKey && keyCode == 13)) && this.currentFocus >= 0) {
		self.picked(this.currentFocus);
	} else if ((e.key === 'ArrowUp' || keyCode == 38) && self.currentFocus >= 0) {
		self.currentFocus--;
		self.addActive();
	} else if (e.key === 'Escape' || keyCode == 27) {
		self.put.value = '';
	} else if (e.key === 'Enter' || keyCode == 13) {
		e.cancelBubble = true;
		e.returnValue = false;
		if (e.stopPropagation) {
			e.stopPropagation();
			e.preventDefault();
		}
		if (self.currentFocus >= 0) {
			if (self.lst) {
				self.picked(self.currentFocus);
			}
		}
		return false;
	}
}


Autocomplete.prototype.picked = function(id)
{
	var entry = this.lst.children[id];
	this.put.value = entry.innerText;
	this.put.focus();
	this.closeAllLists();
	const self = this;
	if (self.log)
		console.log('picked() id:'+ entry.dataset.id+' , value:' + this.put.value + ' ;');
	if (this.onSelect && typeof this.onSelect === 'function') {
		function returnId(obj) {
			if (obj[self.resultId] === entry.dataset.id)
				return self.onSelect(entry.dataset.id, obj);//business);
		}
		this.suggestions.filter(returnId);
	}
}


Autocomplete.prototype.closeAllLists = function(elmnt)
{
//	console.log('closeAllLists()');
	var itms = $(".autocomplete-items");
	if (typeof itms.length == "undefined")
		itms = [itms];
	for (var i = 0; i < itms.length; i++) {
		if (elmnt !== itms[i] && elmnt != this.put) {
			itms[i].parentNode.removeChild(itms[i]);
		}
	}
}


Autocomplete.prototype.autocompleteMatch = function()
{
	if (this.put.value === '') {
		return [];
	}
	var self = this;
	if (self.log)
		console.log('autocompleteMatch() value:'+ this.put.value);
//	var reg = new RegExp(this.put.value);
	return self.suggestions.filter(function(obj) {
		var haystack;
		if (!obj[self.searchTerm]) {
			var array = self.searchTerm.split('_');
			array.pop();
			haystack = obj[array.join('_')];
		} else {
			haystack = obj[self.searchTerm];
		}
		if (!haystack) {
			return;
		}
		//if (obj.term.match(reg)) {
		if (self.caseSensative) {
			if (self.matchStartOnly) {
				if (haystack.startsWith(self.put.value)) {
					return obj;
				}
			} else {
				if (haystack.includes(self.put.value)) {
					return obj;
				}
			}
		} else if (haystack.toLowerCase()) {
			if (self.matchStartOnly) {
				if (haystack.toLowerCase().startsWith(self.put.value.toLowerCase())) {
					return obj;
				}
			} else {
				if (haystack.toLowerCase().includes(self.put.value.toLowerCase())) {
					return obj;
				}
			}
		}
	});
}

Autocomplete.prototype.showResults = function()
{
	if ((typeof $('#'+this.searchTerm + "_autocomplete-list", this.put.parentElement).length !== 'undefined' && $('#'+this.searchTerm + "_autocomplete-list", this.put.parentElement).length) || (typeof $('#'+this.searchTerm + "_autocomplete-list", this.put.parentElement).length === 'undefined' && $('#'+this.searchTerm + "_autocomplete-list", this.put.parentElement)))
		return;		// don't if list element already there
	this.lst = document.createElement('div');
	this.lst.setAttribute("id", this.searchTerm + "_autocomplete-list");
	this.lst.setAttribute("class", "autocomplete-items");
	this.lst.innerHTML = '';
	this.put.parentElement.appendChild(this.lst);
	var el = this.lst;
	while(el = el.parentElement.parentAs('fieldset'))
		el.style.overflow = 'visible';
	let objs = this.autocompleteMatch();
	const self = this;
	for (i=0; i<objs.length; i++) {
		var entry = document.createElement('div');
		entry.setAttribute("data-id", objs[i][self.resultId]);
		entry.setAttribute("data-counter", i);
		var haystack;
		if (!objs[i].hasOwnProperty(self.searchTerm)) {
			var array = self.searchTerm.split('_');
			array.pop();
			haystack = objs[i][array.join('_')];
		} else {
			haystack = objs[i][self.searchTerm];
		}
		entry.innerHTML = haystack;
//		this.lst.innerHTML += '<div data-id="' + objs[i][self.id] + '">' + objs[i][self.search] + '</div>';
		entry.addEventListener("click", function(e) {
			self.picked(this.dataset.counter);
			self.closeAllLists();
		});
		this.lst.appendChild(entry);
	}
	if (self.log)
		console.log('showResults() lst has:'+ this.lst.children.length);
}
var global = window || global;
global.Autocomplete = Autocomplete;
