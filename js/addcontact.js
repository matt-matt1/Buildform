addEvent(window, 'load', load_addcontact);

function load_addcontact()
{
	const btn = document.getElementById('addcontact');
	addEvent(window, 'click', clicked_addcontact);
}

function clicked_addcontact()
{
	const btnp = document.getElementById('addcontact').parentElement.parentElement;
	var clone = btnp.cloneNode(1);

	const filedset = document.getElementById('contact_fieldset');
	if (!filedset) {
		btnp.parentElement.insertBefore(clone, btnp.nextSibling);
		return;
	}
	var newFiledset = filedset.cloneNode(1);
	filedset.parentElement.appendChild(newFiledset);
}

