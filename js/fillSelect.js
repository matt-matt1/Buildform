/**
 * Returns a optgroup element containg option as per obj
 */
function makeOptgroup(obj)
{
	var $out = document.createElement('OPTGROUP');
	if (obj.label) {
		$out.label = obj.label;
	}
	if (obj.attr) {
		var attr = obj.attr.split('=');
		$out.setAttribute(attr[0], attr[1]);
	}
	for (var i=0; i<obj.contents.length; i++) {
		$out.appendChild( makeOption(obj.contents[i]) );
	}
	return $out;
}
/**
 * Returns an option element containing the obj attributes
 */
function makeOption(obj)
{
	var $out = document.createElement('OPTION');
	if (obj.title) {
		$out.title = obj.title;
	}
	if (obj.value) {
		$out.value = obj.value;
	}
	if (obj.attr) {
		var attr = obj.attr.split('=');
		$out.setAttribute( attr[0], attr[1].replace(/^["'](.+(?=["']$))["']$/, '$1') );
	}
	if (obj.text) {
		$out.innerText = obj.text;
	} else if (obj.value) {
		$out.innerText = obj.value;
	}
	return $out;
}
/**
 * Reads a file in raw format then calls the callabck
 * usage:
 * readTextFile("/Users/Documents/workspace/test.json", function(text){
 *	var data = JSON.parse(text);
 *	console.log(data);
 * });
 */
function readTextFile(file, callback) {
	var rawFile = new XMLHttpRequest();
	rawFile.overrideMimeType("application/json");
	rawFile.open("GET", file, true);
	if (typeof rawFile.onload === "object") {
		rawFile.onload = function() {
			if (rawFile.readyState === 4 && rawFile.status == "200") {
				callback(rawFile.responseText);
			}
		}
	} else if (typeof rawFile.onreadystatechange === "object") {
		rawFile.onreadystatechange = function() {
			if (rawFile.readyState === 4 && rawFile.status == "200") {
				callback(rawFile.responseText);
			}
		}
	}
	rawFile.send(null);
//	console.log('Reading "'+ file+ '"...');
}
/**
 * Adds timestamp to given URL
 */
//private addTimeStampTo(url:string){
function addTimeStampTo(url)
{
	//var timestamp = Date.now();
	var timestamp = new Date().getTime();
	var timestampedUrl = (url.indexOf('?') == -1) ? url + '?' : url + '&';
	timestampedUrl += /*'timestamp=' +*/ timestamp;
	return timestampedUrl;
}
/**
 * Builds the inside of a select element using the given JSON array of options
 * sets the first option with firstOptionInner, if given, otherwise uses the select ID
 */
function fillSelect(select, options, firstOptionInner)
{
	if (!select)
		return;
	if (firstOptionInner) {
		var option = document.createElement('OPTION');
		option.innerText = (firstOptionInner && firstOptionInner !== ' ') ? firstOptionInner : select.id;
		option.disabled = true;
		option.selected = true;
		select.appendChild(option);
	}
	for (var i=0; i<options.length; i++) {
		if (options[i].group) {
			select.appendChild(makeOptgroup(options[i].group));
		} else {
			select.appendChild(makeOption(options[i]));
		}
	}
}
/*
var request = new XMLHttpRequest();
//request.overrideMimeType("application/json");
request.open('GET', file, true);
// this following line is needed to tell the server this is a ajax request
request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
//request.onreadystatechange = function() {
request.onload = function () {
	if (this.status >= 200 && this.status < 400) {
		var data = this.response;
//		console.log(data);
//		data = data.replace(/\\"|"(?:\\"|[^"])*"|(\/\/.*|\/\*[\s\S]*?\*\/|\#.*)/g);
//		console.log(data);
//		let nocomment = this.response.toString('utf8').replace(matchHashComment, '').trim();
//		var json = JSON.parse(this.response.replace(/\\"|"(?:\\"|[^"])*"|(\/\/.*|\/\*[\s\S]*?\*\/|\#.*)/g, (m, g) => g ? "" : m));
//		var json = JSON.parse(data);
		//var json = JSON5.parse(data);
//		console.log(json);
	}
};
request.send();
*/
