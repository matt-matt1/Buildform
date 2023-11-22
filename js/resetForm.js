function resetForm(form) {
    // clearing inputs
    var inputs = form.getElementsByTagName('input');
    var i;
    for (i = 0; i<inputs.length; i++) {
		if (inputs[i].type == 'checkbox') {
			inputs[i].checked = false;
		//} else if (inputs[i].type != 'submit' && inputs[i].type != 'button') {
		} else if (['submit', 'button', 'reset'].indexOf(inputs[i].type) == -1) {
			inputs[i].value = '';
		}
/*        switch (inputs[i].type) {
            // case 'hidden':
            case 'text':
                inputs[i].value = '';
                break;
            case 'radio':
            case 'checkbox':
                inputs[i].checked = false;   
        }*/
    }

    // clearing selects
    var selects = form.getElementsByTagName('select');
    for (var i=0; i<selects.length; i++) {
        selects[i].selectedIndex = 0;
	}

    // clearing textarea
    var text = form.getElementsByTagName('textarea');
    for (var i=0; i<text.length; i++) {
        text[i].innerHTML = '';
		text[i].placeholder = '';
		text[i].value = '';
	}

    //return false;
    return true;
}
