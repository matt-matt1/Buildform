var mousex = 0;
var mousey = 0;
var grabx = 0;
var graby = 0;
var orix = 0;
var oriy = 0;
var elex = 0;
var eley = 0;
var dragobj = null;

function falsefunc() { return false; }	// used to block cascading events

function init()
{
	document.onmousemove = getMouseXY;
	getMouseXY();
}
/*
function 
*/
function getMouseXY(e)	// works on IE6,FF,Moz,Opera7
{ 
	if (!e) e = window.event;	// works on IE, but not NS (we rely on NS passing us the event)
	if (e)
	{ 
		if (e.pageX || e.pageY)
		{ // this doesn't work on IE6!! (works on FF,Moz,Opera7)
			mousex = e.pageX;
			mousey = e.pageY;
		}
		else if (e.clientX || e.clientY)
		{ // works on IE6,FF,Moz,Opera7
			//mousex = e.clientX + document.body.scrollLeft;
			mousex = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
			//mousey = e.clientY + document.body.scrollTop;
			mousey = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
		}
/*		if (e.target.dataset.target)
		{
			const constraint = document.getElementById(e.target.dataset.target);
			const bounds = constraint.getBoundingClientRect();
			mousex = Math.max( Math.min( mousex, bounds.right), bounds.left);
			mousey = Math.max( Math.min( mousey, bounds.bottom), bounds.top);
		}*/
	}
}

function grab(context)
{
	document.onmousedown = falsefunc;	// in NS this prevents cascading of events, thus disabling text selection
	if (context.button !== 0)
		return
	dragobj = context.target;
	dragobj.style.zIndex = 10;	// move it to the top
	grabx = mousex;
	graby = mousey;
	document.onmousemove = drag;
	document.onmouseup = drop;
	elex = orix = dragobj.offsetLeft;
	eley = oriy = dragobj.offsetTop;
	getMouseXY();
}

function drag(e)	// parameter passing is important for NS family 
{
	if (dragobj)
	{
		if (dragobj.className.indexOf('dragging') === -1)
		{
			dragobj.className += (dragobj.className !== '') ? ' ' : '';
			dragobj.className += 'dragging';
			if (grabx === 0 && graby === 0)
			{
				grabx = mousex;
				graby = mousey;
			}
		}
		if (dragobj.dataset.confine)
		{
			var confine;
			if (dragobj.dataset.confine.indexOf('#') === 0)
				confine = document.getElementById( dragobj.dataset.confine.substr(1) );
			elex = Math.min( Math.max( 0, orix + (mousex-grabx)), (confine.offsetWidth - dragobj.offsetWidth) );
			eley = Math.min( Math.max( 0, oriy + (mousey-graby)), (confine.offsetHeight - dragobj.offsetHeight) );
		} else {
			elex = orix + (mousex-grabx);
			eley = oriy + (mousey-graby);
		}
//		dragobj.style.position = "absolute";
		dragobj.style.left = (elex).toString(10) + 'px';
		dragobj.style.top = (eley).toString(10) + 'px';
	}
	getMouseXY(e);
	return false;	// in IE this prevents cascading of events, thus text selection is disabled
}

function drop()
{
	if (dragobj)
	{
		dragobj.style.zIndex = 0;
		dragobj.className = dragobj.className.replace(new RegExp('( |^)' + 'dragging' + '( |$)', 'g'), ' ').trim();
		dragobj = null;
	}
	getMouseXY();
	document.onmousemove = getMouseXY;
	document.onmouseup = null;
	document.onmousedown = null;	// re-enables text selection on NS
}
/**
 * Resize an element on all sides and corners
 *
 * example:
 *	element = document.getElementById("element")
 *	makeResizable(element,10,10)
 * example css:
 *	#element {
  position: absolute;
  background-color: #f1f1f1;
  border: 1px solid #d3d3d3;
  left: 40px;
  top:  40px;
  width: 100px;
  height: 100px;
  border-radius: 5px;
}
 */
