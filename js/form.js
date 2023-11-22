//import subjx from 'subjx';
//import 'subjx/dist/style/subjx.css';
	/**
	 * Launch load when the document loads
	 * ----------------------------
	 */
	/*if (typeof window.addEvent === "function") {*/
		addEvent(window, 'load', load_form);
/*	} else {
//		alert('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
		console.error('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
	}*/

/**
 * Fired when the window (for this page) loads
 */
function load_form() {
	const main = document.getElementById('main_form');
/*	var dragresize1 = new DragResize('formresize', {		// enable resize of main form:
		minWidth: main.clientWidth,//300,		// keep the current(set) width
		minHeight: main.clientHeight,//300,		// no less than the set height
		handles: ['mr', 'bm', 'br'],			// can only resize down and right
		minLeft: main.clientLeft,//mainBounds.left,
		minTop: main.clientTop,//mainBounds.top,
		maxLeft: window.screen.width,//mainBounds.width,
		maxTop: window.screen.height//mainBounds.height
	});
	dragresize1.isElement = function(elm)
	{
		if (elm.className && elm.className.indexOf('drsElement') > -1) return true;
	};
	dragresize1.isHandle = function(elm)
	{
		if (elm.className && elm.className.indexOf('drsMoveHandle') > -1) return true;
	};
	dragresize1.ondragfocus = function(elem) { console.log(elem.id+ '('+ dragresize1.myName+ ') came into focus'); };
	dragresize1.ondragstart = function(elem,isResize) { console.log(elem.id+ ' drag began - resizing: '+ isResize); };
	dragresize1.ondragmove = function(elem,isResize,pos) { console.log(elem.id+ ' dragging - resizing: '+ isResize); };
	dragresize1.ondragend = function(elem,isResize) { console.log(elem.id+ ' finished dragging - resizing: '+ isResize); };
	dragresize1.ondragblur = function(elem) { console.log(elem.id+ '('+ dragresize1.myName+ ') lost focus'); };
	//dragresize1.apply(main);
	dragresize1.apply(document);*/
	/* apply to each toolbox item element */
	const tags = document.querySelectorAll('[data-tag]');
	for(i=0; i<tags.length; i++) {						// enable all toolbox elements
		addEvent(tags[i], 'click', addTagToMain);
	}
	const submit = document.getElementById('submit');	// save content on submit
	addEvent(submit, 'click', saveForm);
/*	const anchs = document.querySelectorAll('a');
	for(i=0; i<anchs.length; i++) {						// enable all toolbox elements
		if (a.href[0] === '#')
			addEvent(anchs[i], 'click', smoothScroll);
	}*/

/*	addEvent(main, 'keydown', keyDown);/*function(event) {	//if you return false = .preventDefault(); .stopPropagation(); stops execution and returns immediately
		event = event || window.event;
	});*/
/*	var columns = [];
	columns.push(grid.querySelectorAll('.item.c1'));
	columns.push(grid.querySelectorAll('.item.c2'));
	columns.push(grid.querySelectorAll('.item.c3'));
	columns.push(grid.querySelectorAll('.item.c4'));
	fluid(main, columns);*/
	grid();
/*	for (i=0; i<10; i++)
	{
		for (j=0; j<5; j++)
		{
			var seg = document.createElement('div');		// create an (hidden) input for the tag
			//seg.id = 'real-form-element-' + num;
			seg.className = 'grid-inner';
			gridbox.appendChild(seg);
		}
	}*/
}


