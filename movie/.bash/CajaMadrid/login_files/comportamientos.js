function AsignarComportamiento() {
		var objForm = document.forms
		//Asignamos comportamientos a los campos de texto
		for (i = 0; i < objForm.length; i++)  {
			for(k = 0; k < objForm[i].elements.length; k++) {
				if(objForm[i].elements[k].type == "text" || objForm[i].elements[k].type == "textarea"|| objForm[i].elements[k].type == "select-one") {
					if(objForm[i].elements[k].className != "clave"){
						Object.registraClase(objForm[i].elements[k], MetodoInput);
					}
				}
				//Insertamos el comportamiento de los label a los checkbox;
				//Se quita el día 7-11-2005 por motivos de accesibilidad//
				if(objForm[i].elements[k].type == "radio" ||objForm[i].elements[k].type == "checkbox"){

					//Object.registraClase(objForm[i].elements[k], MetodoClick);
				}
				//Insertamos el comportamiento de la validacion de fechas a los campos fecha
				if(objForm[i].elements[k].type == "text" && objForm[i].elements[k].className == "fecha") {
				//Poner activo cuando venga algiuen de cajamadrid
					Object.registraClase(objForm[i].elements[k], MetodoFecha);

				}

			}
		}

		//Asignamos comportamientos a las etiquetas label, para que al hacer click sobre ellas se active el radiobutton que le precede
		var objLabel = document.getElementsByTagName("label");
		for (i = 0; i < objLabel.length; i++)  {
			Object.registraClase(objLabel[i].parentNode, MetodoClick);
			//Compruebo si la clase del label es activarCajetin, que se encuentra en los label de Clave y Firma correspondientes

			if (objLabel[i].className == "ActivarCajetin") {

				for(w = 0; w < objLabel[i].parentNode.parentNode.childNodes.length; w++){
					if (objLabel[i].parentNode.parentNode.childNodes[w].tagName == "INPUT"){
						Object.registraClase(objLabel[i].parentNode.parentNode.childNodes[w], MetodoFirmaActivar);
					}
				}
			}
			 //Compruebo si la clase del label es DesactivarCajetin, que se encuentra en los label de Clave y Firma correspondientes
			else if (objLabel[i].className == "DesactivarCajetin") {
				for(w = 0; w < objLabel[i].parentNode.parentNode.childNodes.length; w++){
					if (objLabel[i].parentNode.parentNode.childNodes[w].tagName == "INPUT" || objLabel[i].parentNode.parentNode.childNodes[w].tagName == "SELECT"){
						Object.registraClase(objLabel[i].parentNode.parentNode.childNodes[w], MetodoFirmaDesactivar);
					}
				}
			}
		}
		//Opcion de añadir comportamiento en el boton de aceptar para que si no se ha modificado nada en los campos de formulario y hay values de formulario igual que
		//los label, se borren estos de la misma forma que se borran al entrar en un campo cualquiera del formulario.
		var objAceptar = document.getElementById("aceptar");
		//Esta variable sirver para asignar un id al boton cancelar mediante el comportamiento que despues se utilizara para poner el foco de los cajetines de firmas en el caso de que estemos en la ultiam firma posible y aceptemos, ocultemos la capa y tengamos que mandar el foco al enlace Cancelar.
		var almacenarId = false;
		if (objAceptar) {
			for(i = 0; i < objAceptar.childNodes.length;i++) {

				for (x = 0; x < objAceptar.childNodes[i].childNodes.length; x++) {
					if (document.all){
						if(objAceptar.childNodes[i].childNodes[x].childNodes[0]){
							if (almacenarId ==false){
								almacenarId = true;
								objAceptar.childNodes[i].childNodes[x].childNodes[0].id = "focoCajetin";
							}
							Object.registraClase(objAceptar.childNodes[i].childNodes[x].childNodes[0], MetodoInput);
						}
					}
					else{
						if (objAceptar.childNodes[1]) {

							if(objAceptar.childNodes[i].childNodes[x].childNodes[0]){
								if (almacenarId ==false){
									almacenarId = true;
									objAceptar.childNodes[i].childNodes[x].childNodes[0].id = "focoCajetin";
								}
								Object.registraClase(objAceptar.childNodes[i].childNodes[x].childNodes[0], MetodoInput);
							}
						}
					}

				}
			}
		}
	}


function MetodoFirmaActivar(){
	this.onfocus = this._onfocus
}
MetodoFirmaActivar.prototype._onfocus = function () {
MacheaLabel(this)
ActivarCapa(this.id)
}
function MetodoFirmaDesactivar(){
	this.onfocus = this._onfocus
}
MetodoFirmaDesactivar.prototype._onfocus = function () {
MacheaLabel(this)
DesactivarCapa()
}















