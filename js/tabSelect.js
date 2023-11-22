addEvent(window, 'load', load_tabSelect);
//var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
//var triggerTabList = [].slice.call($('.nav-tabs> .nav-item> .nav-link'))
var tabs = $('button[data-bs-toggle="tab"]');
/*tabs.forEach(function (triggerEl) {
	var tabTrigger = new bootstrap.Tab(triggerEl)
	addEvent(tabTrigger, 'click', function (event) {
		console.log('clicked');
		console.log(event.target);
	})
})*/
tabs.forEach(function (tab) {
	addEvent(tab, 'show.bs.tab', function (event) {
//		console.log('showed: '+ event.target.id);
//		console.log(event.target);
//		const ul = event.target.parentElement.parentElement;
		const list = (typeof event.target.dataset === 'undefined' || typeof event.target.dataset.list === 'undefined') ? '' : event.target.dataset.list;
		//var myajax = new MyAjax(jsVar.ajaxURL, {
		new MyAjax(jsVar.ajaxURL, {
			method: 'POST',
			onSuccess: function(res, xhr){
	///			console.log('** xhr success');
//				console.log(xhr);
//				console.log(res.response);
			//	console.log(res.data);
				//self.data = JSON.parse(res.response);
	//			reference = JSON.parse(res.response);
//				console.log(JSON.parse(res.response));
			},
			onError: function(xhr,error){
				console.log('** xhr failure');
				console.log(xhr);
				console.log(error);
			},
			data: "action=notify&tab_id="+ event.target.id+ "&tab_list="+ list,
//			log: true,
		});
		var myajax = new MyAjax(jsVar.ajaxURL, {
			method: 'GET',
			onSuccess: function(res, xhr){
				console.log('** xhr success');
				console.log(xhr);
				console.log(res.response);
				console.log(res.data);
				//self.data = JSON.parse(res.response);
	//			reference = JSON.parse(res.response);
//				console.log(JSON.parse(res.response));
			},
			onError: function(xhr,error){
				console.log('** xhr failure');
				console.log(xhr);
				console.log(error);
			},
			data: {action: "notify", "tab": event.target.id},
			log: true,
			showRequestHeaders: true,
			responseHeaders: function(headers) {
				if (headers) {
					console.log('Response headers:');
					for (let key in headers) {
						console.log(key, headers[key]);
					}
				}
			}
		});
		myajax.makeRequest();
		const pane = $('#'+ event.target.id+ '-pane');
		var myForm = $('form', pane);
		if ([].slice.call(myForm).length) {
			myForm = myForm[0];
		}
		var myTab = document.createElement('input');
		myTab.type = 'hidden';
		myTab.name = 'tab';
		myTab.value = event.target.id;
		myForm.appendChild(myTab);
	})
})
/*triggerTabList.forEach(function (triggerEl) {
	var tabTrigger = new bootstrap.Tab(triggerEl)
	triggerEl.addEventListener('click', function (event) {
		event.preventDefault()
		tabTrigger.show()
	})
})*/
function load_tabSelect()
{
	addClass($('.tab-pane.active'), 'show')
}
