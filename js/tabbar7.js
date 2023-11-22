addEvent(window, 'load', tabs_load);
//var triggerTabList = [].slice.call(document.querySelectorAll('#myTab a'))
var triggerTabList = [].slice.call($('.nav-tabs> .nav-item> .nav-link'))
triggerTabList.forEach(function (triggerEl) {
	var tabTrigger = new bootstrap.Tab(triggerEl)
	triggerEl.addEventListener('click', function (event) {
		event.preventDefault()
		tabTrigger.show()
	})
})
function tabs_load()
{
	showFirst();
}
function showFirst()
{
	var firstTabEl = $('.nav-tabs> .nav-item:first-child> .nav-link')
	var firstTab = new bootstrap.Tab(firstTabEl)
	firstTab.show()
}
