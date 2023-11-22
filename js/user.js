/**
 * JavaScript to manage the user section
 *
 * submits sql to database via AJAX and processes the response
 *
 * @author Yumatechnical@gmail.com
 */
/**
 * client-side operations when the whole document is loaded
 */
var parent = 'undefined';// = null;
//const CONSOLE_USR = 1;
const CONSOLE_USR = false;
addEvent(window, 'load', load_user);
/**
 * Loads the search data
 */
function load_user()
{
	getUserData();
	getUserContactsData();
}

/**
 * Establishes the events
 */
function setEvents()
{
//	var main = parentOfType(this.put, $('fieldset');
//	main.style.backgroundColor = 'lightblue';
/*	addEvent($('#add-user'), 'click', function(e){
		e.preventDefault();
		window.location = jsVar.BASE + 'user/add';
	});*/
	addEvent($('#remove-user', parent), 'click', function(e){
		e.preventDefault();
		window.location = jsVar.BASE + 'user/remove';
	});
	addEvent($('#add-contact', parent), 'click', function(e){
		e.preventDefault();
		addContact(true);
	});
	addEvent(document.body, 'click', function(e){
		e = e || window.event;
		var target = e.target || e.srcElement;
		if (target.className.indexOf('deleteMe') !== -1) {
			e.preventDefault();
			removeContact(e);
		}
	});
	addEvent($('input[type="reset"]', parent), 'click', function(){
		resetForm(this.form);
		eraseExtraContactBoxes();
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
 * Retrieves a list of users from the database via AJAX
 * on success begins the autocomplete operation, sets events, show the pane
 */
var users = [];
function getUserData()
{
	var sql = 'SELECT * from yumatech_organise.user';
	//var sql = '<?=user::getAll('onlySQL'=>true?>';
	var onSuccess = function(res, xhr){
		if (CONSOLE_USR)
			console.log('getUserData success');
		users = JSON.parse(res.response);
		parent = $('#user_name').form;
		const acs = $('.autocomplete-users input');
		//const acs = $('input[data-search-in=users]');
		for (var i=0; i<acs.length; i++) {
			//data-search-in="users" data-search-as="user_first"
			new Autocomplete({
				element: $('#'+acs[i].id, parent),
				searchTerm: (acs[i].dataset.searchAs) ? acs[i].dataset.searchAs : acs[i].id,
				resultId: "user_id",
				log: true,
				suggestions: JSON.parse(res.response),
				onSelect: selectedUser
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
 * Retrieves a list of user-contacts from the database via AJAX
 * on success begins the autocomplete operation
 */
var contacts = [];
function getUserContactsData()
{
	var sql = 'SELECT * from yumatech_organise.contact WHERE `user_id` IS NOT null';
	//var sql = '<?=user::getAll('onlySQL'=>true?>';
	var onSuccess = function(res, xhr){
		contacts = JSON.parse(res.response);
		if (CONSOLE_USR)
			console.log('getUserContactsData success '+contacts.length);
		parent = $('#user_name').form;
		const acs = $('.autocomplete-contacts input');
		for (var i=0; i<acs.length; i++) {
			new Autocomplete({
				element: $('#'+acs[i].id, parent),
				searchTerm: acs[i].id,
				resultId: "contact_id",
				log: true,
				suggestions: JSON.parse(res.response),
				onSelect: selectedUser
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
 * Fires when the user selects a user from the suggestion list
 *
 * @param {int} id - Id of the selected user
 * @param {object} user - User found
 */
function selectedUser(id, user)
{
	if (CONSOLE_USR)
		console.log('selectedUser');
	parent = parentOfType(this.put, 'form');
	getUserCreatedDate(id, user);
	getUserLastEditDate(id, user);
	getUserContacts(user.user_id);
	var main = parentOfType(this.put, 'fieldset');
	main.style.backgroundColor = 'lightblue';
	fillUserData(user);
	if (contacts.length) {
		fillContactsData(contacts);
	}
}

/**
 * Retrieves the journal entry (for user creation) -via AJAX
 * on success puts the retrieved date into the supplied onject
 *
 * @param {int} id - Id of the selected user
 * @param {object} user - User found */
function getUserCreatedDate(id, user)
{
	var sql = 'SELECT * from yumatech_organise.journal WHERE action_id=2 AND user_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_USR)
			console.log('getUserCreatedDate success');
		var entry = JSON.parse(res.response)[0];
		if (typeof entry === 'object' && entry.date) {
//			user.date_first = entry.date;
			$('#user_created', parent).placeholder = entry.date;//'';
			$('#user_created', parent).defaultValue = entry.date;
			$('#user_created', parent).value = entry.date;
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
 * Retrieves the journal entry (for user updated) -via AJAX
 * on success puts the retrieved date into the supplied onject
 *
 * @param id	int		Id of the selected user
 * @param user	object	User found
 */
function getUserLastEditDate(id, user)
{
	var sql = 'SELECT * from yumatech_organise.journal WHERE action_id=3 AND user_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_USR)
			console.log('getUserLastEditDate success');
		var entry = JSON.parse(res.response)[0];
		if (typeof entry === 'object' && entry.date && typeof user === 'object') {
//			user.date_updated = entry.date;
			$('#user_updated', parent).placeholder = entry.date;//'';
			$('#user_updated', parent).defaultValue = entry.date;
			$('#user_updated', parent).value = entry.date;
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
 * Fills the data from the user object into the form values
 *
 * @param user	object	user found
 */
function fillUserData(user)
{
	if (CONSOLE_USR)
		console.log('fillUserData success '+ user);
//	$('#user_id').placeholder = '';
	$('#user_email', parent).placeholder = user.user_email;//'';
//	$('#active').checked = (user.active);
	$('#user_street_address', parent).placeholder = user.user_street_address;//'';
	$('#user_address_line2', parent).placeholder = user.user_address_line2;//'';
	$('#user_city', parent).placeholder = user.user_city;//'';
//	$('#date_first', parent).placeholder = user.date_first;//'';
//	$('#date_updated', parent).placeholder = user.date_updated;//'';
	$('#user_note', parent).placeholder = user.user_note;//'';
	$('#user_post_code', parent).placeholder = user.user_post_code;//'';
	$('#user_province', parent).placeholder = user.user_province;//'';
	$('#user_website', parent).placeholder = user.user_website;//'';
	$('#user_source', parent).placeholder = user.user_source;//'';

	$('#user_id', parent).value = user.user_id;
	$('#user_email', parent).value = user.user_email;
	$('#user_active', parent).checked = (user.user_active);
	$('#user_street_address', parent).value = user.user_street_address;
	$('#user_address_line2', parent).value = user.user_address_line2;
	$('#user_city', parent).value = user.user_city;
//	$('#date_first', parent).value = user.date_first;
//	$('#date_updated', parent).value = user.date_updated;
	$('#user_note', parent).value = user.user_note;
	$('#user_post_code', parent).value = user.user_post_code;
	$('#user_province', parent).value = user.user_province;
	$('#user_website', parent).value = user.user_website;
	$('#user_source', parent).value = user.user_source;
}

/**
 * Retrieves a list of user contacts from the database via AJAX
 *
 * @param id		int		id of the selected user
 */
var selectedUserContacts = [];
function getUserContacts(id)
{
	var sql = 'SELECT * from yumatech_organise.contact WHERE user_id='+ id;
	var onSuccess = function(res, xhr){
		selectedUserContacts = JSON.parse(res.response);
		if (CONSOLE_USR)
			console.log('getUserContacts success '+selectedUserContacts.length);
		fillContactsData(selectedUserContacts);
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
 * @param {int}		id		Id of the selected contact
 * @param {int}		pos		Position id of contact card
 * @param {object}	user	Contact found
 */
function getContactCreatedDate(id, pos, contact)
{
	var sql = 'SELECT * from yumatech_organise.journal WHERE action_id=2 AND contact_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_USR)
			console.log('getContactCreatedDate success');
		var entry = JSON.parse(res.response)[0];
		if (typeof entry === 'object' && entry.date) {
			if (CONSOLE_USR)
				console.log('-inserting "'+entry.date+'" in #contact_created_'+pos);
//			contact.date_created = entry.date;
			$('#contact_created_'+pos, parent).placeholder = entry.date;//'';
			$('#contact_created_'+pos, parent).defaultValue = entry.date;
			$('#contact_created_'+pos, parent).value = entry.date;
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
 * Retrieves the journal entry (for contact updated) -via AJAX
 * on success puts the retrieved date into the supplied onject
 *
 * @param {int}		id		Id of the selected contact
 * @param {int}		pos		Position id of contact card
 * @param {object}	user	Contact found
 */
function getContactLastEditDate(id, pos, contact)
{
	var sql = 'SELECT * from yumatech_organise.journal WHERE action_id=3 AND contact_id='+ id;
	var onSuccess = function(res, xhr){
		if (CONSOLE_USR)
			console.log('getContactLastEditDate success');
		var entry = JSON.parse(res.response)[0];
		if (typeof entry === 'object' && entry.date) {
			if (CONSOLE_USR)
				console.log('-inserting "'+entry.date+'" in #contact_updated_'+pos);
//			contact.date_updated = entry.date;
			$('#contact_updated_'+pos, parent).placeholder = entry.date;//'';
			$('#contact_updated_'+pos, parent).defaultValue = entry.date;
			$('#contact_updated_'+pos, parent).value = entry.date;
		}
	};
	var onError = function(xhr,error){
		console.log('** xhr failure');
		console.log(xhr);
		console.log(error);
	};
	dbQuery(sql, onSuccess, onError, false);
}

function eraseExtraContactBoxes()
{
	const avail = $('.contact', parent);
	if (avail.length) {
		for (var i=1; i<avail.length; i++) {
			avail[0].parentElement.removeChild(avail[i]);
		}
	}
}

/**
 * Fills each user contact object data into the form, by removing all contacts and adding contact sections when required
 *
 * @param array of objects	contacts	List of contacts found
 */
function fillContactsData(contacts)
{
	if (CONSOLE_USR)
		console.log('fillContactsData');
	/*
	 * [{user_id: 1,contact_id: 1,date_created: "2023-08-22 14:08:13",first: "",last: "",number: "416-489-8100",type_id: 1}]
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
		$('#contact_id_'+i, parent).value = contacts[i].contact_id;
		$('#contact_first_'+i, parent).value = contacts[i].contact_first;
		$('#contact_last_'+i, parent).value = contacts[i].contact_last;
		$('#contact_type_'+i, parent).value = contacts[i].contact_type;
		$('#contact_number_'+i, parent).value = contacts[i].contact_number;
	}
}

/**
 * Adds a HTML sectiion for another user contact
 *
 * @param boolean	shift	Flag of whether to move the view port to the location
 */
function addContact(shift)
{
	if (CONSOLE_USR)
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
 * Remove a user contact section from the form
 *
 * @param object	e	Event fired when button with clicked
 */
function removeContact(e)
{
	if (CONSOLE_USR)
		console.log('removeContact');
	e = e || window.event;
	var target = e.target || e.srcElement;
	var parent = target.parentElement;
	var ans = confirm('Are you sure to remove this user contact ('+ parent.id.replace(/^\D+/g, '')+ ')?');
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
