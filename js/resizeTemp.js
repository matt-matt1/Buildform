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
	//static elem = document.createElement('div');
	static top(inside, outside)
	{
		const elem = document.createElement('div');
//		elem.style.backgroundColor = 'transparent';
//		elem.style.position = 'absolute';
		elem.style.width = '100%';
		elem.style.width = 'calc(100% - ' + (2*inside) + 'px)';//calc(100% - 5px)
		elem.style.height = (inside + outside) + 'px';
		elem.style.top = - outside + 'px';
		elem.style.left = inside + 'px';
		//elem.style.cursor = 'n-resize';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-top noup nodown';
		return elem;
	}
	static left(inside, outside)
	{
		const elem = document.createElement('div');
//		elem.style.backgroundColor = 'transparent';
//		elem.style.position = 'absolute';
		elem.style.height = '100%';
		elem.style.height = 'calc(100% - ' + (2*inside) + 'px)';
		elem.style.width = (inside + outside) + 'px';
		elem.style.top = inside + 'px';
		elem.style.left = - outside + 'px';
		//elem.style.cursor = 'e-resize';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-left noleft noright';
		return elem;
	}
	static bottom(inside, outside)
	{
		const elem = document.createElement('div');
//		elem.style.backgroundColor = 'transparent';
//		elem.style.position = 'absolute';
		elem.style.width = '100%';
		elem.style.width = 'calc(100% - ' + (2*inside) + 'px)';
		elem.style.height = (inside + outside) + 'px';
		elem.style.bottom = - outside + 'px';
		elem.style.left = inside + 'px';
		//elem.style.cursor = 'n-resize';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-bottom noup';
		return elem;
	}
	static right(inside, outside)
	{
		const elem = document.createElement('div');
//		elem.style.backgroundColor = 'transparent';
//		elem.style.position = 'absolute';
		elem.style.height = '100%';
		elem.style.height = 'calc(100% - ' + (2*inside) + 'px)';
		elem.style.width = (inside + outside) + 'px';
		elem.style.top = inside + 'px';
		elem.style.right = - outside + 'px';
		//elem.style.cursor = 'e-resize';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-right noleft';
		return elem;
	}
	static topLeft(inside, outside)
	{
		const elem = document.createElement('div');
//		elem.style.backgroundColor = 'transparent';
//		elem.style.position = 'absolute';
		elem.style.width = (inside + outside) + 'px';
		elem.style.height = (inside + outside) + 'px';
		elem.style.top = - outside + 'px';
		elem.style.left = - outside + 'px';
		//elem.style.cursor = 'nw-resize';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-top-left noup noleft';
		return elem;
	}
	static bottomLeft(inside, outside)
	{
		const elem = document.createElement('div');
//		elem.style.backgroundColor = 'transparent';
//		elem.style.position = 'absolute';
		elem.style.width = (inside + outside) + 'px';
		elem.style.height = (inside + outside) + 'px';
		elem.style.bottom = - outside + 'px';
		elem.style.left = - outside + 'px';
		//elem.style.cursor = 'sw-resize';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-bottom-left noleft nodown noright';
		return elem;
	}
	static bottomRight(inside, outside)
	{
		const elem = document.createElement('div');
//		elem.style.backgroundColor = 'transparent';
//		elem.style.position = 'absolute';
		elem.style.width = (inside + outside) + 'px';
		elem.style.height = (inside + outside) + 'px';
		elem.style.bottom = - outside + 'px';
		elem.style.right = - outside + 'px';
		//elem.style.cursor = 'se-resize';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-bottom-right';
		return elem;
	}
	static topRight(inside, outside)
	{
		const elem = document.createElement('div');
//		elem.style.backgroundColor = 'transparent';
//		elem.style.position = 'absolute';
		elem.style.width = (inside + outside) + 'px';
		elem.style.height = (inside + outside) + 'px';
		elem.style.top = - outside + 'px';
		elem.style.right = - outside + 'px';
		//elem.style.cursor = 'ne-resize';
		elem.className += (elem.className !== '') ? ' ' : '';
		elem.className += 'resize resize-top-right noup';
		return elem;
	}
}

//function makeResizable(element, minW = 100, minH = 100, size = 20)
function makeResizable(element, minW = 100, minH = 100, inside = 5, outside = 10)
{
	const top = Edge.top(inside, outside);
	top.addEventListener('mousedown',resizeYNegative())
	element.appendChild(top);

	const bottom = Edge.bottom(inside, outside);
	bottom.addEventListener('mousedown',resizeXNegative())
	element.appendChild(bottom);

	const left = Edge.left(inside, outside);
	left.addEventListener('mousedown',resizeXNegative())
	element.appendChild(left);

	const right = Edge.right(inside, outside);
	right.addEventListener('mousedown',resizeXPositive())
	element.appendChild(right);

	const corner1 = Edge.topLeft(inside, outside);
	corner1.addEventListener('mousedown',resizeXNegative())
	corner1.addEventListener('mousedown',resizeYNegative())
	element.appendChild(corner1);

	const corner2 = Edge.topRight(inside, outside);
	corner2.addEventListener('mousedown',resizeXPositive())
	corner2.addEventListener('mousedown',resizeYNegative())
	element.appendChild(corner2);

	const corner3 = Edge.bottomLeft(inside, outside);
	corner3.addEventListener('mousedown',resizeXNegative())
	corner3.addEventListener('mousedown',resizeYPositive())
	element.appendChild(corner3);

	const corner4 = Edge.bottomRight(inside, outside);
	corner4.addEventListener('mousedown',resizeXPositive())
	corner4.addEventListener('mousedown',resizeYPositive())
	element.appendChild(corner4);

//	makeResizableMain(element, minW, minH, size);

	function get_int_style(key)
	{
		return parseInt(window.getComputedStyle(element).getPropertyValue(key));
	}

	function resizeXPositive()
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
			if(x < minW) x = minW;
			element.style.width =  x + 'px';
		}
		
		function closeDragElement() {
			document.removeEventListener("mouseup", closeDragElement);  
			document.removeEventListener("mousemove", elementDrag);
		}
		return dragMouseDown
	}

	function resizeYPositive()
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
			if(y < minH) y = minH;
			element.style.height = y + 'px';
		}
		
		function closeDragElement() {
			document.removeEventListener("mouseup", closeDragElement);  
			document.removeEventListener("mousemove", elementDrag);
		}
		return dragMouseDown
	}

	function resizeXNegative()
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
			if(w < minW) w = minW;
			if(x > maxX) x = maxX;
			element.style.left = x + 'px';
			element.style.width = w + 'px';
		}
		
		function closeDragElement() {
			document.removeEventListener("mouseup", closeDragElement);  
			document.removeEventListener("mousemove", elementDrag);
		}
		return dragMouseDown
	}

	function resizeYNegative()
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
			let y =  clientY - offsetY
			let h = startH + startY - y
			if(h < minH) h = minH;
			if(y > maxY) y = maxY;
			element.style.top = y + 'px';
			element.style.height = h + 'px';
		}
		
		function closeDragElement() {
			document.removeEventListener("mouseup", closeDragElement);  
			document.removeEventListener("mousemove", elementDrag);
		}
		return dragMouseDown
	}
}
