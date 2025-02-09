<?php
/*
 * @filename:	mod_turbolister2.php
 * @version:	0.1
 * @project:	Turbo Lister 2
 *
 * @author:		Riccardo Roscilli info@oscommerce.it
 * @created:	20/02/2009
 * @modified:	02/10/2009 11:42:10
 *
 * @copyright 	2009 Riccardo Roscilli @ PWS
 *
 * @desc:	
 *
 * @TODO:		
 */

define('TABLE_TL2_CATEGORIES','tl2_categories');
define('TABLE_TL2_PRODUCTS','tl2_products');
if (file_exists(DIR_FS_CATALOG.'pws/include.php'))
	require_once DIR_FS_CATALOG.'pws/include.php';
if(file_exists(DIR_FS_ADMIN . DIR_WS_CLASSES . 'turbolister2_connection.php')) {
	require_once(DIR_FS_ADMIN . DIR_WS_CLASSES . 'turbolister2_connection.php');
}
class	turbolister2	{
	var $version;
	var $version_const='1.01';
	var $tables=array(
		TABLE_TL2_CATEGORIES=>"
(
	categories_id int(11) not NULL
	,ebay_category int(11) default NULL
	,ebay_category2 int(11) default NULL
	,ebay_category_name varchar(255) default NULL
	,ebay_category2_name varchar(255) default NULL
	,store_category int(11) default NULL
	,store_category2 int(11) default NULL
	,PRIMARY KEY(categories_id)
)"
		,TABLE_TL2_PRODUCTS=>"
(
	products_id int(11) not NULL
	,ebay_category int(11) default NULL 
	,ebay_category2 int(11) default NULL
	,store_category int(11) default NULL
	,store_category2 int(11) default NULL
	,PRIMARY KEY(products_id)
)"
	);
	var $configKeys=array(
		'MODULE_TURBOLISTER2_VERSION'=>array(
			'configuration_title'=>'TurboLister2 catalog import/export [Copyright 2009 PWS]'
			,'configuration_description'=>'Versione attuale del modulo per l\'esportazione del catalog in formato csv per TurboLister2'
			,'date_added'=>'now()'
			,'configuration_group_id'=>6
		)
	);

	
	// Importing Internal State
	var $return_state=true;
	// Superclasses
	var $currencies;
	var	$fields;
	var $ofields;
	var $cat_query='';
	var $prod_query='';
	var $products;
	// Formattazione del file in output
	var $category_separator='#';
	var $escaped;	// Array di sequenze di caratteri da "escapare"
	var $replacements;	// Array di rimpiazzi dei caratteri da "escapare"
	var $sep=';';
	var $seprep='.';
	var $nl="\n";
	var $nlrep="";
	var $currency='';
	
