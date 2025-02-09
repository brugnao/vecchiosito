function showICDesc(arrIceCatProducts,arrRelProducts,strdescription) {
	var el=document.createElement('table');
	el.id='prodDescArea';
	el.className='main';
	el.cellSpacing='0';
	el.cellPadding='0';
	var tbd=document.createElement('tbody');	
	var col=document.createElement('td');
	var row=document.createElement('tr');
	tbd.appendChild(row);
	row.appendChild(col);
	el.appendChild(tbd);
	if(arrIceCatProducts!=null && arrIceCatProducts.length>0) {
		var txtArea=document.createElement('div');
		txtArea.id='icecatDescArea';
		col.appendChild(txtArea);
		var dv=document.createElement('div');
		dv.className='featureGroup';
		txtArea.appendChild(dv);
			
		var theTbl=document.createElement('table');
		theTbl.className='desc';
		theTbl.width='100%';
		theTbl.cellSpacing='2';
		theTbl.cellPadding='0';
		
		var theTbd=document.createElement('tbody');
		theTbl.appendChild(theTbd);
		dv.appendChild(theTbl);
		for(var k=0;k<arrIceCatProducts.length;k++) {
			
			var theRow=document.createElement('tr');
			var theCol=document.createElement('th');
			theCol.colSpan='2';
			theCol.appendChild(document.createTextNode(arrIceCatProducts[k]['fgVal']));
			theRow.appendChild(theCol);
			theTbd.appendChild(theRow);
			if(arrIceCatProducts[k]['fgArr'] && arrIceCatProducts[k]['fgArr'].length>0) {
				for(v=0;v<arrIceCatProducts[k]['fgArr'].length;v++) {
					//var dvc=document.createElement('div');
					var theRow=document.createElement('tr');
					var theCol=document.createElement('td');
					theCol.innerHTML=arrIceCatProducts[k]['fgArr'][v]['fname'];
					theCol.className='main_name';
					theCol.vAlign='top';
					theRow.appendChild(theCol);
					var theCol=document.createElement('td');
					if(arrIceCatProducts[k]['fgArr'][v]['img']) {
						var val=document.createElement('img');
						val.src=arrIceCatProducts[k]['fgArr'][v]['img'];
					} else {
						var val=document.createElement('span');
						val.innerHTML = arrIceCatProducts[k]['fgArr'][v]['fvalue'];
					}
					theCol.appendChild(val);
					theCol.className='main_value';
					theRow.appendChild(theCol);
					theTbd.appendChild(theRow);
				}
				//dvc.appendChild(theTbl);
				//dvc.className='content';
				//dv.appendChild(dvc);
			}
		}
		//row.appendChild(col);
	} else if(arrRelProducts!=null && arrRelProducts.length>0) {
		var txtArea=document.createElement('div');
		txtArea.id='icecatDescArea';
		col.className='main';
		txtArea.innerHTML=arrRelProducts;
		col.appendChild(txtArea);
	} else if(strdescription!=null){
		var txtArea=document.createElement('div');
		txtArea.id='icecatDescArea';
		col.className='main';
		txtArea.innerHTML=strdescription;
		col.appendChild(txtArea);
	}
	var area=document.getElementById('productDesc');
	area.appendChild(el);
}
function buildICMenu(arrICEMenu) {
	var el=document.createElement('table');
	el.border='0';
	el.className='main';
	el.cellSpacing='0';
	el.cellPadding='2';
	var tbd=document.createElement('tbody');
	var row=document.createElement('tr');
	row.id='prodDescMenu';
	tbd.appendChild(row);
	el.appendChild(tbd);
	for(var k=0;k<arrICEMenu.length;k++) {
		if(arrICEMenu[k]['name'] && arrICEMenu[k]['js']) {
			var col=document.createElement('td');
			col.className='main';
			row.appendChild(col);
			if(k==0) {
				col.className+='_first';
			} else if(k==(arrICEMenu.length-1)) {
				col.className+='_last';
			}
			if(arrICEMenu[k]['css']) {
				col.className+=arrICEMenu[k]['css'];
			}
			var a=document.createElement('a');
			col.appendChild(a);
			a.innerHTML = arrICEMenu[k]['name'];
			a.href='javascript:'+arrICEMenu[k]['js'];
			a.onclick=arrICEMenu[k]['js'];
		}
	}
	
	var area=document.getElementById('productDesc');
	area.appendChild(el);
}