var camposAccionados = new Array()
function MetodoInput(){
	this.onfocus = this._onfocus
}

MetodoInput.prototype._onfocus = function () {

	if (entrar == true) {
		entrar = false;
		var objForm = document.forms
		for (i = 0; i < objForm.length; i++)  {
			for(k = 0; k < objForm[i].elements.length; k++) {
			if (objForm[i].elements[k].type == "text" || objForm[i].elements[k].type == "textarea") {
				var longitudCampo = objForm[i].elements[k].value.length;
					for (x = 0; x < objForm[i].elements[k].parentNode.childNodes.length; x++) {

						if(objForm[i].elements[k].parentNode.childNodes[x].tagName == "LABEL") {

							var texto = objForm[i].elements[k].parentNode.childNodes[x].innerHTML
							if(texto.substring(0,longitudCampo) == objForm[i].elements[k].value) {

								objForm[i].elements[k].value = "";
							}
						}
						else {
							for (z = 0; z < objForm[i].elements[k].parentNode.childNodes[x].childNodes.length;z++) {

								if(objForm[i].elements[k].parentNode.childNodes[x].childNodes[z].tagName == "LABEL") {
									//Comprobamos si el campo label tiene un <acronym> dentro.
									if(objForm[i].elements[k].parentNode.childNodes[x].childNodes[z].childNodes.length > 0) {
									//Aqui solo se entra si dentro del campo label hay un ACRONYM.
										for (w = 0; w < objForm[i].elements[k].parentNode.childNodes[x].childNodes[z].childNodes.length;w++) {
											if (!objForm[i].elements[k].parentNode.childNodes[x].childNodes[z].childNodes[w].innerHTML) {
													var texto = objForm[i].elements[k].parentNode.childNodes[x].childNodes[z].innerHTML
													if(texto.substring(0,longitudCampo) == objForm[i].elements[k].value) {

														objForm[i].elements[k].value = "";
													}
											}
											else {
												if (objForm[i].elements[k].parentNode.childNodes[x].childNodes[z].childNodes[w].tagName == "ACRONYM") {
													var texto = objForm[i].elements[k].parentNode.childNodes[x].childNodes[z].childNodes[w].innerHTML
													if(texto.substring(0,longitudCampo) == objForm[i].elements[k].value) {

														objForm[i].elements[k].value = "";
													}
												}
											}
										}
									}
									else {
									//Si no hay ACRONYM.
										var texto = objForm[i].elements[k].parentNode.childNodes[x].childNodes[z].innerHTML
										if(texto.substring(0,longitudCampo) == objForm[i].elements[k].value) {

											objForm[i].elements[k].value = "";
										}
									}

								}
							}
						}
					}
				}
			}
		}
	}
}
var entrar = true;
//Metodos para activar el radiobutton de una etiqueta label, habilitar los campo de texto siguientes a la etiqueta y poner el foco en el primer campo de texto.
function MetodoClick(){
	this.onclick = this._onfocus
}
MetodoClick.prototype._onfocus = function () {
MacheaLabel(this)

}

