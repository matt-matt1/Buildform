/**
 * JavaScript to manage the business section
 *
 * submits sql to database via AJAX and processes the response
 *
 * @author Yumatechnical@gmail.com
 */
/**
 * client-side operations when the whole document is loaded
 */
var parent = 'undefined';// = null;
//const CONSOLE_BUS = 1;
const CONSOLE_BUS = false;
addEvent(window, 'load', load_business);
/**
 * Loads the search data
 */
function load_business()
{
	getBusinessData();
	getBusinessContactsData();
}

/**
 * Establishes the events
 */
function setEvents()
{
//	var main = parentOfType(this.put, $('fieldset');
//	main.style.backgroundColor = 'lightblue';
/*	addEvent($('#add-business'), 'click', function(e){
		e.preventDefault();
		window.location = jsVar.BASE + 'business/add';
	});*/
	addEvent($('#remove-business', parent), 'click', function(e){
		e.preventDefault();
		window.location = jsVar.BASE + 'business/remove';
	});
	addEvent($('#add-contact', parent), 'click', function(e){
		e.preventDefault();
		addContact(true);
	});
	addEvent(document.body, 'click', function(e){
		e = event || window.event;
		var target = e.target;
		if (target.className.indexOf('deleteMe') !== -1) {
			e.preventDefault();
			removeContact(e);
		}
	});
	addEvent($('input[type="reset"]', parent), 'click', function(){
		resetForm(this.form);
		eraseExtraContacts();
		var main = $('fieldset', this.form)[0];
		main.style.backgroundColor = '';
	});
}

/**
 * Fires when the user begins a search
 * UNUSED
 */
function startedTyping(input)
{
/*	var form1 = parentOfType(input, 'form');
	form1.style.backgroundColor = 'lightblue';*/
}

/**
 * Retrieves a list of businesses from the database via AJAX
 * on success begins the autocomplete operation, sets events, show the pane
 */
var businesses = [];
function getBusinessData()
{
	var sql = 'SELECT * from yumatech_organise.business';
	//var sql = '<?=Business::getAll('onlySQL'=>true?>';
	var onSuccess = function(res, xhr){
		if (CONSOLE_BUS)
			console.log('getBusinessData success');
		businesses = JSON.parse(res.response);
		parent = $('#business_name').form;
//		const acs = $('.autocomplete-businesses input');
		const acs = $('input[data-search-in=businesses]');
		for (var i=0; i<acs.length; i++) {
			new Autocomplete({
				element: $('#'+acs[i].id, parent),
				searchTerm: acs[i].id,
				resultId: "business_id",
				log: true,
				suggestions: JSON.parse(res.response),
				onSelect: selectedBusiness
			});
		}
		setEvents();
		addClass(parent.parentsWithClass('tab-pane')[0], 'show');
	};
	var onError = function(xhr,error){
		console.log('** xhr failure');
		console.log(xhr);
		console.log(error);
	};
	dbQuery(sql, onSuccess, onError, false);
}

/**
 * Retrieves a list of business-contacts from the database via AJAX
 * on success begins the autocomplete operation
 */
//let contacts = [];

function getBusinessContactsData()
{
	var sql = 'SELECT * from yumatech_organise.contact WHERE `business_id` IS NOT null';
	//var sql = '<?=Business::getAll('onlySQL'=>true?>';
	var onSuccess = function(res, xhr){
		if (CONSOLE_BUS)
			console.log('getBusinessContactsData success');
		businesses = JSON.parse(res.response);
		parent = $('#business_name').form;
		const acs = $('.autocomplete-contacts input');
		for (var i=0; i<acs.length; i++) {
			new Autocomplete({
				element: $('#'+acs[i].id, parent),
				//searchTerm: acs[i].id,
				searchTerm: (acs[i].dataset.searchAs) ? acs[i].dataset.searchAs : acs[i].id,
				resultId: "contact_id",
				log: true,
				suggestions: JSON.parse(res.response),
				onSelect: selectedBusiness
			});
		}
	};
	var onError = function(xhr,error){
		console.log('** xhr failure');
		console.log(xhr);
		console.log(error);
	};
	dbQuery(sql, onSuccess, onError, false);
}

/**
 * Fires when the user selects a business from the suggestion list
 *
 * @param int		id			Id of the selected business
 * @param object	business	Business found
 */
function selectedBusiness(id, business)
{
	if (CONSOLE_BUS)
		console.log('selectedBusiness');
	parent = parentOfType(this.put, 'form');
	getBusinessCreatedDate(id, business);
	getBusinessLastEditDate(id, business);
	getBusinessContacts(business.business_id);
	var main = parentOfType(this.put, 'fieldset');
	main.style.backgroundColor = 'lightblue';
	fillBusinessData(business);
	if (contacts.length) {
		fillContactsData(contacts);
	}
}

/**
 * Retrieves the journal entry (for business creation) -via AJAX
 * on success puts the retrieved date into the supplied onject
 *
 * @param int		id			Id of the selected business
 * @param object	business	Business found
 */
