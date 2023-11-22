/* 
 * Adds an event - Cross browser add listener
 * http://stackoverflow.com/questions/10149963/adding-event-listener-cross-browser
 */

/* global HTMLInputElement, HTMLElement */

//  Note to execute imediately:
//  (function() {
//	otherRelatedParts();
//  }())

//eg.
// addEvent(window, 'load', myFunction);
//or
// addEvent(myForm, 'submit', myFormSubmitFunction);

function addEvent(elem, event, func)
{
	function listenHandler(e)
	{
		var ret = func.apply(this, arguments);
//		if (ret === false) {
//			e.stopPropagation();
//			e.preventDefault();
//		}
		(ret === false) &&
///				e.stopPropagation() &&
				e.preventDefault();
		return(ret);
	}

	function attachHandler()
	{
		var ret = func.call(elem, window.event);
		if (ret === false) {
			window.event.returnValue = false;
			window.event.cancelBubble = true;
		}
		return(ret);
	}

	function addEventListenerDynamic( type, selector, callback )
	{
		document.addEventListener( type, function(e) {
			if ( e.target.matches( selector ) ) {
				var elements = this.querySelectorAll( selector );

				for ( var i = 0; i < elements.length; i++ ) {
					elements[i].addEventListener( type, callback(e) );
				}
			}
		});
	}

	function attachEventDynamic( type, selector, callback )
	{
		document.attachEvent( type, function(e) {
			try {
				if ( e.target.matches( selector ) ) {
					var elements = this.querySelectorAll( selector );

					for ( var i = 0; i < elements.length; i++ ) {
						elements[i].attachEvent( type, callback(e) );
					}
				}
			} catch(e) {
				console.log("attachEventDynamic failed");
			}
		});
	}

	event = event || window.event;
	target = event.target || event.srcElement;
//	while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//		target = target.parentElement;

	if (elem !== null) {
		if (event === 'setValue') {
			var descriptor = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, "value");
			var originalSet = descriptor.set;

			// define our own setter
			descriptor.set = function(val) {
				if (document.addEventListener) {
					listenHandler();
				} else if (document.attachEvent) {
					attachHandler();
				}
//				$func();
	//			console.log("Value set", this, val);
				originalSet.apply(this,arguments);
			};
	//		Object.defineProperty(HTMLInputElement.prototype, "value", descriptor);
			Object.defineProperty(elem, "value", descriptor);

			/*Object.defineProperty(elem, "val", {
				get: function() {
					return this.value;
				},
				set: function(newValue) {
					this.value = newValue;
					if (document.addEventListener) {
						listenHandler();
					} else if (document.attachEvent) {
						attachHandler();
					}
				},
				configurable: true
			});*/
//			$input.addEventListener("input", func, false);
		}
/*		if (elem.addEventListener) {
			elem.addEventListener(event, listenHandler, false);
		} else if (elem.attachEvent) {
			elem.attachEvent("on"+ event, attachHandler);
		} else {
		}*/
		(elem.addEventListener) &&
				elem.addEventListener( event, listenHandler, false ) ||
				(elem.attachEvent) &&
				elem.attachEvent( "on" + event, attachHandler );
	} else {	//elem is not in DOM tree
//		} else {
/*		if (document.addEventListener) {
			addEventListenerDynamic( event, elem, listenHandler );
//			document.addEventListener(event, setDynamicListener, false);
		} else if (document.attachEvent) {
			attachEventDynamic( "on" + event, elem, attachHandler );
//			document.attachEvent("on" + event, setDynamicListener);
		} else {
		}*/
		(document.addEventListener) &&
				addEventListenerDynamic( event, elem, listenHandler ) ||
				(document.attachEvent) &&
				attachEventDynamic( "on" + event, elem, attachHandler );
/*		let msg = 'CAP Error: addEvent element is null (for [event] '+ event+ ')';
//		alert(msg);
		throw new Error(msg);
		return false;*/
//		}
	}

}
/*
 * addListener(element, 'click', function(e) {
    // this refers to element
    // e.currentTarget refers to element
    // e.target refers to the element that triggered the event
});
 */