	// Impostazioni
	var $categories=NULL;	// Nomi delle categorie da esportare
	var $rules=NULL;
	var $location=DIR_FS_CATALOG;
	var $filename=NULL;
	var $headerrow=true;
	var $_tl2storeCats;
	// var $header='Action(CC=Cp1252);SiteID;Format;Title;SubTitle;Custom Label;Category;Category2;StoreCategory;StoreCategory2;Quantity;LotSize;Currency;StartPrice;BuyItNowPrice;ReservePrice;InsuranceOption;InsuranceFee;DomesticInsuranceOption;DomesticInsuranceFee;PackagingHandlingCosts;InternationalPackagingHandlingCosts;Duration;PrivateAuction;Country;ProductIDType;ProductIDValue;ItemID;Description;Counter;PicURL;BoldTitle;Featured;GalleryType;Highlight;Border;Subtitle in search resutls;GiftIcon;GiftExpressShipping;GiftShipToRecipient;GiftWrap;SalesTaxPercent;SalesTaxState;ShippingInTax;UseTaxTable;PostalCode;ApplyShippingDiscount;VATPercent;Location;Region;NowandNew;ImmediatePayRequired;PayPalAccepted;PayPalEmailAddress;PaymentInstructions;CashOnPickupAccepted;CCAccepted;AmEx;Discover;VisaMastercard;COD;CODPrePayDelivery;PostalTransfer;MOCashiers;PersonalCheck;MoneyXferAccepted;MoneyXferAcceptedinCheckout;PaymentOther;OtherOnlinePayments;PaymentSeeDescription;Escrow;ShippingType;InternationalShippingType;ShipFromZipCode;ShippingIrregular;ShippingPackage;WeightMajor;WeightMinor;WeightUnit;PackageDimension;CharityID;CharityName;DonationPercentage;ShippingService-1:Option;ShippingService-1:Cost;ShippingService-1:AdditionalCost;ShippingService-1:Priority;ShippingService-1:AddSurcharge;ShippingService-1:SurchargeVal;ShippingService-2:Option;ShippingService-2:Cost;ShippingService-2:AdditionalCost;ShippingService-2:Priority;ShippingService-2:AddSurcharge;ShippingService-2:SurchargeVal;ShippingService-3:Option;ShippingService-3:Cost;ShippingService-3:AdditionalCost;ShippingService-3:Priority;ShippingService-3:AddSurcharge;ShippingService-3:SurchargeVal;ShippingService-4:Option;ShippingService-4:Cost;ShippingService-4:AdditionalCost;ShippingService-4:Priority;ShippingService-4:AddSurcharge;ShippingService-4:SurchargeVal;ShippingService-5:Option;ShippingService-5:Cost;ShippingService-5:AdditionalCost;ShippingService-5:Priority;ShippingService-5:AddSurcharge;ShippingService-5:SurchargeVal;GetItFast;DispatchTimeMax;IntlShippingService-1:Option;IntlShippingService-1:Cost;IntlShippingService-1:AdditionalCost;IntlShippingService-1:Locations;IntlShippingService-1:Priority;IntlShippingService-2:Option;IntlShippingService-2:Cost;IntlShippingService-2:AdditionalCost;IntlShippingService-2:Locations;IntlShippingService-2:Priority;IntlShippingService-3:Option;IntlShippingService-3:Cost;IntlShippingService-3:AdditionalCost;IntlShippingService-3:Locations;IntlShippingService-3:Priority;IntlShippingService-4:Option;IntlShippingService-4:Cost;IntlShippingService-4:AdditionalCost;IntlShippingService-4:Locations;IntlShippingService-4:Priority;IntlShippingService-5:Option;IntlShippingService-5:Cost;IntlShippingService-5:AdditionalCost;IntlShippingService-5:Locations;IntlShippingService-5:Priority;IntlAddnlShiptoLocations;PaisaPayAccepted;DigitalDeliveryDetails.Method;DigitalDeliveryDetails.Requirements;DigitalDeliveryDetails.Instructions;DigitalDeliveryDetails.URL;BasicUpgradePackBundle;ValuePackBundle;ProPackPlusBundle;BestOffer;AutoDecline;MinBestOfferPrice;BestOfferRejectMessage;LocalOnlyChk;LocalListingDistance;SkypeChat;SkypeVoice;SkypeName;SkypeEnabled;SkypeOption;SkypeID;ShipToRegistrationCountry;ZeroFeedbackScore;MinimumFeedbackScore;MaximumUnpaidItemStrikes;MaximumItemCount;MaximumItemMinimumFeedbackScore;LinkedPayPalAccount;VerifiedUser;VerifiedUserMinimumFeedbackScore;ContactPrimaryPhone;ContactSecondaryPhone;ProStores Name;ProStores Enabled;Domestic Profile Discount;International Profile Discount;Apply Profile Domestic;Apply Profile International;A:Condizioni';
	//var $header='Action(CC=Cp1252);SiteID;Format;Title;SubTitle;Custom Label;Category;Category2;StoreCategory;StoreCategory2;Quantity;LotSize;Currency;StartPrice;BuyItNowPrice;ReservePrice;InsuranceOption;InsuranceFee;DomesticInsuranceOption;DomesticInsuranceFee;PackagingHandlingCosts;InternationalPackagingHandlingCosts;Duration;PrivateAuction;Country;ProductIDType;ProductIDValue;ItemID;Description;Counter;PicURL;BoldTitle;Featured;GalleryType;Highlight;Border;Subtitle in search resutls;GiftIcon;GiftExpressShipping;GiftShipToRecipient;GiftWrap;SalesTaxPercent;SalesTaxState;ShippingInTax;UseTaxTable;PostalCode;ApplyShippingDiscount;VATPercent;Location;Region;NowandNew;ImmediatePayRequired;PayPalAccepted;PayPalEmailAddress;PaymentInstructions;CashOnPickupAccepted;CCAccepted;AmEx;Discover;VisaMastercard;COD;CODPrePayDelivery;PostalTransfer;MOCashiers;PersonalCheck;MoneyXferAccepted;MoneyXferAcceptedinCheckout;PaymentOther;OtherOnlinePayments;PaymentSeeDescription;Escrow;ShippingType;InternationalShippingType;ShipFromZipCode;ShippingIrregular;ShippingPackage;WeightMajor;WeightMinor;WeightUnit;PackageDimension;CharityID;CharityName;DonationPercentage;ShippingService-1:Option;ShippingService-1:Cost;ShippingService-1:AdditionalCost;ShippingService-1:Priority;ShippingService-1:AddSurcharge;ShippingService-1:SurchargeVal;ShippingService-2:Option;ShippingService-2:Cost;ShippingService-2:AdditionalCost;ShippingService-2:Priority;ShippingService-2:AddSurcharge;ShippingService-2:SurchargeVal;ShippingService-3:Option;ShippingService-3:Cost;ShippingService-3:AdditionalCost;ShippingService-3:Priority;ShippingService-3:AddSurcharge;ShippingService-3:SurchargeVal;ShippingService-4:Option;ShippingService-4:Cost;ShippingService-4:AdditionalCost;ShippingService-4:Priority;ShippingService-4:AddSurcharge;ShippingService-4:SurchargeVal;ShippingService-5:Option;ShippingService-5:Cost;ShippingService-5:AdditionalCost;ShippingService-5:Priority;ShippingService-5:AddSurcharge;ShippingService-5:SurchargeVal;GetItFast;DispatchTimeMax;IntlShippingService-1:Option;IntlShippingService-1:Cost;IntlShippingService-1:AdditionalCost;IntlShippingService-1:Locations;IntlShippingService-1:Priority;IntlShippingService-2:Option;IntlShippingService-2:Cost;IntlShippingService-2:AdditionalCost;IntlShippingService-2:Locations;IntlShippingService-2:Priority;IntlShippingService-3:Option;IntlShippingService-3:Cost;IntlShippingService-3:AdditionalCost;IntlShippingService-3:Locations;IntlShippingService-3:Priority;IntlShippingService-4:Option;IntlShippingService-4:Cost;IntlShippingService-4:AdditionalCost;IntlShippingService-4:Locations;IntlShippingService-4:Priority;IntlShippingService-5:Option;IntlShippingService-5:Cost;IntlShippingService-5:AdditionalCost;IntlShippingService-5:Locations;IntlShippingService-5:Priority;IntlAddnlShiptoLocations;PaisaPayAccepted;DigitalDeliveryDetails.Method;DigitalDeliveryDetails.Requirements;DigitalDeliveryDetails.Instructions;DigitalDeliveryDetails.URL;BasicUpgradePackBundle;ValuePackBundle;ProPackPlusBundle;BestOffer;AutoDecline;MinBestOfferPrice;BestOfferRejectMessage;LocalOnlyChk;LocalListingDistance;SkypeChat;SkypeVoice;SkypeName;SkypeEnabled;SkypeOption;SkypeID;ShipToRegistrationCountry;ZeroFeedbackScore;MinimumFeedbackScore;MaximumUnpaidItemStrikes;MaximumItemCount;MaximumItemMinimumFeedbackScore;LinkedPayPalAccount;VerifiedUser;VerifiedUserMinimumFeedbackScore;ContactPrimaryPhone;ContactSecondaryPhone;ProStores Name;ProStores Enabled;Domestic Profile Discount;International Profile Discount;Apply Profile Domestic;Apply Profile International;A:Condizioni';
	var $header='Site;Format;Currency;Title;Condition;SubtitleText;Custom Label;Description;Category 1;Category 2;Store Category;Store Category 2;PicURL;Quantity;LotSize;Duration;Starting Price;Reserve Price;BIN Price;Private Auction;Counter;Buyer pays shipping;Payment Instructions;Specifying Shipping Costs;Insurance Option;Insurance Amount;Sales Tax Amount;Sales Tax State;Apply tax to total;Accept PayPal;PayPal Email Address;Accept MO Cashiers;Accept Personal Check;Accept Visa/Mastercard;Accept AmEx;Accept Discover;IntegratedMerchantCreditCard;Accept Payment Other;Accept Payment Other Online;Accept COD;COD PrePay Delivery;Postal Transfer;Payment See Description;Accept Money Xfer;CCAccepted;CashOnPickupAccepted;MoneyXferAccepted;MoneyXferAcceptedinCheckout;Ship-To Option;Escrow;BuyerPaysFixed;Location - City/State;Location - Country;Title Bar Image;Gallery1.Gallery;Gallery Featured;FeaturedFirstDuration;Gallery URL;PicInDesc;PhotoOneRadio;PhotoOneURL;Gallery2.GalleryPlus;Bold;MotorsGermanySearchable;Border;LE.Highlight;Featured Plus;Home Page Featured;Subtitle in search results;Gift Icon;DepositType;DepositAmount;ShippingRate;ShippingCarrier;ShippingType;ShippingPackage;ShippingIrregular;ShippingWeightUnit;WeightMajor;WeightMinor;MeasurementUnit;CODCost;PackageDimension;ShipFromZipCode;PackagingHandlingCosts;Year;MakeCode;ModelCode;EngineCode;ThemeId;LayoutId;AutoPay;Apply Multi-item Shipping Discount;Attributes;Package Length;Package Width;Package Depth;ShippingServiceOptions;VATPercent;ProductID;ProductReferenceID;UseStockPhotoURLAsGallery;IncludeStockPhotoURL;IncludeProductInfo;UniqueIdentifier;GiftIcon.GiftWrap;GiftIcon.GiftExpressShipping;GiftIcon.GiftShipToRecipient;InternationalShippingServiceOptions;Ship-To Locations;Exclude Ship-To Locations;Exclude Ship-To Type Locations;Rate Tables Domestic;Rate Tables International;Zip;BuyerRequirementDetails/LinkedPayPalAccount;PM.PaisaPayAccepted;PaisaPayEscrowEMI;LE.ProPackBundle;BestOfferEnabled;LiveAuctionDetails/LotNumber;LiveAuctionDetails/SellerSalesNumber;LiveAuctionDetails/LowEstimate;LiveAuctionDetails/HighEstimate;LiveAuctionDetails/eBayBatchNumber;LiveAuctionDetails/eBayItemInBatch;LiveAuctionDetails/ScheduleID;LiveAuctionDetails/UserCatalogID;Item.ExportedImages;PhotoDisplayType;TaxTable;LoanCheck;CashInPerson;HoursToDeposit;DaysToFullPayment;UserHostedOptimizePictureWellBitmap;BuyerResponsibleForShipping;GetItFast;DispatchTimeMax;CharityID;CharityName;DonationPercentage;AutoDecline;ListingDetails/MinimumBestOfferPrice;ListingDetails/MinimumBestOfferMessage;LE.ValuePackBundle;LE.ProPackPlusBundle;LE.BasicUpgradePackBundle;LocalOnlyChk;ListingDetails/LocalListingDistance;ContactPrimaryPhone;ContactSecondaryPhone;LocationInfo;ExtendedSellerContactDetails/ClassifiedAdContactByEmailEnabled;ppl_PhoneEnabled;BuyerRequirementDetails/ShipToRegistrationCountry;BuyerRequirementDetails/ZeroFeedbackScore;BuyerRequirementDetails/MinimumFeedbackScore;BuyerRequirementDetails/MaximumUnpaidItemStrikesInfo;BuyerRequirementDetails/MaximumUnpaidItemStrikesInfo/Count;BuyerRequirementDetails/MaximumUnpaidItemStrikesInfo/Period;BuyerRequirementDetails/MaximumItemRequirements/MaximumItemCount;BuyerRequirementDetails/MaximumItemRequirements/MinimumFeedbackScore;BuyerRequirementDetails/VerifiedUserRequirements/VerifiedUser;BuyerRequirementDetails/VerifiedUserRequirements/MinimumFeedbackScore;DisableBuyerRequirements;BuyerRequirementDetails/MaximumBuyerPolicyViolations/Count;BuyerRequirementDetails/MaximumBuyerPolicyViolations/Period;Domestic Insurance Option;Domestic Insurance Amount;InternationalShippingType;InternationalPackagingHandlingCosts;ProStores Name;ProStores Enabled;Domestic Profile Discount;International Profile Discount;Apply Profile Domestic;Apply Profile International;SellerTags;AutoAccept;ListingDetails/BestOfferAutoAcceptPrice;eBayNotes;Paymate;ProPay;Moneybookers;PromoteCBT;ReturnsAccepted;ReturnsWithin;Refund;ShippingCostPaidBy;WarrantyOffered;WarrantyType;WarrantyDuration;ReturnsDetail;WofGMarketplace;WofGCategoryID;WofGDescription;WofGProducerInfo;WofGRegionOfOrigin;WofProduceerPictureURL;WofGQuestionSet;WofGTrustProvider;Fitments';
	// Switch per esportazione di alcuni campi particolari
	function __construct(){
		$this->turbolister2();
	}
	function turbolister2()
	{
		if (!$this->check())
			$this->install();
	}
	function check()
	{
		return (false!==($this->version=$this->version()) && $this->version==$this->version_const);
	}
	function version()
	{
		$check_query=tep_db_query('select configuration_value from '.TABLE_CONFIGURATION.' where configuration_key="MODULE_TURBOLISTER2_VERSION"');
		return (tep_not_null($result=tep_db_fetch_array($check_query))) ? $result['configuration_value']:false;
	}
	function install()
	{
		switch($this->version)
		{
			case false:
				reset($this->tables);
				foreach ($this->tables as $tablename=>$tabledef)	{
					if (!$this->tableExists($tablename)){
						if (!tep_db_query("CREATE TABLE `$tablename` $tabledef"))	{
							$this->reportError(ERROR_PWS_DATABASE_TABLE_CREATION);
							return false;
						}
					}
				}
				reset($this->configKeys);
				foreach($this->configKeys as $key=>$keydef)	{
					if (!$this->configurationKeyExists($key)){
						tep_db_perform(TABLE_CONFIGURATION,array_merge(array('configuration_key'=>$key),$keydef));
					}
				}
				break;
			default:
				break;
		}
		tep_db_query("update " . TABLE_CONFIGURATION . " set configuration_value='".$this->version_const."',last_modified= now() where configuration_key='MODULE_TURBOLISTER2_VERSION'");
	}
	function remove(){
		
	}
	function tableExists($table_name){
		$found=false;
		$tablesQuery=tep_db_query('show tables');
		while (!$found && $table=tep_db_fetch_array($tablesQuery)){
			$found = $table_name==array_pop($table);
		}
		return $found;
	}
	function configurationKeyExists($keyname){
		$configQuery=tep_db_query("select * from ".TABLE_CONFIGURATION." where configuration_key='$keyname'");
		return tep_db_num_rows($configQuery);
	}
	function fieldExists($fieldname,$tablename){
		$found=false;
		$query=tep_db_query("show columns from $tablename");
		while (!$found && $field=tep_db_fetch_array($query)){
			$found = $fieldname==$field['Field'];
		}
		return $found;
	}
	function reportError($message){
		global $messageStack;
		$messageStack->add($message,'error');
	}
	function reportWarning($message){
		global $messageStack;
		$messageStack->add($message,'warning');
	}
	function reportSuccess($message){
		global $messageStack;
		$messageStack->add($message,'success');
	}
	function init($export_categories=NULL)
	{
		$this->location=DIR_FS_CATALOG.'temp';

		$this->categories=$export_categories;
//		$this->rules=sizeof($export_rules)?$export_rules:NULL;
//		$this->headerrow=$export_headerrow;

		$this->sep=';';
		$this->seprep='.';
				
		$this->nl="\r\n";
		$this->nlrep="";
		$this->escaped=array('"',"\r");//,"\r","\n");
		$this->replacements=array('""','');//,'','');

//		$this->filename=$export_name;
//		$this->formatLocation($this->location);
//		$this->imkdir($this->location);

		ini_set('max_execution_time',360);
		ini_set('memory_limit','16M');
//		array_unshift($this->fields,'products_id');
//		$this->ofields=array();
	}

