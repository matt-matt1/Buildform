/*
 * https://stackoverflow.com/questions/28308103/adjust-width-of-select-element-according-to-selected-options-width
 *
// resize on initial load
document.querySelectorAll("select").forEach(resizeSelect)

// delegated listener on change
document.body.addEventListener("change", (e) => {
	if (e.target.matches("select")) {
		resizeSelect(e.target)
	}
})
*/
const extraWidth = 4;
const extraUnits = 'px';
function resizeSelect(sel) {
	if (typeof sel.selectedOptions[0] === "undefined")
		return;
	let tempOption = document.createElement('option');
	tempOption.textContent = sel.selectedOptions[0].textContent;

	let tempSelect = document.createElement('select');
	tempSelect.style.visibility = "hidden";
	tempSelect.style.position = "fixed"
	tempSelect.appendChild(tempOption);
	
	sel.after(tempSelect);
	sel.style.width = `${+tempSelect.clientWidth + extraWidth} + ${extraUnits}`;
	tempSelect.remove();
}