class Edge {
	static top(inside, outside)
	{
		const elem = document.createElement('div');
		elem.style.width = '100%';
		elem.style.width = 'calc(100% - ' + (2*inside) + 'px)';//calc(100% - 5px)
		elem.style.height = (inside + outside) + 'px';
		//elem.style.top = - outside + 'px';
		elem.style.top = 'calc(0% - ' + outside + 'px)';
		//elem.style.left = inside + 'px';
		elem.style.left = 'calc(0% + ' + inside + 'px)';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-top noup nodown';
		return elem;
	}
	static left(inside, outside)
	{
		const elem = document.createElement('div');
		elem.style.height = '100%';
		elem.style.height = 'calc(100% - ' + (2*inside) + 'px)';
		elem.style.width = (inside + outside) + 'px';
		elem.style.top = inside + 'px';
		elem.style.left = - outside + 'px';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-left noleft noright';
		return elem;
	}
	static bottom(inside, outside)
	{
		const elem = document.createElement('div');
		elem.style.width = '100%';
		elem.style.width = 'calc(100% - ' + (2*inside) + 'px)';
		elem.style.height = (inside + outside) + 'px';
		elem.style.bottom = - outside + 'px';
		elem.style.left = inside + 'px';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-bottom noup';
		return elem;
	}
	static right(inside, outside)
	{
		const elem = document.createElement('div');
		elem.style.height = '100%';
		elem.style.height = 'calc(100% - ' + (2*inside) + 'px)';
		elem.style.width = (inside + outside) + 'px';
		elem.style.top = inside + 'px';
		elem.style.right = - outside + 'px';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-right noleft';
		return elem;
	}
	static topLeft(inside, outside)
	{
		const elem = document.createElement('div');
		elem.style.width = (inside + outside) + 'px';
		elem.style.height = (inside + outside) + 'px';
		elem.style.top = - outside + 'px';
		elem.style.left = - outside + 'px';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-top-left noup noleft';
		return elem;
	}
	static bottomLeft(inside, outside)
	{
		const elem = document.createElement('div');
		elem.style.width = (inside + outside) + 'px';
		elem.style.height = (inside + outside) + 'px';
		elem.style.bottom = - outside + 'px';
		elem.style.left = - outside + 'px';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-bottom-left noleft nodown noright';
		return elem;
	}
	static bottomRight(inside, outside)
	{
		const elem = document.createElement('div');
		elem.style.width = (inside + outside) + 'px';
		elem.style.height = (inside + outside) + 'px';
		elem.style.bottom = - outside + 'px';
		elem.style.right = - outside + 'px';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-bottom-right';
		return elem;
	}
	static topRight(inside, outside)
	{
		const elem = document.createElement('div');
		elem.style.width = (inside + outside) + 'px';
		elem.style.height = (inside + outside) + 'px';
		elem.style.top = - outside + 'px';
		elem.style.right = - outside + 'px';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-top-right noup';
		return elem;
	}
}

