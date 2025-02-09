//Varibles que definen los navegadores
var	ns  = (document.layers) ? true : false;
var	ie     = (document.all && !document.getElementById) ? true : false;
var	ie5    = (document.all && document.getElementById) ? true : false;
var	ns6    = (!document.all && document.getElementById) ? true : false;

//var auxAlfa=new Array(10);
// Array de dos dimensiones:
// aux[0] Contiene los n?meros del cajet?n de firmas en orden aleatorio
// aux[1] Contiene las letras correspondientes a los n?meros indicados arriba
//Defino el array de las capas que se ir?n activando segun pinchemos en los campos que las activan
var ArrCapaAct = new Array();
//Defino el array de las letras genradas para cada firma.
var ArrLetras = new Array();
var auxAlfa=new Array(10);
var aux1 =new Array(20);
var aux2 =new Array(20);
var aux3 =new Array(20);
var aux4 =new Array(20);
var aux5 =new Array(20);
var aux6 =new Array(20);
var aux7 =new Array(20);
var aux8 =new Array(20);

var formulario = "f1";
var nombreAux = "alfa";

// En cualquier momento que se produzca la pulsaci?n completa de una tecla comprobaremos si
// est? activada la capa de firma y si es as? la marcaremos en el cajet?n de firma si es una
// de las teclas permitidas en ?ste
var activada=false;
if (navigator.userAgent.indexOf("Windows CE") == -1){
if (ie||ie5) document.onkeydown=TeclaPulsada;
else if (ns||ns6) document.onkeypress=TeclaPulsada;
}

//Variable que acumula la tecla que se ha pulsado para utilizarla en la funcion de borrar.
var teclaGlobal;
// Funci?n que se ejecuta al pulsar una tecla
function TeclaPulsada(e)
{
	// Si e no existe es que estamos navegando con MS Internet Explorer
	// Si es as?, cogemos el evento con window.event
	if (!e) var e = window.event;
    var tecla = (document.all)?e.keyCode:e.which;
	teclaGlobal = tecla;
    //Si el cajetin est? visible y la tecla pulsada no es el tabulador comprobamos

    if ((activada) && (tecla!=9))
	{
	 comprobar(e,' ',XSMLNP);
	}
}

//funcion que toma el objeto segun requiera cada explorador
function encontrar(id)
{
	if (navigator.appName.indexOf('Microsoft')!=-1){
		obj = document.all[id];
	}else{
		if (document.getElementById) { obj = document.getElementById(id);}
		else if (document.all) { obj = document.all[id]; }
	}
	return obj;
}

// Funcion que oculta la capa que se pasa como argumento
function DesactivarCapa() {

	if (CajetinBackUp != null){
		document.getElementById(CajetinBackUp).innerHTML = "";
	}
	activada = false;


}

//Funcion que activa la capa que se le pasa como argumento
function ActivarCapa(nombre) {
	// Guardamos en XSMLNP el nombre del cajetin de firma activado en ese momento
	XSMLNP=nombre;
	generaFirmas(nombre)
}

// Funcion que coloca el foco en las cajas de los DNI
function PonerFoco(nombre){

	//Recorre el array por orden y cuando llega al campo que lo llamo, comienza a tratar de poner el 
	//foco en los sucesivos, solo si existen y su capa es visible
	var comienzo=false;
	var puesto=false;
	var campos = new Array('Doc1', 'Pin1', 'Firma1', 'Doc2', 'Pin2', 'Firma2', 'Doc3', 'Pin3', 'Firma3', 'focoCajetin');
	for(z=0;z<campos.length;z++)
	{
		//recorre el array
		if(!comienzo){
			//si encuentra el campo actual, comienza la busqueda del campo siguiente para asignarle el foco
			if(nombre==campos[z])
				comienzo=true;
		}else{
			//busca el siguiente campo que exista
			if (encontrar(campos[z])!=null)
			{
				//comprueba que la capa del 'bloque' exista y sea visible 
				var capaaux='firmaBloq'+campos[z].charAt(campos[z].length-1);
				if (encontrar(capaaux)!=null)
				{
					if(encontrar(capaaux).style.display!='none')
					{
						if(encontrar(campos[z]).type!="hidden")
						{
							encontrar(campos[z]).focus();
							puesto=true;
							break;
						}
					}
				}
			}
		}
	}
	if(!puesto)
	{
		if (encontrar('selopcion_s')!=null)
		{
			//si no encuentra nada pero existe este campo estamos en el login y se le manda el foco
			if(encontrar('selopcion_s').type!="hidden")
			{
				encontrar('selopcion_s').focus();
			}
		}else{
			//si no encuentra nada desactiva la capa activa
			DesactivarCapa();
		}
	}
}

