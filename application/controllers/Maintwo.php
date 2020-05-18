<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Load all parser library*/
require "vendor/autoload.php";
use Sunra\PhpSimple\HtmlDomParser; // lib html parser
use stringEncode\Encode; // lib html parser
use PHPHtmlParser\Dom; // lib html parser
use FastSimpleHTMLDom\Document; // lib html parser
class Maintwo extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct(){
		parent::__construct();		
		$this->load->helper('url');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->library('form_validation');
		date_default_timezone_set('Asia/Jakarta'); // default time zone indonesia
	}
	
	public function index()
	{
		$query = "SELECT * FROM ph_scan ORDER BY scan_id DESC";
		$exec = $this->db->query($query);
		$result = $exec->result();

		$query2 = "SELECT * FROM ph_smart_features ORDER BY id DESC";
		$exec2 = $this->db->query($query2);
		$result2 = $exec2->result();

		$data['scan'] = $result;
		$data['features'] = $result2;
        $this->load->view('page_static/static_header', $data);
        $this->load->view('page_static/static_navbar', $data);
        $this->load->view('page_dynamic/dynamic_main', $data);
        $this->load->view('page_static/static_footer', $data);
	}
	

	public function scan(){
		$url = $this->input->post('urls');
		$ch = curl_init($url); 
		$dir = './assets/scanned/';
		// Use basename() function to return 
		// the base name of file  
		$file_name = basename($url.'.html'); 
		
		// Save file into file location 
		$save_file_loc = $dir . $file_name; 
		
		// Open file  
		$fp = fopen($save_file_loc, 'wb'); 
		
		// It set an option for a cURL transfer 
		curl_setopt($ch, CURLOPT_FILE, $fp); 
		curl_setopt($ch, CURLOPT_HEADER, 0); 
		
		// Perform a cURL session 
		curl_exec($ch); 

		// Closes a cURL session and frees all resources 
		curl_close($ch); 
		
		// Close file 
		fclose($fp);  
		$data = array(
			'dataset_url' => $url,
			'dataset_html_file' => $file_name
		);

		$sql = $this->db->insert('ph_scan', $data);
		if($this->db->affected_rows() > 0){
			$id = $this->db->insert_id();
			$file_path = './assets/scanned/'.$file_name;
			// $feature_data = array(
			// 	"scan_id" => $id,
			// 	"url_link"=> "".$url,
			// 	"url_protocol" => "".$this->read_url_protocol($url),
			// 	"url_favicon" => "".$this->read_html_favicon($file_path),
			// 	"url_standard_port" => "".$this->read_url_port($url),
			// 	"url_symbol" => "".$this->read_url_symbol($url),
			// 	"url_subdomain"=> "".$this->read_url_subdomain($url),
			// 	"url_length" => "".$this->read_url_length($url),
			// 	"url_dot_total" => "".$this->read_url_dot_total($url),
			// 	"url_sensitive_char" => "".$this->read_special_char($url),
			// 	"html_login" => "".$this->read_html_login($file_path),
			// 	"html_empty_link" => "".$this->read_html_empty_link($file_path),
			// 	"html_length" => "".$this->read_html_filesize($file_path),
			// 	"html_is_consist" => "".$this->read_consistency($file_path, $url),
			// 	"html_js_list" => "".$this->read_html_enabled_js($file_path),
			// 	"html_link_external_list" => "".$this->read_html_external_link($file_path),
			// 	"html_redirect" => "".$this->read_html_redirect($file_path),
			// 	"html_iframe" => "".$this->read_html_iframe($file_path),
			// 	"html_favicon" => "".$this->read_html_favicon($file_path),
			// 	"feature_type" => "0" // ini bukan fitur ini flag 

			// );	
			// $this->db->insert('ph_features', $feature_data);
			// echo 'ok';

			$feature_data = array(
				"scan_id" => $id,
				"features_a" => "".$this->features_one($url),
				"features_b" => "".$this->features_two($url),
				"features_c" => "".$this->features_three($url),
				"features_d" => "".$this->features_four($url),
				"features_e" => "".$this->features_five($url),
				"features_f" => "".$this->features_six($url, $file_path),
				"features_g" => "".$this->features_seven($url),
				"features_h" => "".$this->features_eight($url),

			);
			$this->db->insert('ph_smart_features', $feature_data);
			echo 'ok';
		}

	}

	/*	+++ DONE ++ 
		FITUR PERTAMA
		http + IP Address + URL panjang + @
	*/
	public function features_one($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_ipaddress($uri);
		$c = $this->cek_panjanguri($uri);
		$d = $this->cek_symbols($uri, '@');

		if(!$a || $b || $c || $d){
			return 1;
		}else{
			return 0;
		}
	}

	/*
		+++ DONE ++ 
		FITUR KEDUA
		http + hosting murah/gratisan + kata sensitif  
	*/
	public function features_two($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_domainmurah($uri);
		$c = $this->cek_symbols($uri, '@');

		if(!$a || $b || $c){
			return 1;
		}else{
			return 0;
		}
	}


	/*
		+++ DONE ++ 
		FITUR KETIGA
		http + jumlah dot +(?)
	*/

	public function features_three($uri){
		$a = $this->cek_http($uri);
		$b= $this->cek_jumlahdot($uri);
		$c = $this->cek_symbols($uri, '@');

		if(!$a || $b || $c){
			return 1;
		}else{
			return 0;
		}

	}


	/*
		+++ DONE ++ 
		FITUR KEEMPAT
		http + double top domain
	*/

	public function features_four($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_doubletopdomain($uri);

		if(!$a || $b){
			return 1;
		}else{
			return 0;
		}
	}


	/*
		+++ DONE ++ 
		FITUR KELIMA
		http + banyak path + URL Panjang
	*/

	public function features_five($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_jumlahpath($uri);
		$c = $this->cek_panjanguri($uri);

		if(!$a || $b || $c){
			return 1;
		}else{
			return 0;
		}
	}

	
	/*
		FITUR KEENAM
		form + kata sensitif
	*/

	public function features_six( $uri, $file){
		// cekapabil terdapata elemen form / action dan method dalam page 
		// markup atau html
		$a = $this->cek_formcredential($file);
		$b = $this->cek_sensitiveinfo($uri);
		if($a || $b){
			return 1;
		}else{
			return 0;
		}
	}

	/*
		+++ DONE ++ 
		FITUR KETUJUH
		FAKE DOMAIN + LOGIN
	*/

	public function features_seven($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_panjanguri($uri);
		$c = $this->cek_jumlahpath($uri);
		if(!$a || $b || $c){
			return 1;
		}else{
			return 0;
		}
	}


	/*
		++ DONE ++
		FITUR KEDELAPAN
		shortlink + kata sensitive
	*/

	public function features_eight($uri){
		$a = $this->cek_sensitiveinfo($uri);
		$b = $this->cek_shortlink($uri);

		if($a || $b){
			return 1;
		}else{
			return 0;
		}
	}


	/*	++ DONE ++
		FITUR KESEMBILAN
		http + (-) + URL Panjang
	*/

	public function features_nine($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_panjanguri($uri);
		$c = $this->cek_symbols($uri, '-');

		if(!$a || $b || $c){
			return 1;
		}else{
			return 0;
		}
	}



	/*
		++ DONE ++
		FITUR KESEPULUH
		http + url Panjang + file terindikasi malware
	*/

	public function features_ten($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_panjanguri($uri);

		if(!$a || $b){
			return 1;
		}else{
			return 0;
		}
	}



	/*	Buat daftar modul berikut biar reusable
		1. cek http (done)
		2. no hostname / ip address (done)
		3. url panjang > 75 (done)
		4. domain/hosting murah (done)
		5. fake login
		6. login (done)
		7. double top domain (done)
		8. jumlah path terlalu banyak (done)
		9. fake domain name
		10. jumlah dot (done)
		11. login redirect

		SEMUA FUNGSI INI MENGEMBALIKAN BOOL true / false 
	 */


	// 1
	public function cek_http($uri){
		/*
		fungsi
		*/
		$result = false;
		$check_protocol =  parse_url($uri, PHP_URL_SCHEME);
		if($check_protocol == 'https'){
			$result = true;
		}

		return $result;
	}


	// 2
	public function cek_ipaddress($uri){
		$result = false;
		$parsed_url = parse_url($uri, PHP_URL_HOST);
		$is_contain_number = is_numeric($parsed_url);
		if($is_contain_number){
			$result = true;
		}

		return $result;
	}


	// 3
	public function cek_panjanguri($uri){
		$result = false;
		$check = strlen($uri);
		if($check > 75){
			$result = true;
		}

		return $result;
	}


	// 4
	public function cek_domainmurah($uri){
		// cek daftar tld dengan harga sewa yang murah
		$array = array("tech","online", "xyz", "red", "blue", "domain", "my.id", "webiste");
		$contains = $this->contains($uri, $array);
		if($contains){
			return true;
		}else{	
			return false;
		}
	}


	// 5
	public function cek_fakelogin($file){
		$dom = new Dom;
		$dom->setOptions([
			'cleanupInput' => false,
			'removeScripts' => true,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$a = $dom->find('a');
		$found = false;
		foreach($a as $links){
			$get_value = $links->getAttribute('href');
			$word1 = 'login';
			$word2 = 'signin';
			$word3 = "Sign";
			if(strpos($get_value, $word1) !== false || strpos($get_value, $word2) !== false || strpos($get_value, $word3) !== false){
				$found = true;
			} else{
				$found = false;
			}
		}
		if($found){
			return true;
		}else{
			return false;
		}
	}


	// 6
	public function cek_login($file){
		$dom = new Dom;
		$dom->setOptions([
			'cleanupInput' => false,
			'removeScripts' => true,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$a = $dom->find('a');
		$found = false;
		foreach($a as $links){
			$get_value = $links->getAttribute('href');
			$word1 = 'login';
			$word2 = 'signin';
			$word3 = "Sign";
			if(strpos($get_value, $word1) !== false || strpos($get_value, $word2) !== false || strpos($get_value, $word3) !== false){
				$found = true;
			} else{
				$found = false;
			}
		}
		if($found){
			return true;
		}else{
			return false;
		}
	}

	// 7
	public function cek_doubletopdomain($uri){
		/*
			cek jika top level domain memiliki
			format sebagai berikut .com.co
		*/
		// cek daftar tld dengan harga sewa yang murah
		$array1 = array(".com.com",".com.net", ".com.id", ".com.us", ".com.org", ".com.info", ".com.online", ".com.jp",".com.cn",".com.xyz",".com.co");
		$array2 = array(".net.com",".net.net", ".net.id", ".net.us", ".net.org", ".net.info", ".net.online", ".net.jp",".net.cn",".net.xyz",".net.co");
		$array3 = array(".id.com",".id.net", ".id.id", ".id.us", ".id.org", ".id.info", ".id.online", ".id.jp",".id.cn",".id.xyz",".id.co");
		$array4 = array(".us.com",".us.net", ".us.id", ".us.us", ".us.org", ".us.info", ".us.online", ".us.jp",".us.cn",".us.xyz",".us.co");
		$array5 = array(".org.com",".org.net", ".org.id", ".org.us", ".org.org", ".org.info", ".org.online", ".org.jp",".org.cn",".org.xyz",".org.co");
		$array6 = array(".info.com",".info.net", ".info.id", ".info.us", ".info.org", ".info.info", ".info.online", ".info.jp",".info.cn",".info.xyz",".info.co");
		$array7 = array(".online.com",".online.net", ".online.id", ".online.us", ".online.org", ".online.info", ".online.online", ".online.jp",".online.cn",".online.xyz",".online.co");
		$array8 = array(".jp.com",".jp.net", ".jp.id", ".jp.us", ".jp.org", ".jp.info", ".jp.online", ".jp.jp",".jp.cn",".jp.xyz",".jp.co");
		$array9 = array(".cn.com",".cn.net", ".cn.id", ".cn.us", ".cn.org", ".cn.info", ".cn.online", ".cn.jp",".cn.cn",".cn.xyz",".cn.co");
		$array10 = array(".xyz.com",".xyz.net", ".xyz.id", ".xyz.us", ".xyz.org", ".xyz.info", ".xyz.online", ".xyz.jp",".xyz.cn",".xyz.xyz",".xyz.co");
		$array11 = array(".co.com",".co.net", ".co.id", ".co.us", ".co.org", ".co.info", ".co.online", ".co.jp",".co.cn",".co.xyz",".co.co");

		$array_all = array_merge($array1,$array2,$array3,$array4,$array5,$array6,$array7,$array8,$array9,$array10,$array11);
		$contains = $this->contains($uri, $array_all);
		if($contains){
			return true;
		}else{	
			return false;
		}
	}


	public function cek_shortlink($uri){
		$array_of_shortlinkurl = array('bit.ly', 'tinyurl', 'rebrandly', 'ink', 'yourls');
		$contains = $this->contains($uri, $array_of_shortlinkurl);
		if($contains){
			return true;
		}else{
			return false;
		}
	}



	public function cek_sensitiveinfo($uri){
		$array_of_sensitive_char = array('login', 'auth', 'signin', 'signin', 'yourls');
		$contains = $this->contains($uri, $array_of_sensitive_char);
		if($contains){
			return true;
		}else{
			return false;
		}
	}

	// 8
	public function cek_jumlahpath($uri){
		$split = explode ("/", $uri);
		$pathtotal = sizeof($split);
		if($pathtotal > 3){
			return true;
		}else{
			return false;
		}
	}

	//9
	public function cek_jumlahdot($uri){
		$split = explode (".", $uri);
		$pathtotal = sizeof($split);
		if($pathtotal > 3){
			return true;
		}else{
			return false;
		}
	}

	// 10
	public function cek_formcredential($file){
		$dom = new Dom;
		$dom->setOptions([
			'cleanupInput' => false,
			'removeScripts' => true,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$a = $dom->find('form');
		$found = false;
		foreach($a as $links){
			$get_value = $links->getAttribute('action');
			if($get_value == "refresh"){
				return true;
			}else{
				return false;
			}
		}
	}

	// 11 check if there is a 
	public function cek_symbols($uri, $condition){
		$cek = $this->str_contains($uri, $condition);
		return $cek;
	}

	public function test(){
		$domain = $this->input->get('domain', TRUE);
		$check_http = $this->cek_http($domain);
		$cek_ipaddress = $this->cek_ipaddress($domain);
		$cek_panjanguri = $this->cek_panjanguri($domain);
		$cek_doubletopdomain = $this->cek_doubletopdomain($domain);
		$cek_domainmurah = $this->cek_domainmurah($domain);
		$cek_doubletopdomain = $this->cek_doubletopdomain($domain);
		$result = array(
			'domain' => $domain,
			'cekipaddress' => $cek_ipaddress,
			'cek_panjanguri' => $cek_panjanguri,
			'checkhttp' => $check_http,
			'cek_doubletopdomain' => $cek_doubletopdomain,
			'cek_domainmurah' => $cek_domainmurah	
		);
		echo json_encode($result);
	}
	

	

	// outter functions
	function contains($str, array $arr)
	{
		foreach($arr as $a) {
			if (stripos($str,$a) !== false) return true;
		}
		return false;
	}

	function str_contains($str, $condition){
		if (strpos($str, $condition) !== false) {
			return true;
		}else{
			false;
		}
	}


}
