addEvent(window, 'load', load_login);
function load_login()
{
	var cookie = new Cookie;
	var myajax = new MyAjax(jsVar.ajaxURL, {
		//method: 'GET',
		method: 'POST',
		onSuccess: function(res, xhr){
			console.log('** xhr success');
			console.log(xhr);
			console.log(res.response);
			console.log(res.data);
			//self.data = JSON.parse(res.response);
//			reference = JSON.parse(res.response);
//			console.log(JSON.parse(res.response));
		},
		onError: function(xhr,error){
			console.log('** xhr failure');
			console.log(xhr);
			console.log(error);
		},
		data: {action: "cookie", cookiesEnabled: cookie.test},
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
}
