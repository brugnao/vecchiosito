<!-- ICECAT {{ -->
<link rel="stylesheet" href="<?=DIR_WS_ICECAT?>template/icecat_style.css" type="text/css" media="screen" />
<script language="JavaScript" src="<?=DIR_WS_ICECAT?>build.js"></script>
<script language="JavaScript" src="<?=DIR_WS_ICECAT_LIBS?>/jshttprequest/Subsys/JsHttpRequest/JsHttpRequest.js"></script>
<script language="JavaScript">
var DEBUG_MOD = false;
var ajaxFile='/<?=DIR_WS_ICECAT?>icecat_ajax.php';
function doLoad(value, file_input, area, fileName) {
    // setTimeout('checkResponse()', 16000);
    if(fileName && fileName!=undefined && fileName.substring(fileName.length-4)=='.php') {
    	ajaxFile=fileName;
    }
    var rInfo = document.getElementById(area);
    //rInfo.innerHTML = '<span> &nbsp; Loading ... &nbsp; </span>';
    if(rInfo) {
    	rInfo.innerHTML = '<img src="<?=DIR_WS_ICECAT?>images/loading.gif" alt="loading..." />';
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
	    if(req.responseJS.text) {
	      eval(req.responseJS.text);
	    }
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
    req.open('POST', ajaxFile+'?' + query + '&currtime=' + Date() +'', true);
    // Send data to backend.
    var data = {q: value,
                'time': new Date().getTime(), 
                'file': file_input
        };
        if(DEBUG_MOD) {
            // Write debug information too (output become responseText).
            	document.getElementById('debug').innerHTML = ajaxFile+'?' + query + '&currtime=' + Date() +'';
            }
        if(file_input=null) delete data.file;
        req.send(data);
}
</script>
<!-- ICECAT }} -->