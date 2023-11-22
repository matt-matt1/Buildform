/*
 * example usage:
var dims = Elem.getWindowDims();
var ajax = new MyAjax('saveForm.php', {
	method: 'POST',
	data: {width: dims.width, height: dims.height},
	onSuccess: function(response) {
		console.log(response);
	},
	log: true
});
ajax.makeRequest();
*/
/* 
 * https://medium.com/swlh/using-ajax-and-json-in-javascript-e81dc7fb4322
 * const APIURL = "http://localhost:3000";
const subscribe = data => {	//	const subscribe = function(data) {
  return fetch(`${APIURL}/subscribers`, {
    method: "POST",
    mode: "cors",
    cache: "no-cache",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify(data)
  }).then(response => response.json());	//	}).then(function(response) { response.json(); });
};
subscribe({
      firstName,
      lastName,
      email
    }).then(response => {
      alert(`${response.firstName} ${response.lastName} has subscribed`);
    });
 */
const HttpStatusFirst = {
	1: 'Information response',
	2: "Successful response",
	3: 'Redirection message',
	4: 'Client error response',
	5: "Server error response"
};
//console.log(HttpStatusFirst);
const HttpStatus = {
//	1: {kind:'Information responses': [
		100: {'name': 'Continue', 'desc': "This means the client should continue with its request. The server returns this response code to inform the client that the initial part of the request has been received and has not yet been rejected by the server."},
		101: {'name': 'Switching Protocols', 'desc': "This means the requester has asked the server to switch protocols and the server is acknowledging that it will do so."},
		102: {'name': 'Processing (WebDAV)', 'desc': "This code indicates that the server has received and is processing the request, but no response is available yet."},
		103: {'name': 'Early Hints', 'desc': "This status code is primarily intended to be used with the Link header, letting the user agent start preloading resources while the server prepares a response."},
	
//		"Successful responses": [
		200: {'name': 'OK', 'desc': "The server successfully processed the request. Generally, this means that the server provided the requested page."},
		201: {'name': 'Created', 'desc': "This means the request was successful and the server created a new resource."},
		202: {'name': 'Accepted', 'desc': "This means the server has accepted the request for processing, but the processing has not been completed."},
		203: {'name': 'Non-Authoritative Information', 'desc': "This means the server successfully processed the request, but is returning information that may be from another source."},
		204: {'name': 'No Content', 'desc': "This means the server successfully processed the request, but isn't returning any content."},
		205: {'name': 'Reset Content', 'desc': "This means the server successfully processed the request, but is not returning any content. Unlike a 204 response, this response requires that the requester reset the document view."},
		206: {'name': 'Partial Content', 'desc': "The server is delivering only part of the resource due to a range header sent by the client."},
		207: {'name': 'Multi-Status (WebDAV)', 'desc': "Conveys information about multiple resources, for situations where multiple status codes might be appropriate."},
		208: {'name': 'Already Reported (WebDAV)', 'desc': "Used inside a <dav:propstat> response element to avoid repeatedly enumerating the internal members of multiple bindings to the same collection."},
		226: {'name': 'IM Used (HTTP Delta encoding)', 'desc': "The server has fulfilled a GET request for the resource, and the response is a representation of the result of one or more instance-manipulations applied to the current instance."},

//		'Redirection messages': [
		300: {'name': 'Multiple Choices', 'desc': "Indicates multiple options for the resource that the client may follow. It, for instance, could be used to present different format options for video or list files with different extensions."},
		301: {'name': 'Moved Permanently', 'desc': "The requested page has been permanently moved to a new location. When the server returns this response, it automatically forwards the requester to the new location."},
		302: {'name': 'Found', 'desc': "This means the requested resource resides temporarily under a different location, but the requester should continue to use the original location for future requests."},
		303: {'name': 'See Other', 'desc': "This means the response to the request can be found under a different location using a GET method."},
		304: {'name': 'Not Modified', 'desc': "Indicates the requested resource hasn't been modified since the last request."},
		305: {'name': 'Use Proxy', 'desc': "This means the requester can only access the requested resource using a proxy. Many HTTP clients (such as Mozilla and Internet Explorer) do not correctly handle responses with this status code, primarily for security reasons."},
		306: {'name': 'Switch Proxy', 'desc': "No longer used."},
		307: {'name': 'Temporary Redirect', 'desc': "This means the requested resource resides temporarily under a different location, but the requester should continue to use the original location for future requests. In contrast to 302, the request method should not be changed when reissuing the original request. For instance, a POST request must be repeated using another POST request."},
		308: {'name': 'Permanent Redirect (experimental)', 'desc': "This means the request, and all future requests should be repeated using another URL. 307 and 308 (as proposed) parallel the behaviours of 302 and 301, but do not allow the HTTP method to change."},
		
//		'Client error responses': [
		400: {'name': 'Bad Request', 'desc': "This means the request cannot be fulfilled due to bad syntax."},
		401: {'name': 'Unauthorized', 'desc': "The request requires user authentication. The server might return this response for a page behind a login."},
		402: {'name': 'Payment Required', 'desc': "This code is reserved for future use. The original intention was that this code might be used as part of some form of digital cash or micropayment scheme, but that has not happened."},
		403: {'name': 'Forbidden', 'desc': "The request was a valid request, but the server is refusing to respond to it. Unlike a 401 Unauthorized response, authenticating will make no difference."},
		404: {'name': 'Not Found', 'desc': "This means the server can't find the requested page. For instance, the server often returns this code if the request is for a page that doesn't exist on the server."},
		405: {'name': 'Method Not Allowed', 'desc': "This means the method specified in the request is not allowed. For example, using GET on a form which requires data to be presented via POST."},
		406: {'name': 'Not Acceptable', 'desc': "This means the requested resource can't respond with the content characteristics requested."},
		407: {'name': 'Proxy Authentication Required', 'desc': "This code is similar to 401 (Unauthorized), but indicates that the client must first authenticate itself with the proxy."},
		408: {'name': 'Request Timeout', 'desc': "The server timed out waiting for the request. This means the client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time."},
		409: {'name': 'Conflict', 'desc': "This means the request could not be completed due to a conflict with the current state of the resource."},
		410: {'name': 'Gone', 'desc': "Indicates that the resource requested is no longer available at the server and will not be available again."},
		411: {'name': 'Length Required', 'desc': "This means the server refuses to accept the request without a defined Content-Length."},
		412: {'name': 'Precondition Failed', 'desc': "This means the server does not meet one of the preconditions that the requester put on the request."},
		413: {'name': 'Request Entity Too Large', 'desc': "This means the server is refusing to process a request because it is larger than the server is willing or able to process."},
		414: {'name': 'Request-URI Too Long', 'desc': "This means the server is refusing to process the request because the Request-URI (typically, a URL) is longer than the server is willing to interpret."},
		415: {'name': 'Unsupported Media Type', 'desc': "The server is refusing to process the request because the entity of the request is in a format which is not supported by the server or requested resource."},
		416: {'name': 'Requested Range Not Satisfiable', 'desc': "The server returns this status code if the request is for a range not available for the selected resource."},
		417: {'name': 'Expectation Failed', 'desc': "This means the server cannot meet the requirements of the Expect request-header field."},
		418: {'name': "I'm a teapot", 'desc': "The server refuses the attempt to brew coffee with a teapot."},
		421: {'name': 'Misdirected Request', 'desc': "The request was directed at a server that is not able to produce a response. This can be sent by a server that is not configured to produce responses for the combination of scheme and authority that are included in the request URI."},
		422: {'name': 'Unprocessable Entity (WebDAV)', 'desc': "The request was well-formed but was unable to be followed due to semantic errors."},
		423: {'name': 'Locked (WebDAV)', 'desc': "The resource that is being accessed is locked."},
		424: {'name': 'Failed Dependency (WebDAV)', 'desc': "The request failed due to failure of a previous request."},
		425: {'name': 'Too Early Experimental', 'desc': "Indicates that the server is unwilling to risk processing a request that might be replayed."},
		426: {'name': 'Upgrade Required', 'desc': "The server refuses to perform the request using the current protocol but might be willing to do so after the client upgrades to a different protocol. The server sends an Upgrade header in a 426 response to indicate the required protocol(s)."},
		428: {'name': 'Precondition Required', 'desc': "The origin server requires the request to be conditional. This response is intended to prevent the 'lost update' problem, where a client GETs a resource's state, modifies it and PUTs it back to the server, when meanwhile a third party has modified the state on the server, leading to a conflict."},
		429: {'name': 'Too Many Requests', 'desc': "The user has sent too many requests in a given amount of time ('rate limiting')."},
		431: {'name': 'Request Header Fields Too Large', 'desc': "The server is unwilling to process the request because its header fields are too large. The request may be resubmitted after reducing the size of the request header fields."},
		451: {'name': 'Unavailable For Legal Reasons', 'desc': "The user agent requested a resource that cannot legally be provided, such as a web page censored by a government."},

//		"Server error responses": [
		500: {'name': 'Internal Server Error', 'desc': "This means the server encountered an unexpected condition which prevented it from processing the request."},
		501: {'name': 'Not Implemented', 'desc': "This means the server either does not recognize the request method, or it lacks the ability to handle the request."},
		502: {'name': 'Bad Gateway', 'desc': "This means the server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request."},
		503: {'name': 'Service Unavailable', 'desc': "This means the server is currently unable to handle the request because it is overloaded or down for maintenance. Generally, this is a temporary state."},
		504: {'name': 'Gateway Timeout', 'desc': "This means the server, while acting as a gateway or proxy, did not receive a timely response from the upstream server."},
		505: {'name': 'HTTP Version Not Supported', 'desc': "This means the server does not support, or refuses to support, the HTTP protocol version used in the request."},
		506: {'name': 'Variant Also Negotiates', 'desc': "The server has an internal configuration error: the chosen variant resource is configured to engage in transparent content negotiation itself, and is therefore not a proper end point in the negotiation process."},
		507: {'name': 'Insufficient Storage (WebDAV)', 'desc': "The method could not be performed on the resource because the server is unable to store the representation needed to successfully complete the request."},
		508: {'name': 'Loop Detected (WebDAV)', 'desc': "The server detected an infinite loop while processing the request."},	
		510: {'name': 'Not Extended', 'desc': "Further extensions to the request are required for the server to fulfill it."},
		511: {'name': 'Network Authentication Required', 'desc': "Indicates that the client needs to authenticate to gain network access."}
		//]}
	};
