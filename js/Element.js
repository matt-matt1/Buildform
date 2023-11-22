class Elem {
/*	#element;*/

	constructor($element)
	{
/*		if ($element)*/
			this.element = $element;
	}

	/**
	 * To get the element’s width and height that include the padding and border
	 */
	static getDimsIncPaddAndBor($element)
	{
/*		if (!$element && ${this.element})
			$element = ${this.element};*/
		return {
			width: $element.offsetWidth,
			height: $element.offsetHeight
		};
	}

	/**
	 * To get the element’s width and height that include padding but without the border
	 */
	static getDimsIncPadd($element)
	{
		return {
			width: $element.clientWidth,
			height: $element.clientHeight
		};
	}

	/**
	 * To get the margin of an element
	 */
	static getMargin($element)
	{
		let style = getComputedStyle($element);
		return {
			left: parseInt(style.marginLeft),
			right: parseInt(style.marginRight),
			top: parseInt(style.marginTop),
			bottom: parseInt(style.marginBottom)
		};
	}

	/**
	 * To get the border width of an element
	 */
	static getBorderWidth($element)
	{
		let style = getComputedStyle($element);
		return {
			top: parseInt(style.borderTopWidth) || 0,
			left: parseInt(style.borderLeftWidth) || 0,
			bottom: parseInt(style.borderBottomWidth) || 0,
			right: parseInt(style.borderRightWidth) || 0
		};
	}

	static getWindowDims()
	{
		return {
			width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth,
			height: window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight
		};
	}

	static getAbsolute($element)
	{
		var top = 0, left = 0;
		do {
			top += $element.offsetTop || 0;
			left += $element.offsetLeft || 0;
			$element = $element.offsetParent;
		} while($element);
		return {
			y: top,
			x: left
		};
	}

	/**
	 * Move $element by relative co-ords ($byX x $byY) using animation at $speed rate
	 *
	 * by omitting $speed will use 3
	 */
	static glideRelative($element, $byX, $byY, $speed)
	{
		var pos = {
			left: 0,
			right: 0,
			top: 0,
			bottom: 0
		};
/*		var delta = {
			//left: this.getAbsolute($element).x < 0 && $toX < 0 ? this.getAbsolute($element).x - $toX : 0,
			left: $toX < 0 ? this.getAbsolute($element).x - $toX : 0,
			right: $toX > 0 ? this.getAbsolute($element).x + $toX : 0,
			top: $toY < 0 ? this.getAbsolute($element).y - $toY : 0,
			bottom: $toY < 0 ? this.getAbsolute($element).y + $toY : 0,
		};*/
		var dest = {
			left: $byX < 0 ? Math.abs($byX) : 0,
			right: $byX > 0 ? $byX : 0,
			top: $byY < 0 ? Math.abs($byY) : 0,
			bottom: $byY > 0 ? $byY : 0
		};
		var max = Math.max(Math.abs($byX), Math.abs($byY));
		if (!$speed)
			$speed = 3;
		var factorX = max / Math.abs($byX);
		var factorY = max / Math.abs($byY);
		var id = setInterval(frame, $speed);

		function frame() {
			if (pos.left === dest.left && pos.right === dest.right && pos.bottom === dest.bottom && pos.top === dest.top) {
				clearInterval(id);
			} else {
				if (pos.left < dest.left)
				{
					pos.left += factorX;
					$element.style.left = pos.left + "px";
				}
				if (pos.right < dest.right)
				{
					pos.right += factorX;
					$element.style.right = pos.right + "px";
				}
				if (pos.top < dest.top)
				{
					pos.top += factorY;
					$element.style.top = pos.top + "px";
				}
				if (pos.bottom < dest.bottom)
				{
					pos.bottom += factorY;
					$element.style.bottom = pos.bottom + "px";
				}
			}
		}
/*		var posX = 0;
		var posY = 0;
		var max = Math.max(Math.abs($byX), Math.abs($byX));
		if (!$speed)
			$speed = 3;
		var factorX = max / Math.abs($byX);
		var factorY = max / Math.abs($byY);
		var id = setInterval(frame, $speed);

		function frame() {
			if (posX == toX && posY == toY) {
				clearInterval(id);
			} else {
				posX++;
				posY++;
				if (posX < 0)
					$element.style.left = (posX * factorX) + "px";
				else if ($byX < 0)
					$element.style.right = (poxX * factorX) + "px";
				if ($byY > 0)
					$element.style.top = (posY * factorY) + "px";
				else if ($byY < 0)
					$element.style.bottom = (poxX * factorY) + "px";
			}
		}*/
	}

	/**
	 * Move $element to absolute coords ($toX x $toY) using animation at $speed rate
	 *
	 * by omitting $speed will use 3
	 */
	static glideAbsolute($element, $toX, $toY, $speed)
	{
		var pos = {
			left: this.getAbsolute($element).x < 0 ? this.getAbsolute($element).x : 0,
			right: this.getAbsolute($element).x > 0 ? this.getAbsolute($element).x : 0,
			top: this.getAbsolute($element).y < 0 ? this.getAbsolute($element).y : 0,
			bottom: this.getAbsolute($element).y > 0 ? this.getAbsolute($element).y : 0
		};
		var delta = {
			//left: this.getAbsolute($element).x < 0 && $toX < 0 ? this.getAbsolute($element).x - $toX : 0,
			left: $toX < 0 ? this.getAbsolute($element).x - $toX : 0,
			right: $toX > 0 ? this.getAbsolute($element).x + $toX : 0,
			top: $toY < 0 ? this.getAbsolute($element).y - $toY : 0,
			bottom: $toY > 0 ? this.getAbsolute($element).y + $toY : 0
		};
		var dest = {
			left: $toX < 0 ? Math.abs($toX) : 0,
			right: $toX > 0 ? $toX : 0,
			top: $toY < 0 ? Math.abs($toY) : 0,
			bottom: $toY > 0 ? $toY : 0
		};
/*		var deltaX = Math.abs(this.getAbsolute($element).x - $toX);
		var deltaY = Math.abs(this.getAbsolute($element).y - $toY);*/
		var max = Math.max(Math.abs($toX), Math.abs($toY));
		if (!$speed)
			$speed = 3;
		var factorX = Math.abs($toX) / max;
		var factorY = Math.abs($toY) / max;
		var id = setInterval(frame, $speed);

		function frame() {
			if (pos.left === dest.left && pos.right === dest.right && pos.bottom === dest.bottom && pos.top === dest.top) {
				clearInterval(id);
			} else {
				if (pos.left < delta.left)
				{
					pos.left += factorX * delta.left;
					$element.style.left = pos.left + "px";
				}
				if (pos.right < delta.right)
				{
					pos.right += factorX * delta.right;
					$element.style.right = pos.right + "px";
				}
				if (pos.top < delta.top)
				{
					pos.top += factorY * delta.top;
					$element.style.top = pos.top + "px";
				}
				if (pos.bottom < delta.bottom)
				{
					pos.bottom += factorY * delta.bottom;
					$element.style.bottom = pos.bottom + "px";
				}
			}
		}
	}

	/**
	 * Move $element to absolute coords ($toX x $toY) using animation at $speed rate
	 *
	 * by omitting $speed will use 3
	 */
	static glideAbsolute2($element, $toX, $toY, $speed)
	{
		var pos = {
			left: this.getAbsolute($element).x < 0 ? this.getAbsolute($element).x : 0,
			right: this.getAbsolute($element).x > 0 ? this.getAbsolute($element).x : 0,
			top: this.getAbsolute($element).y < 0 ? this.getAbsolute($element).y : 0,
			bottom: this.getAbsolute($element).y > 0 ? this.getAbsolute($element).y : 0
		};
		var delta = {
			//left: this.getAbsolute($element).x < 0 && $toX < 0 ? this.getAbsolute($element).x - $toX : 0,
			left: $toX < 0 ? this.getAbsolute($element).x - $toX : 0,
			right: $toX > 0 ? this.getAbsolute($element).x + $toX : 0,
			top: $toY < 0 ? this.getAbsolute($element).y - $toY : 0,
			bottom: $toY > 0 ? this.getAbsolute($element).y + $toY : 0
		};
		var dest = {
			left: $toX < 0 ? Math.abs($toX) : 0,
			right: $toX > 0 ? $toX : 0,
			top: $toY < 0 ? Math.abs($toY) : 0,
			bottom: $toY > 0 ? $toY : 0
		};
/*		var deltaX = Math.abs(this.getAbsolute($element).x - $toX);
		var deltaY = Math.abs(this.getAbsolute($element).y - $toY);*/
		var max = Math.max(Math.abs($toX), Math.abs($toY));
		if (!$speed)
			$speed = 3;
		var factorX = Math.abs($toX) / max;
		var factorY = Math.abs($toY) / max;
		var id = setInterval(frame, $speed);

		function frame() {
			if (pos.left === dest.left && pos.right === dest.right && pos.bottom === dest.bottom && pos.top === dest.top) {
				clearInterval(id);
			} else {
				if (pos.left < delta.left)
				{
					pos.left += factorX * delta.left;
					$element.style.left = pos.left + "px";
				}
				if (pos.right < delta.right)
				{
					pos.right += factorX * delta.right;
					$element.style.right = pos.right + "px";
				}
				if (pos.top < delta.top)
				{
					pos.top += factorY * delta.top;
					$element.style.top = pos.top + "px";
				}
				if (pos.bottom < delta.bottom)
				{
					pos.bottom += factorY * delta.bottom;
					$element.style.bottom = pos.bottom + "px";
				}
			}
		}
	}

}
