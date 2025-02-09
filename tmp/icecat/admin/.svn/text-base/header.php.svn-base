<?php
/*
 * Created on 13 ����. 2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>
<!-- ICECAT {{ -->
<link rel="stylesheet" href="/<?=DIR_WS_ICECAT?>admin/template/icecat_style.css" type="text/css" media="screen" />
<script language="JavaScript" src="/<?=DIR_WS_ICECAT_LIBS?>/jshttprequest/Subsys/JsHttpRequest/JsHttpRequest.js"></script>
<script language="JavaScript">
var DEBUG_MOD = false;
// doLoad AJAX function
function doLoad(value, file_input, area) {
    // setTimeout('checkResponse()', 16000);
    var rInfo = document.getElementById(area);
    //rInfo.innerHTML = '<span> &nbsp; Loading ... &nbsp; </span>';
    if(rInfo) {
    	rInfo.align='left';
    	rInfo.innerHTML = '<img src="/<?=DIR_WS_ICECAT?>images/loading.gif" alt="loading..." />';
    }
    var query = value;
    // Create new JsHttpRequest object.
    var req = new JsHttpRequest('multipart/form-data');
    // Code automatically called on load finishing.
    req.onreadystatechange = function() {
        if (req.readyState == 4) {
        	if(rInfo) {
	            rInfo.innerHTML = '';
	        }
            eval(req.responseJS.text);
            if(req.responseJS.jsText) {
            	eval(req.responseJS.jsText);
            }
			for(thval in value){
				if(value[thval].name!='' && value[thval].text!='' && document.getElementById(value[thval].name))
				document.getElementById(value[thval].name).innerHTML = (value[thval].text);
				thval++;
			}
            if(DEBUG_MOD) {
            // Write debug information too (output become responseText).
            	document.getElementById('debug').innerHTML = req.responseText;
            }
            
            //req = null;            
        }
    }
    // Allow caching (to avoid different server queries for 
        // identical input data). Caching is always disabled if
        // we are uploading a file.
    req.caching = false;
        // Prepare request object.
    //req.loader = loader;
    // Prepare request object (automatically choose GET or POST).
    req.open('POST', ajaxFile + '?' + query + '&currtime=' + Date() +'', true);
    // Send data to backend.
    var data = {q: value,
                'time': new Date().getTime(), 
                'file': file_input
        };
        if(DEBUG_MOD) {
            // Write debug information too (output become responseText).
            	document.getElementById('debug').innerHTML = ajaxFile + '?' + query + '&currtime=' + Date() +'';
            }
        if(file_input=null) delete data.file;
        req.send(data);
}
var arrLangs = new Array();
<?php
$languages = tep_get_languages();
$arrLangs = array();
for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
//	$arrLangs[]=strtolower($languages[$i]['code']);
	echo 'arrLangs[' . $i . ']=new Array();'."\n";
	echo 'arrLangs[' . $i . '][\'id\']='.$languages[$i]['id'].';'."\n";
	echo 'arrLangs[' . $i . '][\'code\']=\''.$languages[$i]['code'].'\';'."\n";
}
?>

function doLoadLang(value, file_input) {
	for(var i=0;i<arrLangs.length;i++) {
		doLoad(value+'&action=getAdminDesc&area=icecatADesc'+arrLangs[i]['code']+'&lang='+arrLangs[i]['code']+'&languages_id='+arrLangs[i]['id'], file_input, 'icecatADesc'+arrLangs[i]['code']);
	}
}
</script>
<!-- ICECAT }} -->