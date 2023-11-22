/*

DragResize v1.0
(c) 2005-2013 Angus Turnbull, TwinHelix Designs http://www.twinhelix.com
Modified by Matthew Scott (http://yumatechnical.com)

Licensed under the GNU LGPL, version 3 or later:
https://www.gnu.org/copyleft/lesser.html
This is distributed WITHOUT ANY WARRANTY; without even the implied
warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

*/


// Common API code.

if (typeof addEvent !== 'function')
{
	var addEvent = function(o, t, f, l)
	{
		var d = 'addEventListener', n = 'on' + t, rO = o, rT = t, rF = f, rL = l;
		if (o[d] && !l)
			return o[d](t, f, false);
		if (!o._evts)
			o._evts = {};
		if (!o._evts[t])
		{
			o._evts[t] = o[n] ? { b: o[n] } : {};
			o[n] = new Function('e',
				'var r = true, o = this, a = o._evts["' + t + '"], i; for (i in a) {' +
				 'o._f = a[i]; r = o._f(e||window.event) != false && r; o._f = null;' +
				 '} return r');
			if (t !== 'unload') addEvent(window, 'unload', function() {
				removeEvent(rO, rT, rF, rL);
			});
		}
		if (!f._i)
			f._i = addEvent._i++;
		o._evts[t][f._i] = f;
	};
	addEvent._i = 1;
	var removeEvent = function(o, t, f, l)
	{
		var d = 'removeEventListener';
		if (o[d] && !l)
			return o[d](t, f, false);
		if (o._evts && o._evts[t] && f._i)
			delete o._evts[t][f._i];
	};
}


function cancelEvent(e, c)
{
	e.returnValue = false;
	if (e.preventDefault)
		e.preventDefault();
	if (c)
	{
		e.cancelBubble = true;
///		if (e.stopPropagation)
///			e.stopPropagation();
	}
}



function arrayRemove(arr, value) {
	return arr.filter(function(ele){
		return ele !== value;
	});
}






// *** DRAG/RESIZE CODE ***

