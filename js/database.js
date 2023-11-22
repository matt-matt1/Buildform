
var table, newDb, oldDb;
addEvent(window, 'load', load_database);

/**
 * Makes an AJAX request for the database names
 */
function getDbNames() {
	var myajax = new MyAjax(jsVar.ajaxURL, {
		method: 'POST',
		onSuccess: function(res, xhr){
			successGotNames(res);
		},
		onError: function(xhr,error){
			console.log('** xhr failure');
//			console.log('xhr:' + xhr);
//			console.log('xhr:');
			console.log(xhr);
//			console.log('error:' + error);
			console.log('* ^xhr error:');
			console.log(error);
		},
		data: "action=databases",
//		headers: null,
//		async: true,
//		async: false,
/*		log: true,
		showRequestHeaders: true,
		responseHeaders: function(headers) {
			if (headers) {
				console.log('Response headers:');
				for (let key in headers) {
					console.log(key, headers[key]);
				}
			}
		}*/
	});
	myajax.makeRequest();
}

/**
 * Fill the select HTML element with the AJAX response as options
 */
function successGotNames(response)
{
	if (response.response) {	// test if a valid response was received
		const data = JSON.parse(response.response);
		const sel = document.querySelectorAll(".list_dbs");
		for (var j=0; j<sel.length; j++) {	// each HTML select box
			for (var i=0; i<data.length; i++) {	// each database name
				const opt = document.createElement('OPTION');
				opt.innerHTML = data[i];
				opt.value = data[i];
				if (sel[j].dataset && sel[j].dataset.href)
					opt.dataset.href = sel[j].dataset.href + data[i];
				if (sel[j].dataset && sel[j].dataset.disable && sel[j].dataset.disable === data[i])
					opt.disabled = true;
				sel[j].appendChild(opt);	// add option (complete with database name) to select box
			}
			//reduceSelect(sel[j]);	// convert HTML select box to a custom-select element
//			new CustomSelect(sel[j]);
		}
/*		const sel2 = document.querySelectorAll(".list_dbs .custom-select-trigger");
		for (var j=0; j<sel2.length; j++) {	// apply a selection event to each custom-select
			addEvent(sel2[j], "custom-select.selected", onSelectDb);
		}*/
	} else {
		if (typeof response === "object")
			console.log('successFilled: response='+ JSON.stringify(response));
		else
			console.log('successFilled: response='+ response);
	}
}

function onSelectDb(e)
{
	let proceed;
	let proceed;
	if (!e.target || !e.target.parentElement || !e.target.parentElement.dataset || !e.target.selected || !e.target.selected.dataset)
		console.log('successFilled: error with "' + e.target + '"');
	else {
		table = e.target.parentElement.dataset.table;
		newDb = e.target.selected.dataset.value;
		oldDb = e.target.parentElement.dataset.disable;
		// TODO: show modal dialog to confirm move
		modal.element.querySelector('.modal-title span').innerHTML = 'Do you really want to...?';
		modal.element.querySelector('.modal-body').innerHTML = 'Move table "' + table + '" into database "' + newDb + '"';
		modal.element.querySelector('.modal-dialog').className = 'modal-dialog';
		if (proceed = modal.element.querySelector('button[value=proceed]')) {
			proceed/*modal.element.querySelector('button[value=proceed]')*/.innerText = 'Move';
			proceed/*modal.element.querySelector('button[value=proceed]')*/.value = 'move';
		}
		modal.element.querySelector('button[value=move]').href = e.target.selected.dataset.href;
		modal.instance.show();
		//moveToDb('table='+ e.target.parentElement.dataset.table+ '&db='+ e.target.selected.dataset.value+ '&was='+ e.target.parentElement.dataset.disable);
	}
}

function modalAction_NotCancel(btn)
{
	if (modal.instance)
		modal.instance.hide();
	if (btn.href)
		window.location.href = btn.href;
}

/**
 * When the user presses the modal delete button
 */
//function modalAction_proceed(btn)
/*function modalAction_deletet(btn)
{
	if (modal.instance)
		modal.instance.hide();
	if (btn.href)
		window.location.href = btn.href;
}
*/
/**
 * When the user presses the modal move button
 */
//function modalAction_proceed(btn)
/*function modalAction_move(btn)
{
	modalAction_deletet(btn);
//	moveToDb('table='+ table+ '&db='+ newDb+ '&was='+ oldDb);
}
*/
/**
 * Performs the AJAX request on selection (of a custom-select)
 */
function moveToDb(data)
{
	const id = 0;
	var myajax = new MyAjax(jsVar.ajaxURL, {
		method: 'POST',
		onSuccess: function(res, xhr){
			successMoveToDb(res);
		},
		onError: function(xhr,error){
			console.log('** xhr failure');
			console.log(xhr);
			console.log('* ^xhr error:');
			console.log(error);
		},
		data: "action=movetodb&"+ data,
	});
	myajax.makeRequest();
}

/**
 * Handle the AJAX selection response
 */
function successMoveToDb(response)
{
			if (response.response) {
				console.log(response.response);
/*				const data = JSON.parse(res.response);
				const sel = document.querySelectorAll(".list_dbs");*/
/*				for (var i=0; i<data.length; i++) {
					const opt = document.createElement('OPTION');
					opt.innerHTML = data[i];//res.response;
					opt.value = data[i];//res.response;
					for (var j=0; j<sel.length; j++) {
						sel[j].appendChild(opt);
					}
//				var put = document.getElementsByClassName("list_dbs");
				}*/
/*				for (var j=0; j<sel.length; j++) {
					for (var i=0; i<data.length; i++) {
						const opt = document.createElement('OPTION');
						opt.innerHTML = data[i];//res.response;
						opt.value = data[i];//res.response;
						sel[j].appendChild(opt);
					}
					reduceSelect(sel[j]);
				}*/
/*			} else {
				if (typeof res === "object")
					document.getElementById("txtHint").innerHTML = JSON.stringify(res);
				else
					document.getElementById("txtHint").innerHTML = res;*/
			}
}

/**
 * Onload function
 */
function load_database()
{
//	getDbNames();
	const sel = document.querySelectorAll(".list_dbs");
	for (var j=0; j<sel.length; j++) {	// each HTML select box
		for (var i=0; i<sel[j].options.length; i++) {	// each database name
			const opt = sel[j].options[i];
			if (sel[j].dataset && sel[j].dataset.href && !opt.disabled)
				opt.dataset.href = sel[j].dataset.href + opt.value;
		}
		new CustomSelect(sel[j]);
	}
	const sel2 = document.querySelectorAll(".list_dbs .custom-select-trigger");
	for (var j=0; j<sel2.length; j++) {	// apply a selection event to each custom-select
		addEvent(sel2[j], "custom-select.selected", onSelectDb);
	}
}
