<?php  
 require('includes/application_top.php');

// /* debug

     
// mail("info@oscommerce.it", "ajax tornado bianco", "messaggio di chiamata POST:" . $_POST['products_model'] . " POST:" . $_POST['products_id']);
if (isset($_POST['products_model'])) // carica le disponibilità in arrivo per il prodotto
		{
		 	$in_arrivo_query = tep_db_query("Select * from products_incomings where Item = '". $_POST['products_model'] ."' order by ConfirmedDeliveryDate Asc");
			
			while ($a_row = tep_db_fetch_array($in_arrivo_query))
			{
				$elenco .=  "In arrivo " . ($a_row['Qty'] - $a_row['DeliveredQty']) .  " pezzi il " . tep_date_short($a_row['ConfirmedDeliveryDate']) . "<br>";
			}
			echo $elenco;
			
			
		 		
		}
if (isset($_POST['products_id']) && $_POST['azione']=='loading') //visualizza l'icona "loading..."
		{
				echo '<img  border="0" src="' . DIR_WS_ICONS . 'ajax_loader.gif"> loading...';
		}
							
?>