function DragResize(myName, config)
{
	var props = {
		myName: myName,					// Name of the object.
		enabled: true,				   // Global toggle of drag/resize.
		handles: ['tl', 'tm', 'tr',
			'ml', 'mr', 'bl', 'bm', 'br'], // Array of drag handles: top/mid/bot/right.
		isElement: function(elm) {},				 // Function ref to test for an element.
		isHandle: function(elm) {},				  // Function ref to test for move handle.
		element: null,				   // The currently selected element.
		handle: null,				  // Active handle reference of the element.
		minWidth: 10, minHeight: 10,	 // Minimum pixel size of elements.
		minLeft: 0, maxLeft: 9999,		// Bounding box area, in pixels.
		minTop: 0, maxTop: 9999,
		zIndex: 1,						// The highest Z-Index yet allocated.
		mouseX: 0, mouseY: 0,			// Current mouse position, recorded live.
		lastMouseX: 0, lastMouseY: 0,	// Last processed mouse positions.
		mOffX: 0, mOffY: 0,			  // A known offset between position & mouse.
		elmX: 0, elmY: 0,				// Element position.
		elmW: 0, elmH: 0,				// Element size.
		allowBlur: true,				 // Whether to allow automatic blur onclick.
		ondragfocus: function(elm) {},				// Event handler functions.
		ondragstart: function(elem, isResize) {},
		ondragmove: function(elem, isResize, pos) {},
		ondragend: function(elem, isResize) {},
		ondragblur: function(elem) {},
//		log: false,
		log: true,
		dragKeyUp: ['up', 'shift+k'],
		dragKeyLeft: ['left', 'shift+h'],
		dragKeyDown: ['down', 'shift+j'],
		dragKeyRight: ['right', 'shift+l'],
		resizeExpandTopEdgeKey: ['alt+up', 'alt+shift+k'],//['ArrowUp', ['AltLeft', 'AltRight']],//KEY.upArrow + (1000*KEY.alt),
		resizeExpandLeftEdgeKey: ['alt+left', 'alt+shift+h'],//['ArrowLeft', ['AltLeft', 'AltRight']],//KEY.leftArrow + (1000*KEY.alt),
		resizeExpandBottomEdgeKey: ['alt+down', 'alt+shift+j'],//['ArrowDown', ['AltLeft', 'AltRight']],//'altArrowDown',
		resizeExpandRightEdgeKey: ['alt-right', 'alt+shift+l'],//['ArrowRight', ['AltLeft', 'AltRight']],//'altArrowRight',
		resizeReduceTopEdgeKey: ['ctrl+up', 'ctrl+shift+k'],//['ArrowUp', ['ControlLeft', 'ControlRight']],//KEY.upArrow + (1000*KEY.ctrl),
		resizeReduceLeftEdgeKey: ['ctrl+left', 'ctrl+shift+h'],//['ArrowLeft', ['ControlLeft', 'ControlRight']],//'ctrlArrowLeft',
		resizeReduceBottomEdgeKey: ['ctrl+down', 'ctrl+shift+j'],//['ArrowDown', ['ControlLeft', 'ControlRight']],//'ctrlArrowDown',
		resizeReduceRightEdgeKey: ['ctrl+right', 'ctrl+shift+l'],//['ArrowRight', ['ControlLeft', 'ControlRight']],//'ctrlArrowRight',
		resizeExpandTopLeftCornerKey: ['alt+meta+up alt+meta+left', 'alt+meta+left alt+meta+up'],//[['ArrowLeft', 'ArrowUp'], ['AltLeft', 'AltRight'], ['MetaLeft', 'MetaRight']],//'UNKaltArrowUp',
		resizeExpandTopRightCornerKey: ['alt+meta+up alt+meta+right', 'alt+meta+right alt+meta+up'],
		resizeExpandBottomLeftCornerKey: ['alt+meta+downalt+meta+left', 'alt+meta+left alt+meta+down'],
		resizeExpandBottomRightCornerKey: ['alt+meta+down alt+meta+right', 'alt+meta+right alt+meta+down'],
		resizeReduceTopLeftCornerKey: ['ctrl+meta+up ctrl+meta+left', 'ctrl+meta+left ctrl+meta+up'],//[['ArrowLeft', 'ArrowUP'], ['ControlLeft', 'ControlRight'], ['MetaLeft', 'MetaRight']],//'UNKctrlArrowUp'
		resizeReduceTopRightCornerKey: ['ctrl+meta+up ctrl+meta+right', 'ctrl+meta+right ctrl+meta+up'],
		resizeReduceBottomLeftCornerKey: ['ctrl+meta+down ctrl+meta+left', 'ctrl+meta+left ctrl+meta+down'],
		resizeReduceBottomRightCornerKey: ['ctrl+meta+down ctrl+meta+right', 'ctrl+meta+right ctrl+meta+down'],
		onMove: function(id, left, top, width, height) {},
	};

	for (var p in props)
		this[p] = (typeof config[p] === 'undefined') ? props[p] : config[p];
}


DragResize.prototype.apply = function(node)
{
	// Adds object event handlers to the specified DOM node.

	var obj = this;
	addEvent(node, 'mousedown',	function(e) { obj.mouseDown(e); } );
	addEvent(node, 'mousemove',	function(e) { obj.mouseMove(e); } );
	addEvent(node, 'mouseup',	function(e) { obj.mouseUp(e); } );
	addEvent(node, 'touchstart', function(e) { obj.mouseDown(e); } );
	addEvent(node, 'touchmove', function(e) { obj.mouseMove(e); } );
	addEvent(node, 'touchend', function(e) { obj.mouseUp(e); } );
	keyEvents = ["dragKeyUp", "dragKeyLeft", "dragKeyDown", "dragKeyRight", "resizeExpandTopEdgeKey", "resizeExpandLeftEdgeKey", "resizeExpandBottomEdgeKey", "resizeExpandRightEdgeKey", "resizeReduceTopEdgeKey", "resizeReduceLeftEdgeKey", "resizeReduceBottomEdgeKey", "resizeReduceRightEdgeKey", "resizeExpandTopLeftCornerKey", "resizeExpandTopRightCornerKey", "resizeExpandBottomLeftCornerKey", "resizeExpandBottomRightCornerKey", "resizeReduceTopLeftCornerKey", "resizeReduceTopRightCornerKey", "resizeReduceBottomLeftCornerKey", "resizeReduceBottomRightCornerKey"];
	for (i=0; i<keyEvents.length; i++)
	{
		const ev = keyEvents[i];
		if (obj.hasOwnProperty(ev) && obj[ev] !== undefined)
		{
			Mousetrap.bind(obj[ev], function(e)
			{
				if (e.preventDefault) {
					e.preventDefault();
				} else {
					// internet explorer
					e.returnValue = false;
				}
				obj.keyboardAction(e, ev);
				if (e) {
					if (e.defaultPrevented) {
						return;
///					if(e.stopPropagation){
///						e.stopPropagation();
					} else if (window.event) {
						window.event.cancelBubble = true;
					}
				}
				return false;
			});
		}
	}
};


