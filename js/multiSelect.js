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
 * Returns an array of the selcted options
 */
function getiSelectedOptionValues(select)
{
	var options = select.options;
	var values = [];
	var text = [];

	Array.prototype.slice.call(select.options);
	Array.from(options).map(function(option) {
		option.selected ? values.push(option.value) : null
		option.selected ? text.push(option.text) : null
	})

	return values;
}

//function inserHTML(selectId)
/**
 * Hides the standard select box and draws a dropdown button
 */
function inserHTML(select)
{
	if (typeof select == "string") {
        select = document.getElementById(select);
    }
//	var select = document.getElementById(selectId);
	//var values = getSelectedOptionValues(select);
	var values = [];
	var selText = [];

	Array.prototype.slice.call(select.options);
	Array.from(select.options).map(function(option) {
		option.selected ? values.push(option.value) : null
		option.selected ? selText.push(option.text) : null
	})

	// hide the typical multiple-select
	select.style.display = 'none';
	// insert with a div container
	var div = document.createElement('div');
	div.className = "btn-group";
	// display a button showing the selected options
	//const btnInner = values.join(", ");
	const btnInner = selText.join(", ");
	const btn = drawDDButton(btnInner);
	div.appendChild(btn);
	// display a list of options from the typical select
	div.appendChild(drawList(select.options, values));
	// place the new div after the typical select
	select.parentNode.insertBefore(div, select.nextSibling);
}

/**
 * Sets the button title and contents with selected options
 * for this select box
 */
function updateBtn()
{
	var select = this.parentElement.previousElementSibling;
	//var selected = getSelectedOptionValues(select);
	var selText = [];

	Array.prototype.slice.call(select.options);
	Array.from(select.options).map(function(option) {
		option.selected ? selText.push(option.text) : null
	})
	var str = selText.join(", ");
	this.title = str;
	span = (this.childElementCount > 0) ? this.children[0] : this;
	span.innerHTML = str;
}

/**
 * Returns a dropdown button
 */
function drawDDButton(inside)
{
	var button = document.createElement('button');
	button.type = "button";
	button.className = "form-control multiselect-btn dropdown-toggle btn btn-default";
	if (inside == '')
	{
		addClass(button, 'hitalic');
	}
	button.dataset.toggle = "dropdown";
	button.setAttribute("aria-expanded", "false");
	button.title = (inside != '') ? inside : jsVar.LANG.multiselectBtnPh;//"Hold Shift or Ctrl to select multiple";
//	button.style = (inside != '') ? '' : 'color: #ccc;";
	var span = document.createElement('span');
	span.className = "multiselect-selected-text";
	//span.innerHTML = inside;
	span.innerText = (inside != '') ? inside : jsVar.LANG.multiselectBtnPh;//"Hold Shift or Ctrl to select multiple";
	button.appendChild(span);
	var b = document.createElement('b');
	b.className="caret";
	button.appendChild(b);
	return button;
}

/**
 * Invert the selected marker for each select option
 */
function invertOptions(options,selected)
{
	for(i=0; i<options.length; i++) {
		opt = options[i];
		opt.value = !opt.value;
//		if (selected.indexOf(opt))
//			input.checked = (selected.indexOf(opt.value) != -1) ? true : false;
	}
}

/**
 * Mark every select option as selected
 */
function selectAllOptions(options)
{
	for(i=0; i<options.length; i++) {
		opt = options[i];
		opt.value = 'on';
		opt.parentElement.value += ' ' + opt.value;
//		if (selected.indexOf(opt))
//			input.checked = (selected.indexOf(opt.value) != -1) ? true : false;
	}
//	options[0].parentElement.value =
}

/**
 * Returns an un-ordered list using the select box options
 */
function drawList(options,selected)
{
	var ul = document.createElement('ul');
	ul.className="multiselect-container dropdown-menu";
	ul.setAttribute("x-placement", "bottom-start");
	ul.style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 52px, 0px);";
	var li1 = document.createElement('li');
/*	li1.className="multiselect-item multiselect-all";
	var a1 = document.createElement('a');
	a1.tabindex="0";
	a1.className="multiselect-all";
	li1.appendChild(a1);
	var label1 = document.createElement('label');
	label1.className="checkbox"
	//label1.innerHTML="Select all";
	label1.innerText = jsVar.LANG.selectAll;
	var input1 = document.createElement('input');
	input1.type="checkbox"
	input1.value="multiselect-all";
	addEvent(input1, 'change', function() {
		selectAllOptions(options);
		updateBtn();
	});
*/
	li1.className="multiselect-item multiselect-invert";
	var a1 = document.createElement('a');
	a1.tabindex="0";
	a1.className="multiselect-invert";
	li1.appendChild(a1);
	var label1 = document.createElement('label');
	label1.className="checkbox"
	//label1.innerHTML="Select all";
	label1.innerText = jsVar.LANG.selectAll;
	var input1 = document.createElement('input');
	input1.type="checkbox"
	input1.value="multiselect-invert";
	//addEvent(input1, 'change', invertOptions);
	addEvent(input1, 'change', function() {
		invertOptions(options,selected);
		updateBtn();
	});
	label1.appendChild(input1);
	a1.appendChild(label1);
	li1.appendChild(a1);
	ul.appendChild(li1);
	for(i=0; i<options.length; i++) {
		opt = options[i];
		var li = document.createElement('li');
		if (opt.dataset && opt.dataset.active)
		{
			li.className="active";
		}
		var a = document.createElement('a');
		a.tabindex="0";
		//a.className="multiselect-all";
		var label = document.createElement('label');
		label.className="checkbox";
		label.value=opt.value;
		var input = document.createElement('input');
		input.type="checkbox";
		input.innerHTML=opt.innerHTML;
		if (selected)
		{
			input.checked = (selected.indexOf(opt.value) != -1) ? true : false;
		}
/*		if (selected.indexOf(opt.value))
		{
			input.checked=true;
		}*/
		label.appendChild(input);
		a.appendChild(label);
		li.appendChild(a);
		ul.appendChild(li);
	}
	return ul;
}

/**
 * Begin the operations when the DOM window is loaded
 * Collects all the multiple-select boxes and
 * draws a button and a list of checkboxes of
 * each of the multiple-select box options
 */
function load() {
//	inserHTML("Key");
	const multis = document.querySelectorAll('select[multiple]');
	//const multi1 = document.getElementById('Key');
//	const dbname = document.querySelector('#dbname');
	for (var i=0; i<multis.length; i++)
	{
		//inserHTML(multis[i]);
		inserHTML(multis[i].id);
		addEvent(multis[i].nextElementSibling.children[0], 'click', updateBtn);
	}
}

function processForm(event)
{
	event.preventDefault();
	var target = event.target;
	const formData = new FormData(target);
	const selectValue = formData.getAll('selectId');
	console.log(selectValue);
}
/*
function verify(e) {
	for(i=0; i<takenDBNames.length; i++) {
		if (this.value === takenDBNames[i]) {
			addClass(dbname, 'error');
			break;
		} else {
			removeClass(dbname, 'error');
		}
	}
}
*/
/**
* Get values from multiple select input field
* @param {string} selectId - the HTML select id of the select field
**/
function getMultiSelectValues(selectId) {
	// get the options of select field which will be HTMLCollection
	// remember HtmlCollection and not an array. You can always enhance the code by
	// verifying if the provided select is valid or not
	var options = document.getElementById(selectId).options;
	var values = [];

	// since options are HtmlCollection, we convert it into array to use map function on it
	Array.from(options).map(function(option) {
		option.selected ? values.push(option.value) : null
	})

	return values;
}
