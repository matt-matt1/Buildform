/**
 * Based on http://www.samantsingh.com/blogs/post/how-to-make-ajax-calls-using-vanilla-javascript/
 * Returns a new, cross-browser XMLHttpRequest object (or ActiveXObject)
 */
function createXHR() {
	var xmlHttp = null;
	try {
		xmlHttp = new XMLHttpRequest();
	} catch (e) {
		try {
			xmlHttp = new XDomainRequest();
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
	if (headers && xhr)
		for (var i = 0; i < headers.length; i++) {
			xhr.setRequestHeader(headers[i].key, headers[i].value);
		}
	if (responseType && xhr)
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
/*
class Ajax {
	const HttpStatus = {
		'Information responses': [
			{100: {'name': 'Continue', 'desc': "This means the client should continue with its request. The server returns this response code to inform the client that the initial part of the request has been received and has not yet been rejected by the server."}},
			{101: {'name': 'Switching Protocols', 'desc': "This means the requester has asked the server to switch protocols and the server is acknowledging that it will do so."}},
			{102: {'name': 'Processing (WebDAV)', 'desc': "This code indicates that the server has received and is processing the request, but no response is available yet."}},
			{103: {'name': 'Early Hints', 'desc': "This status code is primarily intended to be used with the Link header, letting the user agent start preloading resources while the server prepares a response."}}],
	
		"Successful responses": [
			{200: {'name': 'OK', 'desc': "The server successfully processed the request. Generally, this means that the server provided the requested page."}},
			{201: {'name': 'Created', 'desc': "This means the request was successful and the server created a new resource."}},
			{202: {'name': 'Accepted', 'desc': "This means the server has accepted the request for processing, but the processing has not been completed."}},
			{203: {'name': 'Non-Authoritative Information', 'desc': "This means the server successfully processed the request, but is returning information that may be from another source."}},
			{204: {'name': 'No Content', 'desc': "This means the server successfully processed the request, but isn't returning any content."}},
			{205: {'name': 'Reset Content', 'desc': "This means the server successfully processed the request, but is not returning any content. Unlike a 204 response, this response requires that the requester reset the document view."}},
			{206: {'name': 'Partial Content', 'desc': "The server is delivering only part of the resource due to a range header sent by the client."}},
			{207: {'name': 'Multi-Status (WebDAV)', 'desc': "Conveys information about multiple resources, for situations where multiple status codes might be appropriate."}},
			{208: {'name': 'Already Reported (WebDAV)', 'desc': "Used inside a <dav:propstat> response element to avoid repeatedly enumerating the internal members of multiple bindings to the same collection."}},
			{226: {'name': 'IM Used (HTTP Delta encoding)', 'desc': "The server has fulfilled a GET request for the resource, and the response is a representation of the result of one or more instance-manipulations applied to the current instance."}}],

		'Redirection messages': [
			{300: {'name': 'Multiple Choices', 'desc': "Indicates multiple options for the resource that the client may follow. It, for instance, could be used to present different format options for video or list files with different extensions."}},
			{301: {'name': 'Moved Permanently', 'desc': "The requested page has been permanently moved to a new location. When the server returns this response, it automatically forwards the requester to the new location."}},
			{302: {'name': 'Found', 'desc': "This means the requested resource resides temporarily under a different location, but the requester should continue to use the original location for future requests."}},
			{303: {'name': 'See Other', 'desc': "This means the response to the request can be found under a different location using a GET method."}},
			{304: {'name': 'Not Modified', 'desc': "Indicates the requested resource hasn't been modified since the last request."}},
			{305: {'name': 'Use Proxy', 'desc': "This means the requester can only access the requested resource using a proxy. Many HTTP clients (such as Mozilla and Internet Explorer) do not correctly handle responses with this status code, primarily for security reasons."}},
			{306: {'name': 'Switch Proxy', 'desc': "No longer used."}},
			{307: {'name': 'Temporary Redirect', 'desc': "This means the requested resource resides temporarily under a different location, but the requester should continue to use the original location for future requests. In contrast to 302, the request method should not be changed when reissuing the original request. For instance, a POST request must be repeated using another POST request."}},
			{308: {'name': 'Permanent Redirect (experimental)', 'desc': "This means the request, and all future requests should be repeated using another URL. 307 and 308 (as proposed) parallel the behaviours of 302 and 301, but do not allow the HTTP method to change."}}],
		
		'Client error responses': [
			{400: {'name': 'Bad Request', 'desc': "This means the request cannot be fulfilled due to bad syntax."}},
			{401: {'name': 'Unauthorized', 'desc': "The request requires user authentication. The server might return this response for a page behind a login."}},
			{402: {'name': 'Payment Required', 'desc': "This code is reserved for future use. The original intention was that this code might be used as part of some form of digital cash or micropayment scheme, but that has not happened."}},
			{403: {'name': 'Forbidden', 'desc': "The request was a valid request, but the server is refusing to respond to it. Unlike a 401 Unauthorized response, authenticating will make no difference."}},
			{404: {'name': 'Not Found', 'desc': "This means the server can't find the requested page. For instance, the server often returns this code if the request is for a page that doesn't exist on the server."}},
			{405: {'name': 'Method Not Allowed', 'desc': "This means the method specified in the request is not allowed. For example, using GET on a form which requires data to be presented via POST."}},
			{406: {'name': 'Not Acceptable', 'desc': "This means the requested resource can't respond with the content characteristics requested."}},
			{407: {'name': 'Proxy Authentication Required', 'desc': "This code is similar to 401 (Unauthorized), but indicates that the client must first authenticate itself with the proxy."}},
			{408: {'name': 'Request Timeout', 'desc': "The server timed out waiting for the request. This means the client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time."}},
			{409: {'name': 'Conflict', 'desc': "This means the request could not be completed due to a conflict with the current state of the resource."}},
			{410: {'name': 'Gone', 'desc': "Indicates that the resource requested is no longer available at the server and will not be available again."}},
			{411: {'name': 'Length Required', 'desc': "This means the server refuses to accept the request without a defined Content-Length."}},
			{412: {'name': 'Precondition Failed', 'desc': "This means the server does not meet one of the preconditions that the requester put on the request."}},
			{413: {'name': 'Request Entity Too Large', 'desc': "This means the server is refusing to process a request because it is larger than the server is willing or able to process."}},
			{414: {'name': 'Request-URI Too Long', 'desc': "This means the server is refusing to process the request because the Request-URI (typically, a URL) is longer than the server is willing to interpret."}},
			{415: {'name': 'Unsupported Media Type', 'desc': "The server is refusing to process the request because the entity of the request is in a format which is not supported by the server or requested resource."}},
			{416: {'name': 'Requested Range Not Satisfiable', 'desc': "The server returns this status code if the request is for a range not available for the selected resource."}},
			{417: {'name': 'Expectation Failed', 'desc': "This means the server cannot meet the requirements of the Expect request-header field."}},
			{418: {'name': "I'm a teapot", 'desc': "The server refuses the attempt to brew coffee with a teapot."}},
			{421: {'name': 'Misdirected Request', 'desc': "The request was directed at a server that is not able to produce a response. This can be sent by a server that is not configured to produce responses for the combination of scheme and authority that are included in the request URI."}},
			{422: {'name': 'Unprocessable Entity (WebDAV)', 'desc': "The request was well-formed but was unable to be followed due to semantic errors.}},
			{423: {'name': 'Locked (WebDAV)', 'desc': "The resource that is being accessed is locked."}},
			{424: {'name': 'Failed Dependency (WebDAV)', 'desc': "The request failed due to failure of a previous request."}},
			{425: {'name': 'Too Early Experimental', 'desc': "Indicates that the server is unwilling to risk processing a request that might be replayed."}},
			{426: {'name': 'Upgrade Required', 'desc': "The server refuses to perform the request using the current protocol but might be willing to do so after the client upgrades to a different protocol. The server sends an Upgrade header in a 426 response to indicate the required protocol(s)."}},
			{428: {'name': 'Precondition Required', 'desc': "The origin server requires the request to be conditional. This response is intended to prevent the 'lost update' problem, where a client GETs a resource's state, modifies it and PUTs it back to the server, when meanwhile a third party has modified the state on the server, leading to a conflict."}},
			{429: {'name': 'Too Many Requests', 'desc': "The user has sent too many requests in a given amount of time ('rate limiting')."}},
			{431: {'name': 'Request Header Fields Too Large', 'desc': "The server is unwilling to process the request because its header fields are too large. The request may be resubmitted after reducing the size of the request header fields."}},
			{451: {'name': 'Unavailable For Legal Reasons', 'desc': "The user agent requested a resource that cannot legally be provided, such as a web page censored by a government."}}],

		"Server error responses": [
			{500: {'name': 'Internal Server Error', 'desc': "This means the server encountered an unexpected condition which prevented it from processing the request."}},
			{501: {'name': 'Not Implemented', 'desc': "This means the server either does not recognize the request method, or it lacks the ability to handle the request."}},
			{502: {'name': 'Bad Gateway', 'desc': "This means the server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request."}},
			{503: {'name': 'Service Unavailable', 'desc': "This means the server is currently unable to handle the request because it is overloaded or down for maintenance. Generally, this is a temporary state."}},
			{504: {'name': 'Gateway Timeout', 'desc': "This means the server, while acting as a gateway or proxy, did not receive a timely response from the upstream server."}},
			{505: {'name': 'HTTP Version Not Supported', 'desc': "This means the server does not support, or refuses to support, the HTTP protocol version used in the request."}},
			{506: {'name': 'Variant Also Negotiates', 'desc': "The server has an internal configuration error: the chosen variant resource is configured to engage in transparent content negotiation itself, and is therefore not a proper end point in the negotiation process."}},
			{507: {'name': 'Insufficient Storage (WebDAV)', 'desc': "The method could not be performed on the resource because the server is unable to store the representation needed to successfully complete the request."}},
			{508: {'name': 'Loop Detected (WebDAV)', 'desc': "The server detected an infinite loop while processing the request."}},	
			{510: {'name': 'Not Extended', 'desc': "Further extensions to the request are required for the server to fulfill it."}},
			{511: {'name': 'Network Authentication Required', 'desc': "Indicates that the client needs to authenticate to gain network access."}}
		]}
	};
/ *	const request = {
		'status': [
			100 Continue "This means the client should continue with its request. The server returns this response code to inform the client that the initial part of the request has been received and has not yet been rejected by the server.",
101 Switching Protocols "This means the requester has asked the server to switch protocols and the server is acknowledging that it will do so.",
			200 OK
The server successfully processed the request. Generally, this means that the server provided the requested page.
201 Created
This means the request was successful and the server created a new resource.
202 Accepted
This means the server has accepted the request for processing, but the processing has not been completed.
203 Non-Authoritative Information
This means the server successfully processed the request, but is returning information that may be from another source.
204 No Content
This means the server successfully processed the request, but isn't returning any content.
205 Reset Content
This means the server successfully processed the request, but is not returning any content. Unlike a 204 response, this response requires that the requester reset the document view.
206 Partial Content
The server is delivering only part of the resource due to a range header sent by the client.
,
			300 Multiple Choices
Indicates multiple options for the resource that the client may follow. It, for instance, could be used to present different format options for video or list files with different extensions.
301 Moved Permanently
The requested page has been permanently moved to a new location. When the server returns this response, it automatically forwards the requester to the new location.
302 Found
This means the requested resource resides temporarily under a different location, but the requester should continue to use the original location for future requests.
303 See Other
This means the response to the request can be found under a different location using a GET method.
304 Not Modified
Indicates the requested resource hasn't been modified since the last request.
305 Use Proxy
This means the requester can only access the requested resource using a proxy. Many HTTP clients (such as Mozilla and Internet Explorer) do not correctly handle responses with this status code, primarily for security reasons.
306 Switch Proxy
No longer used.
307 Temporary Redirect
This means the requested resource resides temporarily under a different location, but the requester should continue to use the original location for future requests. In contrast to 302, the request method should not be changed when reissuing the original request. For instance, a POST request must be repeated using another POST request.
308 Permanent Redirect (experimental)
This means the request, and all future requests should be repeated using another URL. 307 and 308 (as proposed) parallel the behaviours of 302 and 301, but do not allow the HTTP method to change.,
			400 Bad Request
This means the request cannot be fulfilled due to bad syntax.
401 Unauthorized
The request requires user authentication. The server might return this response for a page behind a login.
402 Payment Required
This code is reserved for future use. The original intention was that this code might be used as part of some form of digital cash or micropayment scheme, but that has not happened.
403 Forbidden
The request was a valid request, but the server is refusing to respond to it. Unlike a 401 Unauthorized response, authenticating will make no difference.
404 Not Found
This means the server can't find the requested page. For instance, the server often returns this code if the request is for a page that doesn't exist on the server.
405 Method Not Allowed
This means the method specified in the request is not allowed. For example, using GET on a form which requires data to be presented via POST.
406 Not Acceptable
This means the requested resource can't respond with the content characteristics requested.
407 Proxy Authentication Required
This code is similar to 401 (Unauthorized), but indicates that the client must first authenticate itself with the proxy.
408 Request Timeout
The server timed out waiting for the request. This means the client did not produce a request within the time that the server was prepared to wait. The client MAY repeat the request without modifications at any later time.
409 Conflict
This means the request could not be completed due to a conflict with the current state of the resource.
410 Gone
Indicates that the resource requested is no longer available at the server and will not be available again.
411 Length Required
This means the server refuses to accept the request without a defined Content-Length.
412 Precondition Failed
This means the server does not meet one of the preconditions that the requester put on the request.
413 Request Entity Too Large
This means the server is refusing to process a request because it is larger than the server is willing or able to process.
414 Request-URI Too Long
This means the server is refusing to process the request because the Request-URI (typically, a URL) is longer than the server is willing to interpret.
413 Request Entity Too Large
This means the server is refusing to process a request because it is larger than the server is willing or able to process.
413 Request Entity Too Large
This means the server is refusing to process a request because it is larger than the server is willing or able to process.
415 Unsupported Media Type
The server is refusing to process the request because the entity of the request is in a format which is not supported by the server or requested resource.
416 Requested Range Not Satisfiable
The server returns this status code if the request is for a range not available for the selected resource.
417 Expectation Failed
This means the server cannot meet the requirements of the Expect request-header field.,
			500 Internal Server Error
This means the server encountered an unexpected condition which prevented it from processing the request.
501 Not Implemented
This means the server either does not recognize the request method, or it lacks the ability to handle the request.
502 Bad Gateway
This means the server, while acting as a gateway or proxy, received an invalid response from the upstream server it accessed in attempting to fulfill the request.
503 Service Unavailable
This means the server is currently unable to handle the request because it is overloaded or down for maintenance. Generally, this is a temporary state.
504 Gateway Timeout
This means the server, while acting as a gateway or proxy, did not receive a timely response from the upstream server.
505 HTTP Version Not Supported
This means the server does not support, or refuses to support, the HTTP protocol version used in the request.
		], 'readyState': [
		];* /

	function ajax(arr)
	{
		/ *
		var readyStates = [
			{'state': 'UNSENT', 'desc': "An XMLHttpRequest object has been created, but the open() method hasn't been called yet (i.e. request not initialized)."},
			{'state': 'OPENED', 'desc': "The open() method has been called (i.e. server connection established)."},
			{'state': 'HEADERS_RECEIVED', 'desc': "The send() method has been called (i.e. server has received the request)."},
			{'state': 'LOADING', 'desc': "The server is processing the request."},
			{'state': 'DONE', 'desc': "The request has been processed and the response is ready."}
		];
		* /
		const readyState = {
			UNSENT: "An XMLHttpRequest object has been created, but the open() method hasn't been called yet (i.e. request not initialized).",
			OPENED: "The open() method has been called (i.e. server connection established).",
			HEADERS_RECEIVED: "The send() method has been called (i.e. server has received the request).",
			LOADING: "The server is processing the request.",
			DONE: "The request has been processed and the response is ready."
		};
		const status = {
			200: "OK. The server successfully processed the request.",
			404: "Not Found. The server can't find the requested page.",
			503: "Service Unavailable. The server is temporarily unavailable."
		};
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function()
		{
			if (this.readyState == readyStates.DONE && this.status == 200)
			{
				if (arr.success)
					arr.success();
			} else {
				if (arr.failure)
					arr.failure();
			}
			if (arr.done)
				arr.done();
		};
		request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");//application/x-www-form-urlencoded, multipart/form-data, application/json, application/xml, text/plain, text/html
		xhttp.open("GET", arr.url, true);
		xhttp.send(arr.body);
	}

}
*/
/**
 * Enum(eration) of readyStates
 *
 * Get all allowed enum values: Object.keys(Direction) returns an array ['Up', 'Down', 'Left', 'Right']
 * Check if a value equals an enum value: val === Direction.Up.name
 * Check if a value is in the enum: Direction.Up instanceof Direction
 */
/*
class Direction {
  static Up = new Direction('Up');
  static Down = new Direction('Down');
  static Left = new Direction('Left');
  static Right = new Direction('Right');

  constructor(name) {
    this.name = name;
  }
  toString() {
    return `Color.${this.name}`;
  }
}
class ReadyState {
	static Unsent = new ReadyState({state: 'Unsent', message: "An XMLHttpRequest object has been created, but the open() method hasn't been called yet (i.e. request not initialized)."});
	static Opened = new ReadyState({'Opened': "The open() method has been called (i.e. server connection established)."});
	static HeadersReceived = new ReadyState({'HeadersReceive'): "The send() method has been called (i.e. server has received the request)."});
	static Loading = new ReadyState({'Loading'): "The server is processing the request."});
	static Done = new ReadyState({'Done': "The request has been processed and the response is ready."});

	constructor({state, message}) {
		this.state = state;
		this.message = message;
	}

	toString() {
		return `ReadyState.${this.state}`;
	}

}
*/
/**
 * Also fetch can be used
 */
/* eg.
fetch('http://example.com/movies.json', { method: 'GET', headers: '', body: '' })
	.then(function(response) {
		return response.json();
	})
	.then(function(myJson) {
		console.log(myJson);
	})
	.catch(errorMsg => { console.log(errorMsg); });
*/
