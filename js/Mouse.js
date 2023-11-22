class Mouse {

	var posx = 0, posy = 0;
	#let element;
	#let modifiers = [
		{'key': 'shiftKey', 'text': 'Shift'},
		{'key': 'altKey', 'text': 'Alt (or Option for Mac)'},
		{'key': 'ctrlKey', 'text': 'Ctrl'},
		{'key': 'metaKey', 'text': 'Cmd for Mac'}
		];

	constructor($bindToElement)
	{
		if ($bindToElement)
		{
			bindTo($bindToElement);
		}
	}

	/**
	 * Listen to mouse events for a specific element
	 */
	bind($bindToElement)
	{
		if (!$bindToElement)
		{
			$bindToElement = document;
		}
		this.element = $bindToElement;
		addEvent( this.element, 'mousemove',	this.move);
		addEvent( this.element, 'mousedown',	this.down);
		addEvent( this.element, 'mouseup',		this.up);
		addEvent( this.element, 'mouseover',	this.over);
		addEvent( this.element, 'mouseout',		this.out);
		addEvent( this.element, 'mouseenter',	this.enter);
		addEvent( this.element, 'click',		this.click);
		addEvent( this.element, 'dblclick',		this.dblclick);
		addEvent( this.element, 'contextmenu',	this.contextmenu);
/*		this.element.addEventListener( 'mousemove',		this.move);
		this.element.addEventListener( 'mousedown',		this.down);
		this.element.addEventListener( 'mouseup',		this.up);
		this.element.addEventListener( 'mouseover',		this.over);
		this.element.addEventListener( 'mouseout',		this.out);
		this.element.addEventListener( 'mouseenter',	this.enter);
		this.element.addEventListener( 'click',			this.click);
		this.element.addEventListener( 'dblclick',		this.dblclick);
		this.element.addEventListener( 'contextmenu',	this.contextmenu);*/
	}

	/**
	 * Find the real mouse coordinantes
	 * posx & posy contain the mouse coordinantes relative to the document
	 */
	#getCoords(e) {
		if (!e)
			var e = window.event;

		if (e.pageX || e.pageY)
		{
			this.posx = e.pageX;
			this.posy = e.pageY;
		} else if (e.clientX || e.clientY)
		{
			this.posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
			this.posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
		}
	}

	move(e)
	{
		this.#getCoords();
	}

	down(e)
	{
//		this.getCoords();
	}

	up(e)
	{
//		this.getCoords();
	}

	over(e)
	{
//		this.getCoords();
	}

	out(e)
	{
//		this.getCoords();
	}

	enter(e)
	{
//		this.getCoords();
	}

	click(e)
	{
//		this.getCoords();
		var pressed = '';
		var arr = JSON.parse(modifiers);
		for(i=0; i<arr.length; i++)
		{
			if (e.arr[i].key)
			{
				pressed += arr[i].text + ' ';
			}
		}
		if (pressed)
			return pressed;
	}

	dblclick(e)
	{
//		this.getCoords();
		var pressed = '';
		var arr = JSON.parse(modifiers);
		for(i=0; i<arr.length; i++)
		{
			if (e.arr[i].key)
			{
				pressed += arr[i].text + ' ';
			}
		}
		if (pressed)
			return pressed;
	}

	contextmenu(e)
	{
//		this.getCoords();
		var pressed = '';
		var arr = JSON.parse(modifiers);
		for(i=0; i<arr.length; i++)
		{
			if (e.arr[i].key)
			{
				pressed += arr[i].text + ' ';
			}
		}
		if (pressed)
			return pressed;
	}

}
