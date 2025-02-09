<?php
/******************************************************************************/
/* PDF Catalogs v.1.56 for osCommerce v.2.2 MS2                               */
/*                                                                            */
/* by Antonios THROUVALAS (antonios@throuvalas.net), April 2004               */
/* by Nicolas Hilly (n.hilly@laposte.net), August 2004                        */
/* by Christophe Buchi (chris@butch.ch), September 2004
/*                                                                            */
/* Based on PDF Catalogs v.1.4 by gurvan.riou@laposte.net                     */
/*                                                                            */
/* Uses FPDF (http://www.fpdf.org), Version 1.52, by Olivier PLATHEY          */
/*                                                                            */
/* Credit goes also to:                                                       */
/* - Yamasoft (http://www.yamasoft.com/php-gif.zip) for their GIF class,      */
/* - Jerome FENAL (jerome.fenal@logicacmg.com) for introducing GIF Support    */
/*   in the FPDF Class,                                                       */
/* - The osC forums members (forums.oscommerce.com)!                          */
/*                                                                            */
/* Please donate to the osCommerce Core Team!                                 */
/* Freeware, You may use, modify and redistribute this software as you wish!  */
/******************************************************************************/

//define('FPDF_FONTPATH','font/');
require('pdf_config.php');
require('pdf_fpdf.php');
require('includes/application_top.php');
require_once DIR_FS_CATALOG.DIR_WS_FUNCTIONS.'thumbnails.php';

$products_index_array;

class PDF extends FPDF
{
//Colonne courante
var $col=0;
//Ordonn�e du d�but des colonnes
var $y0;
var $categories_string_spe = '';
var $categories_string = '';
var $categories_id = '';
var $levels = '';
var $parent_category_name;
var $ifw = 0;     //internal width  margin for the products (image and text) description
var $text_fw = 0; //text width for the products (text) description
var $ifh = 0;     //internal height margin for the products description 
var $products_index_array;
var $products_index_list='';

 function Header()
 {
    
  //Background Color
    $background_color_table=explode(",",BACKGROUND_COLOR);
    $this->SetFillColor($background_color_table[0], $background_color_table[1], $background_color_table[2]);
    $this->ifw = $this->fw * 0.95; // A4 portrait = 200 
    $this->ifh = $this->fh * 0.87; // A4 portrait = 260
    $this->Rect(0,0,$this->fw,$this->fh,F); // Draw background
	
    //Logo: If LOGO_IMAGE defined, show image with logo, else show text
    if (PDF_LOGO) {
        $this->Image(DIR_FS_CATALOG.DIR_WS_IMAGES.PDF_LOGO,10,8,0,29);
    } else {	
        $this->SetFont('Arial','B',18);
	$this->SetLineWidth(0);
        $w=$this->GetStringWidth(PDF_TITLE)+6;
        //$this->SetX((210-$w)/2);
	$this->SetFillColor(100,100,100);
        $this->Cell($w,9,PDF_TITLE,0,0,'C');
    }
    //Ann�e en cour
    $aujourdhui = getdate();
    $annee = strftime(PDF_DATE_FORMAT);

    $this->SetFont('Arial','B',12);
    $this->Cell(0,9,$annee."    ",0,1,'R');
    if (PDF_LOGO) {
        $this->Ln(20);
    } else {
        $this->Ln(2);
    } 
    $x=$this->GetX();
    $y=$this->GetY();
    $this->Line($x,$y,$this->ifw,$y);
    $this->Ln(3);
    //Sauvegarde de l'ordonn�e
    $this->y0=$this->GetY();
 }

