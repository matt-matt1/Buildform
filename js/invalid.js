/**
 * based on https://daverupert.com/2017/11/happier-html5-forms/
 */
addEvent(window, 'load', load_invalid);
function load_invalid()
{
	for (var i=0; i<document.forms.length; i++) {				// each FORM in the DOM
		var form = document.forms[i];
		//addEvent(form, "submit", allFormsOnSubmit);
		//addEvent(form, "invalid", allFormsOnSubmit);
		//addEvent(form, "submit", function() {					// on form submission
		//});
		addEvent(form, "submit", submitAddDbForm);					// on form submission
		const inputs = form.querySelectorAll("input:enabled:not([type=hidden]), select:enabled, textarea:enabled");		// all inputs within the form
		for (var j=0; j<inputs.length; j++) {					// each enabled-non-hidden input/select/textarea in the form
			//addEvent(inputs[j], "invalid", function() {			// attach invalid event to each input
			//});
			addEvent(inputs[j], "invalid", inputInvalid);			// attach invalid event to each input
			//addEvent(inputs[j], "blur", function() {			// validate on blur
			//});
			addEvent(inputs[j], "blur", inputBlurred);			// validate on blur
		}
	}
}
/**
 * Removes all 'error' classes from all form elements upon a successful form submission
 */
function submitAddDbForm()
{
	const inputs = this.querySelectorAll("label input:enabled:not([type=hidden]), select:enabled, textarea:enabled");		// all inputs & labels
	for (var j=0; j<inputs.length; j++) {				// each enabled-non-hidden input/select/textarea in the form
		removeClass(inputs[j], 'error');				// remove all input errors upon a successful form submission
	}
}
/**
 * Marks the associated label as having 'error' className
 */
function inputInvalid()
{
	addClass(this, 'error');						// add "error" class to each invalid input
	const id = this.id;
	const labels = this.querySelectorAll('label');	// get all FORM labels
	for (var k=0; k<labels.length; k++)
		if (labels[k].htmlFor === id)				// discover label that relates to this input
			addClass(labels[k], 'error');			// mark associated label as error
}
/**
 * Checks whether an input is valid
 */
function inputBlurred()
{
	this.checkValidity();
}
