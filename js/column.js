/* THIS IS A TEST */
	/**
	 * Launch load when the document loads
	 * ----------------------------
	 */
	/*if (typeof window.addEvent === "function") {*/
		addEvent(window, 'load', load_column);
/*	} else {
//		alert('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
		console.error('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
	}*/

/**
 * Set all elements with the "disable" class as disabled
 */
function startDisabled(start)
{
	if (!start)
		start = document;
	const dis = start.querySelectorAll('.disable');//input[disabled]');
	for (var i=0; i<dis.length; i++) {
/*		if (dis[i].value)
		{*/
			dis[i].disabled = true;
/*		if (dis[i].value)
			//delete dis[i].value;
			dis[i].value = '';*/
/*		}*/
	}
}

/**
 * Enable/disable an element when its trigger element is selected
 */
function EnDis(start)
{
	if (!start)
		start = document;
	const sels = start.querySelectorAll('[data-endisTrig]');
	for (var i=0; i<sels.length; i++) {
		const row = parseFloat(sels[i].id.match(/[\d\.]+/));
		addEventEnDis(sels[i], row);
	}
}

function addEventEnDis(sel, row)
{
	var id=null, cls=null;
	addEvent(sel, 'change', function(){
		const classes = this.selectedOptions[0].className.split(' ');
		for (var j=0; j<classes.length; j++) {
			if (classes[j].startsWith('endis_id_')) {
				id = classes[j].split('endis_id_');
				elid = document.getElementById(id[1]+row);
				break;
			} else if (classes[j].startsWith('endis_class_')) {
				cls = classes[j].split('endis_class_');
				elid = document.getElementsByClassName(cls+row);
				break;
			}
		}
		if (typeof elid === "undefined")
			return;
		elid.disabled = !(this.selectedOptions && hasClass(this.selectedOptions[0], 'endis'));
		if (!elid.disabled)
			elid.focus();
	});
}

/**
 * Convert elements to custom selects
 */
function compactSelects(elems)
{
	for (var i=0; i<elems.length; i++) {
		//reduceSelect(elems[i]);
		new CustomSelect(elems[i]);
	}
}

/**
 * Prints the form data to the console
 */
function printFormData(form)
{
	const formData = new FormData(form/*, submitter*/);

	//const output = document.getElementById("output");
	for (const [key, value] of formData) {
		//output.textContent += `${key}: ${value}\n`;
		console.log(`${key}: ${value}\n`);
	}
}

/**
 * Performs when the modal button "save" is clicked
 */
function modalAction_save(btn)
{
	btn.disabled = true;
	addClass(btn, 'sending');
	setTimeout(save2, 200);	// call save2 after a delay
/*	sleep(5);
	save2();*/
}
function save2() {
	var form = document.querySelector('form');
	//const form = document.getElementById("form");
	//const submitter = document.querySelector("button[value=save]");

//	/*var*/ form = document.querySelector('.extra');
//	printFormData(form);
/*	if (typeof formHasErrors !== "function") {
		alert('no validation function');
		modal.instance.hide();
		return;
	}*/
//		addSubmitValues();
	printFormData(form);
/*	if (!formHasErrors(form))*/
		form.submit();
}

/**
 * Appends the form-controls, within the modal, to the form as hidden fields
 * Only if the name doesn't exist and has a non-null value
 */
function addSubmitValues()
{
		// get all form-controls (ie inputs, textareas and selects)
	let i;
	if (typeof document.querySelector('form') === "undefined")
		throw new Error(' addSubmitValuesfailed because there is not form');
//		return;
	var form = document.querySelector('form');
	const formData = new FormData(form);
	const checks = modal.element.querySelectorAll(".form-check-input");	// get all checkmarks
	for (i = 0; i<checks.length; i++) {
		if (typeof checks[i] !== "undefined" && typeof checks[i].value !== "undefined" && checks[i].value != false) {
//			formData.set(checks[i].name, checks[i].value);
			makeHiddenInput(checks[i].name, checks[i].value, form);
//			if (typeof form.getElementsByName(checks[i].name) === "undefined")
	//			form.appendChild(makeHiddenInput(checks[i].name, checks[i].value));
//			else {
//				var d
//			}
		}
	}
	const controls = modal.element.querySelectorAll(".form-control");
	for (i=0; i<controls.length; i++) {
		if (typeof controls[i] !== "undefined" && typeof controls[i].value !== "undefined" /*&& controls[i].value != false*/ /*&& typeof document.getElementsByName(controls[i].name) === "undefined"*/) {
//			form.appendChild(makeHiddenInput(controls[i].name, controls[i].value));
			//formData.set(controls[i].name, controls[i].value);
			makeHiddenInput(controls[i].name, controls[i].value, form);
		}
	}
}

/**
 * Appends a name-value form hidden input to the given form
 */
function makeHiddenInput(name, value, form)
{
	var el = (typeof document.getElementsByName(name) === "undefined") ? document.createElement('INPUT') : document.getElementsByName(name)[0].cloneNode();
/*	if (document.getElementsByName(name)[0].type !== "hidden")
		//throw new Error('makeHiddenInput failed because input (name)"'+ .name+ '" is not already hidden');
		console.log('makeHiddenInput failed because input (name)"'+ name+ '" is not already hidden');
//		return;
*/
	el.type = 'hidden';
	el.name = name;
	el.value = value;
	form.appendChild(el);
	return el;
}

