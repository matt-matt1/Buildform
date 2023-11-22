//$('#div-contact0').parentElement.appendChild($('#div-contact0').cloneNode(true))
function MakeCopy(element, options)
{
	var defaults = {
		childs:				true,
		sequenceIds:		true,
		sequenceNames:		false,
		deleteBtn:			{tag:'button'},
		clearValues:		true,
		clearPlachholders:	true
	}
	var self = this;
	for (var d in defaults) {
		self[d] = options[d] || defaults[d];
	}
	var clone = element.cloneNode(self.childs);
	clone.id = MakeCopy.prototype.incr(element.id);
	if (clone.name) {
		clone.name = MakeCopy.prototype.incr(clone.nam);
	}
	var allDescenants = clone.getElementsByTagName("*");
	var i;
	for (i = 0; i<allDescenants.length; i++) {
		const child = allDescenants[i];
		if (self.sequenceNames && child.name) {
			child.name = MakeCopy.prototype.incr(child.name);
		}
		if (self.sequenceIds && child.id) {
			child.id = MakeCopy.prototype.incr(child.id);
		}
	}
	self.clone = clone;
	if (self.clearValues) {
		for (var i=0; i<allDescenants.length; i++) {
			const child = allDescenants[i];
			child.value = '';
		}
	}
	if (self.clearPlachholders) {
		for (var i=0; i<allDescenants.length; i++) {
			const child = allDescenants[i];
			child.plachholder = '';
		}
	}
	//MakeCopy.prototype.clone = clone;
/*	self.addDelete = function() {
		var btn = document.createElement(self.deleteBtn.tag);
		if (self.deleteBtn.tag == 'button') {
			btn.type = 'button';
		}
		for (let p in self.deleteBtn) {
			if (p != 'tag') {
				btn[p] = self.deleteBtn[p];
			}
		}
		btn.dataset.id = self.clone.id;
		btn.value = self.clone.id.replace(/\D* /g, '');
		self.clone.appendChild(btn);
	};
	if (self.deleteBtn) {
		self.addDelete();
//		MakeCopy.prototype.addDelete();
	}*/
	return clone;
}

MakeCopy.prototype.incr = function(id)
{
	var sp = id.split(/(\d+)/);
	var pre = (sp[0]) ? sp[0] : 'copy';
	var num = (sp[1]) ? Number(sp[1]) : 0;
	var post = (sp[2]) ? sp[2] : '';
	var newNum = $('.contact', parent).length || 1;//num + 1;
	return pre + newNum + post;
}