	function sendExportHeaders(){
		header("Content-type: text/csv");
		header("Expires: " . gmdate("D, d M Y H:i:s") . " GMT");		
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Pragma: no-cache"); 
		header("Content-Transfer-Encoding: binary");
		header('Content-Type: text/csv');
		header("Content-Disposition: inline; filename=\"turbolister2.csv\"");
	}
	function export(){
		global $currencies;
		global $pws_products_images;
		$arrAllCats = array();
		$sql = 'select categories_id, parent_id from categories';
		$query = tep_db_query($sql);
		while($row = tep_db_fetch_array($query)) {
			$arrAllCats[$row['categories_id']] = $row['parent_id'];
		}
		$this->sendExportHeaders();
		$this->prod_query=$this->buildProductsQuery();
		$this->cat_query=$this->buildCategoryQuery();
		
		$arrTaxRates = array();
//		$products = array(
//			'categories_name'=>array(),
//			'products'=>array()
//		);
//		$numproducts=0;
		// Preparazione al calcolo dei costi di spedizione per singolo articolo
		$shipping_fee=0;
		// Codice paese del negozio
		$country = tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_id = '" . (int)STORE_COUNTRY. "'");
		$country = tep_db_fetch_array($country);
		$country = $country['countries_iso_code_2'];
		$paypal_on=tep_db_query("select configuration_value from ".TABLE_CONFIGURATION." where configuration_key='MODULE_PAYMENT_PAYPAL_STATUS'");
		if (tep_db_num_rows($paypal_on)>0)	{
			$paypal_on=tep_db_fetch_array($paypal_on);
			$paypal_on=$paypal_on['configuration_value']==1;
			$paypal_email=tep_db_query("select configuration_value from ".TABLE_CONFIGURATION." where configuration_key='MODULE_PAYMENT_PAYPAL_ID'");
			$paypal_email=tep_db_fetch_array($paypal_email);
			$paypal_email=$paypal_email['configuration_value'];
		}
		else
			$paypal_on=false;
		$prodstr='';
		$fields=explode(';',$this->header);
		// Header Row
		for ($i=0; $i<sizeof($fields); $i++, $prodstr.=($i<sizeof($fields))?$this->sep:$this->nl)
			$prodstr.=$fields[$i];
		echo $prodstr;
		$curprod=false;
		//var_dump($fields);var_dump($query_str);exit;
		$pquery = tep_db_query($this->prod_query);
		$skipprod=false;
		$matches=null;
		$languages = tep_get_languages();
		$arrLanguages = array();
		foreach($languages as $k=>$v) {
			$arrLanguages[$v['id']] = $v;
		}
		reset($languages);
		$sql = 'SELECT pd.* FROM pages p, pages_description pd WHERE pd.pages_id=p.pages_id AND pd.pages_title LIKE \'Turbolister Header\'';
		$pg_query = tep_db_query($sql);
		$pageHeader = array();
		while($page = tep_db_fetch_array($pg_query))  {
            $pageHeader[$page['language_id']] = str_replace("\n",' ',$page['pages_html_text']);
        }
        $sql = 'SELECT p.*, pd.* FROM pages p, pages_description pd WHERE pd.pages_id=p.pages_id AND pd.pages_title LIKE \'Turbolister Footer\'';
		$pg_query = tep_db_query($sql);
		$pageFooter = array();
		while($page = tep_db_fetch_array($pg_query))  {
            $pageFooter[$page['language_id']] = str_replace("\n",' ',$page['pages_html_text']);
        }
        $sql = 'SELECT * FROM '.TABLE_CONFIGURATION.' WHERE configuration_key=\'MODULE_TURBOLISTER2_FEE\'';
		$price_fee = tep_db_fetch_array(tep_db_query($sql));
		$price_fee = (float)$price_fee['configuration_value'];
		$price_fee_corrected = $price_fee/100+1;
        global $pws_prices, $pws_engine, $shipping_modules, $shipping_weight, $shipping_module_current, $shipping_module_current_method;
		chdir('..');
		if(!is_object($shipping_modules)) {
			require_once(DIR_WS_CLASSES . 'shipping.php');  
			$shipping_modules = new shipping();
		}
		chdir('admin/');
		while (tep_not_null($product=tep_db_fetch_array($pquery)))
		{
			$curprod = $product;
			$pid=(int)$curprod['products_id'];
			$cquery = tep_db_query($this->cat_query.$pid);
			if (tep_not_null($category=tep_db_fetch_array($cquery)))
			{
				$curprod['categories_id']=$category['categories_id'];
				$curprod['categories_name']=$category['categories_name'];
				if (false===array_search($category['categories_id'],$this->categories))
					continue;
			}
			else
				continue;
//			echo $curprod['products_name'].'--'.$category['categories_id'].'<br/>';
			$numproducts++;
			if (!(isset($product['specials_new_products_price']) && isset($product['specials_price_status']) && $product['specials_price_status']=='1'))
				$curprod['specials_new_products_price']=false;
			$curprod['purl']=HTTP_SERVER.DIR_WS_CATALOG.'product_info.php?cPath='.$this->tep_get_product_path($pid).'&products_id='.$pid;
			$curprod['pimgurl']=HTTP_SERVER.DIR_WS_CATALOG.DIR_WS_IMAGES . $product['products_image'];
			$curprod['main_categories_name']=$this->get_product_categories_names($pid,false);
			$curprod['categories_names_path']=$this->get_product_categories_names($pid,true);
			// Selezione della prima riga della descrizione come campo separato
			$value=trim(strip_tags(html_entity_decode($product['products_description'])));
			preg_match('/^(.*?)[\r|\n]/',$value,$matches);
			$curprod['products_description_firstrow']=$matches[0] ? trim($matches[0]) : $value;

			// Creazione della riga di esportazione 
			$prodstr='';
			$sprice = $curprod['specials_new_products_price'];
			$oprice = $curprod['products_price'];
			$price=$sprice ? $sprice : $oprice;
            $shipping_weight = $curprod['products_weight'];
			//$price=$pws_prices->calculatePrice($pws_prices->getBestPrice($curprod['products_id']),0,$curprod['products_id'],true);
      $pInfo = new objectInfo($curprod);
      $pws_prices->adminLoadProduct($pInfo);
      
      $price = $pws_prices->getBestPrice($curprod['products_id']);
      
      if($curprod['products_tax_class_id']>0) {
        if(!isset($arrTaxRates[$curprod['products_tax_class_id']])) {
          $arrTaxRates[$curprod['products_tax_class_id']] = tep_get_tax_rate_value($curprod['products_tax_class_id']);
        }
        if($arrTaxRates[$curprod['products_tax_class_id']]>0) {
          $price = $price * (($arrTaxRates[$curprod['products_tax_class_id']] / 100)+1);
        }
      }
      $price = number_format($price*(1+$pInfo->pws_purchase_price_commission / 100.0),2);
      //echo '{'.$pInfo->pws_purchase_price_commission.'/'.$price.'}';exit;
      //$pws_engine->triggerHook('ADMIN_LOAD_PRODUCT');
     // echo '/* '.print_r(get_class($pws_prices),1).' *//* <pre>' . print_r(get_class_methods(get_class($pws_prices)), 1) . '</pre> */';
        //exit;
      //continue;
            //echo "\n\n".$price.':'.$price*(1+($price_fee/100)).': '.$this->myformatnumber($price*(1+$price_fee/100),2)."\n\n";exit;
			$shipping_price = $this->getShippingPrice($curprod['products_id'], $price);
//			print_r($shipping_modules);
//			echo $shipping_weight.', '.$shipping_price . print_r($shipping_methods,1) . "\n\n";exit;
			$sql = "select * from ".TABLE_TL2_CATEGORIES." where categories_id=".$curprod['categories_id'];
			$arrECat = tep_db_fetch_array(tep_db_query($sql));
			for ($i=0;$i<sizeof($fields);$i++,$prodstr.=$i<sizeof($fields)?$this->sep:$this->nl)
			{
				switch ($field=$fields[$i])	{
					case 'Format':
						$prodstr.='9';//'Auction';
						break;
					case 'Site':
						$prodstr.= MODULE_TURBOLISTER2_SITEID; //tep_get_country_name(STORE_COUNTRY);
						break;
					case 'Title':
						$prodstr.=$this->escapeField($curprod['products_name']);
						break;
					case 'VATPercent':
						$prodstr .= '20';
						break;
					case 'AutoPay':
						$prodstr .= '0';
						break;
					case 'Apply Multi-item Shipping Discount':
						$prodstr .= '0';
						break;
					case 'Condition':
						$prodstr .= '1000';
						break;
					case 'SubtitleText':
						$prodstr .= MODULE_TURBOLISTER2_COMMISSIONE;//$curprod['products_model'];
						break;
					case 'Quantity':
						$prodstr.=(int)$curprod['products_quantity'];
						break;
					case 'Category 1':
						$prodstr.=$arrECat['ebay_category2'];
						break;
					case 'Category 2':
						$prodstr.=$arrECat['ebay_category2'];
						break;
					case 'Store Category':
						$prodstr.=$arrAllCats[(int)$arrECat['categories_id']];
						break;
					case 'Store Category 2':
						$prodstr.=(int)$arrECat['categories_id'];
						break;
					case 'Currency':
						$prodstr.='7';
						break;
					case 'Custom Label':
						$prodstr .= $curprod['products_model'];
						break;
					case 'Duration':
						$prodstr .= MODULE_TURBOLISTER2_DURATION;
						break;
					case 'Starting Price':
					case 'Buy It Now Price':
						//$prodstr.=$this->myformatnumber($price+($price/100)*$price_fee,2);
						//echo '{'.$price . '?' . (float)$price_fee_corrected.':'.((float)$price*(float)$price_fee_corrected).'}';
						//$price = $price*$price_fee_corrected;
						$prodstr.=$this->myformatnumber($price*$price_fee_corrected, 2);
						break;
						break;
					case 'ShippingType':
						$prodstr .= '1';
						break;
					case 'ShippingWeightUnit':
						$prodstr .= 'kg';
						break;
					case 'WeightMajor':
						$prodstr .= $curprod['products_weight'];
						break;
                    case 'ShippingCarrier':
                        $prodstr .= $shipping_module_current;
                        break;
					case 'ShippingRate':
						$prodstr .= $shipping_price;
						break;
					case 'Private Auction':
						$prodstr .= '0';
						break;
					case 'Buyer pays shipping':
						$prodstr .= '1';
						break;
					case 'Payment See Description':
						$prodstr .= '0';
						break;
					case 'Accept Money Xfer':
						$prodstr .= '1';
						break;
					case 'MoneyXferAccepted':
						$prodstr .= '1';
						break;
					case 'Apply Multi-item Shipping Discount':
						$prodstr .= '0';
						break;
					
					case 'AutoPay':
						$prodstr .= '0';
						break;
					case 'UniqueIdentifier':
						$prodstr.='Acer';
						break;
					case 'UseStockPhotoURLAsGallery':
					case 'IncludeStockPhotoURL':
					case 'IncludeProductInfo':
						$prodstr .= '1';
						break;
						break;
						break;
					case 'Country':
						$prodstr.=$country;
						break;
					case 'Location - Country':
						$prodstr.='IT';
						break;
					case 'Description':
						//$prodstr.='D-E-S-C-R-I-Z-I-O-N-E';//$this->escapeField($curprod['products_description']);
						if (is_object($pws_products_images)){
							$jssrc=HTTP_SERVER.DIR_WS_CATALOG.'includes/AC_RunActiveContent.js';
							$fieldvalue=<<<EOT
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,dependent=yes,fullscreen=yes,width=100,height=100,screenX=150,screenY=150,top=150,left=150')
}
//--></script>
<script src="$jssrc" language="javascript"></script>
EOT;
							$fieldvalue=$pws_products_images->catalogProductInfoSlideshowFlash($curprod['products_id'],true);
						}else{
							$fieldvalue='';
						}
						
						//$fieldvalue.=$curprod['products_description'];
						if((int)$_POST['languages_id']>0) {
							$descquery=tep_db_query("select products_description from ".TABLE_PRODUCTS_DESCRIPTION." where products_id='".$curprod['products_id']."' and language_id='".$arrLanguages[$_POST['languages_id']]['id']."'");
							$desc=tep_db_fetch_array($descquery);
							$fieldvalue.='<fieldset><label><img src="'.HTTP_SERVER.DIR_WS_CATALOG_LANGUAGES . $arrLanguages[$_POST['languages_id']]['directory'] . '/images/' . $arrLanguages[$_POST['languages_id']]['image'].'" title="'. $arrLanguages[$_POST['languages_id']]['name'].'" alt="'. $arrLanguages[$_POST['languages_id']]['name'].'" />'
								.$arrLanguages[$_POST['languages_id']]['name'].'</label>';
							$fieldvalue.=$pageHeader[$arrLanguages[$_POST['languages_id']]['id']] . str_replace("\n",' ',$desc['products_description']).$pageFooter[$arrLanguages[$_POST['languages_id']]['id']].'</fieldset>';
						} else {
							for ($i2=0, $n2=sizeof($languages); $i2<$n2; $i2++) {
								$descquery=tep_db_query("select products_description from ".TABLE_PRODUCTS_DESCRIPTION." where products_id='".$curprod['products_id']."' and language_id='".$languages[$i2]['id']."'");
								$desc=tep_db_fetch_array($descquery);
								$fieldvalue.='<fieldset><label><img src="'.HTTP_SERVER.DIR_WS_CATALOG_LANGUAGES . $languages[$i2]['directory'] . '/images/' . $languages[$i2]['image'].'" title="'. $languages[$i2]['name'].'" alt="'. $languages[$i2]['name'].'" />'
									.$languages[$i2]['name'].'</label>';
								$fieldvalue.=$pageHeader[$languages[$i2]['id']] . str_replace("\n",' ',$desc['products_description']).$pageFooter[$languages[$i2]['id']].'</fieldset>';
							}
						}

						//$prodstr.=$this->escapeField('');
						// corregge i link alle immagini all'interno della descrizione.
						$fieldvalue = str_replace('../images', HTTP_SERVER . DIR_WS_CATALOG. '/images', $fieldvalue );
						$fieldvalue = str_replace('src="/images', 'src="' . HTTP_SERVER . DIR_WS_CATALOG. '/images', $fieldvalue );
						$fieldvalue = str_replace("\r",' ',$fieldvalue);
						$fieldvalue = str_replace("\n",' ',$fieldvalue);
						$prodstr.=$this->escapeField($fieldvalue);
						break;
//					case 'Counter':
//						$prodstr.='BasicStyle';
//						break;
					case 'PicURL':
						$prodstr.=$this->escapeField($curprod['pimgurl']);
						break;
					case 'Accept PayPal':
						$prodstr.=$paypal_on ? '1' : '0';
						break;
					case 'PayPal Email Address':
						$prodstr.= (defined('MODULE_TURBOLISTER2_PAYPAL_EMAIL') && MODULE_TURBOLISTER2_PAYPAL_EMAIL!='') ? MODULE_TURBOLISTER2_PAYPAL_EMAIL:($paypal_on ? $paypal_email : 'rubinaccio@hotmail.com');
						break;
					case 'BuyerRequirementDetails/LinkedPayPalAccount':
					case 'BuyerResponsibleForShipping':
					case 'GetItFast':
					case 'LE.ValuePackBundle':
					case 'Apply Profile Domestic':
					case 'Apply Profile International':
					case 'ReturnsAccepted':
						$prodstr .= '0';
						break;
						break;
						break;
						break;
						break;
						break;
						break;
					case 'ShippingCostPaidBy':
						$prodstr .= '-1';
						break;
					case 'Specifying Shipping Costs':
						$prodstr .= '-1';
						break;
					case 'DispatchTimeMax':
					case 'DisableBuyerRequirements':
						$prodstr .= '1';
						break;
						break;
					case 'InternationalShippingServiceOptions':
					case 'Ship-To Locations':
						$prodstr.='AAA=';
						break;
						break;
					case 'Zip':
						$prodstr.=MODULE_TURBOLISTER2_MERCHANT_ZIP;//'171';
						break;
					default:
						$prodstr .= '~';
						break;
				}
			}
			echo $prodstr;
			
//			$products['categories_name'][]=strtolower($curprod['categories_names_path']);
//			$products['products'][]=$curprod;
		}
//		array_multisort($products['categories_name'],SORT_ASC,SORT_STRING,$products['products']);
//		$this->products=$products['products'];
		exit;
	}

	function getShippingPrice($products_id, $products_price)
   {
   		global $shipping_modules, $shipping_module_current, $shipping_module_current_method;
//   		echo $products_id .'>>'.$products_price.' ';exit;
	  // controlliamo anche se il prezzo del prodotto supera il totale ordine spedizione gratis
//       if ( defined('MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING') && (MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING == 'true') ) {

//					$free_shipping = false;
//				    if ( ($products_price >= MODULE_ORDER_TOTAL_SHIPPING_FREE_SHIPPING_OVER) ) {
//				      $free_shipping = true;
//				    }	

//                }
            $free_shipping = false;
            if ( $free_shipping == true ) 
            {
      			return $shippingprice = 0;
      		}
            elseif($this->tep_count_shipping_modules() >= 1) // ricavo il più economico fra i corrieri configurati escludendo quelli con costo zero (corriere cliente e ritiro in sede)
            {
            $cheapest = ''; 
            $arrQuotes = $shipping_modules->quote_product($products_id);
//            print_r($arrQuotes);
   			foreach ($arrQuotes as $array)
	   			{
	   				if($array['methods']['0']['cost'] >= '0')
	   				{
//	   					if($array['methods']['0']['cost'] >= '0' && $array['methods']['0']['cost'] << $cheapest)
	   						$cheapest = $array['methods']['0']['cost'];
	   				}
					// return $shippingprice = $cheapest;
	   			}
                $shipping_module_current = $array['module'];
                $shipping_module_current_method = $array['methods'][0]['title'];
	   			return $shippingprice = $cheapest;
            
            }
            else {
            	return $shippingprice = -1; // non sappiamo qual è il costo di spedizione
            }
   	
   }
	function tep_count_shipping_modules() {
    	return $this->tep_count_modules(MODULE_SHIPPING_INSTALLED);
	}
	function tep_count_modules($modules = '') {
    $count = 0;

    if (empty($modules)) return $count;

    $modules_array = split(';', $modules);

    for ($i=0, $n=sizeof($modules_array); $i<$n; $i++) {
      $class = substr($modules_array[$i], 0, strrpos($modules_array[$i], '.'));

      if (is_object($GLOBALS[$class])) {
        if ($GLOBALS[$class]->enabled) {
          $count++;
        }
      }
    }

    return $count;
  }
	function buildProductsQuery()
	{
		$lang_id = ($_POST['languages_id'] && (int)$_POST['languages_id']>0)?$_POST['languages_id']:$GLOBALS['languages_id'];
		$i=0;
		$query_str = 'select t1.products_id, products_name, t1.products_quantity, t2.products_description,'
			.'t1.products_price, t1.products_model, t1.products_weight, '
			.'t1.products_shopwindow, t1.products_onlyshow, t1.products_tax_class_id, t1.products_image, '
			//.'t4.specials_new_products_price,t4.status as specials_price_status,'
			.'t3.manufacturers_name, t3.manufacturers_image '
			.' from '.TABLE_PRODUCTS.' t1'
			.' left join '.TABLE_PRODUCTS_DESCRIPTION.' t2 on (t1.products_id=t2.products_id)'
			.' left join '.TABLE_MANUFACTURERS.' t3 on (t1.manufacturers_id=t3.manufacturers_id)'
			//.' left join '.TABLE_SPECIALS.' t4 on (t1.products_id=t4.products_id)'
			.' where t2.language_id='.$lang_id;
		
//		$query=tep_db_query($query_str);
//		$prod=tep_db_fetch_array($query);
//		print_r($prod);
//			exit($query_str);

		return $query_str;
	}
	function buildCategoryQuery()
	{
		$lang_id = ($_POST['languages_id'] && (int)$_POST['languages_id']>0)?$_POST['languages_id']:$GLOBALS['languages_id'];
			$cat_query = 'select t1.categories_id,categories_name '
			.' from '.TABLE_PRODUCTS_TO_CATEGORIES.' t1'
			.' left join '.TABLE_CATEGORIES_DESCRIPTION.' t2 on (t1.categories_id=t2.categories_id)'
			.' where t2.language_id='.$lang_id.' and products_id=';
		return $cat_query;
	}

	function preload($filename,&$remote_cats,&$items) {
		$fp=@fopen($filename,'r');
		if (!$fp)	{
			$this->reportError(ERROR_TURBOLISTER2_FILE_ACCESS_READ);
			return false;
		}
		$numrows=0;
		$rows=array();
		while (($row = fgetcsv($fp, 1000000, ";")) !== FALSE)	{
			if (!is_null($row))
				$rows[]=$row;
			$numrows++;
		}
		fclose($fp);
		if (!sizeof($rows)){
			$this->reportError(ERROR_TURBOLISTER2_FILE_IMPORT);
			return false;
		}
		$fields=array_shift($rows);
		$items=array();
		$remote_cats=array();
		for ($numrow=0;$numrow<sizeof($rows);$numrow++)	{
			$item=array();
			$item['products_name']=$rows[$numrow][array_search('Title',$fields)];
			$item['products_quantity']=$rows[$numrow][array_search('Quantity',$fields)];
			$item['products_description']=urldecode($rows[$numrow][array_search('Description',$fields)]);
			$item['products_price']=str_replace(',','.',$rows[$numrow][array_search('StartPrice',$fields)]);
			$storecategory=$item['store_category']=$rows[$numrow][array_search('StoreCategory',$fields)];
			if (!isset($remote_cats[$storecategory])) {
				$checkCategory=tep_db_query("select categories_id from ".TABLE_TL2_CATEGORIES." where store_category='$storecategory'");
				if (tep_db_num_rows($checkCategory)){
					$category=tep_db_fetch_array($checkCategory);
					$remote_cats[$storecategory]=$category['categories_id'];
				}
				else
					$remote_cats[$storecategory]=0;
			}
			if (!isset($items[$storecategory]))
				$items[$storecategory]=array();
			$items[$storecategory][]=$item;
		}
		return true;
	}

	function import($filename)	{
		// Salva le impostazioni della categoria
		/*
		$cats=$_REQUEST['cat'];
		foreach($cats as $catid=>$categories_id){
			if (!$categories_id){
				$this->reportError(ERROR_TURBOLISTER2_CATEGORIES_TO_ASSIGN);
				return false;
			}
			$checkQuery=tep_db_query("select categories_id from ".TABLE_TL2_CATEGORIES." where store_category='$catid'");
			if (tep_db_num_rows($checkQuery))
				tep_db_query("update ".TABLE_TL2_CATEGORIES." set categories_id='$categories_id' where store_category='$catid'");
			else
				tep_db_query("insert ".TABLE_TL2_CATEGORIES." set store_category='$catid', categories_id='$categories_id'");
		}
		//*/
		/*$catquery=tep_db_query("select categories_id from ".TABLE_CATEGORIES_DESCRIPTION." where categories_name='StoreEbay' and language_id=".$GLOBALS['languages_id']);
		if (!tep_db_num_rows($catquery))
			exit("errore: non trovo la categoria 'StoreEbay'");
		$catquery=tep_db_fetch_array($catquery);
		$current_category_id=$catquery['categories_id'];*/
		$fp=@fopen($filename,'r');
		if (!$fp)	{
			$this->reportError(ERROR_TURBOLISTER2_FILE_ACCESS_READ);
			return false;
		}
		$numrows=0;
		$rows=array();
		while (($row = fgetcsv($fp, 1000000, ";")) !== FALSE)	{
			if (!is_null($row))
				$rows[]=$row;
			$numrows++;
		}
		fclose($fp);
		@chmod($filename,0777);
		@unlink($filename);
		if (!sizeof($rows)){
			$this->reportError(ERROR_TURBOLISTER2_FILE_IMPORT);
			return false;
		}
		$fields=array_shift($rows);
		$items=array();
		for ($numrow=0;$numrow<sizeof($rows);$numrow++)	{
			$item=array();
			$item['products_name']=$rows[$numrow][array_search('Title',$fields)];
			$item['products_quantity']=$rows[$numrow][array_search('Quantity',$fields)];
			$item['products_description']=urldecode($rows[$numrow][array_search('Description',$fields)]);
			$item['products_price']=str_replace(',','.',$rows[$numrow][array_search('StartPrice',$fields)]);
			$item['products_model']=$rows[$numrow][array_search('Custom Label',$fields)];
			$imgdst=false;
			if (strlen($imgurl=$rows[$numrow][array_search('PicURL',$fields)])){
				$imgdst=basename($imgurl);
				if (!@copy($imgurl,DIR_FS_CATALOG_IMAGES.$imgdst))
					$imgdst=false;
			}
			
			$catid1=$rows[$numrow][array_search('Category 1',$fields)];
			$catid2=$rows[$numrow][array_search('Category 2',$fields)];
			
			if((int)$catid2>0) {
   				$catid = $catid2;
   			} else {
   				$catid = $catid1;
   			} 
			if((int)($categoryId = $this->getCategoryRelation($catid))<=0) {
				$this->saveCategories($this->getCategoryQuickTree($catid));
			}
			$categoryId = $this->getCategoryRelation($catid);
			
			$pquery=tep_db_query("select * from ".TABLE_PRODUCTS_DESCRIPTION." t2 left join ".TABLE_PRODUCTS ." t1 on (t1.products_id=t2.products_id)" .
					" where products_name=CONVERT(\"".tep_db_input($item['products_name'])."\" USING latin1)");
			if (($item['products_found']=(tep_db_num_rows($pquery)>0)))	{
				$pid=tep_db_fetch_array($pquery);
				$pid=$pid['products_id'];
				tep_db_query("update ".TABLE_PRODUCTS." set products_quantity=".$item['products_quantity']." where products_id=$pid");
				tep_db_query("update ".TABLE_PRODUCTS_DESCRIPTION." set products_description=\"".tep_db_input($item['products_description'])."\" where products_id=$pid and language_id=".$GLOBALS['languages_id']);
			}	else	{
				$sql_data_array = array('products_quantity' => $item['products_quantity'],
				                         'products_model' => tep_db_input($item['products_model']),
				                         'products_price' => $item['products_price'],
				                         'products_date_available' => 'now()',
				                         'products_weight' => '0.5',
				                         'products_status' => '1',
				                         'products_tax_class_id' => '1',
				                         'manufacturers_id' => '0',
				                         'products_date_added' => 'now()');
				if ($imgdst!==false)
					$sql_data_array['products_image']=$imgdst;
				tep_db_perform(TABLE_PRODUCTS, $sql_data_array);
				$products_id = tep_db_insert_id();
   
   				if((int)$catid2>0) {
   					$catid = $catid2;
   				} else {
   					$catid = $catid1;
   				} 
				if((int)($categoryId = $this->getCategoryRelation($catid))<=0) {
					$this->saveCategories($this->getCategoryQuickTree($catid));
				}
				
				tep_db_query("insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (products_id, categories_id) values ('" . (int)$products_id . "', '" . $categoryId . "')");


				$languages = tep_get_languages();
				for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
				  $language_id = $languages[$i]['id'];
				
					$sql_data_array = array('products_name' => tep_db_prepare_input($item['products_name']),
											'products_description' => tep_db_prepare_input($item['products_description']),
											'products_url' => '');
				
					$insert_sql_data = array('products_id' => $products_id,
											'language_id' => $language_id);
				
					$sql_data_array = array_merge($sql_data_array, $insert_sql_data);
				
					tep_db_perform(TABLE_PRODUCTS_DESCRIPTION, $sql_data_array);
				}
			}
			$items[]=$item;
		}
		return $items;
	}
	function parseContent($text)	{
		$this->return_state=true;
		$items=array();
		$curitem=array();
		$eof=false;
		$i=0;
		while (strlen($text) && $this->return_state)	{
			if ($text[0]=="\r" && $text[1]=="\n")	{
				$text=substr($text,2);
				$items[]=$curitem;
				//print("<br/>item $i<br/>");
				$i++;
				$curitem=array();
			}
			else	{
				if ($text[0]=='"')
					$field=$this->parseEscapedField($text);
				else
					$field=$this->parseField($text);
				$curitem[]=$field;
				//print(".");
			}
		}
		return $this->return_state? $items : $this->return_state;
	}
	function parseField(&$text)	{
		$field='';
		while (strlen($text) && $text[0]!=$this->sep && !($text[0]=="\r" && $text[1]=="\n"))	{
			$field.=$text[0];
			$text=substr($text,1);
		}
		if ($text[0]==$this->sep)
			$text=substr($text,1);
		return $field;
	}
	function parseEscapedField(&$text)	{
		$text=substr($text,1);
		$field='';
		while (strlen($text)>0) {
			if ($text[0]=="\"")	{
				if (substr($text,1,1)!="\"" || strlen($text)==1)	{
					$text=substr($text,1);
					break;
				}
				$field.='"';
				$text=substr($text,2);
				continue;
			}
			$field.=$text[0];
			$text=substr($text,1);
		}
		if ($text[0]==$this->sep)
			$text=substr($text,1);		
		return $field;
	}
	function eoln(&$text)	{
		$eoln=$text[0]=="\r" && $text[1]=="\n";
		return $eoln;
	}
	function escapeField($str)
	{
		$str=(string)$str;
		//	return '"'.str_replace($this->escaped,$this->replacements,$str).'"';
		$str = '"'.str_replace($this->escaped,$this->replacements,$str).'"';
		return $str;
		
	}

	function stripcontrolchars($str)
	{
		return preg_replace("/[\r|\n|$this->sep]/","",$str);
	}
	function myformatnumber($value,$decimalplaces=0)
	{
		return (string)($value>0)?number_format((float)$value,$decimalplaces,',',''):'';
	}
	
	/**
	 * getCategory() method created to fetch category from eBay. It call sendRequest() method from turbolister2_connection class located in backend class dir.
	 * 
	 * @param integer $id eBay category ID
	 * @return array eBay category info
	 */
	function getCategory($id) {
		if(!$this->_tl2conn) $this->_tl2conn = new turbolister2_connection();
		$arrData = $this->_tl2conn
						->setDebug(false)
						->sendRequest('shopping', 'GetCategoryInfo', array('CategoryID'=>$id))
						->asArray();
//		echo '<pre>{'.print_r($this->_tl2conn->getOutput(), 1).'}</pre>';
		if($arrData['Ack']=='Success') {
			$arrData = $arrData['CategoryArray']['Category'];
		} else {
			$this->reportError($arrData['Errors']['SeverityCode'].' '.$arrData['Errors']['ErrorCode'] . ': ' . $arrData['Errors']['ShortMessage'] . '<br /> Class: '.get_class($this).', Method: getCategory(ID: '.$id.')');
			$arrData = false;
		}
		return $arrData;
	}
	
	function getTopCategory($category_name = 'turbolister') {
		if(!isset($this->_topCategory)) {
			$sql = 'select categories_id from ' . TABLE_CATEGORIES_DESCRIPTION . ' where categories_name like \''.tep_db_prepare_input($category_name) . '\' limit 1';
			$arrRes = tep_db_fetch_array(tep_db_query($sql));
			if(!isset($arrRes['categories_id']) || (int)$arrRes['categories_id']<=0) {
				$arrSql = array(
						'parent_id'=>0,
						'categories_status'=>0,
						'date_added' => 'now()'
					);
				if(tep_db_perform(TABLE_CATEGORIES, $arrSql)) {
					$this->_topCategory = tep_db_insert_id();
					$arrLangs = tep_get_languages();
					foreach($arrLangs as $k=>$v) {
						$arrSql = array(
							'categories_id' => $this->_topCategory,
							'categories_name' => $category_name,
							'language_id' => $v['id']
						);
					}
					tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $arrSql);
				}
			} else {
				$this->_topCategory = $arrRes['categories_id'];
			}
		}
		return $this->_topCategory;
	}
	
	/**
	 * getCategoryTree() method created to build eBay category tree (get parent categories)
	 * 
	 * @param integer $id eBay category ID
	 * @return array eBay category array with all parent categories
	 */
	function getCategoryTree($id) {
		$arrCat = $this->getCategory($id);
		if(is_numeric($arrCat['CategoryParentID']) && $arrCat['CategoryParentID']>0) {
			$arrCat['parent'] = $this->getCategoryTree($arrCat['CategoryParentID']);
		}
		return $arrCat;
	}
	
	
	/**
	 * getCategoryTree() method created to build eBay category tree (get parent categories)
	 * 
	 * @param integer $id eBay category ID
	 * @return array eBay category array with all parent categories
	 */
	function getCategoryQuickTree($id) {
		$arrCat = $this->getCategory($id);
		if($arrCat['CategoryNamePath']) {
			$cNp = explode(':', $arrCat['CategoryNamePath']);
			$cIp = explode(':', $arrCat['CategoryIDPath']);
			$arrData = array('name'=>$cNp, 'id'=>$cIp);
			$arrCat = $this->fillCategoryQuickTree($arrData);
		} else {
			$this->reportError('Error: parse error in class: ' . get_class($this) . ', method: getCategoryQuickTree(ID: '.$id.')');
			$arrCat = false;
		}
		return $arrCat;
	}
	
	/**
	 * fillCategoryQuickTree() method fetch categories tree data from just one category 
	 * (eBay category info leave parent categories data such as ID's and Name's in CategoryNamePath and CategoryIDPath array elements)
	 * 
	 * @param array $arr	that array should contain two elements: ['id'] and ['name'] which are strings with path ID's and Name's separated by ":"
	 * 						like array('id'=>'293:14969:14970:39782', 'name'=>'Electronics:Home Audio:Amplifiers:Power Amplifiers'); 
	 */
	function fillCategoryQuickTree($arr) {
		$arrCat = array(
				'CategoryLevel'=>sizeof($arr['id']),
				'CategoryID'=>array_pop($arr['id']),
				'CategoryName'=>array_pop($arr['name']),
				'CategoryNamePath'=>implode(':',$arr['name']),
				'CategoryIDPath'=>implode(':',$arr['id']),
				'CategoryParentID'=>$arr['id'][sizeof($arr['id'])-1]
			);
		if(sizeof($arr['id'])>0) {
			$arrCat['parent'] = $this->fillCategoryQuickTree($arr);
		}
		return $arrCat;
	}
	
	/**
	 * getCategoryRelation() method try to fetch store category ID related to requested eBay one.s
	 * 
	 * @param integer $id eBay category ID
	 * @return integer related store category ID or false in other case 
	 */
	function getCategoryRelation($id) {
		if((int)($resId = $this->_tl2storeCats[$id])<=0) {
			$sql = 'SELECT categories_id FROM ' . TABLE_TL2_CATEGORIES . ' WHERE ebay_category2=\''.$id.'\'';
			$resId = tep_db_fetch_array(tep_db_query($sql));
			$resId = $resId['categories_id'];
			if((int)$resId<=0) {
				$sql = 'SELECT categories_id FROM ' . TABLE_TL2_CATEGORIES . ' WHERE ebay_category=\''.$id.'\' AND (ebay_category2 IS NULL OR ebay_category2=\'\')';
				$resId = tep_db_fetch_array(tep_db_query($sql));
				$resId = $resId['categories_id'];
				if((int)$resId>0) {
					$this->setCategoryRelation($id, $resId);
				}
			} else {
				$this->setCategoryRelation($id, $resId);
			}
		}
		return $resId;
	}
	
	/**
	 * setCategoryRelation() method place eBay to local category relation into array used to increase script speed
	 * 
	 * @param integer $tl2id eBay category ID
	 * @param integer $catid store category ID 
	 */
	function setCategoryRelation($tl2id, $catid) {
		$this->_tl2storeCats[$tl2id] = $catid;
		if(sizeof($this->_tl2storeCats[$tl2id])>1000) {
			array_shift($this->_tl2storeCats);
		}
		return $this;
	}
	
	/**
	 * saveCategories() method for recreated eBay categories tree in local store and place relations between them.
	 * 
	 * @param array eBay categories
	 * @return integer store category ID
	 */
	function saveCategories($arrCat) {
		$pID = $cID = $this->getTopCategory();
//		foreach($arrCats as $k=>$v) {
			$cID = $this->getCategoryRelation($arrCat['CategoryID']);
			if((int)$cID>0) return $cID;
			
			if(isset($arrCat['parent']) && is_array($arrCat['parent'])) {
				$pID = $this->saveCategories($arrCat['parent']);
			}
			$sql="select t2.categories_id, t1.parent_id from ".TABLE_CATEGORIES_DESCRIPTION." t2 left join ".TABLE_CATEGORIES ." t1 on (t1.categories_id=t2.categories_id)" .
					" where categories_name=CONVERT(\"".tep_db_input($arrCat['CategoryName'])."\" USING latin1)";
			$query = tep_db_query($sql);
			while($row = tep_db_fetch_array($query)) {
				$selid = $row['categories_id'];
				$seltopid = $this->getLocalTopCategory($selid);
				if($seltopid == $this->getTopCategory()) {
					$cID = $selid;
					$arrSql = array(
						'categories_id'=>$cID
					);
					if($arrCat['parent']) {
						$arrSql['ebay_category2']=$arrCat['CategoryID'];
						$arrSql['ebay_category2_name']=$arrCat['CategoryName'];
						$arrSql['ebay_category']=$arrCat['parent']['CategoryID'];
						$arrSql['ebay_category_name']=$arrCat['parent']['CategoryName'];
					} else {
						$arrSql['ebay_category']=$arrCat['CategoryID'];
						$arrSql['ebay_category_name']=$arrCat['CategoryName'];
					}
					tep_db_perform(TABLE_TL2_CATEGORIES, $arrSql);
					$this->setCategoryRelation($arrCat['CategoryID'], $cID);
					return $cID;
				}
			}
			$arrSql = array(
					'parent_id'=>$pID,
					'date_added'=>'now()',
					'last_modified'=>'now()',
					'categories_status'=>1
				);
			if(tep_db_perform(TABLE_CATEGORIES, $arrSql)) {
				$cID = tep_db_insert_id();
				$arrLangs = tep_get_languages();
				foreach($arrLangs as $x=>$y) {
					$arrSql = array(
							'categories_id'=>$cID,
							'categories_name'=>$arrCat['CategoryName'],
							'language_id'=>$y['id']
						);
					tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $arrSql);
				}
				$arrSql = array(
						'categories_id'=>$cID
					);
				if($arrCat['parent']) {
					$arrSql['ebay_category2']=$arrCat['CategoryID'];
					$arrSql['ebay_category2_name']=$arrCat['CategoryName'];
					$arrSql['ebay_category']=$arrCat['parent']['CategoryID'];
					$arrSql['ebay_category_name']=$arrCat['parent']['CategoryName'];
				} else {
					$arrSql['ebay_category']=$arrCat['CategoryID'];
					$arrSql['ebay_category_name']=$arrCat['CategoryName'];
				}
				tep_db_perform(TABLE_TL2_CATEGORIES, $arrSql);
				$this->setCategoryRelation($arrCat['CategoryID'], $cID);
			}
