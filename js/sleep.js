/* https://stackoverflow.com/questions/951021/what-is-the-javascript-version-of-sleep */
function sleep(millis){ // Need help of a server-side page
	//let netMillis = Math.max(millis-5, 0); // Assuming 5 ms overhead
	let netMillis = Math.max(millis, 0); // Assuming 5 ms overhead
	let xhr = new XMLHttpRequest();
	//xhr.open('GET', jsVar.BASE+ 'sleep.jsp?millis=' + netMillis + '&rand=' + Math.random(), false);
	xhr.open('GET', jsVar.BASE+ 'sleep.php?n='+ netMillis+ '&rand='+ Math.random(), false);
	try{
		xhr.send();
	}catch(e){
	}
}

function sleepAsync(millis){ // Use only in async function
	let netMillis = Math.max(millis-1, 0); // Assuming 1 ms overhead
	return new Promise((resolve) => {
		setTimeout(resolve, netMillis);
	});
}
function sleepSync(millis){ // Use only in worker thread, currently Chrome-only
	Atomics.wait(new Int32Array(new SharedArrayBuffer(4)), 0, 0, millis);
}

function sleepTest(){
	console.time('sleep');
	sleep(1000);
	console.timeEnd('sleep');
}

async function sleepAsyncTest(){
	console.time('sleepAsync');
	await sleepAsync(1000);
	console.timeEnd('sleepAsync');
}

function sleepSyncTest(){
	let source = `${sleepSync.toString()}
		console.time('sleepSync');
		sleepSync(1000);
		console.timeEnd('sleepSync');`;
	let src = 'data:text/javascript,' + encodeURIComponent(source);
	console.log(src);
	var worker = new Worker(src);
}