//console.log('---');
//console.log(HttpStatus);
		const readyStates = [	// XMLHTTPRequest. <status>
			{'state': 'UNSENT', 'desc': "An XMLHttpRequest object has been created, but the open() method hasn't been called yet (i.e. request not initialized)."},
			{'state': 'OPENED', 'desc': "The open() method has been called (i.e. server connection established)."},
			{'state': 'HEADERS_RECEIVED', 'desc': "The send() method has been called (i.e. server has received the request)."},
			{'state': 'LOADING', 'desc': "The server is processing the request."},
			{'state': 'DONE', 'desc': "The request has been processed and the response is ready."}
		];
//console.log(readyStates);
/**
 * From https://stackoverflow.com/questions/2557247/easiest-way-to-retrieve-cross-browser-xmlhttprequest
 * Returns false on most modern browsers; on Microsoft browsers returns theit version number.
 * Includes Internet Explorer, Edge and Trident browsers
 */
function detectIE() {
	var ua = window.navigator.userAgent,
		msie = ua.indexOf('MSIE '),
		trident = ua.indexOf('Trident/'),
		edge = ua.indexOf('Edge/');
	if (msie > 0)		{	return parseInt(ua.substring( msie+5, ua.indexOf('.', msie+5) ), 10);	}
	if (trident > 0)	{	var rv = ua.indexOf('rv:'); return parseInt(ua.substring( rv + 3, ua.indexOf('.', rv) ), 10);	}
	if (edge > 0)		{	return parseInt(ua.substring( edge+5, ua.indexOf('.', edge+5) ), 10);	}
	return false;
}
/**
 * Based on http://www.samantsingh.com/blogs/post/how-to-make-ajax-calls-using-vanilla-javascript/
 * Returns a new, cross-browser XMLHttpRequest object (or ActiveXObject)
 */
