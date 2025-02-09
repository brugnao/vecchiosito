function addtoCart(item, quantity) {
   
   var url = 'managecart.php';
   var params = 'products_id=' + item + '&quantity=' + quantity;
   var target = 'cartContent';
   
   var ajax = new Ajax.Updater(

	          {success: 'cartContent'},
              url,
              {method: 'post', parameters: params, onFailure: reportError}
				
				);
              
}


function loadCart() {
   
   var url = 'managecart.php';
   var params = '' ;
   var target = 'cartContent';
   
   var ajax = new Ajax.Updater(

	          {success: 'cartContent'},
              url,
              {method: 'post', parameters: params, onFailure: reportError}
				
				);
              
}

function loading(item, azione){
   
   var url = 'getproductincart.php';
   var params = 'products_id=' + item + '&azione=' + azione;

   var target = 'cartquantity' + item  ;
   
   var ajax = new Ajax.Updater(

	          {success: 'cartquantity' + item},
              url,
              {method: 'post', parameters: params, onFailure: reportError}
				
				);
              
}


function loadCartItem(item, azione) {
   
   var url = 'getproductincart.php';
   var params = 'products_id=' + item + '&azione=' + azione;
   var target = 'cartquantity' + item  ;
   
   var ajax = new Ajax.Updater(

	          {success: 'cartquantity' + item},
              url,
              {method: 'post', parameters: params, onFailure: reportError}
				
				);
              
}


function loadIncomings(item, id) {
   
   var url = 'incomings.php';
   var params = 'products_model=' + item + '&products_id=' + id;
   var target = 'incomings' + id  ;
   
   var ajax = new Ajax.Updater(

	          {success: 'incomings' + id},
              url,
              {method: 'post', parameters: params, onFailure: reportError}
				
				);
              
}

function reportError(request) {
   $F('cartquantity'+ item) = "An error occurred";
}


