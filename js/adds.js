addEvent(window, 'load', load_adds);
function load_adds()
{
	const adds = document.querySelectorAll('.dbadd');
	for (i=0; i<adds.length; i++)
	{
		//addEvent( adds[i], 'click', function(e){
		//} );
		addEvent( adds[i], 'click', addDbClicked );
	}
}
function addDbClicked(event)
{
			event.preventDefault();
			const clist = this.classList;
			for (i=0; i<clist.length; i++)
			{
				if (clist[i].substring(0, 3) === 'add')
				{
					const suffix = clist[i].substr(4);
					if (suffix === 'table')
						addNewRow( clist[i]+ '-last', 'dbtab', 'tbname', 'table-name', this, '?remove' );
					else if (suffix === 'form')
						addNewRow( clist[i]+ '-last', 'dbfrm', 'fname', 'form-name', this, '?remove_form' );
					else if (suffix === 'db')
						addNewRow( clist[i]+ '-last', 'db', 'dbname', 'db-name', this, '?remove_db' );
				}
			}
}
function addNewRow( id, spanClass, inputName, aId, trigElem, removeSlug ) {
	const tr_orig = document.getElementById(id);
	if (tr_orig.children[1] && tr_orig.children[1].lastChild && tr_orig.children[1].lastChild.nodeName === 'input')
		return;
	tr_orig.id = '';	// clear original id - since another element will have that id
	if (tr_orig.children[0].lastChild.className === 'empty')
		tr_orig.className = 'hide';	// hide tr if has a grandchild with 'empty' class
	// remove original id
	var tr = document.createElement('tr');
	if (tr_orig.dataset && tr_orig.dataset.placeholder)
		tr.dataset.placeholder = tr_orig.dataset.placeholder;
	tr.id = id;

	var td1 = document.createElement('td');
	td1.className = "symbol";	// row start symbol:

	var span1 = document.createElement('span');
	span1.className = "symbol "+ spanClass;
	td1.appendChild(span1);
	tr.appendChild(td1);

	var td2 = document.createElement('td');

	var form2 = document.createElement('form');
	//form2.className = "symbol "+ spanClass;
	form2.action = trigElem.href;
	form2.method = 'POST';
	td2.appendChild(form2);

	var input1 = document.createElement('input');
	input1.type = 'hidden';
	input1.name = 'requestToken';
	//input1.value = "<?=$_SESSION['requestToken']?>";
	if (tr_orig.dataset && tr_orig.dataset.token)
		input1.value = tr_orig.dataset.token;	// use data value
	form2.appendChild(input1);

	var input = document.createElement('input');
	input.className = "symbol form-control";
	//input.type = 'text';
	input.type = 'search';
	input.id = inputName;
	input.name = inputName;
	input.maxlength = 5;
	input.required = true;
	if (tr_orig.dataset && tr_orig.dataset.placeholder)
		input.placeholder = tr_orig.dataset.placeholder;	// use data value
	form2.appendChild(input);
	tr.appendChild(td2);

	var td3 = document.createElement('td');
	td3.className = "symbol";

/*	var input3 = document.createElement('input');
	input3.className = "symbol form-control no-button";
	input3.type = 'submit';
	td3.appendChild(input3);*/
	var a3 = document.createElement('a');	// trigger modal
	a3.id = aId;//"table-name";
	a3.className = "symbol enter-key xmodal-trigger";
//	a3.dataset.bsToggle = "modal";
//	a3.dataset.bsTarget = "#myModal";
	td3.appendChild(a3);
	// set temp class for final span
	var span3 = document.createElement('span');
	span3.className = "symbol";// _delete";
	var i3 = document.createElement('i');
	i3.className = "fa-solid fa-play"
	span3.appendChild(i3);
	a3.appendChild(span3);
	td3.appendChild(a3);
	tr.appendChild(td3);
	// add the created table row
	tr_orig.parentElement.appendChild(tr);
	input.scrollIntoView();
	input.focus();
	// detect keys
	addEvent( input, 'keyup', keyed );
	addEvent( form2, 'submit', addDbSubmit );
	form2.onsubmit = function() {
		addDbSubmit();
	};
	addEvent( a3, 'click', function(event){
/*		var miss = false;
		if (!input.value)
			miss = true;*/
/*		var tooSh = false;
		if (input.value.length > 1)
			tooSh = true;*/
		var found = false;
		if (typeof reserved !== 'undefined' && reserved)
			found = reserved.indexOf(input.value) !== -1;
		if (/*miss || tooSh ||*/ found) {
			addClass(input, 'bgblink');
		} else
			form2.submit();
/*		if (miss)
			input.title = 'Missing or invalid database name';
		if (tooSh)
			input.title = 'Database name is too short';*/
	});
}

/**
 * Fires when the form is submitted that contains the add-database-name input
 */
function addDbSubmit(event)
{
	event = event || window.event;
	var target = target ? event.target : event.srcElement;
	const input = target.querySelector('input');
	//if (found) {
	if (input && hasClass(input, 'bgblink')) {
		event.preventDefault();
		var msg = target.parentAs('table').querySelector('.invalid-note');
/*		msg.innerHTML = input.dataset.msg;*/
		msg.innerHTML = 'Database name is already taken';
		removeClass(msg, 'hide');
/*		var msg = document.createElement('span');
		msg.innerHTML = 'Database name is already taken';
		msg.style.position = 'absolute';
		const pos = input.getBoundingClientRect();
		msg.style.top = (pos.y + pos.height) + 'px';
		msg.style.left = pos.x + 'px';
		msg.style.padding = '0.5em';
		msg.style.margin = '0.5em';
		msg.style.borderRadius = '5px';
		msg.style.backgrounColor = 'black';
		msg.style.color = '#777';
		//input.parentElement.parentElement.parentElement.parentElement.appendChild(msg);	// tbody
		input.parentAs('tbody').appendChild(msg);*/
	}
}
function keyed(event)
{
	event = event || window.event;
	var target = target ? event.target : event.srcElement;
	event.preventDefault();
	if (event.keyCode === 27)
	{
		const tr = this.parentElement.parentElement.parentElement;
		if (tr.previousElementSibling)
		{
			tr.previousElementSibling.id = tr.id;	// reset id
			if (tr.previousElementSibling.className === 'hide')	// unhide if previous is tr.empty
				tr.previousElementSibling.className = '';
		}
		tr.parentElement.removeChild(tr.parentElement.lastChild);
	}
}