function createXHR() {
	var xmlHttp = null;
	if (window.XDomainRequest && detectIE()) {
		xlmHttp = new XDomainRequest();
		/*
		xdr.open("GET", url, false);
		xdr.onload = function () {
			var res = JSON.parse(xdr.responseText);
			if (res == null || typeof (res) == 'undefined') {
				res = JSON.parse(data.firstChild.textContent);
			}
			publishData(res);
		}
		xdr.send();
		*/
	} else {
		try {
			xmlHttp = new XMLHttpRequest();
			/*
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4) {
					if (xmlhttp.status == 200 || xmlhttp.status == 304) {
						publishData(JSON.parse(xmlhttp.responseText));
					} else {
						setTimeout(function(){ console.log("Request failed!") }, 0);
					}
				}
			}
			xmlhttp.open("GET", url, true);
			xmlhttp.send();
			*/
		} catch (e) {
			var versions = ["MSXML2.XmlHttp.6.0", "MSXML2.XmlHttp.3.0", "Msxml2.XMLHTTP"];
			for (var i = 0, length = versions.length; i < length; i++) {
				try {
					xmlHttp = new ActiveXObject(versions[i]);
				} catch (e) {
					xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
				}
			}
		}
	}
	return xmlHttp;
}
/**
 * Makes CORS (Ajax) call
 * eg.
 * createCORSRequest({
 * 'method': 'POST',
 * 'url': '/asdf.php.,
 * 'callbackSuccess': {
 *	document.body.style.backgroundColor = 'green';
 * },
 * 'callbackError': {
 *	document.body.style.backgroundColor = 'red';
 * },
 * 'data': 'qwerty=zxy',
 * 'headers': {'key': 'Content-Type', 'value': 'text/plain'},{'key': 'Cache-Control', 'value': 'no-cache'},
 * 'callbackProgress': {},
 * 'callbackTimeout': {},
 * 'callbackPreSuccess': {},
 * 'callbackPostSuccess': {},
 * 'responseType': 'json'
 * })
 * onabort: null
onerror: null
onload: null
onloadend: null
onloadstart: null
onprogress: null
onreadystatechange: null
ontimeout: null
readyState: 0
response: ""
responseText: ""
responseType: ""
responseURL: ""
responseXML: null
status: 0
statusText: ""
timeout: 0
upload: XMLHttpRequestUpload {onloadstart: null, onprogress: null, onabort: null, onerror: null, onload: null, …}
withCredentials: false
 */