var addListener = (function() {
	if(document.attachEvent) {
		return function(element, event, handler) {
			element.attachEvent('on' + event, function() {
				var event = window.event;
				event.currentTarget = element;
				event.target = event.srcElement;
				handler.call(element, event);
			});
		};
	}
	else {
		return function(element, event, handler) {
			element.addEventListener(event, handler, false);
		};
	}
}());
/*var addAllEvents = (function() {
	return function(global, element, event, handler){
		addEvent(global, event, function(){
			event = event || window.event;
			target = event.target || event.srcElement;
			while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
				target = target.parentElement;
			if (target === element){
			}
		);
	});
}());
*/

function removeEvent(elem, event, func)
{
	function listenHandler(e)
	{
		var ret = func.apply(this, arguments);
		if (ret === false) {
///			e.stopPropagation();
			e.preventDefault();
		}
		return(ret);
	}

	function attachHandler()
	{
		var ret = func.call(elem, window.event);
		if (ret === false) {
			window.event.returnValue = false;
			window.event.cancelBubble = true;
		}
//		(ret === false) &&
//				window.event.returnValue = false &&
//				window.event.cancelBubble = true;
		return(ret);
	}

//	function addEventListenerDynamic( type, selector, callback ) {
//		document.addEventListener( type, function(e) {
//			if ( e.target.matches( selector ) ) {
//				var elements = this.querySelectorAll( selector );
//
//				for ( var i = 0; i < elements.length; i++ ) {
//					elements[i].addEventListener( type, callback(e) );
//				}
//			}
//		});
//	}
//
//	function attachEventDynamic( type, selector, callback ) {
//		document.attachEvent( type, function(e) {
//			try {
//				if ( e.target.matches( selector ) ) {
//					var elements = this.querySelectorAll( selector );
//
//					for ( var i = 0; i < elements.length; i++ ) {
//						elements[i].attachEvent( type, callback(e) );
//					}
//				}
//			} catch(e) {
//				console.log("attachEventDynamic failed");
//			}
//		});
//	}

	event = event || window.event;
	target = event.target || event.srcElement;

	if (elem !== null) {
/*		if (elem.removeEventListener) {
			elem.removeEventListener(event, listenHandler, false);
		} else if (elem.attachEvent) {
			elem.attachEvent("on"+ event, attachHandler);
		} else {
		}*/
		(elem.removeEventListener) &&
				elem.removeEventListener( event, listenHandler, false ) ||
				(elem.attachEvent) &&
				elem.attachEvent( "on" + event, attachHandler );
	} else {	//elem is not in DOM tree
/*		if (document.addEventListener) {
			addEventListenerDynamic( event, elem, listenHandler );
//			document.addEventListener(event, setDynamicListener, false);
		} else if (document.attachEvent) {
			attachEventDynamic( "on" + event, elem, attachHandler );
//			document.attachEvent("on" + event, setDynamicListener);
		} else {
		}*/
		(document.addEventListener) &&
				addEventListenerDynamic( event, elem, listenHandler ) ||
				(document.attachEvent) &&
				attachEventDynamic( "on" + event, elem, attachHandler );
/*		let msg = 'CAP Error: addEvent element is null (for [event] '+ event+ ')';
//		alert(msg);
		throw new Error(msg);
		return false;*/
	}

	/**
	 * Adds or remove an event listener
	 * based on https://stackoverflow.com/questions/8841138/remove-event-listener-in-javascript
	 * usage:
	 * var a=element.eventListener('click', myFunction, false); //to add
	 * element.eventListener(a); //to remove
	 * @param {string|object} type
	 * @param {function} func optional
	 * @param {boolean} capture optional
	 * @returns {Element.prototype.eventListener.arguments|Arguments|Node.prototype.eventListener.arguments} event listener
	 */
	HTMLElement.prototype.eventListener =
		function(type, func, capture){
			if (typeof arguments[0] === "object" && (!arguments[0].nodeType)) {
	//			return this.removeEventListener.apply(this,arguments[0]);
				return this.addEvent.apply(this, arguments[0]);
			}
	//		this.addEventListener(type, func, capture);
			this.addEvent(elem, event, capture);
			return arguments;
		};

}
