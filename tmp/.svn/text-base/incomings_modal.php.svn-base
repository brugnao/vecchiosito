<div id="ipsum" >
	<p>
	
			<?php  
			 require('includes/application_top.php');
			
			// /* debug

			if (isset($_GET['products_id'])) // carica le disponibilità in arrivo per il prodotto
					{
						$products_query = tep_db_query('Select Codice from ' . TABLE_PRODUCTS . ' where products_id =' . $_GET['products_id']);
						$products_array = tep_db_fetch_array($products_query);
					 	$in_arrivo_query = tep_db_query("Select * from products_incomings where Item = '". $products_array['Codice'] ."' order by ConfirmedDeliveryDate Asc");
						$elenco .=  "Sono in arrivo:<br>";
						while ($a_row = tep_db_fetch_array($in_arrivo_query))
						{
							$elenco .=  ($a_row['Qty'] - $a_row['DeliveredQty']) .  " pezzi il  " . strftime("%d %B %Y", strtotime($a_row['ConfirmedDeliveryDate'] . " GMT")) . "<br>";
						}
						echo $elenco;
					
					 		
					}
					
			?>	
	

			<div align="right" height="20px"><a href="blank-width.html" class="lightwindow_action" rel="deactivate">Chiudi</a><br>_</div>
	</p>


</div>


<style type="text/css">

#ipsum p {
	font-size: 10px;
	line-height: 14px;
	padding: 10px;

	background-color: #f0f0f0;

}

</style>

<script type="text/javascript">


</script>