function grid()
{
	const main = document.getElementById('main_form');
	const gridContainer = main.querySelector('.grid-container');
	//gridContainer.style.setProperty('--minor-color', 'blue');
	gridContainer.style.setProperty('--minor-color', 'rgba(50, 50, 50, 0.05)');
	gridContainer.style.setProperty('--minor-thickness', '1px');
	//gridContainer.style.setProperty('--major-color', 'rgba(50, 50, 50, 0.075)');
	gridContainer.style.setProperty('--major-color', 'rgba(5, 250, 250, 0.15)');
	gridContainer.style.setProperty('--major-thickness', '2px');
	gridContainer.style.setProperty('--minor-length', '20px');
	gridContainer.style.setProperty('--major-length', '100px');
}
function gridOLD()
{
	const main = document.getElementById('main_form');
	//const gridContainer = document.getElementById('main_form_grid');
	const gridContainer = main.querySelector('.grid-container');
	const gridItem = main.querySelector('.grid-item');
//	var container = document.createElement("div");
//	container.id = "main";
//	container.className = "container";
//	document.body.appendChild(container);
//	var main = document.getElementById('main');
//	const itemHeight = gridItem.offsetHeight;//clientHeight;
	const cols = gridContainer.style.gridTemplateColumns.split(" ");
//	var colCount = columns.length;
//	var gridHeight = gridContainer.clientHeight;
//	var cells = gridHeight / itemHeight;
//	var rows = gridHeight / cells;
//	var colWidth = gridWidth / colCount;
//	if( parseInt(colWidth,10) === colWidth ){
/*	for (var i=0; i<6; i++) {
		var row = document.createElement("div");
		row.className = "row";
		row.id = "row" + i;
		gridbox.appendChild(row);
		var roww = document.getElementById('row'+i);*/
		for (var j=0; j<11; j++) {
			var box = document.createElement("div");
			box.className = "box grid-item";
			box.innerText = j;
//			roww.appendChild(box);
			gridContainer.appendChild(box);
		}
/*}*/
}


/**
 * Fired when an element is select from the toolbox
 */
