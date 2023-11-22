//import subjx from 'subjx';
//import 'subjx/dist/style/subjx.css';
var amountScrolled = 250;
var link = document.getElementById("return-to-top");
	/**
	 * Launch load when the document loads
	 * ----------------------------
	 */
	/*if (typeof window.addEvent === "function") {*/
		addEvent(window, 'load', load_form);
/*	} else {
//		alert('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
		console.error('Missing required library - perhaps deactivate & delete, then install a fresh copy and activate');
	}*/

const scrollContainer = function() {
	return document.documentElement || document.body;
};

/**
 * Fired when the window (for this page) loads
 */
function load_form() {
	addClass(link, 'hide');
	const progress = document.querySelector(".scroll-progress");
	progress.style.width = '0%';
	addEvent(window, 'scroll', function(e)
		{
			const scrolledPc = (scrollContainer().scrollTop / (scrollContainer().scrollHeight - scrollContainer().clientHeight)) * 100;
//			console.log('scroll% = '+ scrolledPc);
//			progress.innerText = `${scrolledPc}%`;

			progress.style.width = `${scrolledPc}%`;
			if (window.pageYOffset > amountScrolled)
			{
//			console.log('unhinding '+ window.pageYOffset);

				removeClass(link, 'hide');
//				link.style.display = 'block';
			} else {
//			console.log('hinding '+ window.pageYOffset);
				addClass(link, 'hide');
			}
//			doScroll()
		}
	);
	//addEvent(link, 'click', doScroll);
	addEvent(link, 'click', smoothScroll);
	const anchs = document.querySelectorAll('a');
	for(i=0; i<anchs.length; i++) {						// enable all toolbox elements
		var anc = anchs[i];
		var pos = anc.href.indexOf('#');
		if (pos !== -1)
			addEvent(anchs[i], 'click', smoothScroll);
	}
}

/*
function doScroll(event)
{
	event = event || window.event;
	event.preventDefault();
	if (hasClass(link, 'hide'))
		return;
	window.scroll({
		top: 0,
		left: 0,
		behavior: 'smooth'
	});
}
*/

function animateScroll()
{
	var distance = 0 - window.pageYOffset;
	var increments = distance/(500/16);
	function animateScroll() {
		window.scrollBy(0, increments);
		if (window.pageYOffset <= document.body.offsetTop) {
			clearInterval(runAnimation);
		}
	}
	var runAnimation = setInterval(animateScroll, 16);
}


function smoothScroll(event)
{
	event = event || window.event;
	event.preventDefault();
	//const target = event.target || event.srcElement;
	var target = event.target || event.srcElement;
	if (!target.href && (typeof target.parentElement === undefined || !target.parentElement.href))
		return
	else if (!target.href && typeof target.parentElement !== undefined && target.parentElement.href)
	{
		target = target.parentElement;
//		var pos = target.parentElement.href.indexOf('#');
	} //else
		var pos = target.href.indexOf('#');
	var elmId = target.href.substring(pos+1, target.href.length);
	var elm = document.getElementById(elmId);
	if (typeof elm === undefined || elm === null)
		return;
	elm.scrollIntoView({
		behavior: 'smooth'
	});
}
/*
document.addEventListener("scroll", () => {

  if (scrollContainer().scrollTop > showOnPx) {
    backToTopButton.classList.remove("hidden");
  } else {
    backToTopButton.classList.add("hidden");
  }
});*/
