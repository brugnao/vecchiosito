var radioSelected = 'DNI';
var indiceTab = 700;

function tipoAcceso(tipo) {

	if ( tipo == 'DNI') {
		SelDNI();
	} else if ( tipo == 'MV' ) {
		preMV();
		MV();
	} else if ( tipo == 'DNIe' ) {
		preDNIe();
	}

	AsignarComportamiento();
	document.getElementById('foco_vacio').focus();
	
	
}

function cancelar(tipo) {


	for (i=0; i<document.forms["f1"].TipoTarjeta.length; i++) {
		if (document.forms["f1"].TipoTarjeta[i].value == radioSelected ) {
			 document.forms["f1"].TipoTarjeta[i].checked = true;
			 break;
		}
	}
	
	auxRadio = radioSelected;
	radioSelected = tipo;
	tipoAcceso(auxRadio);
	document.getElementById('foco_vacio').focus();

}

function SelDNI() {

	document.getElementById("webSigner").src = "/CajaMadrid/oi/blank.html";
	
	//Eliminamos el input del DNI
	eliminarDNIfield();
	
	//Creampos el input txt del DNI
	crearDNItxt(varmsgLoginE[2]);
	
	document.getElementById('capaTextoDNIe').style.display = 'none';
	document.getElementById('capaCampos').className = 'lista_dos';	
	
	var txtDNIe = document.getElementById("txtDNIe");
	txtDNIe.className = 'oculto';
	txtDNIe.innerHTML = '';
	
	if (radioSelected == 'DNIe') 
		eliminarCertificadosField();
	
	//Cambiamos los textos
	cambiarTxtCapaCampos(varmsgLoginE[1]);
	cambiarLabelDNI(varmsgLoginE[2] + ':');
	
	
	//Establecemos el valor de la IDOC del tipo de Acceso
	document.forms["f1"].TipoIdentificacion_s.value = 'D';
	radioSelected = 'DNI';
	
}

function preMV() {

	//Eliminamos el input del DNI
	eliminarDNIfield();
	
	//Creampos el input txt del DNI
	crearDNItxt(varmsgLoginE[3]);

	document.getElementById('capaTextoDNIe').style.display = 'none';
	document.getElementById('capaCampos').className = 'lista_dos';	
	
	var txtDNIe = document.getElementById("txtDNIe");
	txtDNIe.className = 'oculto';
	txtDNIe.innerHTML = '';
	
	if (radioSelected == 'DNIe') 
		eliminarCertificadosField();

	cambiarTxtCapaCampos(varmsgLoginE[4]);
	cambiarLabelDNI('<acronym title="Documento Nacional de Identidad">' + varmsgLoginE[3] + '</acronym>:');
	
	

}

function MV() {

	//Activamos el activeX de Geminis
	if (navigator.appName == "Microsoft Internet Explorer") {
		activeXInstalado();
		//Establecemos el valor de la IDOC del tipo de Acceso
		document.forms["f1"].TipoIdentificacion_s.value = 'T';
		radioSelected = 'MV';
	}  else {
		//Mostrar alert de que es necesario explorer y cancelar
		alert(varmsgLoginE[0]);
		cancelar('MV');
	}
	
}

function preDNIe() {

	document.getElementById('capaTextoDNIe').style.display = 'block';
	document.getElementById('capaCampos').className = 'lista_tres';
	cambiarTxtCapaCampos(varmsgLoginE[5]);
	cambiarLabelDNI('<acronym title="Documento Nacional de Identidad">' + varmsgLoginE[3] + '</acronym>:');

	//Eliminamos el input del DNI
	eliminarDNIfield();
	
	//Creamos input hidden del DNI
	crearDNIhidden();
	
	//Cargamos el Websigner en el iFrame
	document.getElementById("webSigner").src = "/CajaMadrid/oi/pt_oi/Login/loginScriptsDNIe";

}

function DNIe(arrCertsAut) {

	if (arrCertsAut.length == 1) {

		dni = obtenerDNI(arrCertsAut[0][0]);
		
		var txtDNIe = document.getElementById("txtDNIe");
		txtDNIe.className = 'negrita';
		txtDNIe.innerHTML = dni;
		
		crearCertificadosHidden();
	
		//Creamos el hidden con el numero de certificado del certificado
		document.forms["f1"].certificados.value = arrCertsAut[0][0];
		
	} else {
	
		//Creamos select del DNI
		crearDNIselect();
		
		var DNIselect = document.forms["f1"].certificados;
		
		for (i=0; i<arrCertsAut.length; i++) {
			dni = obtenerDNI(arrCertsAut[i][0]);
			// Añado el DNI al combo
			addOption(DNIselect, dni, arrCertsAut[i][0]);
		}
	
	}

	//Establecemos el valor de la IDOC del tipo de Acceso
	document.forms["f1"].TipoIdentificacion_s.value = 'E';
	radioSelected = 'DNIe';

}

function eliminarDNIfield() {
	
	var filaDNIe = document.getElementById('filaDNIe');
	
	var campoDoc = document.getElementById('Documento_s');
	
	// Almacenamos el tabIndex del campo
	if (document.forms["f1"].Documento_s.type == 'text' )
		indiceTab = campoDoc.tabIndex;
	
	filaDNIe.removeChild(campoDoc);
	
}

function eliminarCertificadosField() {

	var filaDNIe = document.getElementById('filaDNIe');
	
	var campoDoc = document.getElementById('certificados');
	
	if (campoDoc != null)
		filaDNIe.removeChild(campoDoc);

}