//		}
		return $cID;
	}
	function getLocalTopCategory($id) {
		$res = $id;
		$sql = 'SELECT categories_id, parent_id FROM categories WHERE categories_id=\''.$id.'\'';
		$row = tep_db_fetch_array(tep_db_query($sql));
		if((int)$row['parent_id']>0) {
			$res = $this->getLocalTopCategory($row['parent_id']);
		}
		return $res;
	}
	function getCategories(&$categories_ids,&$categories_names)
	{
		$categories_ids=array();
		$categories_names=array();
		$cat_query = tep_db_query("select t1.categories_id,categories_name " .
				"from ".TABLE_CATEGORIES." t1 left join ".TABLE_CATEGORIES_DESCRIPTION." t2 on (t1.categories_id = t2.categories_id) " .
				"where t1.categories_status_pc=1 AND t1.categories_status=1 AND t2.language_id=".$GLOBALS['languages_id']." order by sort_order, categories_name");
		array_push($categories_ids,0);
		array_push($categories_names,TEXT_TURBOLISTER2_MAIN_CATEGORY);
		while (tep_not_null($cat=tep_db_fetch_array($cat_query)))
		{
			array_push($categories_ids,$cat['categories_id']);
			array_push($categories_names,$cat['categories_name']);
		}
	}
	function getCategoriesNames()
	{
		$cat_query = tep_db_query("select t1.categories_id,categories_name " .
				"from ".TABLE_CATEGORIES." t1 left join ".TABLE_CATEGORIES_DESCRIPTION." t2 on (t1.categories_id = t2.categories_id) " .
				"where t1.categories_status_pc=1 AND t1.categories_status=1 AND t2.language_id=".$GLOBALS['languages_id']." order by sort_order, categories_name");
		$categories=array(TEXT_TURBOLISTER2_MAIN_CATEGORY);
		while (tep_not_null($cat=tep_db_fetch_array($cat_query)))
			array_push($categories,$cat['categories_name']);
		return $categories;
	}
	// @descr Restituisce l'albero delle categorie
	function getCategoriesTree(&$tree,&$numnodes,$parent_id,&$level,&$export_categories)
	{
		if (!is_array($tree))	{
			$tree=array(array(
				'id' => 0,
				'name'=>TEXT_TURBOLISTER2_MAIN_CATEGORY,
				'nodename' => "export_categories[0]",
				'parent' => -1,
				'level' => $level,
				'subfolders'=>0,
				'selected'=>(in_array($catid, $export_categories))
			));
			$level++;
			$this->getCategoriesTree(&$tree,&$tree[sizeof($tree)-1]['subfolders'],0,&$level,&$export_categories);
			--$level;
			return;
		}
		$categories_query = tep_db_query("select t1.categories_id, categories_name, parent_id " .
				"from " . TABLE_CATEGORIES . " t1 left join " . TABLE_CATEGORIES_DESCRIPTION . " t2 on (t1.categories_id=t2.categories_id) " .
				"where t1.categories_status_pc=1 AND t1.categories_status=1 AND t1.parent_id=$parent_id and t2.language_id=".$GLOBALS['languages_id']." order by sort_order, categories_name");
		while ($categories = tep_db_fetch_array($categories_query))  {
			$catid = $categories['categories_id'];
			$numnodes++;
			$thisnode = array(
				'id' => $catid,
				'name' => $categories['categories_name'],
				'nodename' => "export_categories[$catid]",
				'parent' => $categories['parent_id'],
				'level' => $level,
				'subfolders'=>0,
				'selected'=>(in_array($catid, $export_categories))
			);
			array_push($tree, $thisnode);
			$level++;
			$this->getCategoriesTree(&$tree,&$tree[sizeof($tree)-1]['subfolders'],$catid,&$level,&$export_categories);
			--$level;
		}
	}
	// @descr Restituisce l'albero delle categorie
	function getCategoriesTreeLocal(&$tree,$parent_id,&$level)
	{
		if (!is_array($tree))
			$tree=array();
		$categories_query = tep_db_query("select t1.categories_id, categories_name, parent_id from " . TABLE_CATEGORIES . " t1 left join " . TABLE_CATEGORIES_DESCRIPTION . " t2 on (t1.categories_id=t2.categories_id) where t1.parent_id=$parent_id and t2.language_id=".$GLOBALS['languages_id']." order by sort_order, categories_name");
		while ($categories = tep_db_fetch_array($categories_query))  {
			$catid = $categories['categories_id'];
			$thisnode = array(
				'id' => $catid,
				'name' => $categories['categories_name'],
				'parent' => $categories['parent_id'],
				'level' => $level,
				'subfolders'=>0
			);
			array_push($tree, $thisnode);
			$level++;
			$this->getCategoriesTreeLocal(&$tree,$catid,&$level);
			--$level;
		}
	}
	function getCategoriesLocal()
	{
		static $categories_tree;
		if (!is_array($categories_tree)){
			$parent_id=0;
			$level=0;
			$this->getCategoriesTreeLocal($categories_tree,$parent_id,&$level);
			$categories=array();
			//print_r($categories_tree);exit();
			for($i=0;$i<sizeof($categories_tree);$i++)
			{
				$name=$categories_tree[$i]['name'];
				for($l=0;$l<$categories_tree[$i]['level'];$l++)
					$name='. '.$name;
				$categories[]=array('id'=>$categories_tree[$i]['id']
									,'text'=>$name);
			}
			array_unshift($categories,array('id'=>-1,'text'=>STRING_TL2_SELECT));
			$categories_tree=$categories;
		}
		return $categories_tree;
	}
	////////////////////////////////////////////////////////////////////
	// General Functions
	////////////////////////////////////////////////////////////////////
	//	@function imkdir
	//	@desc	Crea, se non esiste, ogni directory nel path passato come parametro
	//	@param	$path
	function	imkdir($path)
	{
		if (strlen( $path) == 0) {
		return false;
		}
		//
		if ( strlen( $path) < 3) {
			return true; // avoid 'xyz:\' problem.
		}
		elseif (is_dir($path)) {
			return true; // avoid 'xyz:\' problem.
		}
		elseif   ( dirname( $path) == $path) {
			return true; // avoid 'xyz:\' problem.
		}
		
		return ( $this->imkdir( dirname( $path)) && mkdir( $path, 0775));
	}
	function formatLocation(&$location)
	{
		$location=tep_db_prepare_input(preg_replace("[:|~]","",str_replace("\\","/",$location)));
		if (substr($location,-1)!='/')
			$location .= '/';
		if (substr($location,0,1)!='/')
			$location = '/'.$location;
	}
	function tep_get_parent_categories(&$categories, $categories_id) {
		$parent_categories_query = tep_db_query("select parent_id from " . TABLE_CATEGORIES . " where categories_id = '" . (int)$categories_id. "'");
		while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
			if ($parent_categories['parent_id'] == 0) return true;
			$categories[sizeof($categories)] = $parent_categories['parent_id'];
			if ($parent_categories['parent_id'] != $categories_id) {
				$this->tep_get_parent_categories($categories, $parent_categories['parent_id']);
			}
		}
	}
	function tep_get_parent_categories_names(&$categories, $categories_id) {
		$parent_categories_query = tep_db_query("select parent_id, categories_name from " . TABLE_CATEGORIES . " t1 left join " . TABLE_CATEGORIES_DESCRIPTION. " t2 on (t1.categories_id=t2.categories_id) where t1.categories_id=$categories_id and t2.language_id=".$GLOBALS['languages_id']);
		while ($parent_categories = tep_db_fetch_array($parent_categories_query)) {
			$categories[] = $parent_categories['categories_name'];
			if ($parent_categories['parent_id'] == 0) return true;
			if ($parent_categories['parent_id'] != $categories_id) {
				$this->tep_get_parent_categories_names($categories, $parent_categories['parent_id']);
			}
		}
	}
	function tep_get_product_path($products_id) {
		$cPath = '';
	
		$category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
		if (tep_db_num_rows($category_query)) {
			$category = tep_db_fetch_array($category_query);
	
			$categories = array();
			$this->tep_get_parent_categories($categories, $category['categories_id']);
			
			$categories = array_reverse($categories);
			
			$cPath = implode('_', $categories);
			
			if (tep_not_null($cPath)) $cPath .= '_';
			$cPath .= $category['categories_id'];
		}
	
		return $cPath;
	}
	function get_product_categories_names($products_id, $fullpath=false)
	{
		$category_query = tep_db_query("select p2c.categories_id from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c where p.products_id = '" . (int)$products_id . "' and p.products_status = '1' and p.products_id = p2c.products_id limit 1");
		if (0==tep_db_num_rows($category_query))
			return '';
		$category = tep_db_fetch_array($category_query);
		$catnames = array();
		$this->tep_get_parent_categories_names($catnames, $category['categories_id']);
		return $fullpath ? implode($this->category_separator, array_reverse($catnames)) : array_pop($catnames);
				
	}
	function insertCategory($categories_name)	{
		$current_category_id=0;
// ####################### Added Categories Enable / Disable ###############
//        $sql_data_array = array('sort_order' => $sort_order);
        $sql_data_array = array('sort_order' => 1, 'categories_status' => 1);
// ####################### End Added Categories Enable / Disable ###############

         $insert_sql_data = array('parent_id' => $current_category_id,
                                  'date_added' => 'now()');

         $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

         tep_db_perform(TABLE_CATEGORIES, $sql_data_array);

         $categories_id = tep_db_insert_id();

        $languages = tep_get_languages();
        for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
          $language_id = $languages[$i]['id'];

          $sql_data_array = array('categories_name' => $categories_name);

//          if ($action == 'insert_category') {
            $insert_sql_data = array('categories_id' => $categories_id,
                                     'language_id' => $languages[$i]['id']);

            $sql_data_array = array_merge($sql_data_array, $insert_sql_data);

            tep_db_perform(TABLE_CATEGORIES_DESCRIPTION, $sql_data_array);
        }
        tep_db_query("update " . TABLE_CATEGORIES . " set categories_image = 'logoEbay_150x70.gif' where categories_id = '" . (int)$categories_id . "'");

        if (USE_CACHE == 'true') {
          tep_reset_cache_block('categories');
          tep_reset_cache_block('also_purchased');
        }
	}
}
?>