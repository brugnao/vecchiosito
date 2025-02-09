<?

/**
 * image_bestshopping_php5.class.php, pointerCore Documentation
 *
 * This implements the image_bestshopping_php5.class methods for use in the
 * bestshopping checkout tracker procedure and PHP5 and above
 * @author Alberto Roli <alberto.roli@bestshopping.com>
 * @copyright Copyright (c) 2007, Pointer S.r.l.
 * @version v1.2 - 06/11/2007
 * @package pointerCore
 */

// da non modificare in alcun modo
define('IMAGE_URL', 'http://tracker.bestshopping.com/save_checkout.php');
define('MAX_URL_LENGTH', 2048);
define('DATA_KEY', 'b1bf957bd1e598fa860d9a634f15eeac');

/**
 * class OB_image_bestshopping
 * @package pointerCore
 * @brief classe per la creazione di un immagine per il passaggio di dati di acquisto
 *         verso bestshopping
 */
class OB_image_bestshopping {
	private $transaction_id;      /**< @brief id della transazione di bestshopping */
	private $order_id;            /**< @brief id dell'acquisto dell'utente (ideentificativo ordine),
                                           serve per avere un dato univoco per non salvare duplicati */
	private $shipping_cost;       /**< @brief spese di spedizione */
	private $list_item;           /**< @brief array contenente gli item comprati dallo user */

    /**
     * Constructor to initialize data.
     */
    function __construct() {

        $this->transaction_id = 0;
        $this->order_id       = 0;
        $this->shipping_cost  = 0;
        $this->list_item      = array();
    }

	/**
	@private
	@brief Restituisce se il cookie passato come parametro è stato settato
	@param $cookie_name il nome del cookie da verificare
	@return true | false
	*/
	private function IsCookieSet($cookie_name) {
		if (isset($_COOKIE) && isset($_COOKIE[$cookie_name]) && !empty($_COOKIE[$cookie_name])) {
			return true;
		} else {
			return false;
		}
	}

	/**
	@public
	@brief Prende il cookie dello user e lo inserisce in questo oggetto
	@return true | false
	*/
	public function SetTransactionId() {
		if (!$this->IsCookieSet('tid_bs')) {
			return false;
        }
		$this->transaction_id = $_COOKIE['tid_bs'];
		return true;
	}

	/**
	@public
	@brief Inserisce nel'oggetto l'id della transazione del partner
	@return true | false
	*/
	public function SetOrderId($order_id = null) {
        $this->order_id = $order_id;
	}

	/**
	@public
	@brief Inserisce nel'oggetto il costo delle spese di spedizioni

    Il divisore per i decimali deve essere un punto, non la virgola
	Il numero viene approssimato alla seconda cifra decimale
	*/
	public function SetShippingCost($shipping_cost = null) {
        $shipping_cost = str_replace(',', '.', $shipping_cost);
        $shipping_cost = round($shipping_cost, 2);
        $this->shipping_cost = $shipping_cost;
	}

	/**
	@public
	@brief Aggiunge un oggetto item all'array $this->list_item
	@param $item oggetto da aggiungere
	@return true | false
	*/
	public function AppendItem($item) {
		if (!is_null($item) && is_a($item, 'OB_item_bestshopping')) {
			$this->list_item[] = $item;
			$item->delete();
		} else {
            return false;
        }
        return true;
	}

	/**
	@public
	@brief Metodo che restituisce l'html dell'immagine che serve per tener traccia degli utenti provenienti da bestshopping e dei loro acquisti
	@return la stringa dell'immagine o stringa vuota
	*/
	public function WriteImage() {
		
        $result = '';
		if (!$this->SetTransactionId()) {
            return $result;
        }

		$url_img = '';
		$items = array();
		for ($i=0; $i < count($this->list_item); $i++) {
            $items[$i] = '';
            foreach (get_object_vars($this->list_item[$i]) as $name => $value) {
				$items[$i] .= '&'. $name .'['. $i .']='. $this->Encrypt($value);
			}
		}
        // writing url image, if url > MAX_URL_LENGTH then write multiple url images
        //  each not exceding the MAX_URL_LENGTH
        $end = false;
        $i = 0;
        do {
            $url_img  = IMAGE_URL .'?tid_bs='. $this->transaction_id .'&';
		    $url_img .= 'tr='.  $this->Encrypt($this->order_id) .'&';
		    $url_img .= 'sc='.  $this->Encrypt($this->shipping_cost);
            while (! $end) {
                if ($i < count($items)) {
                    if ((MAX_URL_LENGTH - strlen($url_img)) >= strlen($items[$i])) {
                        $url_img .= $items[$i++];
                    } else {
                        break;
                    }
                } else {
                    $end = true;
                }
            }
            $result .= '<img src="'. $url_img .'" width="1" height="1" border="0" />';
        } while (! $end);
		return $result;
	}

