const elems = document.querySelectorAll('.list-group-item');
const hides = document.querySelectorAll('.hide-start-drag');
const shows = document.querySelectorAll('.show-end-drag');
const dest = document.getElementById('main_form');
/*new Sortable(dest, {
	animation: 150,
	ghostClass: 'blue-background-class'
});*/
elems.forEach(el => {
	el.addEventListener('dragstart', dragStart)
	el.addEventListener('dragend', dragEnd)
});
dest.addEventListener('dragover', dragOver);
//dest.addEventListener('dragenter', dragEnter);
//dest.addEventListener('dragleave', dragLeave);
dest.addEventListener('drop', dragDrop);

/*let dragItem = null;*/
function dragOver(e) {
	e.preventDefault();
	//console.log('drag over');
}
function dragStart() {
	//console.log('drag started');
/*	dragItem = this;*/
	this.classList.add("dragging");
	for (i=0; i<hides.length; i++)
	{
		hides[i].classList.add('hide');
	}
	//setTimeout(() => this.className = 'invisible', 0);
}
function dragEnd() {
	//console.log('drag ended');
	this.className = 'list-group-item';
/*	dragItem = null;*/
	this.classList.remove("dragging");
	for (i=0; i<shows.length; i++)
	{
		shows[i].classList.add('show');
	}
}
function dragDrop(e) {
	//console.log('drag dropped');
/*	this.append(dragItem);*/
	//this.appendChild(document.querySelector(".dragging"));
	const dragged = document.querySelector(".dragging")
	const after = getDragAfterElement(dest, e.clientY)
	if (after === undefined)
		this.appendChild(dragged.cloneNode())
	else
		this.insertBefore(dragged.cloneNode(), after)
	//cursor = crosshairs;
	const tag = dragged.dataset.tag;
	this.appead(tag);
}
const getDragAfterElement = (container, y) => {
	const notDraggedCards = [...container.querySelectorAll(".card:not(.dragging)")]

	return notDraggedCards.reduce((closest, child) => {
		const box = child.getBoundingClientRect()
		const offset = y - box.top - box.height / 2
		if (offset < 0 && offset > closest.offset) {
			return { offset, element: child }
		} else return closest
	}, { offset: Number.NEGATIVE_INFINITY }).element
}
