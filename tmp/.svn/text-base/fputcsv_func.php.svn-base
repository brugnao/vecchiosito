<?php
/*
 * @filename:	fputcsv_func.php
 * @version:	1.00
 * @project:	General Functions
 *
 * @author:		Riccardo Roscilli <info@oscommerce.it>
 * @created:	10/apr/07 18:01:15
 * @modified:	10/apr/07 18:01:15
 *
 * @copyright:	2007	Riccardo Roscilli	@ OsCommerceIT
 *
 * @desc:	Definizione della funzione fputcsv, per phpver < 5.0.0
 *
 * @TODO:		
 */

function fputcsv($fp, &$fields, $delimiter=',', $enclosure='"')	{
	$output='';
	for ($i=0;$i<sizeof($fields);$i++)	{
		$field=$fields[$i];
		if ($escaped=(false!==strpos($field,$delimiter)
			|| false!==strpos($field,$enclosure)
			|| false!==strpos($field,"\r")
			|| false!==strpos($field,"\n")))	{
			$field=str_replace($enclosure,$enclosure.$enclosure,$field);
		}
		$output.=($escaped) ? $enclosure.$field.$enclosure : $field;
		$output.=$delimiter;
	}
	$output=substr($output,0,-1);
	$output.="\r\n";
	//echo "$output<br/>";
	return fwrite($fp, $output);
}

?>