function arrayNumeros() {
	array = new Array(10);
	for(e=0; e<9; e++) {
	 array[e] = (e+1);
	}
	array[9]=0;
	return array;
}

//Funcion que transforma un entero a su caracter correspondiente en ascii
function ACaracter (codigo) {
 return unescape('%' +codigo.toString(16));
}

// Funcion que genera el array de relaciones entre numeros y caracteres
function arrayRelacionado(nombre) {

   	arrayAlfa = new Array(10);
   	arrayNum = new Array(10);
   	arrayLetras = new Array(10);

	arrayRel=new Array(2);
 	  for (t=0; t<2; t++)
   	   arrayRel[t]=new Array(10);

	arrayNum=arrayNumeros();

	//genera el array de caracteres
	alfa = ACaracter(getRandom(65, 90));
	arrayAlfa[0] = alfa;
	for(numGenerados=1;numGenerados<10;) {
        	alfa = ACaracter(getRandom(65, 90));
     		yaExiste=false;
	for(t=0; t<10; t++) {
		if (arrayAlfa[t] == alfa) yaExiste=true;
		}
	if (yaExiste==false) arrayAlfa[numGenerados++] = alfa;
	}

	/*utilizo el arrayLetras para rellenar las variables que envian la correspondencia entre letras y n?meros)
	para ello pongo la ultima letra de arrayAlfa en la primera posici?n de arrayLetras ya que la ?ltima letra del array
	corresponde con el n?mero cero*/

	arrayLetras[0]=arrayAlfa[9];
	for(t=1; t<=9; t++)
		arrayLetras[t]=arrayAlfa[t-1];

	var textoArray = '';
	for(t=0; t<arrayLetras.length; t++)
		textoArray = textoArray + arrayLetras[t];

    // se rellena el campo de la relacion
    document.getElementById("tr_"+nombre).value = textoArray;

    //rellena el array final de relaciones
	for (t=0; t<10; t++){
 	  arrayRel[0][t]=arrayNum[t];
	  arrayRel[1][t]=arrayAlfa[t];
	}
	return arrayRel;

}

function transposicionLetras(nombre)
{
	var resultado="";
	var arrayLetras;
	var fuente;

	arrayLetras = encontrar("tr_"+nombre);
	fuente = encontrar(nombre);

	for(i=0;i<fuente.value.length;i++)
	{
		var letraaux = fuente.value.charAt(i);
		if(isNaN(letraaux))
			resultado+=arrayLetras.value.indexOf(letraaux);
		else
			resultado+=letraaux;
	}
	return resultado;
}

// Funcion que genera numeros aleatorios desde un comienzo a un final
function getRandom(start,end) {
    var range = end - start + 1;
    var result = start + Math.floor(Math.random()*range);
    return result;
}

// Funcion que borra de la caja de firmas
function Borrar(nombre) {
            document.forms[formulario].elements[nombre].value = (document.forms[formulario].elements[nombre].value).substring(0, document.forms[formulario].elements[nombre].value.length-1);


		var elementoFocus = nombreAux+parseInt(tab+1);
        //document.getElementById(elementoFocus).focus();
      document.getElementById("enlace_limbo").focus();
}

function BorrarTrasposicion()
{
	var aux20=encontrar('Firma1');
	if (aux20!=null) aux20.value = '';
	var aux21=encontrar('Firma2');
	if (aux21!=null) aux21.value = '';
	var aux22=encontrar('Firma3');
	if (aux22!=null) aux22.value = '';
	var aux23=encontrar('Pin1');
	if (aux23!=null) aux23.value = '';
	var aux24=encontrar('Pin2');
	if (aux24!=null) aux24.value = '';
	var aux25=encontrar('Pin3');
	if (aux25!=null) aux25.value = '';
	var aux=encontrar('tr_Firma1');
	if (aux!=null) aux.value = '';
	var aux1=encontrar('tr_Firma2');
	if (aux1!=null) aux1.value = '';
	var aux2=encontrar('tr_Firma3');
	if (aux2!=null) aux2.value = '';
	var aux3=encontrar('tr_Pin1');
	if (aux3!=null) aux3.value = '';
	var aux4=encontrar('tr_Pin2');
	if (aux4!=null) aux4.value = '';
	var aux5=encontrar('tr_Pin3');
	if (aux5!=null) aux5.value = '';
}

