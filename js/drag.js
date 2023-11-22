class Drag {
//	items;
//	targets;
//	ondrop;	/* function that will execute on a successful drop - returns dropTarget'id and dragItem's id */
//	const plainText = 'text/plain';
//	const uris = 'text/uri-list';

	constructor({dragItems, dropTargets, forceTraditional, ondrop})
	{
		this.items = dragItems;
		this.targets = dropTargets;
//		this.forceTraditional = forceTraditional;
		this.onDrop = ondrop;
		if (forceTraditional)
		{
			// Uses the traditional mouse movement for the drag/ drop
			this.setDrops();
			this.bindMouse();
		} else {
			// Uses the modern drag/ drop events
	//		this.items = dragItems;
			this.bindDraggable();
	//		this.targets = dropTargets;
			this.bind();
		}
	}

	/**
	 * When $forceTraditional is set, each target has the class 'droppable' added (if no there already)
	 *
	 * Ensures that each dropTarget has the droppable class
	 */
	setDrops()
	{
		for (var i=0; i<this.targets.length; i++)
		{
			var target = this.targets[i];
			if (target.className.indexOf('droppable') !== -1)
				break;
			target.className += (target.className !== '') ? ' ' : '';
			target.className += 'droppable';
		}
	}

	/**
	 * When $forceTraditional is set, each target has the class 'draggable' added (if no there already)
	 * and is bound to the mouseDown method when the onmousedown event is fired
	 */
	bindMouse()
	{
		for (var i=0; i<this.items.length; i++)
		{
			var item = this.items[i];
			if (!item.hasAttribute('draggable') || !item.getAttribute('draggable'))
				item.setAttribute('draggable', 'true');
			//let currentDroppable = null;
			addEvent(item, 'mousedown', this.mouseDown);
		}
		//addEvent(document, 'mousedown', this.mouseDown);
	}

	/**
	 * Fires when the mouse is moved
	 */
	mouseMove(e)
	{
		this.style.left = e.pageX - this.offsetWidth / 2 + 'px';
		this.style.top = e.pageY - this.offsetHeight / 2 + 'px';
		this.hidden = true;
		let elemBelow = document.elementFromPoint(e.clientX, e.clientY);
		this.hidden = false;
		if (!elemBelow)
			return;
		let droppableBelow = elemBelow.closest('.droppable');
		if (currentDroppable !== droppableBelow) {
			if (currentDroppable) { // null when we were not over a droppable before this event
//				leaveDroppable(currentDroppable);
				dragLeave(window.event);
			}
			currentDroppable = droppableBelow;
			if(currentDroppable) { // null if we're not coming over a droppable now
				// (maybe just left the droppable)
//				enterDroppable(currentDroppable);
				dragEnter(window.event);
			}
		}
	}

	/**
	 * Fires when the mouse button is pressed
	 */
	mouseDown(e)
	{
		if (!this.hasAttribute('draggable') || !this.getAttribute('draggable'))
			return;
		// only respond to events on a draggable iotem
		this.classList.add('drag-start');
		// sets the CSS class
		let shiftX = e.clientX - this.getBoundingClientRect().left;
		let shiftY = e.clientY - this.getBoundingClientRect().top;
		// stores to the relative mouse position inside the dragItem
		this.style.position = 'absolute';
		this.style.zIndex = 1000;
		// sets the styles for dragging
		document.body.append(this);
		// attach dragItem to body
		this.style.left = e.pageX - this.offsetWidth / 2 + 'px';
		this.style.top = e.pageY - this.offsetHeight / 2 + 'px';
		// sets the dragItem to follow the mouse position
		addEvent(document, 'mousemove', this.mouseMove);
		// detect movement
		//addEvent(this, 'mouseup', this.mouseUp);
		this.onmouseup = function() {
			removeEvent(document, 'mousemove', this.mouseMove);
			this.onmouseup = null;
		};
		// set only one event to monitor when the mouse button is released
		this.ondragstart = function() {
			return false;
		}
		// clears the modern drag signal
	}
/*
	function enterDroppable(elem) {
		elem.style.background = 'pink';
	}

	function leaveDroppable(elem) {
		elem.style.background = '';
	}
*/
	/**
	 * Fires when the mouse button is released
	 */
	mouseUp(e)
	{
		removeEvent(document, 'mousemove');
		removeEvent(this, 'mouseup');
	}

	/**
	 * Attaches drag events to each dragItem
	 */
	bindDraggable()
	{
		//this.items.foreach(itm => {
		for (var i=0; i<this.items.length; i++)
		{
			var itm = this.items[i];
			if (!itm.hasAttribute('draggable') || !itm.getAttribute('draggable'))
				itm.setAttribute('draggable', 'true');
			itm.addEventListener('dragstart', this.dragStart);
			itm.addEventListener('drag', this.drag);
			itm.addEventListener('dragend', this.dragEnd);

			itm.addEventListener('touchmove', this.touchMove);
			itm.addEventListener('touchend', this.touchEnd);
		}/*)*/
	}

	/**
	 * Fires when dragging begins
	 */
	dragStart(e)
	{
		e.dataTransfer.setData('text/plain', this.id);
		this.classList.add('drag-start');
	}

	/**
	 * Fires when the mouse is moved while a drag event is in effect
	 */
	drag(e)
	{
		e.dataTransfer.setData('text/plain', this.id);
		this.classList.add('dragging');
	}

	/**
	 * Fires when the dragging is completed - mouse button is released
	 */
	dragEnd(e)
	{
		e.dataTransfer.setData('text/plain', this.id);
		this.classList.add('drag-end');
	}

	/**
	 * Attaches drag/ drop events to each dropTarget
	 */
	bind() {
		//this.targets.forEach(box => {
		for (var i=0; i<this.targets.length; i++)
		{
			var box = this.targets[i];
			box.addEventListener('dragenter', this.dragEnter);
			box.addEventListener('dragover', this.dragOver);
			box.addEventListener('dragleave', this.dragLeave);
			box.addEventListener('drop', this.drop);
		}/*)*/
	}

	/**
	 * Fires when the dragged object penetrates a potential target's boundary
	 */
	dragEnter(e) {
		e.preventDefault();
		this.classList.add('drag-over');
	}

	/**
	 * Fires when the mouse is over a potential target element while dragging
	 */
	dragOver(e) {
		e.preventDefault();
		this.classList.add('drag-over');
	}

	/**
	 * Fires when the mouse moves out a target's boundary while dragging
	 */
	dragLeave(e) {
		this.classList.remove('drag-over');
	}

	/**
	 * Sets the function to perform when a successful drop is completed
	 */
	setDrop($func)
	{
		this.ondrop = $func;
	}

	/**
	 * Returns the function that is performed on a sucessful drop
	 */
	get dropFunction()
	{
		return this.ondrop;
	}

	/**
	 * Fires when a successful drop is made
	 */
	drop(e) {
		this.classList.remove('drag-over');

		// get the draggable element
		const id = e.dataTransfer.getData('text/plain');
		const item = document.getElementById(id);
		var mousex = 0;
		var mousey = 0;
		if (e.pageX || e.pageY)
		{ // this doesn't work on IE6!! (works on FF,Moz,Opera7)
			mousex = e.pageX;
			mousey = e.pageY;
		}
		else if (e.clientX || e.clientY)
		{ // works on IE6,FF,Moz,Opera7
			mousex = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
			mousey = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
		}

		// add it to the drop target
		//e.target.appendChild(draggable);

		//ondrop(target, draggable);
		//onDrop(target.id, id, {x: item.offsetLeft, y: item.offsetTop, width: item.offsetWidth, height: item.offsetHeight});
		//onDrop(target.id, id, {x: item.style.left, y: item.style.top, width: item.style.width, height: item.style.height});
		//onDrop(target.id, id, {x: item.clientX, y: item.clientY, width: item.style.width, height: item.style.height});
		onDrop(target.id, id, {x: mousex, y: mousey, width: item.style.width, height: item.style.height});

		// display the draggable element
		//draggable.classList.remove('hide');
	}

	/**
	 * Fires on a mobile device, when a touch event with movement is detected (drag with finger)
	 *
	 * Sets the current touch co-ordinates as the location of this dragItem
	 */
	touchMove(e)
	{
//		e.preventDefault();
		var touchLocation = thisTouches[0];

		this.style.left = touchLocation.pageX + 'px';
		this.style.top = touchLocation.pageY + 'px';
	}

	/**
	 * Fires on a mobile device, when a touch event is completed
	 *
	 * Returns the new co-ordinates for this dragItem
	 */
	touchEnd(e)
	{
		var x = parseInt(this.style.left);
		var y = parseInt(this.style.top);
		return {x, y};
	}

}
/* eg. */
/* draggable element
const item = document.querySelector('.item');

item.addEventListener('dragstart', dragStart);

	dragStart(e) {
	e.dataTransfer.setData('text/plain', e.target.id);
	setTimeout(() => {
		e.target.classList.add('hide');
	}, 0);
}
*/

/* drop targets
const boxes = document.querySelectorAll('.box');
*/


/* eg. CSS
* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}

body {
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
	font-size: 16px;
	background-color: #fff;
	overflow: hidden;
}

h1 {
	color: #323330;
}

.container {
	height: 100vh;
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;
	margin: 20px;

}

.drop-targets {
	display: flex;
	flex-direction: row;
	justify-content: space-around;
	align-items: center;

	margin: 20px 0;
}

.box {
	height: 150px;
	width: 150px;
	border: solid 3px #ccc;
	margin: 10px;

	/ * align items in the box * /
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;

}


.drag-over {
	border: dashed 3px red;
}

.item {
	height: 75px;
	width: 75px;
	background-color: #F0DB4F;

}

.hide {
	display: none;
}
*/