 function Footer()
 {
    //Pied de page
    $this->SetY(-15);
    $x=$this->GetX();
    $y=$this->GetY();
    $this->SetLineWidth(0.2);
    $this->Line($x,$y,$this->ifw,$y);
    $this->SetFont('Arial','I',8);
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}   ',0,0,'R');
 }
 
 function CheckPageBreak($h)
 {
    //Si la hauteur h provoque un d�bordement, saut de page manuel
    if($this->GetY()+$h>$this->PageBreakTrigger)    $this->AddPage($this->CurOrientation);
 }
 
    function NbLines($w,$txt)
    {
	//Calcule le nombre de lignes qu'occupe un MultiCell de largeur w
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
    }

 function LineString($x,$y,$txt,$cellheight)
 {
    //calculate the width of the string
    $stringwidth=$this->GetStringWidth($txt);
    //calculate the width of an alpha/numerical char
    $numberswidth=$this->GetStringWidth('1');
    $xpos=($x+$numberswidth);
    $ypos=($y+($cellheight/2));
    $this->Line($xpos,$ypos,($xpos+$stringwidth),$ypos);
 }
 
 function ShowImage(&$width,&$height,$link,$path)
 {
    $width=min($width,MAX_IMAGE_WIDTH);
    $height=min($height,MAX_IMAGE_HEIGHT);

    if(RESIZE_IMAGES) {
	$destination=DIR_FS_CATALOG."catalogues/";
	if(substr(strtolower($path), (strlen($path)-4),4)==".jpg" || substr(strtolower($path), (strlen($path)-5),5)==".jpeg") {
            $src=imagecreatefromjpeg($path);
   	} else if (substr(strtolower($path), (strlen($path)-4),4)==".png") {
      	    $src=imagecreatefrompng($path);
   	} else {
      	    echo "Only PNG and JPEG";
            exit();
   	}
   
   	$array=explode("/", $path);
   	$last=sizeof($array);
        $size = getimagesize($path);
   	if($size[0] > $size[1]) {
     	    $im=imagecreate($width/PDF_TO_MM_FACTOR, $height/PDF_TO_MM_FACTOR);
            imagecopyresized($im, $src, 0, 0, 0, 0,$width/PDF_TO_MM_FACTOR, $height/PDF_TO_MM_FACTOR, $size[0], $size[1]);
   	} else {
     	    $im=imagecreate($height/PDF_TO_MM_FACTOR,$width/PDF_TO_MM_FACTOR);
            imagecopyresized($im, $src, 0, 0, 0, 0, $height/PDF_TO_MM_FACTOR, $width/PDF_TO_MM_FACTOR, $size[0], $size[1]);
  	}
  	if(!imagejpeg($im, $destination.$array[$last-1])) {
    	    exit();
    	}

        $path=$destination.$array[$last-1];
        $this->SetLineWidth(1);  
	$this->Cell($width+3,$height,"",1,0);
	$this->SetLineWidth(0.2);
	$this->Image($path,($this->GetX()-$width), $this->GetY(), $width, $height,'',$link);
	$this->SetFont('Arial','',8);
	unlink($path);
    } else {
	$this->SetLineWidth(1);
	// NH $this->Cell($width,$height,"",1,0);
	$this->Cell($width+3,$height,"",SIZE_BORDER_IMAGE,0);
	$this->SetLineWidth(0.2);
	//NH $this->Image($path,($this->GetX()-$width), $this->GetY(), $width, $height,'',$link);
	$this->Image($path,($this->GetX()-$width), $this->GetY(),$width ,'' ,'',$link);
	$this->SetFont('Arial','',8);
    }
 }


