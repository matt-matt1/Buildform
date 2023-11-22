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

/**
 * For cross-browser support this returns the height of the element to scroll
 */
/*let scrollHeight = Math.max(
	document.body.scrollHeight, document.documentElement.scrollHeight,
	document.body.offsetHeight, document.documentElement.offsetHeight,
	document.body.clientHeight, document.documentElement.clientHeight
);*/

/**
 * Returns the DOM object for scrolling
 */
const scrollContainer = function() {
	return document.documentElement || document.body;
};

/**
 * Creates the element to guage the verticle scroll amount
 */
var makeProgress = function ()
//function makeProgress()
{
	var progress = document.createElement("DIV");
	progress.id = "scroll-progress";
	progress.className = "scroll-progress";
	document.body.appendChild(progress);
	progress.style.width = '0%';
	return progress;
}

/**
 * Set width of the scroll-progress element as the percentage of verticle scroll
 * Shows/hides the scroll-to-top element
 */
function doProgress(e)
{
	const progress = document.getElementById("scroll-progress");
/*			var variation = parseInt(e.deltaY);
			//if (!variation || scrollContainer().scrollHeight < e.screenY)	// do nothing if no scroll movement or page has no scxrolling need
			if (!variation || scrollHeight < e.screenY)	// do nothing if no scroll movement or page has no scxrolling need
				return;*/
	const docHeight = scrollContainer().scrollHeight - scrollContainer().clientHeight;
	const scrolledPc = (window.pageYOffset / docHeight) * 100;
	progress.style.width = `${scrolledPc}%`;	// adjust progress line width as verticle scroll distance
	if (window.pageYOffset > amountScrolled)
	{
		removeClass(link, 'hide');
	} else {
		addClass(link, 'hide');	// hide (or unhide) the scroll-to-top element
	}
}

/**
 * Fired when the window (for this page) loads
 */
function load_form() {
	addClass(link, 'hide');	// initialise the scroll-to-top element as hidden
	// Create the scroll-progress element (once per page)
	const progress = makeProgress();
	// Calculates the document scroll height
	const docHeight = scrollContainer().scrollHeight - scrollContainer().clientHeight;
//	addEvent(window, 'wheel', function(e)
	// Whenever the DOM is scrollled the doProgess function is fired
	addEvent(window, 'scroll', doProgress);//function(e)	//firefox: DOMMouseScroll
	// Srolls to elemnt when link is clicked
	addEvent(link, 'click', smoothScroll);
	// Attaches each in-page-link (#...) anchor to the smoothScroll function
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

/**
 * Does an animated scroll to the top of the page
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

/**
 * Performs a smooth scroll to an in-page-link (#...) when an event is fired
 */
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
