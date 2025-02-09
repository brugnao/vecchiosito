<?php
$store_name=STORE_NAME;
$store_owner=STORE_OWNER;

// titolo della pagina
define('HEADING_TITLE_PRIVACY', 'Licenza');

// Testo delle informazioni riguardo privacy e quant'altro.
// Nota: $store_name e $store_owner verranno rimpiazzate nel testo inserito, 
//		con il nome del negozio, ed il nome del proprietario, come inseriti 
//		nella sezione di configurazione dal lato amministrazione (admin)
// Potete cambiare il testo in completa libertà, ma senza toccare la linea seguente, nè l'ultima linea : EOPA
$TEXT_PRIVACY_INFORMATION = <<< EOPA
<br/>
<h3>$store_name<br/>
<small>($store_owner)</small></h3><br/>
<br/>
DISCLAIMER<br/>
<br/>
$store_owner ha realizzato questo sito di commercio elettronico, ceduto in gestione 
alla societ&agrave; titolare del servizio e del nome a dominio che individua il sito stesso.<br/>  
L'applicazione &egrave; stata concepita e realizzata per consentire agli utenti  che 
accedono al sistema di acquistare prodotti e/o servizi 
messi a disposizione dalla societ&agrave; titolare del dominio ed 
inseriti su un apposito catalogo di vendita predisposto on-line.<br/>
Al fine di regolamentare l'accesso del singolo utente al sito di commercio elettronico, 
$store_owner ha predisposto le seguenti condizioni che l&agrave;utente medesimo dichiara 
di accettare al momento della registrazione.<br/>
<br/>
***<br/>
<br/>
1 ACCETTAZIONE DELLE CONDIZIONI<br/>
Per effettuare la registrazione al sito di commercio elettronico 
necessaria per l'accesso allo stesso l'utente dovr&agrave;:
-	inserire su apposito modulo predisposto on-line uno username ed una password 
da lui stesso scelti e manifestare il proprio consenso, nell'apposito riquadro 
presente nel modulo predisposto on-line, affinch&egrave; $store_owner raccolga, 
immagazzini ed eventualmente comunichi a terzi i propri dati per le seguenti 
finalit&agrave;: erogazione di servizi terzi, statistiche, altro.<br/>
A seguito della registrazione effettuata dall'utente, $store_owner raccoglier&agrave; i 
dati inseriti  nel modulo di registrazione e invier&agrave; all'indirizzo dell'utente 
una e-mail  di conferma dell'avvenuta registrazione e dell'inserimento dei dati 
personali nel database per le finalit&agrave; indicate nel modulo di registrazione.<br/>
<br/>
<br/>
2 COMUNICAZIONI<br/>
Qualsiasi comunicazione inviata dall'utente al sistema di commercio elettronico
tramite e-mail contenente dati personali sar&agrave; trattata dai titolari del servizio 
in conformit&agrave; alle previsioni del D.Lgs. 196 del 2003, nonch&egrave; secondo 
le modalit&agrave; e per le finalit&agrave; descritte nella 'Informativa privacy e consenso 
che l'utente legger&agrave; al momento della registrazione.<br/> 
<br/>
3 MODIFICHE ALLE CONDIZIONI <br/>
$store_owner si riserva il diritto di modificare e/o integrare, in qualsiasi 
momento e senza preavviso, le presenti condizioni di accesso al sistema di 
commercio elettronico; tali modifiche e/o integrazioni saranno immediatamente 
pubblicate sul sito stesso e comunicate al singolo utente registrato 
attraverso l'invio di una e-mail all'indirizzo di posta elettronica dello stesso.<br/>
In ogni caso, l'accesso al sistema di commercio elettronico successivo alla 
pubblicazione delle modifiche e/o integrazioni apportate e all'inoltro delle 
stesse all'indirizzo di posta elettronica dell'utente, sar&agrave; considerato alla 
stregua di una manifestazione di volont&agrave; dell'utente medesimo e varr&agrave; come 
accettazione alle modifiche e/o integrazioni.<br/>
<br/>
4 CONTENUTI DEL SITO DI COMMERCIO ELETTRONICO<br/>
La responsabilit&agrave; circa eventuali errori od omissioni concernenti le 
informazioni contenute nelle schede illustrative dei prodotti e/o servizi 
inseriti sul catalogo di vendita del sito di commercio elettronico &egrave; del titolare del sito/dominio.<br/>
<br/>
5 DINIEGO DI ACCESSO AL SITO DI COMMERCIO ELETTRONICO<br/>
Il titolare del sito/dominio si riserva la facolt&agrave; di negare l'accesso al sistema di Piattaforma 
Servizi a qualsiasi utente per ragioni rimesse esclusivamente al suo 
insindacabile giudizio.<br/>
<br/>
6 PROPRIETA' INTELLETTUALE<br/>
Con l'accettazione delle condizioni di accesso al sistema di commercio elettronico, 
l'utente si impegna ad accedere per scopi legittimi.<br/> In nessun 
caso &egrave; consentito all'utente di modificare, copiare, sfruttare o distribuire 
i prodotti e/o servizi offerti sul sito di commercio elettronico essendo essi coperti 
da diritti di propriet&agrave; intellettuale e/o industriale, copyright nazionale 
e/o internazionale, se non per esplicito consenso dei titolari del sito stesso.<br/>
$store_owner si riserva la facolt&agrave; di denunciare alle autorit&agrave; competenti 
eventuali violazioni dei diritti sopra citati.<br/>
<br/>
7 SITI COLLEGATI DI TERZI<br/>
Eventuali collegamenti ad altri siti internet sono forniti per comodit&agrave; 
dell'utente che accede ad essi a proprio rischio.<br/> $store_owner non &egrave; responsabile 
dei contenuti e/o dei prodotti/servizi offerti dai siti terzi rispetto al 
sistema di commercio elettronico.<br/>
I siti collegati non sono posseduti, controllati o gestiti da $store_owner<br/>
<br/>
8 LIMITAZIONE DI RESPONSABILITA'<br/>
$store_owner non &egrave; responsabile di qualsiasi danno i natura contrattuale o 
extracontrattuale possa derivare all'utente registrato o a terzi:
-	dall'accesso, dall'utilizzo o dall'impossibilit&agrave; di accedere al sito di commercio elettronico;
-	dai contenuti dei siti collegati;<br/>
-	dall'impossibilit&agrave; temporanea di accedere al sito di commercio elettronico 
e visionare i siti ad essa collegati mediante link 
di accesso.<br/>
<br/>
9 GIURISDIZIONE E FORO COMPETENTE <br/>
Ogni controversia che dovesse insorgere tra $store_owner e l'utente registrato in 
seguito all'accesso al sistema di commercio elettronico sar&agrave; sottoposta alla 
giurisdizione italiana previo esperimento di un tentativo di conciliazione tra 
le Parti; quantunque l'utente sia una persona fisica o persona giuridica 
sar&agrave; esclusivamente competente il foro di Roma.<br/>
<br/>
<br/>
EOPA;
?>