// Funcion que muestra u oculta los bloques de firmas dependiendo de el parametro enviado
function mostrarBloques(numFirmas)
{
		if (numFirmas == "1")
		{
			if (document.getElementById("firmaBloq2"))
				document.getElementById("firmaBloq2").style.display="none";
			
			if (document.getElementById("firmaBloq3"))
				document.getElementById("firmaBloq3").style.display="none";
		}
		else if (numFirmas == "2")
		{
			if (document.getElementById("firmaBloq2"))
				document.getElementById("firmaBloq2").style.display="inline";
			
			if (document.getElementById("firmaBloq3"))
				document.getElementById("firmaBloq3").style.display="none";
		}
		else
		{
			if (document.getElementById("firmaBloq2"))
				document.getElementById("firmaBloq2").style.display="inline";
			
			if (document.getElementById("firmaBloq3"))
				document.getElementById("firmaBloq3").style.display="inline";
		}
}

// Funcion que almacena en un campo oculto el valor que se esta introduciendo
function almacenar(valor,nombre,valorAux) {
		var campofir = encontrar(nombre);

		if (campofir.value.length == 8) return;
		campofir.value = campofir.value + valor;
		var elementoFocus = nombreAux+parseInt(tab+1);
		document.getElementById("enlace_limbo").focus();		
		//document.getElementById(elementoFocus).focus();
}

//Funcion que comprueba que la tecla pulsada se corresponde con uno de los caracteres de la segunda fila
function comprobar(e,valor,nombre){
	// Si tenemos activado el cajet?n haremos la comprobaci?n de la tecla pulsada
	if (activada)
	{

		var almacena=false;
		var tecla = (document.all)?e.keyCode:e.which;

		//comprobamos si se pulso la tecla de "ir atras" en cuyo caso borramos caracter de firma.

		if ((tecla==8)||(tecla==46)){
		  e.returnValue = false;
		  Borrar(nombre);
     	  if (ns||ns6) e.preventDefault();
		  return;
		}

		if (tecla==9){
		//DesactivarCapas();
		almacena=false;
		DesactivarCapa(nombre);
		return;
		}

		var pulsado = String.fromCharCode(tecla).toUpperCase();
		//comprobaci?n de que no se trata de blockNum
		if (ie||ie5)
			if(ip=="0")
				if(tecla>=96 && tecla<=105)
				{
					tecla=tecla-48;
					pulsado = String.fromCharCode(tecla).toUpperCase();
				}

		for (i=0;i<10;i++){
		 if ((((pulsado==aux[0][i])||(pulsado==aux[1][i])) && (ip=="1")) || (pulsado==aux[1][i]))
		 	{
		 		almacena=true;
				valor=aux[0][i];
			}
		}

		if (ie||ie5)
			//Si la tecla pulsada es f1..f12 no se almacena
			if(tecla>=112 && tecla<=123){
				almacena=false;
				}
		if (almacena)
			{
			// Si el evento viene del cajet?n de la firma el valor ser? pasado desde all?, sin embargo
			// si se produce desde fuera del cajet?n ser? un evento global, por lo que tendremos
			// la tecla que se ha pulsado, pero no su pareja en el array de n?mero aleatorios
			almacenar(pulsado,nombre,valor);
			}
	}
	return;
}



//Funcion que oculta todas las capas al cargar la jsp
function ocultar(nombre){
	//encontrar(nombre).style.display='none';
}