function crearDNIhidden() {

	var filaDNIe = document.getElementById('filaDNIe');
	
	var campoDoc = document.createElement('input');
	campoDoc.setAttribute('type', 'hidden');
	campoDoc.setAttribute('name', 'Documento_s');
	campoDoc.setAttribute('id', 'Documento_s');
	
	
	filaDNIe.appendChild(campoDoc);
	
}

function crearCertificadosHidden() {

	var filaDNIe = document.getElementById('filaDNIe');
	
	var campoDoc = document.createElement('input');
	campoDoc.setAttribute('type', 'hidden');
	campoDoc.setAttribute('name', 'certificados');
	campoDoc.setAttribute('id', 'certificados');
	
	
	filaDNIe.appendChild(campoDoc);
	
}

function crearDNItxt(value) {
	
	var filaDNIe = document.getElementById('filaDNIe');
	
	var campoDoc = document.createElement('input');
	campoDoc.setAttribute('type', 'text');
	
	if (navigator.appName == "Microsoft Internet Explorer")
		campoDoc.setAttribute('className', 'ancho');
	else 
		campoDoc.setAttribute('class', 'ancho');
	
	campoDoc.setAttribute('name', 'Documento_s');
	campoDoc.setAttribute('id', 'Documento_s');
	campoDoc.setAttribute('autocomplete', 'off');
	campoDoc.setAttribute('maxlength', '15');
	campoDoc.setAttribute('value', value);
	
	//Establecemos los eventos
	if (navigator.appName == "Microsoft Internet Explorer")
		campoDoc.onkeypress = function() {return tabOnEnter2 (0, event);};
	else
		campoDoc.onkeypress = function(event) {return tabOnEnter2 (0, event);};
		
	campoDoc.onclick = function() {seleccionarTexto(this);};
	campoDoc.onfocus = function() {seleccionarTexto(this);};
	
	//Añadimos el elemento al DOM	
	filaDNIe.appendChild(campoDoc);
	
	//Establecemos el tabindex despues porque sino explorer no lo pilla
	campoDoc = document.getElementById('Documento_s');
	campoDoc.tabIndex = indiceTab;
	
}

function crearDNIselect() {

	var filaDNIe = document.getElementById('filaDNIe');
	
	var campoDoc = document.createElement('select');
	campoDoc.setAttribute('name', 'certificados');
	campoDoc.setAttribute('id', 'certificados');
	
	if (navigator.appName == "Microsoft Internet Explorer")
		campoDoc.setAttribute('className', 'clave');
	else 
		campoDoc.setAttribute('class', 'clave');
	
	//Añadimos el elmento al DOM
	filaDNIe.appendChild(campoDoc);
	
	//Establecemos el tabindex despues porque sino explorer no lo pilla
	campoDoc = document.getElementById('certificados');
	campoDoc.tabIndex = indiceTab;
	
}

function cambiarLabelDNI(texto) {

	document.getElementById('labelDocumento_s').innerHTML = texto;

}

function cambiarTxtCapaCampos(texto) {

	document.getElementById('txtCapaCampos').innerHTML = texto;

}

function obtenerDNI(indexCert) {

	//Recuperamos la informacion del certificado
	infoCert = getInfoByIndex(indexCert,parent.frames["webSigner"].OUT_JAVA);
	subject = infoCert[1];

	DNI = '';
	indiceCampo = subject.indexOf('SERIALNUMBER');
	
	serialNumber = new String(subject.substring(indiceCampo,subject.indexOf(',',indiceCampo)));
	NIF = serialNumber.substring(serialNumber.indexOf('=')+1);
	DNI = NIF.substring(0,NIF.length-1);
	
	return DNI;

}

/*
 * Funciones y variables de Geminis
 */
 
var objCMCAPCSC;
var timerX;

function iniciaAX()
{
		objCMCAPCSC = new ActiveXObject("CMCATI32.CMCAPCSC");
		objCMCAPCSC.Iniciar();
		objCMCAPCSC.ControlExtraccion=1;
		return true;
}

function compruebaAX()
{
	try{	
		iniciaAX();
	}catch(err){
		setTimeout("compruebaAX()",1000);
	}
}

function activeXInstalado(){
	try{	
		return iniciaAX();
	}catch(err){
		var iframeOculta = document.getElementById("webSigner");
		iframeOculta.src="/CajaMadrid/oi/pt_oi/Geminis/iframeGemActiveX";
		compruebaAX();
		return false;
	}
}

/*
 * Fin Geminis
 */



//Añade opciones a un combo
function addOption(list,optionText,optionValue){
    list.options[list.options.length] = new Option(optionText,optionValue);
}

/*
 * Mapeo de funciones javascript al frame de websigner
 * Simplemente se duplica la funcion del iframe y se llama a la funcion del iframe
 * El unico objetivo de esto es ayudar a la claridad del codigo
 */
function firmarLogin(form, anyadirTextoAFirmar) {

	return parent.frames["webSigner"].firmarLogin(form, anyadirTextoAFirmar);

}

function getInfoByIndex(iCert, format) {

	return parent.frames["webSigner"].getInfoByIndex(iCert, format);

}

/*
 * Funciones visuales para las imagenes
 */
function op1(id){
var obj = new Image();
 obj = encontrar(id);
obj.src ="/CajaMadrid/oi/imagenes/interr.gif";
}

function op2(id){
var obj = new Image();
 obj = encontrar(id);
obj.src ="/CajaMadrid/oi/imagenes/interr_v.gif";
}

function encontrar(id) {
	if (document.getElementById) { 
		obj = document.getElementById(id);
	}
	else if (document.all) { 
		obj = document.all[id]; 
	}return obj;
}
