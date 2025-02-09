/* CONTROL DE VERSIONES
$Id: ayuda.js,v 1.17 2006/11/22 17:14:44 felix3 Exp $
*/
function lanzaAyuda( numeroPagina, nombreJSP)
{
	
	url = "/CajaMadrid/oi/pt_oi/muestraAyuda?numeroPagina=" + numeroPagina + "&nombreJSP=" + nombreJSP ;
	v1 = window.open( url, "PantallaAyuda",  "left=75,top=90,width=640,height=450,scrollbars=yes");
	//alert(url);
}


function abrirPopUpLogin(numeroPagina,numeroSeccion) {
  window.open('/CajaMadrid/oi/pt_oi/Login/generaPopUpLogin?NumeroPagina=' + numeroPagina + '&NumeroSeccion=' + numeroSeccion ,"informacion","left=0,top=0,width=800,height=505,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no");
}

function abrirPopUpContenidoLibre(numPag, numSec) {
  window.open('/CajaMadrid/oi/pt_oi/popUpContenidoLibre?NumeroPagina=' + numPag + '&NumeroSeccion=' + numSec ,"informacion","left=0,top=0,width=800,height=505,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no");
}

function abrirPopUpURL (url) {
	window.open(url,"url","left=0,top=0,width=800,height=505,toolbar=yes,location=yes,directories=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes");
}