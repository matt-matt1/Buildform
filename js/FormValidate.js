class FormValidate {
	
	constructor(form, field) {
		// active form
		this.form = form;
		this.form.noValidate = true;

		// custom validation functions
		this.custom = [];

		// validate fields on focus change?
		this.validate = !!field;
		
		// field focusout event
		//this.form.addEventListener('focusout', e => this.changeHandler(e) );
		addEvent( this.form, 'focusout', this.changeHandler );

		// form submit event
		//this.form.addEventListener('submit', e => this.submitHandler(e) );
		addEvent( this.form, 'submit', this.submitHandler );
	}

	// add a custom validation function
	// it's passed the field and must return true (valid) or false (invalid)
	addCustom(field, vfunc) {
		// get index
		let c = field.CustomValidator;
		if (typeof c === 'undefined') {
			c = this.custom.length;
			field.CustomValidator = c;
		}

		// store function reference
		this.custom[c] = (this.custom[c] || []).concat(vfunc);
	}

	// validate a field when focus changes
	changeHandler(e) {
		const t = e.target;
		if (this.validate && t && t.checkValidity) 
			this.validateField(t);
	}


	// validate all fields on submit
	submitHandler(e) {
		// validate all fields
		let first, invCount = 0;
		Array.from(this.form.elements).forEach(function(f) {
			if (!this.validateField(f)) {
				// find first visible invalid
				if (f.offsetHeight) first = first || (f.focus && f);
				invCount++;
			}
		});

		// at least one field is invalid
		if (invCount) {
			// stop submission
///			e.stopImmediatePropagation();
			e.preventDefault();
			// enable field focusout validation
			this.validate = true;

			// focus first invalid field
			if (first) {
				first.parentElement.scrollIntoView(true);
				setTimeout(function() {
					first.focus(), 800
				});
			}
		}

		// form is valid - submit
		else if (this.submit)
			this.submit(e);
	}


	// validate a field
	validateField(field) {
		const
			parent = field.parentElement,
			c = field.CustomValidator,
			inv = 'invalid';

		field.setCustomValidity('');

		// default validation
		let valid = field.checkValidity();

		// custom validation
		if (valid && typeof c !== 'undefined') {
			valid = !this.custom[c].some(function(fn) {
				!fn(field)
			});
		}

		if (valid) {
			// field is valid
			parent.classList.remove(inv);
			return true;
		} else {
			// field is not valid
			field.setCustomValidity(inv);
			parent.classList.add(inv);
			return false;
		}
	}

}

// ___________________________________________________________________
// validate contact form
const contactForm = new FormValidate(document.getElementById('contact'), false);

// custom validation - email and/or telephone
const
	email = document.getElementById('email'),
	tel = document.getElementById('tel');

contactForm.addCustom(email, function(f) {
	f.value || tel.value
});
contactForm.addCustom(tel, function(f) {
	f.value || email.value
});

// custom submit
contactForm.submit = function(e) {
	e.preventDefault();

	alert('Form is valid!\n(open the console)');

	const fd = new FormData(e.target);
	for (const [name, value] of fd.entries()) {
		console.log(name + ': ' + value);
	}
}
/*
 * eg.
 * html
 * <form id="contact" action="./" method="post">

	<div>
		<label for="username">username</label>
		<input type="text" autocomplete="username" id="username" name="username" required />
		<p class="help">Please enter your username.</p>
	</div>

	<div>
		<label for="email">email</label>
		<input type="email" autocomplete="email" id="email" name="email" />
		<p class="help">Please enter your email or telephone.</p>
	</div>

	<div>
		<label for="tel">telephone</label>
		<input type="tel" autocomplete="tel" id="tel" name="tel" />
		<p class="help">Please enter your email or telephone.</p>
	</div>

	<div>
		<button type="submit">submit</button>
	</div>

</form>

css
* {
	font-family: sans-serif;
	font-size: 1em;
}

div {
	width: 100%;
	max-width: 15em;
	margin: 1em auto;
}

label, input {
	display: block;
	width: 100%;
}

input {
	padding: 0.5em;
	border: 1px solid #666;
	border-radius: 4px;
}

button {
	display: block;
	margin: 0 auto;
}

.help {
	display: none;
	font-size: 0.8em;
	text-align: center;
	margin: 0.2em 0 1em 0;
	color: #c00;
}

.invalid .help {
	display: block;
}

.invalid label, .invalid input {
	color: #c00;
	border-color: #c00;
}
*/