DragResize.prototype.keyboardAction = function(e, action) { //with (this)
{
	if (log)
	{
		console.log('pressed '+ action);
	}
//	var elm = e.target || e.srcElement;
	//var sel = document.getElementByClassName('selected');
	var elm = document.querySelector('.selected.drsElement');
	elmX = parseInt(elm.style.left) || minLeft;
	elmY = parseInt(elm.style.top) || minTop;
	elmW = elm.offsetWidth || minWidth;
	elmH = elm.offsetHeight || minHeight;
console.log('element was at [(left) '+ elmX+ ', (top) '+ elmY+ ', (width) '+ elmW+ ', (height) '+ elmH+ ']');
console.log('limits: minLeft '+ minLeft+ '/'+ maxLeft+ ', minTop '+ minTop+ '/'+ maxTop+ ', minWidth '+ minWidth+ ', minHeight '+ minHeight+ '');
	if (!elmX)	//		handles: ['tl', 'tm', 'tr', 'ml', 'mr', 'bl', 'bm', 'br'], // Array of drag handles: top/mid/bot/right.
	{
		elmX = 0;
		if (["dragKeyLeft", "resizeExpandLeftEdgeKey"].indexOf(action) !== -1)
			return;
	} else if (elm && elmX > maxLeft)
	{
		elmX = element.parentElement.offsetWidth;
		if (["dragKeyRight", "resizeExpandRightEdgeKey"].indexOf(action) !== -1)
			return;
	}
//	if (this.element && this.element.style && this.element.style.visibility)
//		resizeHandleSet(this.element, true);
	if (!elmY)
	{
		elmY = 0;//minLeft;
		if (["dragKeyUp", "resizeExpandTopEdgeKey"].indexOf(action) !== -1)
			return;
	} else if (elm && elmY > maxLeft)
	{
		elmY = element.parentElement.offsetHeight;
		if (["dragKeyRight", "resizeExpandRightEdgeKey"].indexOf(action))
			return;
	}
	var dx = 0, dy = 0, diffX = 0, diffY = 0;	// initial has no difference
	// shift x &/ y
	if (["dragKeyLeft", "resizeExpandLeftEdgeKey", "resizeExpandTopLeftCornerKey", "resizeExpandBottomLeftCornerKey"].indexOf(action) !== -1 && elmX >= minLeft)
	{
		addClass(elm, 'dragging');
		elmX -= 1;
		if (log)
			console.log('. reduced x');
		removeClass(elm, 'dragging');
	}
	if (["dragKeyUp", "resizeExpandTopEdgeKey", "resizeExpandTopLeftCornerKey", "resizeExpandTopRightCornerKey"].indexOf(action) !== -1 && elmY > minTop)
	{
		addClass(elm, 'dragging');
		elmY -= 1;
		if (log)
			console.log('. reduced y');
		removeClass(elm, 'dragging');
	}
	if (["dragKeyRight", "resizeReduceLeftEdgeKey", "resizeReduceTopLeftCornerKey", "resizeReduceBottomLeftCornerKey"].indexOf(action) !== -1 && (elmX + elmW) < maxLeft)
	{
		addClass(elm, 'dragging');
		elmX += 1;
		if (log)
			console.log('. increased x');
		removeClass(elm, 'dragging');
	}
	if (["dragKeyDown", "resizeReduceBottomEdgeKey", "resizeReduceBottomLeftCornerKey", "resizeReduceBottomRightCornerKey"].indexOf(action) !== -1 && (elmY + elmH) < maxTop)
	{
		addClass(elm, 'dragging');
		elmY += 1;
		if (log)
			console.log('. increased y');
		removeClass(elm, 'dragging');
	}
//	if (!elm.className || elm.className.indexOf('drsMoveHandle') === -1)
//		return;
	// expand w &/ h
	if (["resizeExpandLeftEdgeKey", "resizeExpandRightEdgeKey", "resizeExpandTopLeftCornerKey", "resizeExpandBottomLeftCornerKey", "resizeExpandTopRightCornerKey", "resizeExpandBottomRightCornerKey"].indexOf(action) !== -1 && elmW >= minWidth && elmX > minLeft)
	{
		addClass(elm, 'resizing');
		elmW += 1;
//			elmX -= 1;
		if (log)
			console.log('. increased w');
		removeClass(elm, 'resizing');
	}
	if (["resizeExpandTopEdgeKey", "resizeExpandTopLeftCornerKey", "resizeExpandBottomEdgeKey", "resizeExpandTopRightCornerKey", "resizeExpandBottomLeftCornerKey", "resizeExpandBottomRightCornerKey"].indexOf(action) !== -1 && elmH >= minHeight && elmY > minTop)
	{
		addClass(elm, 'resizing');
		elmH += 1;
//			elmY -= 1;
		if (log)
			console.log('. increased h');
		removeClass(elm, 'resizing');
	}
	// reduce w &/ h
	if (["resizeReduceLeftEdgeKey", "resizeReduceTopLeftCornerKey", "resizeReduceBottomLeftCornerKey", "resizeReduceRightEdgeKey", "resizeReduceTopRightCornerKey", "resizeReduceBottomRightCornerKey"].indexOf(action) !== -1 && elmW > minWidth && elmX > minLeft)
	{
		addClass(elm, 'resizing');
		elmW -= 1;
//			elmX += 1;
		if (log)
			console.log('. reduced w');
		removeClass(elm, 'resizing');
	}
	if (["resizeReduceBottomEdgeKey", "resizeReduceTopLeftCornerKey", "resizeReduceBottomLeftCornerKey", "resizeReduceTopEdgeKey", "resizeReduceBottomRightCornerKey", "resizeReduceTopRightCornerKey"].indexOf(action) !== -1 && elmH > minHeight && elmY > minTop)
	{
		addClass(elm, 'resizing');
		elmH -= 1;
//			elmY += 1;
		if (log)
			console.log('. reduced h');
		removeClass(elm, 'resizing');
	}
	// Assign new info back to the element, with minimum dimensions.
	with (elm.style)
	{
		left =   elmX + 'px';
		width =  elmW + 'px';
		top =    elmY + 'px';
		height = elmH + 'px';
	}
	// Evil, dirty, hackish Opera select-as-you-drag fix.
	if (window.opera) {
		if (document.documentElement) {
			var oDF;
			oDF = document.getElementById('op-drag-fix');
			if (!oDF) {
				var oDF = document.createElement('input');
				oDF.id = 'op-drag-fix';
				oDF.style.display = 'none';
				document.body.appendChild(oDF);
			}
			oDF.focus();
		}
	}

	if (ondragmove)
		ondragmove(element, isResize, {left: elmX, width: elmW, top: elmY, height: elmH});

	// Stop a normal drag event.
	cancelEvent(e);
	}
}


	// Selects an element for dragging.