/*
//function createCORSRequest(method, url, callbackSuccess, callbackError, data) {
//function createCORSRequest(arr){	//arr = {'method', 'url', 'callbackSuccess', 'callbackError', 'data', 'headers'}
function createCORSRequest({method, url, callbackSuccess, callbackError, data, headers, callbackProgress, callbackTimeout, callbackPreSuccess, callbackPostSuccess, callbackAbort, responseType, log}) {
	var xhr = createXHR();
	if (xhr && "withCredentials" in xhr) {	// Check if the object has a "withCredentials" property. only exists on XMLHTTPRequest2 objects.
		//xhr.open(method, url, true);
		xhr.open(method, url, true);
		xhr.withCredentials = true;
		xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
	} else if(window.ActiveXObject) {
		//xhr.open(method, url);
		xhr.open(method, url);
	} else {	// Otherwise, CORS is not supported by the browser.
		xhr = null;
	}
	if (headers)
		for (var i = 0; i < headers.length; i++) {
			xhr.setRequestHeader(headers[i].key, headers[i].value);
		}
	if (responseType)
		xhr.responseType = responseType;
	//	onabort, (onerror), (onload), onloadstart, onloadend, (onprogress), (onreadystatechange), (ontimeout)
	//	(readyState), response, responseText, responseType, responseUzrl, responseXML, (status), statusText, timeout, upload, (withCredentials), __proto__
	if (xhr) {	// Lets get the response data and pass it to our callback function
		if("withCredentials" in xhr){	// IE7 and below do not support onload. Hence using readyState for them
			//xhr.onprogress = function() {};	// no aborting
			xhr.onprogress = function(resp) {
				if (callbackProgress)
					callbackProgress(xhr, resp);
			};
			//xhr.ontimeout = function() {};	// "
			xhr.ontimeout = function(resp) {
				if (callbackTimeout)
					callbackTimeout(xhr, resp);
			};
			xhr.onload = function (resp) {//ok
				//callbackSuccess(xhr, resp);
				if (callbackSuccess)
					callbackSuccess(xhr, resp);
			};
			xhr.onerror = function (resp) {//ok
				//callbackError(xhr, resp);
				if (callbackError)
					callbackError(xhr, resp);
					};
			xhr.onloadstart = function(resp) {
				if (callbackPreSuccess)
					callbackPreSuccess(xhr, resp);
			};
			xhr.onloadend = function(resp) {
				if (callbackPostSuccess)
					callbackPostSuccess(xhr, resp);
			};
			xhr.onabort = function(resp) {
				if (callbackAbort)
					callbackAbort(xhr, resp);
			};
		}else{
			xhr.onreadystatechange = function(resp) {
				if(xhr.readyState === 4) {
					if(xhr.status === 200) {
						//callbackSuccess(xhr, resp);
						if (callbackPreSuccess)
							callbackPreSuccess(xhr, resp);
						if (callbackSuccess)
							callbackSuccess(xhr, resp);
						if (callbackPostSuccess)
							callbackPostSuccess(xhr, resp);
					}else{
						//callbackError(xhr, resp);
						if (callbackError)
							callbackError(xhr, resp);
					}
				}else{
					//callbackError(xhr, resp);
					if (callbackError)
						callbackError(xhr, resp);
				}
			};
		}
		if (log)
		{
			console.log('XML-HTTP-Request via '+ method+ ' to "'+ url+ '":');
			console.log(JSON.stringify(data, null, 4));
		}
		xhr.send(data);
	}
}
*/


/*"use strict";*/
/*
 * MyAjax is an intelligenr AJAX solution
 * example:
 *		var data = 'dialog_button=clicked&text='+ encodeURIComponent(this.innerText.trim());
		//var data = {'dialog_button': 'clicked', 'text': this.innerText.trim()};
		//var use = 'FETCH';
		var use = 'XHR';
		makeRequest('ajax1', 'ajax.php', data);
 */
function MyAjax(url, config)
{
	var props = {
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
	}
	if (!this.url && url)
		this.url = url;
	if (!this.method) {
		if (this.type)
			this.method = this.type;
		else
			this.method = 'GET';
	}
}