/**
 * Performs when the modal is shown - when the AJAX request responds
 */
function modalShown(modal)
{
/*	const select = modal.querySelectorAll('.custom-select-wrapper');
	for (var i=0; i<dis.length; i++) {
		select[i].style.zIndex = HIGHEST_Z_INDEX;
	}*/
	const dis = modal.querySelectorAll('.disable');//input[disabled]');
	for (var i=0; i<dis.length; i++) {
		dis[i].disabled = true;
	}
	EnDis(modal);
}

/**
 * Validate the form
 */
function validate()
{
	    var validator = new FormValidator('example_form', [{
        name: 'req',
        display: 'required',
        rules: 'required'
    }, {
        name: 'alphanumeric',
        rules: 'alpha_numeric'
    }, {
        name: 'password',
        rules: 'required'
    }, {
        name: 'password_confirm',
        display: 'password confirmation',
        rules: 'required|matches[password]'
    }, {
        name: 'email',
        rules: 'valid_email'
    }, {
        name: 'minlength',
        display: 'min length',
        rules: 'min_length[8]'
    }, {
        names: ['fname', 'lname'],
        rules: 'required|alpha'
    }], function(errors) {
        if (errors.length > 0) {
            // Show the errors
        }
    });
}

/**
 * Apply the data-apply-* attributes to the clen(column type length) element
 */
function applyAttributes(trigger)
{
	if (/*trigger.nodeName.toUpperCase() != 'SELECT' ||*/ typeof trigger.selected === "undefined"/*!trigger.dataset || !trigger.dataset.applyElement*/)
		return;
/*	const options = option.nextElementSibling.children;
	var option = null;
	if (!trigger.select) {
		for (var i=0; i<options.length; i++) {
			if (options[i].selected === true) {
				option = options[i];
				break;
			}
		}
	} else*/
		var option = trigger.selected;
	if (!option)
		throw new Error('applyAttributes cannot find selected option');
		//return;
	if (!option.dataset /*|| !option.dataset.applyElement*/)
		return;
	const row = parseFloat( trigger.parentElement.id.match(/[\d\.]+/) );
	const dest = document.getElementById('Type-len'+ row);
	if (option.dataset.applyType)
		dest.type = option.dataset.applyType;
	if (option.dataset.applyMin)
		dest.min = option.dataset.applyMin;
	if (option.dataset.applyMax)
		dest.max = option.dataset.applyMax;
	if (option.dataset.applyMinlength)
		dest.minlength = option.dataset.applyMinlength;
	if (option.dataset.applyMaxlength)
		dest.maxlength = option.dataset.applyMaxlength;
	if (option.dataset.applyDefault)
		console.log('setting "'+ dest.id+ '" to "'+ option.dataset.applyDefault+ '"');
//		dest.value = select.dataset.applyDefault;
	if (option.dataset.applyPlaceholder) {
		dest.placeholder = option.dataset.applyPlaceholder;
		dest.value = '';
	}
}

/**
 * Apply attributes when a custom select trigger is clicked
 */
function triggerOnclick()
{
	const clicked = document.querySelectorAll('.custom-select-trigger');
	//const clicked = document.querySelectorAll('.custom-select-option');
	for (var i=0; i<clicked.length; i++) {
		addEvent(clicked[i], "custom-select.selected", function(e) {
//			if (hasClass(e.target, 'custom-select-trigger')) {
				//callback([e], e.target);
				applyAttributes(e.target);
//			}
		});
	}
}

/**
 * Function to observe when a value changes UNUSED
 */
function setMuitativeObserver()
{
	const callback = function(mutationList, observer) {
		for (const mutation of mutationList) {
			if (mutation.type === "childList") {
				console.log("A child node has been added or removed.");
			} else if (mutation.type === "attributes") {
				console.log(`The ${mutation.attributeName} attribute was modified.`);
			}
/*			if (mutation.type === "attributes" && mutation.attributeName === 'innerText') {
				applyAttributes(observer);
			}*/
/*			mutationList.forEach(function(mutation) {
				//applyAttributes(observer);
				console.log(mutation);
			});*/
		}
	};
	const types = document.querySelectorAll('.Type-select');
	for (var i=0; i<types.length; i++) {
/*		var observer = new MutationObserver(callback);
			for (const mutation of mutationList) {
		});*/
/*		if (MutationObserver) {
			var config = {characterData: true, subtree: true};
			const observer = new MutationObserver(callback);
			observer.observe(types[i], config);
		} else if (document.DOMCharacterDataModified)
			addEvent(types[i], "DOMCharacterDataModified", callback);*/
	}
}

/**
 * Onload function
 */
function load_column()
{
	startDisabled();
	EnDis();
	const types = document.querySelectorAll('.Type-select');
	compactSelects(types);
	addEvent(modal.element, 'shown.bs.modal', function(){
		//reduceSelect(document.querySelector('.collation-Select'));
		new CustomSelect(document.querySelector('.collation-Select'));
	});
	addEvent(modal.element, 'hide.bs.modal', function(){
		addSubmitValues();
	});
//	validate();
	document.querySelectorAll("select").forEach(resizeSelect)
//	setMutativeObserver();
	addEvent(document.body, "change", function(e) {
		if (e.target.matches("select")) {
			resizeSelect(e.target);
		}
	});
	var form = document.querySelector('form');
	addEvent(form, "submit", function(e) {
		addSubmitValues();
	});
	triggerOnclick();
}