DragResize.prototype.select = function(newElement) { with (this)
{
	if (!document.getElementById || !enabled)
		return;

	// Activate and record our new dragging element.
	if (newElement && (newElement !== element) && enabled)
	{
		element = newElement;
		addClass(element, 'selected');
		// Elevate it and give it resize handles.
		element.style.zIndex = ++zIndex;
		if (this.resizeHandleSet)
			this.resizeHandleSet(element, true);
		// Record element attributes for mouseMove().
		elmX = parseInt(element.style.left);
		elmY = parseInt(element.style.top);
		elmW = element.offsetWidth;
		elmH = element.offsetHeight;
		if (ondragfocus)
			this.ondragfocus(element);
	}
}};


	// Immediately stops dragging an element. If 'delHandles' is true, this
	// remove the handles from the element and clears the element flag,
	// completely resetting the .
DragResize.prototype.deselect = function(delHandles) { with (this)
{
	if (!document.getElementById || !enabled) return;

	if (delHandles)
	{
		if (ondragblur)
			this.ondragblur(element);
		if (this.resizeHandleSet)
			this.resizeHandleSet(element, false);
		removeClass(element, 'selected');
		element = null;
	}

	handle = null;
	mOffX = 0;
	mOffY = 0;
}};



	// Suitable elements are selected for drag/resize on mousedown.
	// We also initialise the resize boxes, and drag parameters like mouse position etc.
