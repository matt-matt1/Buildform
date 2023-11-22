function MultiselectDropdown(options){
	var config={
		//search:true,
		height:'15rem',
		placeholder:'select',
		txtSelected:'selected',
		txtAll:'All',
/*		txtInvert:'Invert',*/
		txtRemove:'Remove',
		txtSearch:'search',
		...options
	};
	function newEl(tag,attrs){
		var e=document.createElement(tag);
		if(attrs!==undefined) Object.keys(attrs).forEach(k=>{
			if(k==='class') {
				Array.isArray(attrs[k]) ? attrs[k].forEach(o=>o!==''?e.classList.add(o):0) : (attrs[k]!==''?e.classList.add(attrs[k]):0)
			}else if(k==='style'){
				Object.keys(attrs[k]).forEach(ks=>{
					e.style[ks]=attrs[k][ks];
				});
			}
			else if(k==='text'){
				attrs[k]==='' ? e.innerHTML='&nbsp;' : e.innerText=attrs[k]
			}
			else e[k]=attrs[k];
		});
		return e;
	}

	//document.querySelectorAll("select[multiple]").forEach((el,k)=>{
	document.querySelectorAll("select[multiple]").forEach(function(el,k){
		//var div=newEl('div',{class:'multiselect-dropdown',style:{width:config.style?.width??el.clientWidth+'px',padding:config.style?.padding??''}});
		var div=newEl('div',{class:'multiselect-dropdown',style:{padding:config.style?.padding??''}, title:(el.title)?el.title:''});
		el.style.display='none';
		el.parentNode.insertBefore(div,el.nextSibling);
		var listWrap=newEl('div',{class:'multiselect-dropdown-list-wrapper'});
		var list=newEl('div',{class:'multiselect-dropdown-list',style:{height:config.height}});
//		if(el.dataset && el.dataset.multiselectSearch=='true'){
			var search=newEl('input',{
				class:['multiselect-dropdown-search'].concat([config.searchInput?.class??'form-control']),
				style:{width:'100%',display:(el.dataset && el.dataset.multiselectSearch==='true')?'block':'none'},
				placeholder:config.txtSearch
			});
/*				style:{width:'100%',display:el.attributes['multiselect-search']?.value==='true'?'block':'none'},*/
/*				style:{width:'100%'},*/
			listWrap.appendChild(search);
//		}
		div.appendChild(listWrap);
		listWrap.appendChild(list);

		//el.loadOptions=()=>{
		el.loadOptions=function(){
			list.innerHTML='';
/*			if(el.attributes['multiselect-invert']?.value=='true'){
				var oi=newEl('div',{class:'multiselect-dropdown-invert-selector'})
				var ii=newEl('input',{type:'checkbox'});
				oi.appendChild(ii);
				oi.appendChild(newEl('label',{text:config.txtInvert}));
	
				if (typeof addEvent == "function")
				{
					addEvent(oi, 'click', function(){
						oi.classList.toggle('checked');
						oi.querySelector("input").checked = !oi.querySelector("input").checked;
			
						var ci=oi.querySelector("input").checked;
						list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)")
							.forEach(i=>{if(i.style.display!=='none'){i.querySelector("input").checked=ci; i.optEl.selected=ci}});
	
						el.dispatchEvent(new Event('change'));
					});
					addEvent(ii, 'click', function(ev){
						ii.checked=!ii.checked;
					});
					addEvent(el, 'change', function(ev){
						let itms=Array.from(list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)"))
							.filter(e=>e.style.display!=='none')
//						let existsNotSelected=itms.find(i=>!i.querySelector("input").checked);
//						if(ic.checked && existsNotSelected) ic.checked=false;
//						else if(ic.checked==false && existsNotSelected===undefined) ic.checked=true;
						ii.checked=!ii.checked;
					});
				} else {
					//op.addEventListener('click',()=>{
					oi.addEventListener('click',function(){
						oi.classList.toggle('checked');
						oi.querySelector("input").checked = !oi.querySelector("input").checked;
			
						var ci=oi.querySelector("input").checked;
						list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)")
							.forEach(i=>{if(i.style.display!=='none'){i.querySelector("input").checked=ci; i.optEl.selected=ci}});
	
						el.dispatchEvent(new Event('change'));
					});
					//ic.addEventListener('click',(ev)=>{
					ii.addEventListener('click',function(ev){
						ii.checked = !ii.checked;
					});
					//el.addEventListener('change',(ev)=>{
					el.addEventListener('change', function(ev){
						let itms=Array.from(list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)"))
							.filter(e=>e.style.display!=='none')
						let existsNotSelected=itms.find(i=>!i.querySelector("input").checked);
						if(ii.checked && existsNotSelected) ii.checked=false;
						else if(ii.checked==false && existsNotSelected===undefined) ii.checked=true;
					});
				}
				list.appendChild(oi);
			}*/

			//if(el.attributes['multiselect-select-all']?.value=='true'){
			if(el.dataset && el.dataset.multiselectSelectAll=='true'){
				var op=newEl('div',{class:'multiselect-dropdown-all-selector'})
				var ic=newEl('input',{type:'checkbox'});
				op.appendChild(ic);
				op.appendChild(newEl('label',{text:config.txtAll}));
	
			//addDynamicEventListener(document.body, 'change', el.parentElement, function (e) {
				if (typeof addEvent == "function")
				{
//					addEvent(op, 'click', opClick);
					addEvent(document.body, 'click', function(){
						event = event || window.event;
						target = event.target || event.srcElement;
//						while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//							target = target.parentElement;
						if (target === op){
							op.classList.toggle('checked');
							op.querySelector("input").checked=!op.querySelector("input").checked;
				
							var ch=op.querySelector("input").checked;
							list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)")
							.forEach(i=>{if(i.style.display!=='none'){i.querySelector("input").checked=ch; i.optEl.selected=ch}});
		
							el.dispatchEvent(new Event('change'));
						}
					});
					//addEvent(ic, 'click', function(ev){
					addEvent(document.body, 'click', function(){
						event = event || window.event;
						target = event.target || event.srcElement;
//						while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//							target = target.parentElement;
						if (target === ic){
							ic.checked=!ic.checked;
						}
					});
					//addEvent(el, 'change', function(ev){
					addEvent(document.body, 'change', function(){
						event = event || window.event;
						target = event.target || event.srcElement;
//						while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//							target = target.parentElement;
						if (target === el){
							let itms=Array.from(list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)"))
								.filter(e=>e.style.display!=='none')
							let existsNotSelected=itms.find(i=>!i.querySelector("input").checked);
							if(ic.checked && existsNotSelected) ic.checked=false;
							else if(ic.checked==false && existsNotSelected===undefined) ic.checked=true;
						}
					});
				} else {
					//op.addEventListener('click',()=>{
					op.addEventListener('click',function(){
						op.classList.toggle('checked');
						op.querySelector("input").checked=!op.querySelector("input").checked;
			
						var ch=op.querySelector("input").checked;
						list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)")
						.forEach(i=>{if(i.style.display!=='none'){i.querySelector("input").checked=ch; i.optEl.selected=ch}});
	
						el.dispatchEvent(new Event('change'));
					});
					//ic.addEventListener('click',(ev)=>{
					ic.addEventListener('click',function(ev){
						ic.checked=!ic.checked;
					});
					//el.addEventListener('change',(ev)=>{
					el.addEventListener('change', function(ev){
						let itms=Array.from(list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)"))
							.filter(e=>e.style.display!=='none')
						let existsNotSelected=itms.find(i=>!i.querySelector("input").checked);
						if(ic.checked && existsNotSelected) ic.checked=false;
						else if(ic.checked==false && existsNotSelected===undefined) ic.checked=true;
					});
				}
				list.appendChild(op);
			}

			Array.from(el.options).map(o=>{
				var op=newEl('div',{class:o.selected?'checked':'',optEl:o})
				var ic=newEl('input',{type:'checkbox',checked:o.selected});
				for(i=0; i<o.attributes.length; i++)
				{
					if (o.attributes[i].name==='class')
						//ic.setAttribute(o.attributes[i].name, o.attributes[i].value);
						op.className += ((!ic.className || ic.className != '') ? ' ' : '') + o.attributes[i].value;
					else{
						op.setAttribute(o.attributes[i].name, o.attributes[i].value);
						if (o.attributes[i].name==='id')
							o.setAttribute(o.attributes[i].name, 'old_'+o.attributes[i].value);
					}
				}
				op.appendChild(ic);
				op.appendChild(newEl('label',{text:o.text}));

				if (typeof addEvent == "function")
				{
					//addEvent(op, 'click', function(){
					addEvent(document.body, 'click', function(){
						event = event || window.event;
						target = event.target || event.srcElement;
//						while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//							target = target.parentElement;
						if (target === op){
							op.classList.toggle('checked');
							op.querySelector("input").checked=!op.querySelector("input").checked;
							op.optEl.selected=!!!op.optEl.selected;
							if(el.dataset && el.dataset.multiselectChangeTitle=='true'){
								var cDivs = list.querySelectorAll(".checked");
								var str = '';
								for(i=0; i<cDivs.length; i++)
								{
									var label = cDivs[i].querySelector('label').innerText;
									str += label + ', ';
								}
								str = str.substring(0, str.length-2);
								op.parentElement.parentElement.parentElement.title = str;
							}
							el.dispatchEvent(new Event('change'));
						}
					});
				} else {
					op.addEventListener('click',function(){//()=>{
						op.classList.toggle('checked');
						op.querySelector("input").checked=!op.querySelector("input").checked;
						op.optEl.selected=!!!op.optEl.selected;
						if(el.dataset && el.dataset.multiselectChangeTitle=='true'){
							var cDivs = list.querySelectorAll(".checked");
							var str = '';
							for(i=0; i<cDivs.length; i++)
							{
								var label = cDivs[i].querySelector('label').innerText;
								str += label + ', ';
							}
							str = str.substring(0, str.length-2);
							op.parentElement.parentElement.parentElement.title = str;
						}
						el.dispatchEvent(new Event('change'));
					});
				}
				if (typeof addEvent == "function")
				{
					//addEvent(ic, 'click', function(ev){
					addEvent(document.body, 'click', function(){
						event = event || window.event;
						target = event.target || event.srcElement;
//						while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//							target = target.parentElement;
						if (target === ic){
							ic.checked=!ic.checked;
						}
					});
				} else {
					//ic.addEventListener('click',(ev)=>{
					ic.addEventListener('click',function(ev){
						ic.checked=!ic.checked;
					});
				}
				o.listitemEl=op;
				list.appendChild(op);
			});
			div.listEl=listWrap;

			//div.refresh=()=>{
			div.refresh=function(){
				div.querySelectorAll('span.optext, span.placeholder').forEach(t=>div.removeChild(t));
				var sels=Array.from(el.selectedOptions);
				if(sels.length>(el.attributes['multiselect-max-items']?.value??5)){
					div.appendChild(newEl('span',{class:['optext','maxselected'],text:sels.length+' '+config.txtSelected}));
				}
				else{
					sels.map(x=>{
						var c=newEl('span',{class:'optext',text:x.text, srcOption: x});
						if((el.attributes['multiselect-hide-x']?.value !== 'true'))
							//c.appendChild(newEl('span',{class:'optdel',text:'🗙',title:config.txtRemove,onclick:(ev)=>{c.srcOption.listitemEl.dispatchEvent(new Event('click'));div.refresh();ev.stopPropagation();}}));
							c.appendChild(newEl('span',{
								class:'optdel', text:'🗙', title:config.txtRemove,
								onclick:function(ev){c.srcOption.listitemEl.dispatchEvent(new Event('click'));div.refresh();ev.stopPropagation();}
							}));
						div.appendChild(c);
					});
				}
				if(0==el.selectedOptions.length) div.appendChild(newEl('span',{
					class:'placeholder',text:el.attributes['placeholder']?.value??config.placeholder
				}));
			};
			div.refresh();
		}
		el.loadOptions();
		
//		if(el.dataset && el.dataset.multiselectSearch=='true'){
			if (typeof addEvent == "function")
			{
				//addEvent(search, 'input', function(){
				addEvent(document.body, 'input', function(){
					event = event || window.event;
					target = event.target || event.srcElement;
//					while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//						target = target.parentElement;
					if (target === search){
						list.querySelectorAll(":scope div:not(.multiselect-dropdown-all-selector):not(.multiselect-dropdown-invert-selector)").forEach(d=>{
							var txt=d.querySelector("label").innerText.toUpperCase();
							d.style.display=txt.includes(search.value.toUpperCase())?'block':'none';
						});
					}
				});
			} else {
				//search.addEventListener('input',()=>{
				search.addEventListener('input',function(){
					list.querySelectorAll(":scope div:not(.multiselect-dropdown-all-selector):not(.multiselect-dropdown-invert-selector)").forEach(d=>{
						var txt=d.querySelector("label").innerText.toUpperCase();
						d.style.display=txt.includes(search.value.toUpperCase())?'block':'none';
					});
				});
			}
//		}

		if (typeof addEvent == "function")
		{
			//addEvent(div, 'click', function(){
			addEvent(document.body, 'click', function(){
				event = event || window.event;
				target = event.target || event.srcElement;
//				while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//					target = target.parentElement;
				if (target === div){
					div.listEl.style.display='block';
					search.focus();
					search.select();
				}
			});
		} else {
			div.addEventListener('click', function() {
				div.listEl.style.display='block';
				search.focus();
				search.select();
			});
		}

		if (typeof addEvent == "function")
		{
			addEvent(document, 'click', function(event){
/*			addEvent(document.body, 'click', function(){
				event = event || window.event;
				target = event.target || event.srcElement;
//				while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
//					target = target.parentElement;
				if (target === ic){*/
					if (!div.contains(event.target)) {
						listWrap.style.display='none';
						div.refresh();
					}
/*				}*/
			});
		} else {
			document.addEventListener('click', function(event) {
				if (!div.contains(event.target)) {
					listWrap.style.display='none';
					div.refresh();
				}
			});
		}
	});
}

if (typeof addEvent == "function")
{
	addEvent(window, 'load', function(){
		MultiselectDropdown(window.MultiselectDropdownOptions);
	});
/*	addEvent(document.body 'click', function(event){
		event = event || window.event;
		target = event.target || event.srcElement;
		// srcElement and target both refer to the element that triggered the event, not the element that caught (& handles) it
		while (!target.getAttribute('class').indexOf('eventCatcher') >= 0)
			target = target.parentElement;
//		if (target ===)
	});
	addEvent(document.body, 'change', function(event){
	});*/
} else {
	window.addEventListener('load',function(){
		MultiselectDropdown(window.MultiselectDropdownOptions);
	});
}
/*
function opClick(event)
{
	op.classList.toggle('checked');
	op.querySelector("input").checked=!op.querySelector("input").checked;

	var ch=op.querySelector("input").checked;
	list.querySelectorAll(":scope > div:not(.multiselect-dropdown-invert-selector):not(.multiselect-dropdown-all-selector)")
	.forEach(i=>{if(i.style.display!=='none'){i.querySelector("input").checked=ch; i.optEl.selected=ch}});

	el.dispatchEvent(new Event('change'));
}*/
