/* 
 * Functions to add / remove / has a particular class
 */

function addClass(element, myClass) {
    if (!element /*|| element.className.indexOf(myClass) !== -1*/) {
        return false;
    }
	if (hasClass(element, myClass)/*element.classList && element.classList.contains && element.classList.contains(myClass)*/)
	{
		return false;
	}
    if (element.classList && element.classList.add) {
        element.classList.add(myClass);
    } else {
//        if (element.className !== '') {
//            element.className += ' ';
//        }
		element.className += (element.className !== '') ? ' ' : '';
        element.className += myClass;
    }
    return true;
}

function getClassStartingWith(element, myClass) {
	if (!element) {
		return;
	}
	var list;
    if (element.classList) {
        list = element.classList;
    } else {
		list = element.className.split(' ');
    }
	for (var i = 0; i < list.length; i++) {
		if (list[i].startsWith(myClass)) {
			return list[i];
		}
	}
    return false;
}

function removeClass(element, myClass) {
    if (!element || element.className.indexOf(myClass) === -1) {
        return false;
    }
    if (element.classList && element.classList.remove) {
        element.classList.remove(myClass);
    } else {
        element.className = element.className.replace(new RegExp('( |^)' + myClass + '( |$)', 'g'), ' ').trim();
    }
    return true;
}
function removeClass2(element, myClass) {
    if (!element || element.className.indexOf(myClass) === -1) {
        return false;
    }
    if (element.classList && element.classList.remove) {
        element.classList.remove(myClass);
    } else {
        var elClass = element.className;
        while(elClass.indexOf(myClass) !== -1) {
            elClass = elClass.replace(myClass, '');
            elClass = elClass.trim();
        }
        element.className = elClass;
    }
    return true;
}

function hasClass(element, myClass) {
    if (element.classList && element.classList.contains) {
        return element.classList.contains(myClass);
    }
//	element.classList && element.classList.contains &&
//			return element.classList.contains(myClass);
    return (' ' + element.className+ ' ').indexOf(' ' + myClass+ ' ') > -1;
}