DragResize.prototype.mouseDown = function(e) { with (this)
{
	if (e.button !== 0)
		return;
	if (!document.getElementById || !enabled)
		return true;

	// Fake a mousemove for touch devices.
	if (e.touches && e.touches.length) this.mouseMove(e);

	var elm = e.target || e.srcElement,
		newElement = null,
		newHandle = null,
		hRE = new RegExp(myName + '-([trmbl]{2})', '');

	while (elm)
	{
		// Loop up the DOM looking for matching elements. Remember one if found.
		if (elm.className)
		{
			if (!newHandle && (hRE.test(elm.className) || isHandle(elm)))
				newHandle = elm;
			if (isElement(elm))
			{
				newElement = elm;
				break
			}
		}
		elm = elm.parentNode;
	}

	// If this isn't on the last dragged element, call deselect(),
	// which will hide its handles and clear element.
	if (element && (element !== newElement) && allowBlur)
		deselect(true);

	// If we have a new matching element, call select().
	if (newElement && (!element || (newElement === element)))
	{
		// Stop mouse selections if we're dragging a handle.
		if (newHandle)
			cancelEvent(e);
		select(newElement, newHandle);
		handle = newHandle;
		if (handle && ondragstart)
			this.ondragstart(element, hRE.test(handle.className));
	}
}};


	// This continually offsets the dragged element by the difference between the
	// last recorded mouse position (mouseX/Y) and the current mouse position.