//Rangement de l'arbre (Level est le niveau de sous-categorie)
 function Order($cid, $level, $foo, $cpath)
 {
    if ($cid != 0) {
	if($level>1) {
	    $nbspaces=7;
            $dessinrep="|___ ";
	    //j'inverse le dessin
	    $revstring = strrev($dessinrep);
            //je lui ajoute nbspace pour chaque niveau de sous-repertoire
	    $revstring .= str_repeat(" ",$nbspaces*($level-2));
	    //je r�inverse la chaine
	    $this->categories_string_spe .= strrev($revstring);			  
	} 
	$this->levels .=$level." ";
	$this->categories_id .= $cid." ";
	$this->categories_string .= $foo[$cid]['name'];
        $this->categories_string_spe .=  $foo[$cid]['name'];
     
        if (SHOW_COUNTS) {
            $products_in_category = tep_products_in_category_count($cid,'false');
            if ($products_in_category > 0) {
                $this->categories_string_spe .= ' (' . $products_in_category . ')';
            }
        }
	$this->categories_string .= "\n";
        $this->categories_string_spe .= "\n";
    }
    //Parcourir l'arbre des categories (lecture de la table de hachage comme en Perl)
    if (sizeof($foo) > 0 ) {
        foreach ($foo as $key => $value) {
            if ($foo[$key]['parent'] == $cid) {
                $this->Order($key, $level+1, $foo, $cid);
            }
        }
    }
 }

 function ParentsName($current_category_level,$i,&$categorieslevelsarray, &$categoriesnamearray)
 {

    $k=$i;
    while($k>0)	{
    	if($categorieslevelsarray[$k] == ($current_category_level-1)) {
	    $this->$parent_category_name=$categoriesnamearray[$k];
            break;
    	}	
	$k--;
    }
 }
 
 function CalculatedSpace($y1,$y2,$imageheight)
 {
    //Si les commentaires sont - importants que l'image au niveau de l'espace d'affichage
    if(($h2=$y2-$y1) < $imageheight) {
        $this->Ln(($imageheight-$h2)+3);
    } else {
        $this->Ln(3);
    }
 }
 
  function PrepareIndex($name,$manufacturer,$category)
 {
    $this->products_index_array[] = array (
                                        'name' => substr($name,0,55),
                                        'manufacturer' => substr($manufacturer,0,20),
                                        'category' => substr($category,0,18),
                                        'page' => $this->PageNo());
 }

  function DrawIndex()
 {
 	global $pws_prices;
    //5 = hauteur des cellules
    $h= 5 * sizeof($this->products_index_array) ."<br>";
    if($h< $this->ifh) {
	$this->CheckPageBreak($h);
    }
    $this->AddPage();
    $this->Ln(5);
//    echo "<br>HHHH sizeof= " . sizeof($this->products_index_array);

    if (!function_exists(CompareIndex)) {
        function CompareIndex($a, $b)
       {
    //        return strcmp($a['name'], $b['name']);
            return strncasecmp($a['name'],$b['name'],8); // seulement les 8 premiers caracteres
       }
    }
    usort($this->products_index_array, CompareIndex);

    $this->SetFont('Courier','B',11);
    $this->Cell(1,11,"",0,0);
    $this->MultiCell($this->ifw,11,PDF_INDEX_HEADER,0,'C');
    $this->SetFont('Courier','',11);
    if (strlen(INDEX_SEPARATOR) < 1) {
        $index_separator=" ";
    } else {
        $index_separator=INDEX_SEPARATOR;
    }
    foreach ($this->products_index_array as $key => $value) {
        if (strlen($value['manufacturer']) > 0) {
            $ligne_index = str_pad($value['name']." - ". $value['manufacturer'],53,$index_separator,STR_PAD_RIGHT);
        } else {
            $ligne_index = str_pad($value['name'],53,$index_separator,STR_PAD_RIGHT);
        }
	$ligne_index .= str_pad($value['category'],18,$index_separator,STR_PAD_LEFT);
	$ligne_index .= str_pad($value['page'], 5, $index_separator, STR_PAD_LEFT);
	$this->Cell(1,6,"",0,0);
	$this->MultiCell(0,6,$ligne_index,0,'C');
//        echo "<br>HHHH : " . $ligne_index;
    }
//    echo "<br>HHHH wpt =" .$this->wPt .  " fw =" . $this->fw.  " ifw =" . $this->ifw ." text_fw =" . $this->text_fw;
//    echo "<br>HHHH hpt =" .$this->hPt .  " fh =" . $this->fh.  " ifh =" . $this->ifh;
 }

 function DrawCells($data_array)
 {
    $totallines=0;
	 for($i=2;$i<(sizeof($data_array)-1);$i++)
	 {
	    $totallines+=$this->NbLines(($this->ifw -$data_array[0]),$data_array[$i]);
	 }
	 
	 //5 = hauteur des cellules
	 $h=5*($totallines+1)."<br>";
	 
	 //si la description du produit ne prend pas toute la page
	 if($h< $this->ifh)
	 {
	    $this->CheckPageBreak($h);
	 }
	 
	 
	 if(SHOW_PRODUCTS_LINKS)
	 { // NH   DIR_WS_CATALOG
	 	$link=HTTP_CATALOG_SERVER . DIR_WS_CATALOG ."product_info.php?products_id=".$data_array[10]."&language=".$data_array[11];
	 }
	 else
	 {
	 	 $link='';
	 }
	 
	 if(SHOW_IMAGES && strlen($data_array[12]))
	 {	
	 	//If Small Image Width and Small Image Height are defined
	 	if(strlen($data_array[0])>1 && strlen($data_array[1])>1)
		{
	 	    $this->ShowImage($data_array[0],$data_array[1],$link,$data_array[12]);
                    $y1=$this->GetY();
		}
                //If only Small Image Width is defined
		else if(strlen($data_array[0])>1 && strlen($data_array[1]))
		{   
		    $heightwidth=getimagesize($data_array[12]);
		    $data_array[0]=$data_array[0];
		    $data_array[1]=$heightwidth[1]*PDF_TO_MM_FACTOR;
                    $this->ShowImage($data_array[0],$data_array[1],$link,$data_array[12]);
	 	    $y1=$this->GetY();
		}
		//If only Small Image Height is defined
		else if(strlen($data_array[0]) && strlen($data_array[1])>1)
		{
		    $heightwidth=getimagesize($data_array[12]);
                    $data_array[0]=$width=$heightwidth[0]*PDF_TO_MM_FACTOR;
		    $data_array[1]=$data_array[1];
	 	    $this->ShowImage($data_array[0],$data_array[1],$link,$data_array[12]);
                    $y1=$this->GetY();
		}
		else
		{
		    $heightwidth=getimagesize($data_array[12]);
                    $data_array[0]=$heightwidth[0]*PDF_TO_MM_FACTOR;
		    $data_array[1]=$heightwidth[1]*PDF_TO_MM_FACTOR;
                    $this->ShowImage($data_array[0],$data_array[1],$link,$data_array[12]);
	 	    $y1=$this->GetY();
		}
		
		//Margin=10
		$this->SetX(10);
	}
	else
	{
		$data_array[0]=$data_array[1]=0;
		$y1=$this->GetY();
		$this->SetFont('Arial','',8);
	}
	// Calcul l'espace libre a droite de l'image
        $this->text_fw = $this->ifw - 18 - $data_array[0];
	 
	 if(SHOW_NAME)
	 {
	    if(strlen($data_array[2]))
	    {
	        // Cell(marge gauche, hauteur, text, bordure, )
		$this->Cell($data_array[0]+6,5,"",0,0);
                $x=$this->GetX();
                $y=$this->GetY();
                $name_color_table=explode(",",NAME_COLOR);
                $this->SetFillColor($name_color_table[0],$name_color_table[1],$name_color_table[2]);
 		$this->MultiCell($this->text_fw,5,$data_array[2],PRODUCTS_BORDER,'L',1);
            }
	 }
	 if(SHOW_MODEL)
	 {
	 	if(strlen($data_array[3]))
		{
	 		$this->Cell($data_array[0]+6,5,"",0,0);
	 		$this->MultiCell($this->text_fw,5,"Model  ".$data_array[3],PRODUCTS_BORDER,'L');
		}
	 }

	 if(SHOW_DATE_ADDED)
	 {
	 	if(strlen($data_array[4]))
		{
	    	$this->Cell($data_array[0]+6,5,"",0,0);
	 		$this->MultiCell($this->text_fw,5,$data_array[4],PRODUCTS_BORDER,'L');
		}
	 }
	 if(SHOW_MANUFACTURER)
	 {
	    if(strlen($data_array[5]))	{
	 	$this->Cell($data_array[0]+6,5,"",0,0);
	    	$this->SetFont('Arial','I');
                $this->MultiCell($this->text_fw,5,$data_array[5],PRODUCTS_BORDER,'L');
		$this->SetFont('Arial','');
	    }
	 }
	 // NH  si il n'y a pas de bordure, ajout d'un petit separateur
	 if (!PRODUCTS_BORDER) {
            $this->Cell($data_array[0]+6,2,"",0,0);
            $x=$this->GetX();
            $y=$this->GetY();
            $this->MultiCell($this->text_fw,1,"",0,'C');
            //$this->LineString($x+3,$y,"                 ",2);
            $this->Line($x+4,$y,$x+15,$y);
	 }
	 if(SHOW_DESCRIPTION)
	 {
	 	if(strlen($data_array[6]))
		{
	 		$this->Cell($data_array[0]+6,5,"",0,0);
	 		$this->MultiCell($this->text_fw,5,$data_array[6],PRODUCTS_BORDER,'L');
		}
	 }
	 if(SHOW_TAX_CLASS_ID)
	 {
	 	if(strlen($data_array[7]))
		{
                        $this->Cell($data_array[0]+6,5,"",0,0);
	 		$this->MultiCell($this->text_fw,5,$data_array[7],PRODUCTS_BORDER,'L');
		}
	 
	 }
	 if(VAT)
	 {
		$vatprice=sprintf("%01.".DIGITS_AFTER_DOT."f",((VAT/100)*$data_array[9])+$data_array[9]);
		$vatspecialsprice=sprintf("%01.".DIGITS_AFTER_DOT."f",((VAT/100)*$data_array[8])+$data_array[8]);
	 }
	 else
	 {
	 	$vatprice=sprintf("%01.".DIGITS_AFTER_DOT."f",$data_array[9]);
	 	$vatspecialsprice=sprintf("%01.".DIGITS_AFTER_DOT."f",$data_array[8]);
	 }
	 if(SHOW_PRICES)
	 {
            // NH  si il n'y a pas de bordure, ajout d'un petit separateur
                if (!PRODUCTS_BORDER) {
                    $this->Cell($data_array[0]+6,2,"",0,0);
                    $x=$this->GetX();
                    $y=$this->GetY();
                    $this->MultiCell($this->text_fw,1,"",0,'C');
                    //$this->LineString($x+3,$y,"                 ",2);
                    $this->Line($x+4,$y,$x+15,$y);
                }

	 	if(strlen($data_array[8]) && $data_array[8]!=$data_array[9]) //If special price 
		{		
		    $this->Cell($data_array[0]+6,5,"",0,0);
		
            $x=$this->GetX();
		    $y=$this->GetY();
		    $specials_price_color_table=explode(",",SPECIALS_PRICE_COLOR);
		    $this->SetTextColor($specials_price_color_table[0],$specials_price_color_table[1],$specials_price_color_table[2]);
		    $this->SetFont('Arial','B','');


			if (false){
				if(CURRENCY_RIGHT_OR_LEFT == 'R') {
							$this->MultiCell($this->text_fw,5,$vatprice.CURRENCY."\t\t\t".$vatspecialsprice.CURRENCY,PRODUCTS_BORDER,'L'); // le rajout d'un param  ,1 remplie la couleur de fond );
				} else if (CURRENCY_RIGHT_OR_LEFT == 'L') {
				$this->MultiCell($this->text_fw,5,CURRENCY.$vatprice."\t\t\t".CURRENCY.$vatspecialsprice,PRODUCTS_BORDER,'L'); // le rajout d'un param  ,1 remplie la couleur de fond );
				} else {
							echo "<b>Choose L or R for CURRENCY_RIGHT_OR_LEFT</b>";
							exit();
		    	}
				$this->LineString($x,$y,$vatprice.CURRENCY,5);
			}else{
				$this->MultiCell($this->text_fw,5,$data_array[9]."\t\t\t".$data_array[8],PRODUCTS_BORDER,'L');
				$this->LineString($x,$y,$data_array[9],5);
			}
		}
		else if(strlen($data_array[9]))
		{
		    $this->Cell($data_array[0]+6,5,"",0,0);
            if (false){
				if(CURRENCY_RIGHT_OR_LEFT == 'R') {
				$this->MultiCell($this->text_fw,5,$vatprice.CURRENCY,PRODUCTS_BORDER,'L');
				}else if(CURRENCY_RIGHT_OR_LEFT == 'L') {
				$this->MultiCell($this->text_fw,5,CURRENCY.$vatprice,PRODUCTS_BORDER,'L');
				} else {
					echo "<b>Choose L or R for CURRENCY_RIGHT_OR_LEFT</b>";
					exit();
				}
			}else{
				$this->MultiCell($this->text_fw,5,$data_array[9],PRODUCTS_BORDER,'L');
			}
		}
		$this->SetTextColor(0,0,0);
	 }
	 $y2=$this->GetY();
	 
	 //si la description du produit ne prend pas toute la page
	 if($h< $this->ifh)
	 {
		 $this->CalculatedSpace($y1,$y2,$data_array[1]);
 	 }
	 else
	 {
	 	$this->Ln(5);
	 }
 
 }
 
 function CategoriesTree($languages_id,$languages_code)
 { 
    //selectionne toute les categories
    $query = "SELECT c.categories_id, cd.categories_name, c.parent_id
              FROM " . TABLE_CATEGORIES . " c, " . TABLE_CATEGORIES_DESCRIPTION . " cd
              WHERE c.categories_id = cd.categories_id and cd.language_id='" . $languages_id ."'
	      ORDER by sort_order, cd.categories_name";

    $categories_query = tep_db_query($query);
    while ($categories = tep_db_fetch_array($categories_query)) {
        //Table de hachage
        $foo[$categories['categories_id']] = array(
					'name' => @html_entity_decode($categories['categories_name'],ENT_QUOTES,'UTF-8'),
					'parent' => $categories['parent_id']);
    }

        $this->Order(0, 0, $foo, '');
        $this->AddPage();
        $this->TitreChapitre("");
    if (SHOW_INTRODUCTION) {
        $this->Ln(18);
        $file= DIR_FS_CATALOG_LANGUAGES . tep_get_languages_directory($languages_code) . '/pdf_define_intro.php';

//            echo "<br>HHHH " . $file;
        if (file_exists($file)) {
            $file_array = @file($file);
            $file_contents = @implode('', $file_array);
            $this->MultiCell(0,6,strip_tags($file_contents),$this->ifw,1,'J');
        }

    }
    $this->SetFont('Arial','',DIRECTORIES_TREE_FONT_SIZE);
    if (SHOW_TREE) {
        $this->Ln(15);
        $this->MultiCell(0,6,$this->categories_string_spe,0,1,'L');
    }

 }
 
 function CategoriesListing($languages_id, $languages_code)
 {
 	global $pws_prices;   
    $this->products_index_array=array();
    $this->products_index_list='';
    $this->index_lenght=0;

    //Recuperation de toutes les categories dans l'ordre
    $categoriesidarray=explode(" ",$this->categories_id);
    $categoriesnamearray=explode("\n",$this->categories_string);
    $categorieslevelsarray=explode(" ",$this->levels);
	  
    //Convertion pixels -> mm
    $imagewidth=SMALL_IMAGE_WIDTH*PDF_TO_MM_FACTOR;
    $imageheight=SMALL_IMAGE_HEIGHT*PDF_TO_MM_FACTOR;
	 
    for($i=0; $i<sizeof($categoriesidarray)-1; $i++) {
        $category_count_products = tep_products_in_category_count($categoriesidarray[$i],'false');
        if (!((!SHOW_EMPTY_CATEGORIES) and ($category_count_products < 1))) {
            $taille=0;
            $current_category_id=$categoriesidarray[$i];
            $current_category_name=$categoriesnamearray[$i];
            $current_category_level=$categorieslevelsarray[$i];
            $requete_prod="select p.products_id, pd.products_name, pd.products_description, p.products_image, p.products_model, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id='".$current_category_id."' order by pd.products_name, p.products_date_added DESC";
							  
            $SHOW_catalog_query = tep_db_query($requete_prod);
            while ($print_catalog = tep_db_fetch_array($SHOW_catalog_query)) {
                $print_catalog_array[$taille++] = array(
                                        'id' => $print_catalog['products_id'],
			            	'name' => $print_catalog['products_name'],		                          'description' => $print_catalog['products_description'],
			                'model' => $print_catalog['products_model'],
			            	'image' => $print_catalog['products_image'],
		            		'price' => $print_catalog['products_price'],					    'specials_price' => $print_catalog['specials_new_products_price'],
    		       			'tax_class_id' => $print_catalog['products_tax_class_id'],
	               			'date_added' => tep_date_long($print_catalog['products_date_added']),
    	               			'manufacturer' => $print_catalog['manufacturers_name']);
            }

            //recherche le nom de la categorie pere
            $this->$parent_category_name='';
            $this->ParentsName($current_category_level,$i,$categorieslevelsarray, $categoriesnamearray);
                            
            if (($current_category_level == 1) and (CATEGORIES_PAGE_SEPARATOR)) {
                $this->AddPage();
                $this->Ln(120);
                $this->SetFont('Arial','',12);
                $titles_color_table=explode(",",CENTER_TITLES_CELL_COLOR);
                $this->SetFillColor($titles_color_table[0], $titles_color_table[1], $titles_color_table[2]);
                $this->Cell(45,5,"",0,0);
                $this->MultiCell(100,10,$current_category_name,1,'C',1);
            }
    
            if ($taille > 0) { // categorie non vide
                $this->AddPage();
                if (strlen($this->$parent_category_name) > 0 ) {
                    $this->TitreChapitre($this->$parent_category_name. CATEGORIES_SEPARATOR .$current_category_name);
                } else {
                    $this->TitreChapitre($current_category_name);
                }
                $this->Ln(3); // NH
                $this->SetFont('Arial','',11);

                for($j=0; $j<$taille; $j++ ) {
                    // NH si pas d'image definie, image par default 
                    if (strlen($print_catalog_array[$j]['image']) > 0) {
                        $imagedir=DIR_FS_CACHE;
                        //$imagename=basename($print_catalog_array[$j]['image']);
						$imagename=getThumbnail(DIR_FS_CATALOG_IMAGES.$print_catalog_array[$j]['image'],$imagewidth,$imageheight);
						$imagename=$imagename['fileName'];
						$imagename=basename($imagename);
						$imagepath = $imagedir.$imagename;
                    } else {
                        $imagepath=DIR_WS_IMAGES.''.DEFAULT_IMAGE;
                    }
                    
                    // Uncomment next 4 lines if you need to debug 
                 
//echo "DEBUG: print_catalog_array :".$print_catalog_array[$j]['image']."<br>"; 
//echo "DEBUG: imagedir :".$imagedir."<br>"; 
//echo "DEBUG: imagename :".$imagename."<br>"; 
//echo "DEBUG: imagepath :".$imagepath."<br>";
                    
                    $id=$print_catalog_array[$j]['id'];
                    $name=@html_entity_decode(rtrim(strip_tags($print_catalog_array[$j]['name'])),ENT_QUOTES,'UTF-8');
                    $model=@html_entity_decode(rtrim(strip_tags($print_catalog_array[$j]['model'])),ENT_QUOTES,'UTF-8');
                    $description=@html_entity_decode(rtrim(strip_tags($print_catalog_array[$j]['description'])),ENT_QUOTES,'UTF-8');
                    $manufacturer=@html_entity_decode(rtrim(strip_tags($print_catalog_array[$j]['manufacturer'])),ENT_QUOTES,'UTF-8');
                    $price=@html_entity_decode(rtrim(strip_tags($pws_prices->getFirstPrice($id))),ENT_QUOTES,'UTF-8');
                    $specials_price=@html_entity_decode(rtrim(strip_tags($pws_prices->getBestPrice($id))),ENT_QUOTES,'UTF-8');
                    $tax_class_id=rtrim(strip_tags($print_catalog_array[$j]['tax_class_id']));
                    $date_added=rtrim(strip_tags($print_catalog_array[$j]['date_added']));
			
                    $data_array=array($imagewidth,$imageheight,$name,$model,$date_added,$manufacturer,$description,$tax_class_id,$specials_price,$price,$id,$languages_code,$imagepath);
                    $this->Ln(PRODUCTS_SEPARATOR); // NH blank space before the products description cells 
                    $this->DrawCells($data_array);
                    if (SHOW_INDEX) {
                        switch (INDEX_EXTRA_FIELD) {
                            case 1 : $this->PrepareIndex($name,$manufacturer,$current_category_name);
                                    break;
                            case 2 : $this->PrepareIndex($name,$model,$current_category_name);
                                    break;
                            case 3 : $this->PrepareIndex($name,$date_added,$current_category_name);
                                    break;
                           default : $this->PrepareIndex($name,"",$current_category_name);
                        }
                    }
                }
            }
        }
    }   
 }
 
 function NewProducts($languages_id, $languages_code)
 {
 	global $pws_prices;
    $products_new_query_raw = "select p.products_id, pd.products_name, pd.products_description, p.products_image, p.products_model, p.products_price, p.products_tax_class_id, IF(s.status, s.specials_new_products_price, NULL) as specials_new_products_price, p.products_date_added, m.manufacturers_name from " . TABLE_PRODUCTS . " p left join " . TABLE_MANUFACTURERS . " m on p.manufacturers_id = m.manufacturers_id, " . TABLE_PRODUCTS_DESCRIPTION . " pd left join " . TABLE_SPECIALS . " s on p.products_id = s.products_id, " . TABLE_CATEGORIES . " c, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where products_status = '1' and p.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id order by p.products_date_added DESC, pd.products_name";
	
    $products_new_query = tep_db_query($products_new_query_raw);
   
    while($products_new = tep_db_fetch_array($products_new_query)) {
        $products_new_array[] = array('id' => $products_new['products_id'],
                                  'name' => $products_new['products_name'],
                                  'image' => $products_new['products_image'],
				  'description' => $products_new['products_description'],
				  'model' => $products_new['products_model'],
                                  'price' => $products_new['products_price'],
                                  'specials_price' => $products_new['specials_new_products_price'],
                                  'tax_class_id' => $products_new['products_tax_class_id'],
                                  'date_added' => tep_date_long($products_new['products_date_added']),
                                  'manufacturer' => $products_new['manufacturers_name']);
    }
  
    $this->AddPage();
    $this->Ln(120);
    $this->SetFont('Arial','',12);
    $new_color_table=explode(",",NEW_CELL_COLOR);
    $this->SetFillColor($new_color_table[0], $new_color_table[1], $new_color_table[2]);
    $this->Cell(45,5,"",0,0);
    $this->MultiCell(100,10,NEW_TITLE,1,'C',1);
    $this->Ln(100);
	
    //Convertion pixels -> mm
    $imagewidth=SMALL_IMAGE_WIDTH*PDF_TO_MM_FACTOR;
    $imageheight=SMALL_IMAGE_HEIGHT*PDF_TO_MM_FACTOR;
    
    for($nb=0; $nb<MAX_DISPLAY_PRODUCTS_NEW; $nb++) {
	$id=$products_new_array[$nb]['id'];
//    $name=rtrim(strip_tags($products_new_array[$nb]['name']));
//	$model=rtrim(strip_tags($products_new_array[$nb]['model']));
//	$description=rtrim(strip_tags($products_new_array[$nb]['description']));
//    $manufacturer=rtrim(strip_tags($products_new_array[$nb]['manufacturer']));
//	$price=rtrim(strip_tags($products_new_array[$nb]['price']));
//	$specials_price=rtrim(strip_tags($products_new_array[$nb]['specials_price']));
//	$tax_class_id=rtrim(strip_tags($products_new_array[$nb]['tax_class_id']));
//	$date_added=rtrim(strip_tags($products_new_array[$nb]['date_added']));		
//	$imagepath=DIR_FS_CATALOG.DIR_WS_CACHE.$products_new_array[$nb]['image'];
//	$imagepath=DIR_FS_CACHE.$products_new_array[$nb]['image'];
	if (strlen($products_new_array[$nb]['image']) > 0) {
		$imagedir=DIR_FS_CACHE;
		//$imagename=basename($print_catalog_array[$j]['image']);
		$imagename=getThumbnail(DIR_FS_CATALOG_IMAGES.$products_new_array[$nb]['image'],$imagewidth,$imageheight);
		$imagename=$imagename['fileName'];
		$imagename=basename($imagename);
		$imagepath = $imagedir.$imagename;
	} else {
		$imagepath=DIR_WS_IMAGES.''.DEFAULT_IMAGE;
	}
	
	$name=@html_entity_decode(rtrim(strip_tags($products_new_array[$nb]['name'])),ENT_QUOTES,'UTF-8');
	$model=@html_entity_decode(rtrim(strip_tags($products_new_array[$nb]['model'])),ENT_QUOTES,'UTF-8');
	$description=@html_entity_decode(rtrim(strip_tags($products_new_array[$nb]['description'])),ENT_QUOTES,'UTF-8');
	$manufacturer=@html_entity_decode(rtrim(strip_tags($products_new_array[$nb]['manufacturer'])),ENT_QUOTES,'UTF-8');
	$price=@html_entity_decode(rtrim(strip_tags($pws_prices->getFirstPrice($id))),ENT_QUOTES,'UTF-8');
	$specials_price=@html_entity_decode(rtrim(strip_tags($pws_prices->getBestPrice($id))),ENT_QUOTES,'UTF-8');
	$tax_class_id=rtrim(strip_tags($products_new_array[$nb]['tax_class_id']));
	$date_added=rtrim(strip_tags($products_new_array[$nb]['date_added']));
	$data_array=array($imagewidth,$imageheight,$model,$name,$date_added,$manufacturer,$description,$tax_class_id,$specials_price,$price,$id,$languages_code,$imagepath);
	$this->DrawCells($data_array);
    }
 }

 function TitreChapitre($lib) {
    //Titre
    $this->SetFont('Arial','',12);
    $titles_color_table=explode(",",HEIGHT_TITLES_CELL_COLOR);
    $this->SetFillColor($titles_color_table[0], $titles_color_table[1], $titles_color_table[2]);
    $this->Cell(0,6,$lib,$this->ifw,1,'L',1);
    $this->Ln(2);
    //Sauvegarde de l'ordonn�e
    $this->y0=$this->GetY();
 }
 
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td width="<?php echo BOX_WIDTH; ?>" valign="top">
	  <table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
      </table>
		</td>
