x = (screen.height / 2) - 480 
y = (screen.width / 2) - 350
function abrirDemo() {
  window.open("http://www.cajamadrid.es/Portal_Corporativo/html/Demo_OI/html/demo1.html","demo_oi","left="+x+",top="+y+",width=800,height=505,toolbar=yes,location=yes,directories=no,status=no,menubar=yes,scrollbars=yes,resizable=no");
}
function abrirInformacion() {
  window.open("http://www.cajamadrid.es/CajaMadrid/Home/puente?pagina=3497","informacion","left="+x+",top="+y+",width=800,height=505,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no");  
}
function abrirInformacionClave() {
  window.open("/CajaMadrid/oi/pt_oi/Login/generaPopUpLogin?NumeroPagina=5032&NumeroSeccion=31059","informacionclave","left="+x+",top="+y+",width=800,height=505,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=no");
}

function seleccionarTexto(campo) {
	campo.value="";
}
function filtraNum(text)
{
   var cadena=text.value;
   var len=cadena.length;
   var i;
   var salida="";
   for (i=0;i<len;i++)
   {
       var c=cadena.charAt(i);
       if (!isNaN(parseInt(c))) salida+=c;
   }
   return(salida);
}
function esVacio( cadena )
{
    str = trimString(cadena.value);    
      if (( str == null ) || ( str == "" )) { return (true); } else { return (false); }
}
function esNumerico( s ) {		
	var i;
	for( i=0; i<s.length; i++)
	{	
		var c = s.charAt( i );
		if( c!='1' && c!='2' && c!='3' && c!='4' && 
			c!='5' && c!='6' && c!='7' && c!='8' && 
			c!='9' && c!='0' )
		{
			return (false);	
			i = s.length;			
		}
	}								
	return (true);	
}

var requested = 0;

function tabOnEnter2 (orden, evt) {
  var keyCode = document.layers ? evt.which : document.all ? 
evt.keyCode : evt.keyCode;

  if (keyCode != 13)
    return true;
  else {
   	if(orden == 0)
		document.forms["f1"].elements[XSMLNP].focus();
    if (orden == 1)     
		document.forms["f1"].selopcion_s.focus();	    
  	if(orden == 2)
		document.all.ir.focus();
	return false;
  }
}
function valida(contrato) {
    if (XSMLNP == ""){
        alert(varmsgLoginE01[5]);
	    si = false ;
    }
    //validar que los dos campos esten rellenos y que el campo clave es numerico y tiene al menos 4 caracteres
	var si = true ;
	
	if(contrato==1) {
	
		document.forms["f1"].NumeroCliente_s.value = filtraNum(document.forms["f1"].NumeroCliente_s);
		if (esVacio(document.forms["f1"].NumeroCliente_s))
		{
			document.forms["f1"].NumeroCliente_s.focus();
			alert(varmsgLoginE01[0]);
			si = false ;
		}
	
		if (si && !esNumerico(document.forms["f1"].NumeroCliente_s.value))
		{
			document.forms["f1"].NumeroCliente_s.value = "";
			document.forms["f1"].NumeroCliente_s.focus();
			alert(varmsgLoginE01[1]);
			si = false ;
		}
		
		if (si && document.forms["f1"].NumeroCliente_s.value.length != 13)
		{
			document.forms["f1"].NumeroCliente_s.value = "";
			document.forms["f1"].NumeroCliente_s.focus();
			alert(varmsgLoginE01[2]);
			si = false ;
		}
	
	} else {
		 
		if (radioSelected == 'DNIe') {
	
			if (document.forms["f1"].certificados.type == 'hidden') {
				document.forms["f1"].Documento_s.value = obtenerDNI(document.forms["f1"].certificados.value);
			} else {
				document.forms["f1"].Documento_s.value = obtenerDNI(document.forms["f1"].certificados[document.forms["f1"].certificados.selectedIndex].value);
			}
			
			if (!firmarLogin(this.document.forms["f1"]))
				si = false;
			
			
		} else if (radioSelected == 'MV') {
		
			try
	        	{
		       		var resultado = objCMCAPCSC.Firmar(document.forms["f1"].Desafio.value);
		       		if(resultado==0){
		       			document.forms["f1"].NumeroTarjeta.value = objCMCAPCSC.PAN;
		       			if(document.forms["f1"].NumeroSecuencialTarjeta.value=="")
		       			{
		       				document.forms["f1"].NumeroSecuencialTarjeta.value = objCMCAPCSC.PSN;
		       			}
		       			document.forms["f1"].FirmaTarjeta.value = objCMCAPCSC.Firma;
		       		}else if(resultado==13){
		       			alert(varmsgLoginE01[7]);
		       			return false;
		       		}else {
		       			return false;
	       			}
		       		objCMCAPCSC.Terminar();
	       		} catch(e) {
	       			activeXInstalado();
	       			return false;
	       		}
			
			
		} else { //DNI
			
		}
		
		document.forms["f1"].Documento_s.value = filtraNum(document.forms["f1"].Documento_s);
		if (esVacio(document.forms["f1"].Documento_s)) {
			document.forms["f1"].Documento_s.focus();
			alert(varmsgLoginE01[3]);
			si = false ;
		}
		
		
	}

    if (si && document.forms["f1"].elements[XSMLNP].value.length < 4 || document.forms["f1"].elements[XSMLNP].value.length > 8) {
		document.forms["f1"].elements[XSMLNP].value = "";
	    document.forms["f1"].elements[XSMLNP].focus();
	    alert(varmsgLoginE01[5]);
	    si = false ;
   	}
	if (si && document.forms["f1"].selopcion_s.value == "nada") {
	    document.forms["f1"].selopcion_s.focus();
	    alert(varmsgLoginE01[6]);
	    si = false ;
   	}

	if ( si && requested==0 ){
        document.forms["f1"].elements[XSMLNP.substr(0, 8)].value = cifrarRSA(transposicionLetras(XSMLNP));
        document.forms["f1"].elements[XSMLNP].value ="";
        document.forms["f1"].elements["tr_"+XSMLNP].value ="";
        
        document.forms['f1'].submit() ;
		requested = 1;
		document.links[0].href = '#';

	} else if (radioSelected == 'MV') {
		iniciaAX();
	}

	
}

function abrirSello(){
	window.open('/CajaMadrid/oi/pt_oi/Login/generaPopUpSello','cond','toolbar = no, location = no, directories = no, status = no, scrollbars = yes, resizable = no, width="+ancho+",height="+alto+",top="+x+",left="+y+"');
}