function getBusinessCreatedDate(id, business)
{
	var sql = 'SELECT * from yumatech_organise.journal WHERE action_id=2 AND business_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_BUS)
			console.log('getBusinessCreatedDate success');
		var entry = JSON.parse(res.response)[0];
		if (typeof entry === 'object' && entry.date) {
//			business.date_first = entry.date;
			$('#business_created', parent).placeholder = entry.date;//'';
			$('#business_created', parent).defaultValue = entry.date;
			$('#business_created', parent).value = entry.date;
		}
//		fillContactsData(contacts);
	};
	var onError = function(xhr,error){
		console.log('** xhr failure');
		console.log(xhr);
		console.log(error);
	};
	dbQuery(sql, onSuccess, onError, false);
}

/**
 * Retrieves the journal entry (for business updated) -via AJAX
 * on success puts the retrieved date into the supplied onject
 *
 * @param id		int		id of the selected business
 * @param business	object	business found
 */
function getBusinessLastEditDate(id, business)
{
	var sql = 'SELECT * from yumatech_organise.journal WHERE action_id=3 AND business_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_BUS)
			console.log('getBusinessLastEditDate success');
		var entry = JSON.parse(res.response)[0];
		if (typeof entry === 'object' && entry.date && typeof business === 'object') {
//			business.date_updated = entry.date;
			$('#business_updated', parent).placeholder = entry.date;//'';
			$('#business_updated', parent).defaultValue = entry.date;
			$('#business_updated', parent).value = entry.date;
		}
//		fillContactsData(contacts);
	};
	var onError = function(xhr,error){
		console.log('** xhr failure');
		console.log(xhr);
		console.log(error);
	};
	dbQuery(sql, onSuccess, onError, false);
}

/**
 * Fills the data from the business object into the form values
 *
 * @param business	object	business found
 */
function fillBusinessData(business)
{
		if (CONSOLE_BUS)
			console.log('fillBusinessData success '+ business);
//	$('#business_id').placeholder = '';
	$('#business_email', parent).placeholder = business.business_email;//'';
//	$('#active').checked = (business.active);
	$('#business_street_address', parent).placeholder = business.business_street_address;//'';
	$('#business_address_line2', parent).placeholder = business.business_address_line2;//'';
	$('#business_city', parent).placeholder = business.business_city;//'';
//	$('#date_first', parent).placeholder = business.date_first;//'';
//	$('#date_updated', parent).placeholder = business.date_updated;//'';
	$('#business_note', parent).placeholder = business.business_note;//'';
	$('#business_post_code', parent).placeholder = business.business_post_code;//'';
	$('#business_province', parent).placeholder = business.business_province;//'';
	$('#business_website', parent).placeholder = business.business_website;//'';
	$('#business_source', parent).placeholder = business.business_source;//'';

	$('#business_id', parent).value = business.business_id;
	$('#business_email', parent).value = business.business_email;
	$('#business_active', parent).checked = (business.business_active);
	$('#business_street_address', parent).value = business.business_street_address;
	$('#business_address_line2', parent).value = business.business_address_line2;
	$('#business_city', parent).value = business.business_city;
//	$('#date_first', parent).value = business.date_first;
//	$('#date_updated', parent).value = business.date_updated;
	$('#business_note', parent).value = business.business_note;
	$('#business_post_code', parent).value = business.business_post_code;
	$('#business_province', parent).value = business.business_province;
	$('#business_website', parent).value = business.business_website;
	$('#business_source', parent).value = business.business_source;
}

contacts = [];

function getBusinessContacts(id)
{
	var sql = 'SELECT * from yumatech_organise.contact WHERE business_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_BUS)
			console.log('getBusinessContacts success');
		contacts = JSON.parse(res.response);
		fillContactsData(contacts);
	};
	var onError = function(xhr,error){
		console.log('** xhr failure');
		console.log(xhr);
		console.log(error);
	};
	dbQuery(sql, onSuccess, onError, false);
}

/**
 * Retrieves the journal entry (for contact creation) -via AJAX
 * on success puts the retrieved date into the supplied onject
 *
 * @param int		id			Id of the selected contact
 * @param int		pos			Position id of contact card
 * @param object	business	Contact found
 */
function getContactCreatedDate(id, pos, contact)
{
	var sql = 'SELECT * from yumatech_organise.journal WHERE action_id=2 AND contact_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_BUS)
			console.log('getContactCreatedDate success');
		var entry = JSON.parse(res.response)[0];
		if (typeof entry === 'object' && entry.date) {
			if (CONSOLE_BUS)
				console.log('-inserting "'+entry.date+'" in #contact_created_'+pos);
//			contact.date_created = entry.date;
			$('#contact_created_'+pos, parent).placeholder = entry.date;//'';
			$('#contact_created_'+pos, parent).defaultValue = entry.date;
			$('#contact_created_'+pos, parent).value = entry.date;
		}
//		fillContactsData(contacts);
	};
	var onError = function(xhr,error){
		console.log('** xhr failure');
		console.log(xhr);
		console.log(error);
	};
	dbQuery(sql, onSuccess, onError, false);
}