MyAjax.prototype.onSuccess = function(func, response)
{
				//request.onload = function (response) {//ok	// readyState will be 4
				var loadEvent = request.readystatechange ? 'readystatechange' : 'load';
				//addEvent(request, 'load', func);
				//addEvent(request, 'readystatechange', func);
				addEvent(request, loadEvent, func);
/*					if (onSuccess)
						onSuccess(response, request);
				};*/
};
MyAjax.prototype.onError = function(func, request, status, error)
{
				//request.onerror = function (response) {
				addEvent(request, 'error', func);
/*					if (onError)
						onError(response, request);
				};*/
};
MyAjax.prototype.onComplete = function(func)
{
				addEvent(request, 'done', func);
};
MyAjax.prototype.onProgress = function(func)
{
	//with(request)
	{
		if (request)
		{
				//request.onprogress = function() {};	// no aborting
				addEvent(request, 'progress', func);
/*				request.onprogress = function(progress) {//{}	// readyState will be 3
					if (onProgress)
						onProgress(progress, request);
				};*/
		}
	//}
};
MyAjax.prototype.onTimeout = function(func)
{
				//request.ontimeout = function(event) {//{}	// ProgressEvent
				addEvent(request, 'timeout', func);
					//lengthComputable	Read only	A boolean flag indicating if the total work to be done, and the amount of work already done, by the underlying process is calculable. In other words, it tells if the progress is measurable or not.
					//loaded	Read only	A 64-bit unsigned integer value indicating the amount of work already performed by the underlying process. The ratio of work done can be calculated by dividing total by the value of this property. When downloading a resource using HTTP, this only counts the body of the HTTP message, and doesn't include headers and other overhead.
					//total	Read only	A 64-bit unsigned integer representing the total amount of work that the underlying process is in the progress of performing. When downloading a resource using HTTP, this is the Content-Length (the size of the body of the message), and doesn't include the headers and other overhead.
/*					if (onTimeout)
						onTimeout(event, request);
				};*/
};
MyAjax.prototype.beforeSend = function(func)
{
				//request.onloadstart = function(event) {//-
				addEvent(request, 'loadstart', func);
/*					if (beforeSend)
						beforeSend(event, request);//response, request);
				};*/
};
MyAjax.prototype.afterSend = function(func)
{
				//request.onloadend = function(event) {//-
				addEvent(request, 'loadend', func);
/*					if (afterSend)
						afterSend(event, request);//response, request);
				};*/
};
MyAjax.prototype.onAbort = function(func)
{
				//request.onabort = function(event) {//-
				addEvent(request, 'abort', func);
/*					if (onAbort)
						onAbort(event, request);//response, request);
					request.abort();
				};*/
};
MyAjax.prototype.onReadystateChange = function(func)
{
				//request.onreadystatechange = function(response) {
				addEvent(request, 'readystatechange', func);
};


MyAjax.prototype.doneResponse = function(response, request)
//MyAjax.prototype.onComplete = function(func, response, request)
{
	//with (this)
	//{
/*		if (request.status === 0 && !request.statusText) {
			if (log)
				console.error('XML-HTTP-Request CORS violation');
			if (onError)
	//			onError(request.response, request);
				onError(request);
		} else*/
		if (!(request.status === 200 || request.status === 304)) {
			if (request.status > 200 && request.status < 400) {
				var rdata;
				if (log) {
					var desc = HttpStatus[request.status].desc;
					console.log(desc);
					var str = 'XML-HTTP-Request was completed (' + request.status + ')';
					if (request.response)
						str += ' and recieved ' + request.response.length + ' bytes';
					console.log(str);
				}
				if (response instanceof XMLHttpRequestProgressEvent)
					console.log('XML-HTTP-Request response (' + request.status + ') loaded ' + response.loaded + 'bytes');
				try {
					rdata = JSON.parse(request.responseText);
				} catch (err) {
					if (log) {
						/*					var str = err.message;
                                            if (request.responseText)
                                                str += " in " + request.responseText
                        //					console.error(str);
                                            console.log(str);*/
						console.info('XML-HTTP-Request response (' + request.status + ') was not valid JSON');
					}
					rdata = request.response || "";
				}
				if (onSuccess)
					onSuccess(rdata, request);
			} else {
				if (log)
					//console.error('XML-HTTP-Request encountered an error: '+ request.status+ ' '+ request.statusText);
					//console.error('XML-HTTP-Request encountered an error: ');
					console.log('XML-HTTP-Request encountered an error: ');
				var status1 = Math.floor(request.etatus / 100);
				/*			if (status1 === 1)
                            {
                                // Informational 1xx
                                console.log("Information code " + status1);
                            } else if (status1 === 2:
                            {
                                // Successful 2xx
                                console.log("Successful code " + status1);
                            } else if (status1 === 3:
                            {
                                // Redirection 3xx
                                console.log("Redirection code " + status1);
                            } else*/
				if (status1 === 4) {
					//alert(`Client Error #${msg}`);
					if (log)
						console.log('Client Error (' + HttpStatusFirst.status1 + ') ' + request.status + ' ' + request.statusText);
				} else if (status1 === 5) {
					//alert(`Server Error #${msg}`);
					if (log)
						console.log("Server Error " + status1);
				}
				if (onError)
					onError(/*request.response,*/ request);
			}
		} else {
			var rdata;
			if (log) {
				var str = 'XML-HTTP-Request was successful';
				if (request.response)
					str += ' and recieved ' + request.response.length + ' bytes';
				console.log(str);
			}
//			if (response instanceof XMLHttpRequestProgressEvent)
//				console.log('XML-HTTP-Request was successful but the response loaded '+ response.loaded+ 'bytes');
			try {
				rdata = JSON.parse(request.responseText);
			} catch (err) {
				if (log) {
					/*					var str = err.message;
                                    if (request.responseText)
                                        str += " in " + request.responseText
                    //					console.error(str);
                                    console.log(str);*/
					console.info('XML-HTTP-Request response was not valid JSON');
				}
				rdata = request.response || "";
			}
			if (onSuccess)
				onSuccess(rdata, request);
		}
	//}
};


MyAjax.prototype.getResponseHeaders = function(request)
{
	with (this)
	{
		if (/*request.readyState === this.HEADERS_RECEIVED &&*/ responseHeaders)
		{
			const headers = request.getAllResponseHeaders();
			if (!headers)
				return;
			const arr = headers.trim().split(/[\r\n]+/);
			const headerMap = {};
			arr.forEach((line) => {
				const parts = line.split(': ');
				const header = parts.shift();
				const value = parts.join(': ');
				headerMap[header] = value;
			});
			responseHeaders(headerMap);
			responseHeaders = null;	// dont fire again
		}
	}
};


MyAjax.prototype.serializeObject = function (obj)
{
	var serialized = [];
	for (var key in obj) {
		if (obj.hasOwnProperty(key)) {
			serialized.push(encodeURIComponent(key) + '=' + encodeURIComponent(obj[key]));
		}
	}
	return serialized.join('&');
};


MyAjax.prototype.makeRequest = function()//url, value)
{
	with (this)
	{
		if (use && use === "FETCH")
		{
/*			fetch (url, {method: method, body: data, onSuccess: null, onError: null, onProgress: function(progress, request) {}, onTimeout: function(event, request) {}, beforeSend: function(event, request) {}, afterSend: function(event, request) {}, onAbort: function(event, request) {}, headers: [], responseHeaders: function(headers) {}, timeout: null, forceMIME: null, async: true, log: false, username: '', password: '', responseAs: '', mode: 'cors', cache: 'no-cache'})
				.then (function(request) { request.text(); })	// also request.json();
				.then (function(response) { console.log(response); })
				.catch (function(err) { console.error(err); })
//				.finally (function() { console.log("FINALLY") })
			;*/
			const loadData = async function()
			{
				try {
					const request = await fetch (url, {method: method, body: data, onSuccess: null, onError: null, onProgress: function(progress, request) {}, onTimeout: function(event, request) {}, beforeSend: function(event, request) {}, afterSend: function(event, request) {}, onAbort: function(event, request) {}, headers: [], responseHeaders: function(headers) {}, timeout: null, forceMIME: null, async: true, log: false, username: '', password: '', responseAs: '', mode: 'cors', cache: 'no-cache'});
					const data = await request.text();
//					console.log(data);
					return data;
				} catch (err) {
					console.error(err);
				}
			}
			loadData().then(function(data) { console.log(data); });
			return;
		}
		var request = createXHR();	// readyState will be 0
		const charset = jsVar.CHARSET || 'UTF-8';
		// ** prepare headers to send ** /
		if (request) {
			if ("withCredentials" in request) {	// IE7 and below do not support onload. Hence using readyState for them
				if (log)
					console.log('XML-HTTP-Request using XMLHTTPRequest2 (has "withCredentials")');
				// ** register monitored events ** //
				//request.onprogress = function() {};	// no aborting
				//onProgress(onProgress);
				// ** collect response headers ** /
				addEvent(request, 'readystatechange', function(response) {
					//if (request.readyState === this.HEADERS_RECEIVED)
					if (this.readyState === XMLHttpRequest.HEADERS_RECEIVED && responseHeaders)
						responseHeaders(getResponseHeaders(request));
/*					else if (request.readyState === XMLHttpRequest.DONE) {//4) {
						if (beforeSend)
							beforeSend(request);
//							beforeSend(event, request);
						doneResponse(response, request);
						if (afterSend)
//							afterSend(event, request);
							afterSend(request);
					} else {
						// ** if an error occured ** /
						var error = readyStates[request.readyState];
						if (log)
							console.error('XML-Http-Request (Ready-State '+ request.readyState+ ') '+ error.state+ ': '+ error.desc)
					}*/
				});
				// ** mark progress **
				addEvent(request, 'progress', function(progress) {
//				request.onprogress = function(progress) {//{}	// readyState will be 3
					if (log)
						if (progress.lengthComputable)
							console.log(`XML-HTTP-Request received ${progress.loaded} of ${progress.total}`);
						else
							console.log(`XML-HTTP-Request received ${progress.loaded}`);
					if (onProgress)
						onProgress(progress, request);
				});
				// ** if request times-out ** /
				//request.ontimeout = function(event) {//{}	// ProgressEvent
				addEvent(request, 'timeout', function(progress) {
					//lengthComputable	Read only	A boolean flag indicating if the total work to be done, and the amount of work already done, by the underlying process is calculable. In other words, it tells if the progress is measurable or not.
					//loaded	Read only	A 64-bit unsigned integer value indicating the amount of work already performed by the underlying process. The ratio of work done can be calculated by dividing total by the value of this property. When downloading a resource using HTTP, this only counts the body of the HTTP message, and doesn't include headers and other overhead.
					//total		Read only	A 64-bit unsigned integer representing the total amount of work that the underlying process is in the progress of performing. When downloading a resource using HTTP, this is the Content-Length (the size of the body of the message), and doesn't include the headers and other overhead.
					if (log)
						if (progress.lengthComputable)
							console.log(`XML-HTTP-Request received ${progress.loaded} of ${progress.total} before timeout`);
						else
							console.log(`XML-HTTP-Request received ${progress.loaded} (last piece of data) before timeout`);
					if (onTimeout)
						onTimeout(progress, request);
				});
				// ** when request completes ** /
				//request.onload = function (response) {//ok	// readyState will be 4
/*				if (typeof request.readystatechange !== "undefined")
					var loadEvent = 'readystatechange';
				else if (typeof request.onreadystatechange !== "undefined")
					var loadEvent = 'onreadystatechange';
				else if (typeof request.load !== "undefined")
					var loadEvent = 'load';
				else if (typeof request.onload !== "undefined")
					var loadEvent = 'onload';*/
				//var loadEvent = typeof request.readystatechange !== "undefined" ? typeof request.onreadystatechange ? 'onreadystatechange' : typeof request.load !== 'load';
				//addEvent(request, 'readystatechange', function(response) {
				addEvent(request, 'load', function(response) {
				//addEvent(request, loadEvent, function(response) {
					doneResponse(response, request);
				});
				// ** if an error occurs ** /
				//request.onerror = function (response) {
				addEvent(request, 'error', function(response) {
					if (onError)
						onError(/*response,*/ request);
				});
				// ** when data is about to be sent ** /
				//request.onloadstart = function(event) {//-
				addEvent(request, 'loadstart', function(event) {
					if (beforeSend)
						beforeSend(event, request);//response, request);
				});
				// ** after data was sent ** /
				//request.onloadend = function(event) {//-
				addEvent(request, 'loadend', function(event) {
					if (afterSend)
						afterSend(event, request);//response, request);
				});
				// ** if the request was aborted ** /
				//request.onabort = function(event) {//-
				addEvent(request, 'abort', function(event) {
					if (onAbort)
						onAbort(/*event,*/ request);//response, request);
					request.abort();
				});
			} else {	// ** not XMLHTTPRequest2 capable (!"withCredentials" in request) ** //
				addEvent(request, 'error', function(response) {
					if (onError)
						onError(/*response,*/ request);
				});
				// ** when the request readysate changes ** //
				//request.onreadystatechange = function(response) {
				addEvent(request, 'readystatechange', function(response) {
					// ** format response headers ** //
					if (/*request*/this.readyState === XMLHttpRequest./*this.*/HEADERS_RECEIVED)
						responseHeaders(getResponseHeaders(request));
					// ** if the request was completed ** /
					else if (request.readyState === XMLHttpRequest.DONE) {//4) {
						if (beforeSend)
							beforeSend(/*event,*/ request);
						doneResponse(response, request);
						if (afterSend)
							afterSend(/*event,*/ request);
					} else {
						// ** if an error occured ** /
						var error = readStates[request.readyState];
						if (log)
							console.error('XML-Http-Request ('+ request.readyState+ ') '+ error.state+ ': '+ error.desc)
					}
				});	// end addEvent-readystatechange
			}
		}	// end valid request
// ** Additional calls (to setRequestHeader) add information to the header, don’t overwrite it. ** //
		if (!headers) {
			var headers = [];
		}
/*		if (!connection) {
//			headers.push({'Connection': 'Keep-Alive'});
		} else {
			headers.push({'Connection': connection});
		}*/
		if (!compatible) {
			headers.push({'X-Ua-Compatible': 'IE=Edge'});
		} else {
			headers.push({'X-Ua-Compatible': compatible});
		}
		if (!cors) {
			headers.push({'Access-Control-Allow-Origin': '*'});
		} else {
			headers.push({'Access-Control-Allow-Origin': cors});
		}
		if (!accept) {
			headers.push({'Accept': 'application/json, text/javascript, */*; q=0.01'});
//			headers.push({'Accept-Encoding': 'gzip, deflate'});
			headers.push({'Accept-Language': 'en-US, en; q=0.9'});
		}
		if (!cache) {	// will only work correctly with HEAD and GET requests
/*			headers.push({'Cache-Control': 'no-cache, no-store, max-age=0, must-revalidate'});				//don't cache the AJAX
			headers.push({'Pragma': 'no-cache'});
			headers.push({'Expires': '0'});*/
			if (method.toUpperCase() === 'GET' || method.toUpperCase() === 'HEAD') {
				if (url.strlen() < 2038) {
					var timestamp = new Date().getTime();
					url = (url.indexOf('?') == -1) ? url + '?' : url + '&';
					url += '_='/*'timestamp='*/ + timestamp;
				} else {
					throw new error("MyAjax:: URL is too long (must be less than 2048)");
				}
			}
		}
		// ** valid request ** /
		if (request) {
			if (data.segments || enctype) {
				// ** enctype is multipart/form-data ** //
				const boundary = "---------------------------" + Date.now().toString(16);
				//request.setRequestHeader( "Content-Type", `multipart\/form-data; boundary=${boundary}` );
				headers.push({"Content-Type": `multipart\/form-data; boundary=${boundary}`});
/*				data += `--${boundary}\r\n`;
				data += 'content-disposition: form-data; '
				// Define the name of the form data
					+ `name="${file.dom.name}"; `
				// Provide the real name of the file
					+ `filename="${file.dom.files[0].name}"\r\n`;
				// And the MIME type of the file
				data += `Content-Type: ${file.dom.files[0].type}\r\n`;
				// There's a blank line between the metadata and the value
				data += '\r\n';
				data += file.binary + '\r\n';	*/
/* -or-
				data += `--${boundary}\r\n`;
				data += `content-disposition: form-data; name="${text.name}"\r\n`;
				// There's a blank line between the metadata and the value
				data += '\r\n';
				// Append the text data to our body's request
				data += text.value + "\r\n";
				// Once we are done, "close" the body's request
				data += `--${boundary}--`;*/
//				request.sendAsBinary( `--${boundary}\r\n` + data.segments.join(`--${boundary}\r\n`) + `--${boundary}--\r\n` );
			} else {
				if (!contentType || contentType !== false) {
					if (contentType !== true)
						headers.push({"Content-Type": contentType});
					else if (data && method == 'POST' && !headers["Content-Type"] && processData)
						headers.push({"Content-Type": "application/x-www-form-urlencoded; charset="+ charset});	// --> php $_POST
		//				request.setRequestHeader('Content-type','application/x-www-form-urlencoded');// --> php $_POST
					else /*if (contentType !== false)*/
						headers.push({"Content-Type": "application/json; charset="+ charset});
		//			if (!headers["Content-Type"])
		//				headers.push({"Content-Type": "application/x-www-form-urlencoded; charset="+ charset});	// --> php $_POST
		//			headers['Accept'] = 'application/json';						//type of return data
				}
				if (username || password)
					headers.push({"Authorization": "Basic " + btoa("username:password")});
			}
			// ** open request ** //
			if ("withCredentials" in request) {	// Check if the object has a "withCredentials" property. only exists on XMLHTTPRequest2 objects.
				if (username || password)
					request.open(method, url, async, username, password);	// readyState will be 1
				else
					request.open(method, url, async);	// readyState will be 1
				request.withCredentials = true;	// boolean value that indicates whether or not cross-site Access-Control requests should be made using credentials
				request.setRequestHeader("X-Requested-With", "XMLHttpRequest", true);
//				headers.push({"X-Requested-With": "XMLHttpRequest"});
				request.setRequestHeader("X-Requested", "withCredentials", true);
//				request.setRequestHeader("X-Requested", "withCredentials", true);
	//			request.setRequestHeader("Content-Type", "application/json;charset="+ charset, true);
	/*		} else if (window.XDomainRequest && detectIE()) {
				request.open(method, url, false);	// readyState will be 1
				request.setRequestHeader("X-Requested-With", "XDomainRequest", true);*/
			} else if (window.ActiveXObject) {
				request.open(method, url);	// readyState will be 1
				request.setRequestHeader("X-Requested-With", "ActiveXObject", true);
//				request.setRequestHeader("X-Requested-With", "ActiveXObject", true);
			}
			// < IE7 : request.setRequestHeader('User-Agent','XMLHTTP/1.0');
			if (log && showRequestHeaders)
				console.log('Request headers:');
			// ** formulate request headers - no headers array hereafter ** //
			for (var i=0; i<headers.length; i++) {
				const header = headers[i];
				for (let key in header) {
					if (header.hasOwnProperty(key)) {
						request.setRequestHeader(key, header[key], true);
						if (log && showRequestHeaders)
							console.log('- '+ key+ ': '+ header[key]);
					}
				}
			}
			if (forceMIME)
				request.overrideMimeType(forceMIME);
			if (timeout && async)
				request.timeout = timeout;
			// ** if XMLHTTPRequest2 capable ** /
			if (log)
			{
				var str = 'XML-HTTP-Request sending ';
				if (username || password)
					str += '- with authorisation - ';
				console.log(str+ ' to "'+ url+ '" via '+ method+ ':');
				console.log(JSON.stringify(data, null, 4));
			}
/*			if (!window.XDomainRequest && !detectIE()) {
				request.open(method, url, true);*/
			if (typeof dataType !== "undefined" && dataType && !responseAs)
				responseAs = dataType;
			if (responseAs && async)
				request.responseType = responseAs;
			if (data.segments)
				request.sendAsBinary( `--${boundary}\r\n` + data.segments.join(`--${boundary}\r\n`) + `--${boundary}--\r\n` );
			else
				request.send(data||null);
			//}
		} else {	// request is invalid, CORS is not supported by the browser.
			request = null;
//				alert('Giving up :( Cannot create an XMLHTTP instance');
			throw new Error("Request failed - Cannot create an XMLHTTP instance");
			//return false;
		}
	}
};