function addTagToMain(e) {
	const main = document.getElementById('main_form');
	const kids = main.querySelectorAll('.form-element');	// form-elements
	const num = kids.length;
	for (i=0; i<kids.length; i++)
	{
		removeClass(kids[i], 'selected');				// deselect all form-elements
	}
	var input = document.createElement('input');		// create an (hidden) input for the tag
	input.type = 'hidden';
	input.id = 'real-form-element-' + num + '-tag';
	input.name = 'form-element[' + num + '][tag]';		// enabled array of contents on submit
	input.className = 'hide real-form-element';
	//box.draggable = true;
	//box.dataset.confine = '#' + main.id;
	input.value = this.dataset.tag;
	main.appendChild(input);
	var input1 = document.createElement('input');		// create an (hidden) input for the position
	input1.type = 'hidden';
	input1.id = 'real-form-element-' + num + '-left';
	input1.name = 'form-element[' + num + '][left]';	// position left
	input1.className = 'hide real-form-element';
	input1.value = '0';
	main.appendChild(input1);
	var input2 = document.createElement('input');		// create an (hidden) input for the position
	input2.type = 'hidden';
	input2.id = 'real-form-element-' + num + '-top';
	input2.name = 'form-element[' + num + '][top]';		// position top
	input2.className = 'hide real-form-element';
	input2.value = '0';
	main.appendChild(input2);
	var input3 = document.createElement('input');		// create an (hidden) input for the position
	input3.type = 'hidden';
	input3.id = 'real-form-element-' + num + '-width';
	input3.name = 'form-element[' + num + '][width]';	// position width
	input3.className = 'hide real-form-element';
	input3.value = '0';
	main.appendChild(input3);
	var input4 = document.createElement('input');		// create an (hidden) input for the position
	input4.type = 'hidden';
	input4.id = 'real-form-element-' + num + '-height';
	input4.name = 'form-element[' + num + '][height]';	// position height
	input4.className = 'hide real-form-element';
	input4.value = '0';
	main.appendChild(input4);
	var input5 = document.createElement('input');		// create an (hidden) input for the text
	input5.type = 'hidden';
	input5.id = 'real-form-element-' + num + '-text';
	input5.name = 'form-element[' + num + '][text]';	// text
	input5.className = 'hide real-form-element form-element-input';
	input5.value = this.dataset.value || '';
	main.appendChild(input5);
	var box = document.createElement('div');			// create a draggable/resizable box
	box.id = 'form-element-' + num;
//	box.className = 'drsElement drsMoveHandle selected current-form-element form-element';//'can-drag';
	box.className = 'drsElement drsMoveHandle selected form-element';//'can-drag';
	//box.draggable = true;
	box.dataset.confine = '#' + main.id;
	box.title = this.title;
//	box.value = this.dataset.value || '';
	//box.innerHTML = '<' + this.dataset.tag + '>';		// include tag inside it
	const tag = this.dataset.tag;
	const first = tag.split(" ")[0];
	var innerHTML = '<' + tag;		// include tag inside it
	var value = this.dataset.value || '';
	if (first === "input")
		innerHTML += ' value="'+ value+ '" name="form-element['+ num+ '][text]" id="form-element-'+ num+ '"';
	else
		innerHTML += ">"+ this.dataset.value+ "</"+ first;
	box.innerHTML = innerHTML + ">";
	box.ondblclick = handleInput;
/*	if (num === 0 && main.firstElementChild.nodeName.toUpperCase() != 'DIV')
	{
		main.removeChild( main.firstElementChild );		// clear form at start
	}*/
	var dragresize = new DragResize('dragresize', {		// enable drag/resize
		minWidth: 160,
		minHeight: 30,//25,
		handles: ['tl', 'tm', 'tr', 'ml', 'mr', 'bl', 'bm', 'br'],
		//handles: [ 'mr', 'bm', 'br'],
		minLeft: main.clientLeft,//mainBounds.left,
		minTop: main.clientTop,//mainBounds.top,
		maxLeft: main.clientWidth,//mainBounds.width,
		maxTop: main.clientHeight//mainBounds.height
	});
	var c_log = true;
	dragresize.isElement = function(elm)
	{
		if (elm.className && elm.className.indexOf('drsElement') > -1)
			return true;
//		if (c_log)
//			console.log('draggable: '+ elm.parentNode.id);
		//addClass(elm, 'dragging');
	};
	dragresize.isHandle = function(elm)
	{
		if (elm.className && elm.className.indexOf('drsMoveHandle') > -1)
			return true;
//		if (c_log)
//			console.log('resizable: '+ elm.parentNode.id);
		//addClass(elm, 'dragging');
	};
	dragresize.ondragfocus = function(elem)
	{
		if (c_log)
			console.log(elem.id+ ' gained drag focus');
	};
	dragresize.ondragstart = function(elem, isResize)
	{
		if (c_log)
			console.log(elem.id+ ' began to be '+ (isResize ? 'resized' : 'dragged'));
//		removeClass(dragresize.element, (!isResize ? 'resizing' : 'dragging'));
		addClass(dragresize.element, 'dragging');
	};
	dragresize.ondragmove = function(elem, isResize, pos)
	{
		if (c_log)
			console.log(elem.id+ ' was '+ (isResize ? 'resized to left:'+ pos.left+ ',top:'+ pos.top+ ',width:'+ pos.width+ ',height:'+ pos.height : 'dragged to left:'+ pos.left+ ',top:'+ pos.top));
	//	addClass(elm, (isResize ? 'resizing' : 'dragging'));
		var lt = document.getElementById('pointer-l');
		lt.style.left = pos.left + 'px';				// position left pointer to selected element move/resize left edge
		var rt = document.getElementById('pointer-r');
		rt.style.left = (pos.left + pos.width + 2) + 'px';	// position right pointer to selected element left position plus element width
		var tp = document.getElementById('pointer-t');
		tp.style.top = pos.top + 'px';
		var bt = document.getElementById('pointer-b');
		bt.style.top = (pos.top + pos.height + 1) + 'px';
	};
	dragresize.ondragend = function(elem, isResize)
	{
		if (c_log)
			console.log(elem.id+ ' ended being '+ (isResize ? 'resized' : 'dragged'));
		removeClass(dragresize.element, (isResize ? 'resizing' : 'dragging'));
	};
	dragresize.ondragblur = function(elem)
	{
		if (c_log)
			console.log(elem.id+ ' lost drag focus');
	};
	//dragresize.apply(main);
	dragresize.apply(document);			// listen to document mouse events
	main.appendChild(box);
/*	animate({											// animate tag box onto main form
		duration: 1000,
		timing: function(timeFraction) {
			return timeFraction;
		},
		draw: function(progress) {
			//b.style.left = progress * 200 + 'px';
			//b.style.left += progress * 100;
/ *			while (b.offsetLeft < (mainPos.x + main.offsetWidth - b.offsetWidth))
			{* /
				b.offsetLeft += progress * 10;
				b.style.left = b.offsetLeft + 'px';
/ *			}* /
		}
	});*/
	//var top = ((dragresize.minHeight+5) * (kids-1));	// set position new element to avoid previous
	var gapX = 20;						// minimum pixels between horizontal elements
	var gapY = 5;						//	"	"	"	"	"	" vertical	"	"
	var maxX = main.offsetWidth;		// maximum pixels per row
	var maxY = main.offsetHeight;
	var boxX = dragresize.minWidth;		// mimimum horizontal pixels of item
	var boxY = dragresize.minHeight;	//	"	"  vertical		"	"	"
	var top = ((boxY+gapY) * num);		// set position new element to avoid previous
	var left = 0;
	while (top+boxY+gapY > maxY)
	{
		top -= maxY;
		left += boxX+gapX;
	}
	box.style.top = top + 'px';
	if (left)
		box.style.left = left + 'px';
}