/**
 * Retrieves the journal entry (for contact updated) -via AJAX
 * on success puts the retrieved date into the supplied onject
 *
 * @param int		id			Id of the selected contact
 * @param int		pos			Position id of contact card
 * @param object	business	Contact found
 */
function getContactLastEditDate(id, pos, contact)
{
	var sql = 'SELECT * from yumatech_organise.journal WHERE action_id=3 AND contact_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_BUS)
			console.log('getContactLastEditDate success');
		var entry = JSON.parse(res.response)[0];
		if (typeof entry === 'object' && entry.date) {
			if (CONSOLE_BUS)
				console.log('-inserting "'+entry.date+'" in #contact_updated_'+pos);
//			contact.date_updated = entry.date;
			$('#contact_updated_'+pos, parent).placeholder = entry.date;//'';
			$('#contact_updated_'+pos, parent).defaultValue = entry.date;
			$('#contact_updated_'+pos, parent).value = entry.date;
		}
//		fillContactsData(contacts);
	};
	var onError = function(xhr,error){
		console.log('** xhr failure');
		console.log(xhr);
		console.log(error);
	};
	dbQuery(sql, onSuccess, onError, false);
}

function eraseExtraContacts()
{
	const avail = $('.contact', parent);
	if (avail.length) {
		for (var i=1; i<avail.length; i++) {
			avail[0].parentElement.removeChild(avail[i]);
		}
	}
}

/**
 * Fills each business contact object data into the form, by removing all contacts and adding contact sections when required
 *
 * @param array of objects	contacts	List of contacts found
 */
function fillContactsData(contacts)
{
		if (CONSOLE_BUS)
			console.log('fillContactsData');
	/*
	 * [{business_id: 1,contact_id: 1,date_created: "2023-08-22 14:08:13",first: "",last: "",number: "416-489-8100",type_id: 1}]
	 */
	eraseExtraContacts();
	for (var i=0; i<contacts.length; i++) {
		if (i>0) {
			addContact();
		}
		getContactCreatedDate(contacts[i].contact_id, i, contacts[i]);
		getContactLastEditDate(contacts[i].contact_id, i, contacts[i]);
		$('#contact_id_'+i, parent).placeholder = contacts[i].contact_id;//'';
		$('#contact_first_'+i, parent).placeholder = contacts[i].contact_first;//'';
		$('#contact_last_'+i, parent).placeholder = contacts[i].contact_last;//'';
		$('#contact_type_'+i, parent).placeholder = contacts[i].contact_type;//'';
		$('#contact_number_'+i, parent).placeholder = contacts[i].contact_number;//'';
//		$('#contact_date_'+i, parent).placeholder = contacts[i].date_created;//'';
		$('#contact_id_'+i, parent).value = contacts[i].contact_id;
		$('#contact_first_'+i, parent).value = contacts[i].contact_first;
		$('#contact_last_'+i, parent).value = contacts[i].contact_last;
		$('#contact_type_'+i, parent).value = contacts[i].contact_type;
		$('#contact_number_'+i, parent).value = contacts[i].contact_number;
//		$('#contact_date_'+i, parent).value = contacts[i].date_created;
	}
}

/**
 * Adds a HTML sectiion for another business contact
 *
 * @param boolean	shift	Flag of whether to move the view port to the location
 */
function addContact(shift)
{
		if (CONSOLE_BUS)
			console.log('addContact');
	var copy = MakeCopy($('#div-contact_0', parent), {deleteBtn:{tag:"button",type:"button",name:"removeContact",innerText:"Remove",className:"btn deleteMe"}});
	removeClass($('.deleteMe', copy), 'hide');
	$('#div-contact_0', parent).parentElement.appendChild(copy);
	if (shift) {
		scrollIntoWindow(copy);
		var firstInput = $('input:not([type="hidden"])', copy)[0];
		if (firstInput) {
			firstInput.setAttribute('tabindex', '-1');
			firstInput.focus();
			firstInput.removeAttribute('tabindex')
		}
	}
}

/**
 * Remove a business contact section from the form
 *
 * @param object	event	Event fired when button with clicked
 */
function removeContact(event)
{
		if (CONSOLE_BUS)
			console.log('removeContact');
	event = event || window.event;
	var target = event.target || event.srcElement;
	var parent = target.parentElement;
	var ans = confirm('Are you sure to remove this business contact ('+ parent.id.replace(/^\D+/g, '')+ ')?');
	if (ans) {
		var ele = $('.contact_id', parent);
		if (!ele) {
			throw new Error('Cannot find contact_id element');
		} else {
			var sql = 'DELETE FROM yumatech_organise.contact WHERE contact_id='+ ele.value;
			console.log(sql);
			parent.parentElement.removeChild(parent);
		}
	}
}