	/**
	@public
	@brief Cifra il dato e appone un CRC
    @param $data data to crypt
	@return crypted data
	*/
    public function Encrypt($data) {
        // ecrypting data
        return OB_CryptXor::Encrypt($this->GetCRC($data) . $data, DATA_KEY);
    }

	/**
	@public
	@brief Controlla e decifra il dato
    @param $data data to decrypt
	@return decripted data o false if error
	*/
    public function Decrypt($crypt_data) {
        $data = OB_CryptXor::Decrypt($crypt_data, DATA_KEY);
        // checking for CRC & returning result
        return (substr($data, 0, 8) == $this->GetCRC(substr($data, 8))) ? substr($data, 8) : false;
    }

	/**
	@private
	@brief Genera e restituisce il CRC di un dato
    @param $data data to generate CRC from
	@return CRC of data
	*/
    private function GetCRC($data) {
        return sprintf("%08x", crc32($data));
    }
}


/**
 * class OB_item_bestshopping
 * @package pointerCore
 * @brief classe per la creazione di un prodotto acquistato da passare a bestshopping
 *         quest'oggetto viene distrutto una volta che viene passato all'oggetto
 *         image_bestshopping quindi deve essere reinizializzato per ogni prodotto
 */
class OB_item_bestshopping {
	public $id;       /**< @brief id del prodotto */
	public $pr;       /**< @brief prezzo del prodotto */
	public $qt;       /**< @brief quantità di prodotti uguali acquistati */

    /**
     * Constructor to initialize data.
     */
    function __construct() {

        $this->id = 0;
        $this->pr = 0;
        $this->qt = 0;
    }

	/**
	@public
	@brief Inserisce l'id del prodotto nell'oggetto
	@param $item_id L'id del prodotto
	*/
	public function SetId($item_id = null) {
        $this->id = $item_id;
	}

	/**
	@public
	@brief Inserisce il prezzo del prodotto nell'oggetto
	@param $item_price Il prezzo del prodotto

	Il divisore per i decimali deve essere un punto, non la virgola
    Il numero viene approssimato alla seconda cifra decimale
	*/
	public function SetPrice($item_price = null) {
        $item_price = str_replace(',', '.', $item_price);
        $item_price = round($item_price, 2);
        $this->pr = $item_price;
	}

	/**
	@public
	@brief Inserisce la quantità di prodotti acquistati nell'oggetto
	@param $item_quantity La quantità del prodotto
	*/
	public function SetQuantity($item_quantity = null) {
        $this->qt = $item_quantity;
	}

	/**
	@public
	@brief Restituisce l'id del prodotto nell'oggetto
	@return L'id del prodotto
	*/
	public function GetId() {
        return $this->id;
	}

	/**
	@public
	@brief Restituisce il prezzo del prodotto nell'oggetto
    @return Il prezzo del prodotto
	*/
	public function GetPrice() {
        return $this->pr;
	}

	/**
	@public
	@brief Restituisce la quantità di prodotti acquistati nell'oggetto
    @return La quantità del prodotto
	*/
	public function GetQuantity() {
        return $this->qt;
	}

	/**
	@public
	@brief metodo che canceWriteImage
	@see OB_image_bestshopping::WriteImage

	Serve per non avere campi duplicati una volta che viene inserito nell'array OB_image_bestshopping::list_item
	*/
	public function Delete() {
		unset($this);
	}
}


/**
 * class OB_CryptXor
 * @package pointerCore
 * @brief classe per la cifrature dei dati, l'algortimo è semplice e serve per
 *         garantire una certa sicurezza sui dati passati dal merchant da utenti
 *         ritenuti normali e quindi non in grado di decifrare il codice e
 *         replicare/generare invii di ordini fasulli
 */
class OB_CryptXor {

	/**
	@public
	@brief metodo che cifra i dati
    @param $str il dato da cifrare
    @param $key la chiave da usare per cifrare il dato
    @return il dato cifrato
	*/
    public static function Encrypt($str, $key) {

        $s = str_split($str);
        while (strlen($str) > strlen($key)) {
            $key .= $key;
        }
        $key = substr($key, 0, strlen($str));
        $k = str_split($key);
        $c = array();
        for ($i=0; $i < count($s); $i++) {
            $c[$i] = chr(ord($s[$i]) ^ ord($k[$i]));
        }
        return bin2hex(implode("", $c));
    }

	/**
	@public
	@brief metodo che decifra i dati
    @param $str il dato da decifrare
    @param $key la chiave da usare per decifrare il dato
    @return il dato decifrato
	*/
    public static function Decrypt($str, $key) {

        $str = pack("H*", $str);
        $s = str_split($str);
        while (strlen($str) > strlen($key)) {
            $key .= $key;
        }
        $key = substr($key, 0, strlen($str));
        $k = str_split($key);
        $d = array();
        for ($i=0; $i < count($s); $i++) {
            $d[$i] = chr(ord($s[$i]) ^ ord($k[$i]));
        }
        return implode("", $d);
    }
}

?>