DragResize.prototype.mouseMove = function(e) { with (this)
{
	if (!document.getElementById || !enabled)
		return true;
	e = e || window.event;

	if (!elmX)	//		handles: ['tl', 'tm', 'tr', 'ml', 'mr', 'bl', 'bm', 'br'], // Array of drag handles: top/mid/bot/right.
	{
		elmX = 0;
/*		if (this.handles.indexOf('tl') !== -1)
			this.handles = arrayRemove(this.handles, 'tl');
		if (this.handles.indexOf('ml') !== -1)
			this.handles = arrayRemove(this.handles, 'ml');
		if (this.handles.indexOf('bl') !== -1)
			this.handles = arrayRemove(this.handles, 'bl');
	} else {
		if (this.handles.indexOf('tl') === -1)
			this.handles.unshift('tl');
		if (this.handles.indexOf('ml') === -1)
			this.handles.unshift('ml');
		if (this.handles.indexOf('bl') === -1)
			this.handles.unshift('bl');*/
	}
	if (this.element && this.element.style && this.element.style.visibility)
		resizeHandleSet(this.element, true);
	if (!elmY)
	{
		elmY = 0;
/*		if (this.handles.indexOf('tl') !== -1)
			this.handles = arrayRemove(this.handles, 'tl');
		if (this.handles.indexOf('tm') !== -1)
			this.handles = arrayRemove(this.handles, 'tm');
		if (this.handles.indexOf('tr') !== -1)
			this.handles = arrayRemove(this.handles, 'tr');
	} else {
		if (this.handles.indexOf('tl') === -1)
			this.handles.unshift('tl');
		if (this.handles.indexOf('tm') === -1)
			this.handles.unshift('tm');
		if (this.handles.indexOf('tr') === -1)
			this.handles.unshift('tr');*/
	}
	if (e instanceof KeyboardEvent)
	{
		elmX = element.offsetLeft;
		elmY = element.offsetTop;
		var code = ('which' in event) ? event.which : event.code;
		chknum = (code >= 48 && code <= 57) || (code >= 96 && code <= 105 );
		var mod = ( event.altKey || event.ctrlKey || event.shiftKey );
		//return !chknum || mod ;
	}

	// We always record the current mouse/touch position.
	var mt = (e.touches && e.touches.length) ? e.touches[0] : e;
	mouseX = mt.pageX || mt.clientX + document.documentElement.scrollLeft;
	mouseY = mt.pageY || mt.clientY + document.documentElement.scrollTop;
	/*// We always record the current mouse position.
	mouseX = e.pageX || e.clientX + document.documentElement.scrollLeft;
	mouseY = e.pageY || e.clientY + document.documentElement.scrollTop;*/
	// Record the relative mouse movement, in case we're dragging.
	// Add any previously stored & ignored offset to the calculations.
	var diffX = mouseX - lastMouseX + mOffX;
	var diffY = mouseY - lastMouseY + mOffY;
	mOffX = mOffY = 0;
	// Update last processed mouse positions.
	lastMouseX = mouseX;
	lastMouseY = mouseY;

	// That's all we do if we're not dragging anything.
	if (!handle)
		return true;

	// If included in the script, run the resize handle drag routine.
	// Let it create an object representing the drag offsets.
	var isResize = false;
	if (this.resizeHandleDrag && this.resizeHandleDrag(diffX, diffY))
	{
		addClass(this.element, 'resizing');
		isResize = true;
	}
	else
	{
		// If the resize drag handler isn't set or returns false (to indicate the drag was
		// not on a resize handle), we must be dragging the whole element, so move that.
		// Bounds check left-right...
		addClass(this.element, 'dragging');
		var dX = diffX, dY = diffY;
		if (elmX + dX < minLeft)
		{
			if (this.handles.indexOf('tl') !== -1)
				this.handles = arrayRemove(this.handles, 'tl');
			if (this.handles.indexOf('ml') !== -1)
				this.handles = arrayRemove(this.handles, 'ml');
			if (this.handles.indexOf('bl') !== -1)
				this.handles = arrayRemove(this.handles, 'bl');
			mOffX = (dX - (diffX = minLeft - elmX));
		} else if (elmX + elmW + dX > maxLeft)
		{
			if (this.handles.indexOf('tr') !== -1)
				this.handles = arrayRemove(this.handles, 'tr');
			if (this.handles.indexOf('mr') !== -1)
				this.handles = arrayRemove(this.handles, 'mr');
			if (this.handles.indexOf('br') !== -1)
				this.handles = arrayRemove(this.handles, 'br');
			mOffX = (dX - (diffX = maxLeft - elmX - elmW));
		} else {
			if (this.handles.indexOf('tl') === -1)
				this.handles.unshift('tl');
			if (this.handles.indexOf('ml') === -1)
				this.handles.unshift('ml');
			if (this.handles.indexOf('bl') === -1)
				this.handles.unshift('bl');
			if (this.handles.indexOf('tr') === -1)
				this.handles.unshift('tr');
			if (this.handles.indexOf('mr') === -1)
				this.handles.unshift('mr');
			if (this.handles.indexOf('br') === -1)
				this.handles.unshift('br');
		}
	//if (this.element && this.element.style && this.element.style.visibility)
		resizeHandleSet(this.element, true);
		// ...and up-down.
		if (elmY + dY < minTop)
		{
/*			if (this.handles.indexOf('tl') !== -1)
				this.handles = arrayRemove(this.handles, 'tl');
			if (this.handles.indexOf('tm') !== -1)
				this.handles = arrayRemove(this.handles, 'tm');
			if (this.handles.indexOf('tr') !== -1)
				this.handles = arrayRemove(this.handles, 'tr');*/
			mOffY = (dY - (diffY = minTop - elmY));
		} else if (elmY + elmH + dY > maxTop)
		{
/*			if (this.handles.indexOf('bl') !== -1)
				this.handles = arrayRemove(this.handles, 'bl');
			if (this.handles.indexOf('bm') !== -1)
				this.handles = arrayRemove(this.handles, 'bm');
			if (this.handles.indexOf('br') !== -1)
				this.handles = arrayRemove(this.handles, 'br');*/
			mOffY = (dY - (diffY = maxTop - elmY - elmH));
/*		} else {
			if (this.handles.indexOf('tl') === -1)
				this.handles.unshift('tl');
			if (this.handles.indexOf('tm') === -1)
				this.handles.unshift('tm');
			if (this.handles.indexOf('bl') === -1)
				this.handles.unshift('bl');
			if (this.handles.indexOf('tr') === -1)
				this.handles.unshift('tr');
			if (this.handles.indexOf('bm') === -1)
				this.handles.unshift('bm');
			if (this.handles.indexOf('br') === -1)
				this.handles.unshift('br');*/
		}
		elmX += diffX;
		elmY += diffY;
	}
//	if (this.handles)
//		this.handles.filter((item, index) => this.handles.indexOf(item) === index);


	// Assign new info back to the element, with minimum dimensions.
	with (element.style)
	{
		left =   elmX + 'px';
		width =  elmW + 'px';
		top =    elmY + 'px';
		height = elmH + 'px';
	}

	// Evil, dirty, hackish Opera select-as-you-drag fix.
	if (window.opera && document.documentElement)
	{
		var oDF = document.getElementById('op-drag-fix');
		if (!oDF)
		{
			var oDF = document.createElement('input');
			oDF.id = 'op-drag-fix';
			oDF.style.display = 'none';
			document.body.appendChild(oDF);
		}
		oDF.focus();
	}

	if (ondragmove)
		ondragmove(element, isResize, {left: elmX, width: elmW, top: elmY, height: elmH});

	// Stop a normal drag event.
	cancelEvent(e);
}};