/**
 * Save contents of tags via ajax
 */
function saveForm(e) {
//	e.preventDefault();
	var obj = [];
	//const regEx = #^(.*)></.*>$#g;
	//const regEx = new RegExp('^([^(</)]*)(</.*)?$', 'g');
//	const regEx = new RegExp('^([^(</)]*)$', 'g');
	const elems = document.querySelectorAll('.form-element');
	for(i=0; i<elems.length; i++) {						// assemble details for each tag
		var elem = elems[i];
		var arr = {};
		arr.id = elem.id;				// store tag ID
		try {							// attempt to set element size values for form
			arr.x = elem.style.left || (0 + 'px');	// store tag position
			elem.parentNode.querySelector('#real-'+ elem.id+ '-left').value = arr.x;
			arr.y = elem.style.top || (0 + 'px');
			elem.parentNode.querySelector('#real-'+ elem.id+ '-top').value = arr.y;
			arr.w = elem.style.width || (elem.clientWidth + 'px');
			elem.parentNode.querySelector('#real-'+ elem.id+ '-width').value = arr.w;
			arr.h = elem.style.height || (elem.clientHeight + 'px');
			elem.parentNode.querySelector('#real-'+ elem.id+ '-height').value = arr.h;
			arr.text = elem.querySelector('input').value;
			elem.parentNode.querySelector('#real-'+ elem.id+ '-text').value = arr.text;
		} catch(err){}
		//var inner = elem.innerHTML.substr(1, elem.innerHTML.length-2);		// store tag inside
//		const first = elem.firstElementChild;
//		var inner = first.substr(1);		// store tag inside
/*		var parser = new DOMParser();
		var inner = parser.parseFromString(elem.innerHTML, 'text/html');
		console.log('parser inner:');
		console.log(inner);
		const match = elem.innerHTML.match(/<([^>]*)>/);
		inner = match[1];
		//var match = regEx.exec(inner);
		//var split = inner.split("</");
		//var s = inner.split(">").join('').split('');
		var s = inner.split(">").filter(n => n);
//		var split = inner.split(/>(<\/.*>)?/);
//		var match = inner.matchAll(regEx) || [""];
	//	console.log('transform: '+ inner+ ' to:');
//		console.log(match);
//		s = s.trim;
	//	console.log(s);
//		arr.inner = match[1] || "";
		arr.inner = s[0] || "";
		obj.push(arr);*/
	}
	var myajax = new MyAjax(jsVar.BASE + 'getAjax.php'/*'gethint.php'*/, {
		//method: 'GET',
		method: 'POST',
		//url: 'gethint.php',//jsVar.BASE + 'saveForm.php', 
		onSuccess: function(response,xhr){
		//	e.preventDefault();
        //document.getElementById("txtHint").innerHTML = this.responseText;
/*        document.getElementById("txtHint").innerHTML = response;*/
			console.log('** xhr success');
//			console.log('xhr:');
			console.log(xhr);
		//	console.log('response:' + response);
			console.log('* ^xhr response:');
			console.log(response);
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
		//data: "action=hint&request=print&q="+ str,
		data: {action: "form_element_pos", obj: arr},
		headers: null,
		log: true
	});
/*  if (str.length == 0) { 
    document.getElementById("txtHint").innerHTML = "";
    return;
  } else {*/
	  myajax.makeRequest();
/*  }*/
/*	createCORSRequest({									// send via ajax
		method: 'POST',
		url: jsVar.BASE + 'saveForm.php', 
		callbackSuccess: function(xhr,response){
			e.preventDefault();
			console.log('** xhr success');
//			console.log('xhr:');
			console.log(xhr);
		//	console.log('response:' + response);
			console.log('* ^xhr response:');
			console.log(response);
		},
		callbackError: function(xhr,error){
			console.log('** xhr failure');
//			console.log('xhr:' + xhr);
//			console.log('xhr:');
			console.log(xhr);
//			console.log('error:' + error);
			console.log('* ^xhr error:');
			console.log(error);
		},
		data: {obj},
		headers: null,
		log: true
	});*/
/*	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function()
	{
		if (this.readyState == readyStates.DONE && this.status == 200)
		{
//			if (arr.success)
//				arr.success();
			console.log('success');
			console.log(out);
		} else {
		//	if (arr.failure)
		//		arr.failure();
			console.log('failure');
			console.log(out);
		}
//		if (arr.done)
//			arr.done();
	};
	xhttp.open("POST", 'saveForm.php', true);
	xhttp.send({obj});*/
}
function fluid(grid,columns){
	var colCount = columns.length;
	var gridWidth = grid.clientWidth;
	var colWidth = gridWidth / colCount;
	if( parseInt(colWidth,10) === colWidth ){
		var i;
		for(i = 0; i < colCount; i++){
			var col = columns[i];
			for(var n = 0; n < col.length; n++){
				col[n].style.width = colWidth + "px";
			}
		}
	}else{
		var remainder = gridWidth - parseInt(colWidth,10) * colCount;
		var added = 0;
		for(var i = 0; i < colCount; i++){
			var calcWidth;
			if( i > 0 && added < remainder ){
				added++;
				calcWidth = parseInt(colWidth,10)+1 + "px";
			}else{
				calcWidth = parseInt(colWidth,10) + "px";
			}
			var col = columns[i];
			for(var n = 0; n < col.length; n++){
				col[n].style.width = calcWidth;
			}
		}
	}
}

function handleInput(event)
{
	event = event || window.event;
	const target = event.target || event.srcElement;
	addClass(target, 'editing');
	const first = target.firstElementChild;
	first.style.display = "none";
	var input = target.querySelector('.form-element-input');
	if (!input)
	{
		input = document.createElement('input');
		target.appendChild(input)
	}
	removeClass(input, 'hide');
	input.focus();
	const done = function(e)
	{
		if ((e instanceof KeyboardEvent && (e.hasOwnProperty('key') && e.key === "Enter") || (e.hasOwnProperty("which") && e.which === 13)) || (e instanceof MouseEvent))
		{
			removeClass(input, 'editing');
//			removeClass(target, '');
			input.blur();
		}
	};
	addEvent(input, 'click', done);
	addEvent(input, 'keydown', done);
}
/*
function smoothScroll(event)
{
	consol.log('want to smooth scroll');
	event = event || window.event;
	const target = event.target || event.srcElement;
	target.scrollIntoView({
		behavior: 'smooth'
	});
}*/