function MacheaLabel(paramObj){
	objPrincipal = paramObj.childNodes
	for (i = 0; i < objPrincipal.length;i++) {
		if (objPrincipal[i].tagName == "LABEL"){
			var objOriginal = objPrincipal[i];
			var obj = objPrincipal[i];
			break;
		}
	}
	if (objOriginal){

		var x = 1;
		var focus = true;
		var checked = false;
		obj = obj.nextSibling;
		if (obj){
			objPrevious = obj.previousSibling;

			for (i = 0; i < x; i++)
			{
				checked = true;
				if(objPrevious){
					if(objPrevious.tagName == "INPUT" && objPrevious.type == "radio") {

						if (objOriginal.attributes['for']){
							var strFor = objOriginal.attributes['for'].value;
							if(strFor != objPrevious.id)
							{
								objPrevious.checked = true;
								break;
							}

						}
						else if (objOriginal.type == "radio"){
							objPrevious.checked = true;
							break;
						}
					}
					else {
						x = x + 1;
					}
					objPrevious =objPrevious.previousSibling;
				}

			}

		}
		//Activamos el evento onclick original de la etiqueta.
		if (objOriginal.attributes['onclick']){
			var strFor = objOriginal.attributes['onclick'].value;
			eval(strFor)
		}
	}
}
//Metodos para la validación del formato de fechas automatizado.
function MetodoFecha(){
	this.onkeyup = this._onkeyup
}
ns4 = (document.layers)? true:false
ie4 = (document.all)? true:false
var borrar = false;
function TeclaPulsadaFecha(e) {
    if (ie4) {var TeclaN=event.keyCode}
	else {var TeclaN=e.which}
	if (TeclaN == 8 || TeclaN == 46){borrar = true}


}
MetodoFecha.prototype._onkeyup = function () {

	//averiguar cual es la tecla pulsada para no eejcutar el evento cuando se pulsa la tecla de borrar o de suprimir.
	document.onkeydown = TeclaPulsadaFecha
	if (ns4) document.captureEvents(Event.KEYDOWN)
	if(borrar == false){
		if (this.value != "dd/mm/aaaa"){
			var valorCampo = this.value;
			if (valorCampo.length < 4){
				if (valorCampo.charAt(1) && valorCampo.charAt(1) == "/" &&  valorCampo.charAt(0)){
					this.value = "0"+valorCampo;
				}
				else if (valorCampo.charAt(1) && valorCampo.charAt(1) != "/" && valorCampo < 32) {
					this.value = valorCampo + "/";
				}
				else if (valorCampo.charAt(1) && valorCampo.charAt(1) != "/" && valorCampo > 31)
				{
					alert(varmsgComportamientos[0])
					this.value = "";
				}
			}
			if (valorCampo.length < 7){
				if (valorCampo.charAt(3) && valorCampo.charAt(3)== "/") {
					alert(varmsgComportamientos[1])
					this.value = valorCampo.slice(0,3);
				}
				else if (valorCampo.charAt(3)) {
					if (valorCampo.charAt(3) && valorCampo.charAt(4) && valorCampo.charAt(4) == "/" &&  valorCampo.charAt(3) != 0){
						this.value = valorCampo.slice(0,3) + "0" + valorCampo.slice(3,4)+"/";
						this.value = validaDiaMes(this.value)
	
	
					}
					else if (valorCampo.charAt(4) && valorCampo.charAt(4) != "/" && !valorCampo.charAt(5) && valorCampo.slice(3,5) < 13){
						this.value = valorCampo + "/";
						this.value = validaDiaMes(this.value)
	
					}
					else if(valorCampo.charAt(4) && valorCampo.charAt(4) != "/" && !valorCampo.charAt(5) && valorCampo.slice(3,5) > 12){
						alert(varmsgComportamientos[2]);
						this.value = valorCampo.slice(0,3);
					}
	
	
				}
	
			}
	
			if (valorCampo.charAt(9) && valorCampo.length < 12){
				if (valorCampo.charAt(2) && valorCampo.charAt(3)== "/") {
					alert(varmsgComportamientos[1])
					this.value = valorCampo.slice(0,3);
				}
				if(valorCampo.charAt(2)!= "/" ){
					alert(varmsgComportamientos[3])
	
					this.value = valorCampo.slice(0,2);
					this.value = this.value + "/";
				}
				if (valorCampo.charAt(5)!= "/") {
					alert(varmsgComportamientos[3])
					this.value = valorCampo.slice(0,5);
					this.value = this.value + "/";
				}
				this.value = validaDiaMes(this.value)
				this.value = validaAnyo(this.value)
			}
		}
	}
	borrar = false;

}
function validaDiaMes(valor){
	var valor = valor;
	var dia = parseInt(valor.slice(0,2), 10);
	var mes = parseInt(valor.slice(3,5), 10);
	var anio = parseInt(valor.slice(6,10), 10);
	if (mes > 12){
		alert(varmsgComportamientos[2]);
		valor = valor.slice(0,3);
	}else if(mes==2){
		if((anio%4==0)&&!((anio%100==0)&&(anio%400!=0)))
		{
			//es bisiesto
			if(dia>29)
			{
				alert(varmsgComportamientos[6]);
				valor = "";
			}
		}
		else
		{
			//no es bisiesto
			if(dia>28)
			{
				alert(varmsgComportamientos[5]);
				valor = "";
			}
				
		}
	}else if(((mes%2 == 0 && mes<7)||(mes%2 != 0 && mes>8))&&( dia > 30)){
		alert(varmsgComportamientos[4]);
		valor = "";
	}
	return valor;
}
function validaAnyo (valor) {
	var valor = valor;
	var anyo = parseInt(valor.slice(6,10), 10);
	if (anyo <= 1899){
		alert(varmsgComportamientos[7])
		valor = valor.slice(0,6)
	}
	return valor;
}	
//Funcion que hace un eval del getElementByID() para la PDA
if (navigator.userAgent.indexOf("Windows CE") != -1){
	if (document.getElementById){
	eval('getElementById(selector)');
	}
	function getElementById (selector){
	return eval(selector);
	}
}
var XSMLNP="";