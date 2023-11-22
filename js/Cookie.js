function Cookie()
{
/*	var props = {
		method: 'GET',			// HTTP method to send request
//		url: '.',				// URL destination for the data
		onSuccess: null,//function(response) {},		// Function ref to execute if the request was successful
		onError: null,//function(request, status, error) {},			// Function ref to execute if a connection error occurs during the request
		onProgress: null,//function(progress, request) {},
		onComplete: null,//function(request) {},//function(event, request) {},
		onTimeout: null,//function(event, request) {},	// event when progression is terminated due to preset time expiring
		beforeSend: null,//function(request) {},//function(event, request) {},
		afterSend: null,//function(request) {},//function(event, request) {},
		onAbort: null,//function(request) {},//function(event, request) {},
		headers: [],			// headers to send eg. headers: [{"Accept": "application/json"}, {"X-Powered-by": "ACME Inc."}],
		responseHeaders: null,//function(headers) {},
		timeout: null,			// maximum amount of request time in milliseconds (not applicable for a synchronous connection)(default 5)
		max: null,				// (default 100)
		forceMIME: null,		// specifies a MIME type other than the one provided by the server to be used instead eg. "text/plain"
		async: true,			// if false (synchronous) the request will wait for response before continuing
		log: false,				// whether to make notes in the console
		showRequestHeaders: false,	// whether to log the request headers also
		username: '',			// username, if required for http basic authorisation
		password: '',			// password, if required for http basic authorisation - plain text
		responseAs: '',			// preferred format of the response data - arraybuffer/blob/document/json/text(default)/ms-stream (not applicable for a synchronous connection)
		use: '',				// default "XHR"('') also "FETCH"
		cache: true,			// don't cache results
		connection: false,		// default ==> 'Keep-Alive'
		compatible: false,		// default ==> 'IE=Edge'
		cors: false,			// default ==> '*'
		accept: false,			// 
		processData: true,		// if true, transforms data into www-encoded
		contentType: true,		// Content-Type header, if false then none is set
		enctype: null,			// form post data using this encoding type
		data: {}				// data to send
	};

	for (var p in props)
	{
		//console.log('MyAJax - '+ p+ ':'+ typeof config[p]);
		//this[p] = (typeof config[p] === 'undefined') ? props[p] : config[p];
		this[p] = (typeof config[p] === undefined || typeof config[p] === "undefined") ? props[p] : config[p];
	}*/
}

Cookie.prototype.getAll = function ()
{
	return document.cookie;
}

Cookie.prototype.test = function ()
//function testCookie()
{
	if (navigator.cookieEnabled) return true;
	document.cookie = "cookietest=1";
	var ret = document.cookie.indexOf("cookietest=") != -1;
	document.cookie = "cookietest=1; expires=Thu, 01-Jan-1970 00:00:01 GMT";
	return ret;
}

Cookie.prototype.get = function (cname)
//function getCookie(cname)
{
	var name = cname + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var ca = decodedCookie.split(';');
	for(var i = 0; i <ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) === ' ') {
			c = c.substring(1);
		}
		if (c.indexOf(name) === 0) {
			return c.substring(name.length, c.length);
		}
	}
	return "";
}
