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