// *****************************************************************************//
// ???IMP:Funciones que generan el componenete grafico para introducir las firmas //
// ****************************************************************************//
//DEfino variable global de tabulacion
var tab = 900;
	function generaElemento(nombre,aux,auxAlfa)
	{
		var tabParticular = tab ;

		cadena = cadena + '<table ><tr>';
		for (var id=0; id<10;id++){			cadena = cadena + '<th scope="col"><span class="oculto">D&iacute;gito n&ordm; '+ id +' </span></th>';
		}
		cadena = cadena + '</tr><tr>';

		tabParticular = tabParticular + 1;
		for (var id=0; id<10;id++){
	    	cadena = cadena + '<td><input id="'+nombreAux+tabParticular+'" type="button" tabindex="'+tabParticular+'"  class="alfa" title="Letra ' + aux[1][id] + ', corresponde al n&uacute;mero ' + aux[0][id] + ' " value="' + aux[1][id] +'" onClick="almacenar(\''+aux[1][id]+'\',\''+nombre+'\',\''+aux[0][id]+'\');" readonly></td>';
			tabParticular = tabParticular + 2
	    	}
		tabParticular = tab + 2;
		cadena = cadena + '</tr><tr>';
	    for (var id=0; id<10;id++){
			cadena = cadena + '<td><input id="num'+tabParticular+'" type="button" tabindex="'+tabParticular+'"  class="styleNum"  value="' + aux[0][id] + '" title="N&uacute;mero ' + aux[0][id] + ', corresponde a la letra ' + aux[1][id] + '" onClick="almacenar(\''+aux[1][id]+'\',\''+nombre+'\',\''+aux[0][id]+'\');" readonly></td>';
			tabParticular = tabParticular + 2
		}
		cadena = cadena + '</tr></table>';
	}