function makeResizable(element, minW = 100, minH = 100, inside = 5, outside = 10)
{
	if (element.dataset.confine /*&& confine.offsetHeight*/)
	{
		var confine = element.dataset.confine;
		if (confine.indexOf('#') === 0)
			confine = document.getElementById( confine.substr(1) );
	}

	const top = Edge.top(inside, outside);
	//top.addEventListener('mousedown',resizeYNegative())
	addEvent( top, 'mousedown', resizeYNegative() );
	element.appendChild(top);

	const bottom = Edge.bottom(inside, outside);
	//bottom.addEventListener('mousedown',resizeYPositive())
	//bottom.addEventListener('mousedown',resizeXNegative())	// mistake
	addEvent( bottom, 'mousedown', resizeYPositive() );
	element.appendChild(bottom);

	const left = Edge.left(inside, outside);
	//left.addEventListener('mousedown',resizeXNegative())
	addEvent( left, 'mousedown', resizeXNegative() );
	element.appendChild(left);

	const right = Edge.right(inside, outside);
	//right.addEventListener('mousedown',resizeXPositive())
	addEvent( right, 'mousedown', resizeXPositive() );
	element.appendChild(right);

	const corner1 = Edge.topLeft(inside, outside);
	//corner1.addEventListener('mousedown',resizeXNegative())
	//corner1.addEventListener('mousedown',resizeYNegative())
	addEvent( corner1, 'mousedown', resizeXNegative() );
	addEvent( corner1, 'mousedown', resizeYNegative() );
	element.appendChild(corner1);

	const corner2 = Edge.topRight(inside, outside);
	//corner2.addEventListener('mousedown',resizeXPositive())
	//corner2.addEventListener('mousedown',resizeYNegative())
	addEvent( corner2, 'mousedown', resizeXPositive() );
	addEvent( corner2, 'mousedown', resizeYNegative() );
	element.appendChild(corner2);

	const corner3 = Edge.bottomLeft(inside, outside);
	//corner3.addEventListener('mousedown',resizeXNegative())
	//corner3.addEventListener('mousedown',resizeYPositive())
	addEvent( corner3, 'mousedown', resizeXNegative() );
	addEvent( corner3, 'mousedown', resizeYPositive() );
	element.appendChild(corner3);

	const corner4 = Edge.bottomRight(inside, outside);
	//corner4.addEventListener('mousedown',resizeXPositive())
	//corner4.addEventListener('mousedown',resizeYPositive())
	addEvent( corner4, 'mousedown', resizeXPositive() );
	addEvent( corner4, 'mousedown', resizeYPositive() );
	element.appendChild(corner4);


	function get_int_style(key)
	{
		return parseInt(window.getComputedStyle(element).getPropertyValue(key));
	}

	function resizeXPositive()	// right
	{
		let offsetX
		function dragMouseDown(e) {
			if(e.button !== 0) return
			e = e || window.event;
			e.preventDefault();
			const {clientX} = e;
			offsetX = clientX - element.offsetLeft - get_int_style('width');
			document.addEventListener('mouseup', closeDragElement)
			document.addEventListener('mousemove', elementDrag)
		}
		
		function elementDrag(e) {
			const {clientX} = e;
			let x = clientX - element.offsetLeft - offsetX
			if(x < minW)
			{
				x = minW;
				e.target.className += (e.target.className !== '') ? ' ' : '';
				e.target.className += 'noleft';
			} else if (e.target.className.indexOf('noleft') !== -1) {
				e.target.className = e.target.className.replace(new RegExp('( |^)' + 'noleft' + '( |$)', 'g'), ' ').trim();
			}
			//element.style.left = Math.max(x, 0) + 'px';
			element.style.width = ((confine && confine.offsetWidth) ? Math.min(x, confine.offsetWidth) : x) + 'px';
			//element.style.width = x + 'px';
			//element.style.left = ((confine && confine.offsetWidth) ? Math.min(x, confine.offsetWidth) : x) + 'px';
		}
		
		function closeDragElement() {
			document.removeEventListener("mouseup", closeDragElement);  
			document.removeEventListener("mousemove", elementDrag);
		}
		return dragMouseDown
	}

	function resizeYPositive()	// downward
	{
		let offsetY
		function dragMouseDown(e) {
			if(e.button !== 0) return
			e = e || window.event;
			e.preventDefault();
			const {clientY} = e;
			offsetY = clientY - element.offsetTop - get_int_style('height');
	
			document.addEventListener('mouseup',closeDragElement)
			document.addEventListener('mousemove',elementDrag)
		}
		
		function elementDrag(e) {
			const {clientY} = e;
			let y =  clientY - element.offsetTop - offsetY;
			if(y < minH)
			{
				y = minH;
				e.target.className += (e.target.className !== '') ? ' ' : '';
				e.target.className += 'noup';
			} else if (e.target.className.indexOf('noup') !== -1) {
				e.target.className = e.target.className.replace(new RegExp('( |^)' + 'noup' + '( |$)', 'g'), ' ').trim();
			}
			//element.style.height = y + 'px';
			element.style.height = ((confine && confine.offsetHeight) ? Math.min(y, confine.offsetHeight) : y) + 'px';
		}
		
		function closeDragElement() {
			document.removeEventListener("mouseup", closeDragElement);  
			document.removeEventListener("mousemove", elementDrag);
		}
		return dragMouseDown
	}

	function resizeXNegative()	// left
	{
		let offsetX
		let startX
		let startW
		let maxX
		function dragMouseDown(e) {
			if(e.button !== 0) return
			e = e || window.event;
			e.preventDefault();
			const {clientX} = e;
			startX = get_int_style('left')
//			if(startX <= 0) return
			startW = get_int_style('width')
			offsetX = clientX - startX;
			maxX = startX + startW - minW
	
			document.addEventListener('mouseup',closeDragElement)
			document.addEventListener('mousemove',elementDrag)
		}
		
		function elementDrag(e) {
			const {clientX} = e;
			let x = clientX - offsetX
			let w = startW + startX - x
			if(w < minW)
			{
				w = minW;
				e.target.className += (e.target.className !== '') ? ' ' : '';
				e.target.className += 'noright';
			} else if (e.target.className.indexOf('noright') !== -1) {
				e.target.className = e.target.className.replace(new RegExp('( |^)' + 'noright' + '( |$)', 'g'), ' ').trim();
			}
			if(x > maxX)
			{
				x = maxX;
				e.target.className += (e.target.className !== '') ? ' ' : '';
				e.target.className += 'noright';
			} else if (e.target.className.indexOf('noright') !== -1) {
				e.target.className = e.target.className.replace(new RegExp('( |^)' + 'noright' + '( |$)', 'g'), ' ').trim();
			}
			//if(x > maxX) x = Math.max(maxX, 0);
			//element.style.left = x + 'px';
			element.style.left = ((confine) ? Math.max(x, 0) : x) + 'px';
/*			if (confine && confine.offsetWidth)
			{
				element.style.width = Math.min(w, confine.offsetWidth) + 'px';
			} else {
				element.style.width = w + 'px';
			}*/
			element.style.width = ((confine && confine.offsetWidth) ? Math.min(w, confine.offsetWidth) : w) + 'px';
		}
		
		function closeDragElement() {
			document.removeEventListener("mouseup", closeDragElement);  
			document.removeEventListener("mousemove", elementDrag);
		}
		return dragMouseDown
	}

	function resizeYNegative()	// upward
	{
		let offsetY
		let startY
		let startH
		let maxY
		function dragMouseDown(e) {
			if(e.button !== 0) return
			e = e || window.event;
			e.preventDefault();
			const {clientY} = e;
			startY = get_int_style('top')
			startH = get_int_style('height')
			offsetY = clientY - startY;
			maxY = startY + startH - minH 
	
			document.addEventListener('mouseup',closeDragElement,false)
			document.addEventListener('mousemove',elementDrag,false)
		}
		
		function elementDrag(e) {
			const {clientY} = e;
			let y = clientY - offsetY
			let h = startH + startY - y
			if(h < minH)
			{
				h = minH;
				addClass( e.target, 'nodown' );
				//e.target.className += (e.target.className !== '') ? ' ' : '';
				//e.target.className += 'nodown';
			} else if (e.target.className.indexOf('nodown') !== -1) {
				e.target.className = e.target.className.replace(new RegExp('( |^)' + 'nodown' + '( |$)', 'g'), ' ').trim();
			}
			if(y > maxY)
			{
				y = Math.max(maxY, 0);
				addClass( e.target, 'nodown' );
				//e.target.className += (e.target.className !== '') ? ' ' : '';
				//e.target.className += 'nodown';
			} else if (e.target.className.indexOf('nodown') !== -1) {
				e.target.className = e.target.className.replace(new RegExp('( |^)' + 'nodown' + '( |$)', 'g'), ' ').trim();
			}
//			element.style.top = y + 'px';
//			element.style.height = h + 'px';
/*		if (e.target.parentElement.dataset.confine / *&& confine.offsetHeight* /)
		{
			var confine = e.target.parentElement.dataset.confine;
			if (confine.indexOf('#') === 0)
				confine = document.getElementById( confine.substr(1) );
		//	elex = Math.min( Math.max( 0, orix + (mousex-grabx)), (confine.offsetWidth - dragobj.offsetWidth) );
			let y = Math.min( Math.max( 0, oriy + (mousey-graby)), (confine.offsetHeight - dragobj.offsetHeight) );
			let h = Math.min( Math.max( 0, oriy + (mousey-graby)), (confine.offsetHeight - dragobj.offsetHeight) );
		} else {
		//	elex = orix + (mousex-grabx);
		}*/
			element.style.height = ((confine && confine.offsetHeight) ? Math.min(h, confine.offsetHeight) : h) + 'px';
			element.style.top = ((confine) ? Math.max(y, 0) : y) + 'px';
		}
		
		function closeDragElement() {
			document.removeEventListener("mouseup", closeDragElement);  
			document.removeEventListener("mousemove", elementDrag);
		}
		return dragMouseDown
	}
}