<!-- body_text //-->
    <td width="100%" valign="top">
     <table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td>
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
              <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
            </tr>
          </table>
	</td>
      </tr>
<?php
    switch ($HTTP_GET_VARS['action']) {
      case 'save':
        $languages = tep_get_languages();
        $languages_string = '';
      
//        $i=1;
        for ($i=0; $i<sizeof($languages); $i++)
        {
            $pdf=new PDF();
            $pdf->Open();
            $pdf->SetDisplayMode("real");
            $pdf->AliasNbPages();
            if(SHOW_NEW_PRODUCTS) $pdf->NewProducts($languages[$i]['id'],$languages[$i]['code']);
            $pdf->CategoriesTree($languages[$i]['id'],$languages[$i]['code']);
            $pdf->CategoriesListing($languages[$i]['id'],$languages[$i]['code']);
            if (SHOW_INDEX) {
                $pdf->DrawIndex();
            }
            $pdf->Output(DIR_FS_CATALOG . DIR_WS_PDF_CATALOGS . PDF_FILENAME . "_" . $languages[$i]['id'].".pdf",false);
        }
?>
      <tr>
	<td>
	  <table>
    	    <tr>
		<td class="main"><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? echo PDF_GENERATED . " <font color=red>".$i. "</font>";  ?></td>
	    </tr>
	  </table>
        </td>
      </tr>
<?php
        break;
      default:
      	// query sui prodotti
      	$products_number_query = tep_db_query("select Products_id from products where 1");
      	if (tep_db_num_rows($products_number_query) >= 100)
      		echo '<font color=red>Non è consentito generare il catalogo per più di 100 prodotti</font>';
      	else
      		{
        echo '<tr><td class="main"><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp' . PDF_PRE_GENERATED . '&nbsp;&nbsp;';
        echo tep_draw_form('language', FILENAME_PDF_CATALOGUE, 'action=save');
        echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;<a href="' . tep_href_link(FILENAME_PDF_CATALOGUE, 'lngdir=' . $HTTP_GET_VARS['lngdir']) . '">';
        echo "</td></tr></form>";
      		}
    }
?>      
     </table>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>