DragResize.prototype.mouseUp = function(e) { with (this)
{
	// On mouseup, stop dragging, but don't reset handler visibility.
	if (!document.getElementById || !enabled)
		return;

	// remove verb classes, if present
	removeClass(this.element, 'resizing');
	removeClass(this.element, 'dragging');

	var hRE = new RegExp(myName + '-([trmbl]{2})', '');
	if (handle && ondragend)
		this.ondragend(element, hRE.test(handle.className));
	deselect(false);
}};



/* Resize Code -- can be deleted if you're not using it. */

	// Either creates, shows or hides the resize handles within an element.
DragResize.prototype.resizeHandleSet = function(elm, show) { with (this)
{
	// If we're showing them, and no handles have been created, create 4 new ones.
	if (!elm._handle_tr)
	{
		var h;
		for (h = 0; h < handles.length; h++)
		{
			// Create 4 news divs, assign each a generic + specific class.
			var hDiv = document.createElement('div');
			hDiv.className = myName + ' ' +  myName + '-' + handles[h];
			elm['_handle_' + handles[h]] = elm.appendChild(hDiv);
		}
	}

	// We now have handles. Find them all and show/hide.
	for (var h = 0; h < handles.length; h++)
	{
		if (elm['_handle_' + handles[h]])
			elm['_handle_' + handles[h]].style.visibility = show ? 'inherit' : 'hidden';
	}
}};


	// Passed the mouse movement amounts. This function checks to see whether the
	// drag is from a resize handle created above; if so, it changes the stored
	// elm* dimensions and mOffX/Y.
DragResize.prototype.resizeHandleDrag = function(diffX, diffY) { with (this)
{
	var hClass = handle && handle.className &&
		handle.className.match(new RegExp(myName + '-([tmblr]{2})')) ? RegExp.$2 : '';

	// If the hClass is one of the resize handles, resize one or two dimensions.
	// Bounds checking is the hard bit -- basically for each edge, check that the
	// element doesn't go under minimum size, and doesn't go beyond its boundary.
	var dY = diffY, dX = diffX, processed = false;
	if (hClass.indexOf('t') >= 0)
	{
		rs = 1;
		if (elmH - dY < minHeight)		mOffY = (dY - (diffY = elmH - minHeight));
		else if (elmY + dY < minTop)	mOffY = (dY - (diffY = minTop - elmY));
		elmY += diffY;
		elmH -= diffY;
		processed = true;
	}
	if (hClass.indexOf('b') >= 0)
	{
		rs = 1;
		if (elmH + dY < minHeight)			mOffY = (dY - (diffY = minHeight - elmH));
		else if (elmY + elmH + dY > maxTop)	mOffY = (dY - (diffY = maxTop - elmY - elmH));
		elmH += diffY;
		processed = true;
	}
	if (hClass.indexOf('l') >= 0)
	{
		rs = 1;
		if (elmW - dX < minWidth)		mOffX = (dX - (diffX = elmW - minWidth));
		else if (elmX + dX < minLeft)	mOffX = (dX - (diffX = minLeft - elmX));
		elmX += diffX;
		elmW -= diffX;
		processed = true;
	}
	if (hClass.indexOf('r') >= 0)
	{
		rs = 1;
		if (elmW + dX < minWidth)				mOffX = (dX - (diffX = minWidth - elmW));
		else if (elmX + elmW + dX > maxLeft)	mOffX = (dX - (diffX = maxLeft - elmX - elmW));
		elmW += diffX;
		processed = true;
	}

	return processed;
}};

/*
DragResize.prototype.onMove = function(id, left, top, width, height)
{
}*/