//Defino la variable global AyudaFirmas, que sera el codigo que se emplee para las ayudas de las firmas.
var AyudaFirmas = "5937";
//Defino la variable que contendra la capa en la que pinto el cajetin de la firma para que despues al pintar otro cajetin me borre el anterior;
var CajetinBackUp = null;
//Defino la cadena de codigo HTML que pintare en la capa determinada;
var cadena;
function generaFirmas(nombre) {
		cadena = '';
		//cadena = "<span >&nbsp;</span>";
		cadena = cadena + '<div class="wrapp_firma"> ';
		cadena = cadena + '<div id="limbo"><a id="enlace_limbo" tabindex="'+tab+'" href="#">&nbsp;</a></div>';		
		cadena = cadena + '<div class="teclado_firma"> ';

		//Asigno en la siguiente variable el nombre de la capa en la que pintare el contenido de la firma.
		//Esta capa se compone siempre de el literal "Cajetin"+nombre del campo que lo lanza, que viene definido por el argumento "nombre"
		var capa = "Cajetin"+nombre;
		cadena = cadena +'<div class="capa_firma">';
		cadena = cadena +'<h3 class="oculto">Firma Electr&oacute;nica Accesible </h3>';
		cadena = cadena +'<p class="oculto">La forma de introducir su clave y firma electr&oacute;nica se ha modificado. A partir de ahora podr&aacute; utilizar tanto el teclado como el rat&oacute;n para incorporar su clave de acceso y firma en Oficina Internet.</p>';
		cadena = cadena +'<p class="oculto">Para introducir su clave o firma con el teclado, introduzca las letras asociadas a sus claves que aparecer&aacute;n en el gr&aacute;fico. Estas letras se ver&aacute;n modificadas aleatoriamente en cada conexi&oacute;n que realice, por lo que deber&aacute; introducir diferente combinaci&oacute;n alfab&aacute;tica en cada acceso. </p>';
		cadena = cadena +'<p class="oculto">Si desea utilizar el rat&oacute;n seleccione con el puntero las celdas que contienen los d&iacute;gitos de sus claves.</p>';
		cadena = cadena +'<p class="oculto">En cualquiera de los casos, deber&aacute; pulsar Anotar para introducir en el cajet&iacute;n correspondiente su clave de acceso o su firma electr&oacute;nica. Posteriormente para acceder al servicio o completar una operaci&oacute;n dentro de Oficina Internet, deber&aacute; pinchar en el bot&oacute;n Entrar o  Aceptar de la pantalla correspondiente.  </p>';
		cadena = cadena +'<p class="oculto">En el caso de que no disponga de clave y firma electr&oacute;nica o se encuentren bloqueadas, deber&aacute; solicitarlas en  su oficina, quien por motivos de seguridad se las entregar&aacute; personalmente.</p>';


		if ( capa.substr(capa.length -3) == 'OIE' ){
            		formulario = "f2";
            		nombreAux = "OIEalfa";
        	}
	        else{
	            formulario = "f1";
	            nombreAux = "alfa";
	        }

		//Comprobamos si estamos navegando por PDA
	if (navigator.userAgent.indexOf("Windows CE") == -1){
		var insertNombre = true;
		for(i = 0; i < ArrCapaAct.length;i++){
			if (ArrCapaAct[i] == nombre){
				insertNombre = false;
				aux = ArrLetras[i];
			}
		}
		if (insertNombre == true || ArrCapaAct.length == 0){
			ArrCapaAct[ArrCapaAct.length] = nombre
			aux = arrayRelacionado(nombre);
			ArrLetras[i] = aux;
		}
	}
	else{
		aux = arrayRelacionado(nombre);
	}
		if (nombre=="Pin1"){
			tab = 800;
		}else if (nombre=="Firma1"){
			tab = 900;
		}else if (nombre=="Pin2"){
			tab = 1100;
		}else if (nombre=="Firma2"){
			tab = 1200;
		}else if (nombre=="Pin3"){
			tab = 1400;
		} else if (nombre=="Firma3"){
			tab = 1500;
		}else{ //solo uno
			tab = 900;
		}

		cadena = cadena + '<p class="right"><a id="botonAyuda" href=javascript:lanzaAyuda("'+AyudaFirmas+'","Comunes_Plantilla_Firmas") tabindex="'+tab+'">&gt; '+varmsgcm_CajetinFirmas[0]+'</a></p>'





			generaElemento(nombre,aux);


		cadena = cadena + '<ul title="Botones para anotar o borrar la firma">';
		cadena = cadena + '<li><a href="#" onClick="DesactivarCapa();PonerFoco(\''+nombre+'\');return false" onnkeypress="DesactivarCapa();PonerFoco(\''+nombre+'\');return false" tabindex="'+parseInt(tab+50)+'">&gt; '+varmsgcm_CajetinFirmas[1]+'</a></li>';
		cadena = cadena + '<li><a href="#" onClick="Borrar(\''+nombre+'\');return false" onkeypress="Borrar(\''+nombre+'\');return false" tabindex="'+parseInt(tab+52)+'">&gt; '+varmsgcm_CajetinFirmas[2]+'</a></li>';
		cadena = cadena + '</ul>';
		cadena = cadena + '</div>';
		cadena = cadena + '</div>';
		cadena = cadena + '</div>';

		//Comprobamos si estamos navegando por PDA
		if (navigator.userAgent.indexOf("Windows CE") == -1){
		//Compruebo si el CajetinBackUp es null o es distinto de la capa en la que tengo que pintar, para borrar el codigo HTML de la capa que esta en el cjetinBackUp y asi aparezca siempre un solo cajetin
			if (CajetinBackUp == null)
				CajetinBackUp = capa;
			if (CajetinBackUp != capa){
				document.getElementById(CajetinBackUp).innerHTML = "";
				document.getElementById(CajetinBackUp).style.display = "none"

				CajetinBackUp = capa;
			}
			document.getElementById(capa).style.display = "inline"

		}

		document.getElementById(capa).innerHTML = cadena;
		activada = true;

	}

//Con esta funcion reconocemos si estamos navegando en una PDA y si es as? tenemos que lanzar nosotros mismos la funcion de generarFirmas
if (navigator.userAgent.indexOf("Windows CE") != -1){

		var objForm = document.forms

		//Asignamos comportamientos a los campos de texto
		for (i = 0; i < objForm.length; i++)  {
			for(k = 0; k < objForm[i].elements.length; k++) {
				if(objForm[i].elements[k].type == "text" || objForm[i].elements[k].type == "password") {
					var nombre = objForm[i].elements[k].name;
					if ((nombre == "Firma1")||(nombre == "Firma2")||(nombre == "Firma3")){
						XSMLNP=nombre;
						generaFirmas(nombre)
					}
					else if ((nombre == "Pin3")||(nombre == "Pin2")||(nombre == "Pin3")){
						XSMLNP=nombre;
						generaFirmas(nombre)
					}
					else if (nombre == XSMLNP){
						XSMLNP=nombre;
						generaFirmas(XSMLNP)
					}
				}
			}
		}
}