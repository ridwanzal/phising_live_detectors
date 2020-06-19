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

		$query3 = "SELECT * FROM ph_features ORDER BY feature_id DESC";
		$exec3 = $this->db->query($query3);
		$result3 = $exec3->result();

		$data['scan'] = $result;
		$data['smart_features'] = $result2;
		$data['features'] = $result3;
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
			$feature_data = array(
				"scan_id" => $id,
				"url_link"=> "".$url,
				"url_protocol" => "".$this->read_url_protocol($url),
				"url_symbol" => "".$this->cek_symbols($url),
				"url_length" => "".$this->cek_panjanguri($url),
				"url_dot_total" => "".$this->cek_jumlahdot($url),
				"url_sensitive_char" => "".$this->read_special_char($url),
				"html_login" => "".$this->cek_login($file_path),
				"html_empty_link" => "".$this->read_html_empty_link($file_path),
				"html_length" => "".$this->read_html_filesize($file_path),
				"html_redirect" => "".$this->read_html_redirect($file_path),
				"html_iframe" => "".$this->read_html_iframe($file_path),
				"html_favicon" => "".$this->read_html_favicon($file_path),
				"url_doubletopdomain" => "".$this->cek_doubletopdomain($file_path),
				"url_shortlink" => "".$this->cek_shortlink($file_path),
				"url_domain_murah" => "".$this->cek_domainmurah($file_path),
				"url_totalpath" => "".$this->cek_jumlahpath($url)
			);	
			$this->db->insert('ph_features', $feature_data);

			// count rules
			$f_1 = $this->features_one($url);
			$f_2 = $this->features_two($url);
			$f_3 = $this->features_three($url);
			$f_4 = $this->features_four($url);
			$f_5 = $this->features_five($url);
			$f_6 = $this->features_six($url,$file_path);
			$f_7 = $this->features_seven($url);
			$f_8 = $this->features_eight($url);

			// count features
			$b_f1 = $this->read_url_protocol($url) == true ? 1 : 0;
			$b_f2 = $this->cek_symbols($url) == true ? 1 : 0;
			$b_f3 = $this->cek_panjanguri($url)  == true ? 1 : 0;
			$b_f4 = $this->cek_jumlahdot($url) == true ? 1 : 0;
			$b_f5 = $this->read_special_char($url)  == true ? 1 : 0;
			$b_f6 = $this->cek_login($file_path)  == true ? 1 : 0;
			$b_f7 = $this->read_html_empty_link($file_path)  == true ? 1 : 0;
			$b_f8 = $this->read_html_filesize($file_path)  == true ? 1 : 0;
			$b_f9 = $this->read_html_redirect($url)  == true ? 1 : 0;
			$b_f10 = $this->read_html_iframe($url)  == true ? 1 : 0;
			$b_f11 = $this->read_html_favicon($url)  == true ? 1 : 0;
			$b_f12 = $this->cek_doubletopdomain($url)  == true ? 1 : 0;
			$b_f13 = $this->cek_shortlink($url) == true ? 1 : 0;
			$b_f14 = $this->cek_domainmurah($url) == true ? 1 : 0 ;
			$b_f15 = $this->cek_jumlahpath($url) == true ? 1 : 0;


			// hitung berapa banyak fitur yang terdeteksi dan bernilai true (1)
			$arrayof_true = array($b_f1,$b_f2,$b_f3,$b_f4,$b_f5,$b_f6,$b_f7,$b_f8,$b_f9,$b_f10,$b_f11,$b_f12,$b_f13,$b_f14,$b_f15);
			$counts = array_count_values($arrayof_true);
			$count_true =  $counts[1];

			// echo $count_true;

			$f_9 = 0;
			$f_10 = 0;
			if($count_true >= 3){
				$f_9 = 1;
			}

			if($count_true >= 4){
				$f_10 = 1;
			}

			$feature_data2 = array(
				"scan_id" => $id,
				"features_a" => "".$f_1,
				"features_b" => "".$f_2,
				"features_c" => "".$f_3,
				"features_d" => "".$f_4,
				"features_e" => "".$f_5,
				"features_f" => "".$f_6,
				"features_g" => "".$f_7,
				"features_h" => "".$f_8,
				"features_i" => "".$f_9,
				"features_j" => "".$f_10
			);
			$this->db->insert('ph_smart_features', $feature_data2);

			// echo json_encode($arrayof_true);

			// $check = $this->db->affected_rows() > 0;
			// if($check){
			// 	$insert_id = $this->db->insert_id();
			// 	$query = "SELECT * FROM ph_smart_features where id = $insert_id";
			// }
			echo 'ok';
		}

	}

	/*
	all features
	o	One: http
	o	Two: http + total dot
	o	Three: http + Panjang url
	o	Four: http + double top domain
	o	Five: http + jumlah path
	o	Six: formcredential + sensitive info
	o	Seven: http + domain murah
	o	Eight: shortlink + sensitive info
	o	Nine: Minimal 3 fitur bebas terdeteksi
	o	Ten: Minimal 4 fitur bebas terdeteksi
	*/

	/*	+++ DONE ++ 
		FITUR PERTAMA
		o	One: http
	*/
	public function features_one($uri){
		$a = $this->cek_http($uri);
		// $b = $this->cek_ipaddress($uri);
		// $c = $this->cek_panjanguri($uri);
		// $d = $this->cek_symbols($uri, '@');

		if($a){
			return 1;
		}else{
			return 0;
		}
	}

	/*
		+++ DONE ++ 
		FITUR KEDUA
		o	Two: http + total dot
	*/
	public function features_two($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_jumlahdot($uri);

		if($a && $b){
			return 1;
		}else{
			return 0;
		}
	}


	/*
		+++ DONE ++ 
		FITUR KETIGA
		o	Three: http + Panjang url
	*/

	public function features_three($uri){
		$a = $this->cek_http($uri);
		$b = $this->cek_panjanguri($uri);
		if($a && $b){
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

		if($a && $b){
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

		if($a && $b){
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
		if($a && $b){
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
		$b = $this->cek_domainmurah($uri);
		if($a && $b){
			return 1;
		}else{
			return 0;
		}
	}


	/*domainm
		++ DONE ++
		FITUR KEDELAPAN
		shortlink + kata sensitive
	*/

	public function features_eight($uri){
		$a = $this->cek_sensitiveinfo($uri);
		$b = $this->cek_shortlink($uri);

		if($a && $b){
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
		$c = $this->cek_symbols($uri);

		if($a || $b || $c){
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
		$c = $this->cek_sensitiveinfo($uri);
		$d = $this->cek_shortlink($uri);

		if($a || $b || $c || $d){
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
		if($check_protocol == 'http'){
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
		if($check > 40){
			$result = true;
		}

		return $result;
	}


	// 4
	public function cek_domainmurah($uri){
		// cek daftar tld dengan harga sewa yang murah
		$array = array(".tech",".online", ".xyz", ".red", ".blue", ".domain", ".my.id", ".website", ".info");
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
			$word4 = 'log';
			$word5 = 'log in';
			$word6 = "sign in";
			if(strpos($get_value, $word1) !== false || strpos($get_value, $word2) !== false || strpos($get_value, $word3) !== false  || strpos($get_value, $word4) !== false || strpos($get_value, $word5) !== false ||  strpos($get_value, $word6 ) !== false ){
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
			$word4 = 'log';
			$word5 = 'log in';
			$word6 = "sign in";
			if(strpos($get_value, $word1) !== false || strpos($get_value, $word2) !== false || strpos($get_value, $word3) !== false  || strpos($get_value, $word4) !== false || strpos($get_value, $word5) !== false ||  strpos($get_value, $word6 ) !== false ){
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
		$array_of_sensitive_char = array('login', 'auth', 'signin', 'signin', 'yourls','pay', 'payrol', 'bonus', 'kuota', 'gratis', 'discount', 'free', 'exe', 'pdf');
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
		$pathtotal = $pathtotal - 3;
		if($pathtotal >= 1){
			return true;
		}else{
			return false;
		}
	}

	//9
	public function cek_jumlahdot($uri){
		$split = explode (".", $uri);
		$dotttal = sizeof($split);
		$dotttal = $dotttal - 1;
		if($dotttal > 3){
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
	public function cek_symbols($uri){
		// $cek = $this->str_contains($uri, $condition);
		// return $cek;
		$array = array("@","-", "?");
		$contains = $this->contains($uri, $array);
		if($contains){
			return true;
		}else{	
			return false;
		}
	}

	public function test(){
		$domain = $this->input->get('domain', TRUE);
		$check_http = $this->cek_http($domain);
		$cek_ipaddress = $this->cek_ipaddress($domain);
		$cek_panjanguri = $this->cek_panjanguri($domain);
		$cek_doubletopdomain = $this->cek_doubletopdomain($domain);
		$cek_jumlahdot = $this->cek_jumlahdot($domain);
		$cek_domainmurah = $this->cek_domainmurah($domain);
		$cek_doubletopdomain = $this->cek_doubletopdomain($domain);
		$jumlah_path = $this->cek_jumlahpath_test($domain);
		$result = array(
			'domain' => $domain,
			'cekipaddress' => $cek_ipaddress,
			'cek_panjanguri' => $cek_panjanguri,
			'checkhttp' => $check_http,
			'cek_doubletopdomain' => $cek_doubletopdomain,
			'cek_domainmurah' => $cek_domainmurah,
			'dottotal' => $cek_jumlahdot,
			'jumlah_path' => $jumlah_path
		);
		echo json_encode($result);
	}

	public function cek_jumlahpath_test($uri){
		$split = explode ("/", $uri);
		$pathtotal = sizeof($split);
		$pathtotal = $pathtotal - 3;
		// if($pathtotal > 1){
		// 	return true;
		// }else{
		// 	return false;
		// }
		return $pathtotal;
	}

	public function read_url_length($uri){
		$check = strlen($uri);
		$result = 0;
		if($check > 40){
			$result = 1;
		}
		return $result;
	}




	// Old modules

	public function read_html_login($file){
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
			return 1;
		}else{
			return 0;
		}
	}



	public function read_consistency($file, $uri){
		// check on data uri/url or title
		$dom = new Dom;
		$get_host = parse_url($uri, PHP_URL_HOST); 
		$dom->setOptions([
			'cleanupInput' => false,
			'removeScripts' => true,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$title = $dom->find('title');
		$found = false;
		foreach($title as $t){
			$get_value = $t->innerHtml;
			if($get_value == $get_host || strpos($get_value, $get_host) !== false){
				$found = true;
			}else{
				$found = false;
			}
		}

		if($found){
			return 0;
		}else{
			return 1;
		}
	}

	public function read_url_protocol($uri){
		$check_protocol =  parse_url($uri, PHP_URL_SCHEME);
		$result = 0;
		if($check_protocol != 'https'){
			$result = 1;
		}
		return $result;
	}

	public function read_html_external_link($file){
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
			if(substr( $get_value, 0, 7 ) === "http://" || substr( $get_value, 0, 8 ) === "https://" ){
				$found = true;
			}else{
				$found = false;
			}
		}
		if($found){
			return 1;
		}else{
			return 0;
		}
	}


	public function read_html_enabled_js($file){
		$dom = new Dom;
		$dom->setOptions([
			'cleanupInput' => false,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$a = $dom->find('script');
		$found = false;
		foreach($a as $links){
			if($links){
				$found = true;
			}else{
				$found = false;
			}
		}

		if($found){
			return 1;
		}else{
			return 0;
		}	
	}


	public function read_html_redirect($file){
		$dom = new Dom;
		$dom->setOptions([
			'cleanupInput' => false,
			'removeScripts' => true,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$a = $dom->find('meta');
		$found = false;
		foreach($a as $links){
			$get_value = $links->getAttribute('http-equiv');
			if($get_value == "refresh"){
				$found = true;
			}else{
				$found = false;
			}
		}

		if($found){
			return 1;
		}else{
			return 0;
		}	
	}


	public function read_html_empty_link($file){
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
			if($get_value == "" || $get_value == null){
				$found = true;
			}else{
				$found = false;
			}
		}

		if($found){
			return 1;
		}else{
			return 0;
		}	
	}
	

	public function read_html_filesize($file){
		$result = 0;
		$size = filesize($file);
		if($size < 10240){
			$result = 1;
		}else{
			$result = 0;
		}

		return $result;
	}

	
	public function read_html_iframe($file){
		$dom = new Dom;
		$dom->setOptions([
			'cleanupInput' => false,
			'removeScripts' => true,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$a = $dom->find('iframe');
		$found = false;
		foreach($a as $links){
			$get_value = $links->getAttribute('src');
			if($get_value !="" || $get_value == ""){
				$found = true;
			}else{
				$found = false;
			}
		}

		if($found){
			return 1; // kalo ketemu phishing
		}else{
			return 0; // kalo ketemu bukan phishing
		}	
	}


	public function read_html_favicon($file){
		// echo $file;
		$dom = new Dom;
		$dom->setOptions([
			'cleanupInput' => false,
			'removeScripts' => true,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$a = $dom->find('link');
		$found = false;
		foreach($a as $links){
			$get_value = $links->getAttribute('rel');
			if($get_value == 'shortcut icon' || $get_value == 'icon'){
				$found = false;
			}else{
				$found = true;
			}
		}

		if($found){
			return 0; // kalo ketemu bukan phishing
		}else{
			return 1; // kalo ketemu phishing
		}
	}
	
	public function read_url_subdomain($uri){
		$result = 0 ;
		$parsed_url = parse_url($uri, PHP_URL_HOST);
		if($parsed_url != null || $parsed_url != ''){
			$check_contain_sub = explode('.', $parsed_url);
			if($check_contain_sub[0]){
				$result = 1;
			}else{
				$result = 0;
			}
		}else{
			$result = 0;
		}

		return $result;
	}


	public function read_url_port($uri){
		$check_protocol =  parse_url($uri, PHP_URL_SCHEME);
		$result = 0;
		if($check_protocol == 'http'){
			$result = 1;
		}
		return $result;
	}


	public function read_special_char($uri){
		$result = 0 ;
		$vocab_phishing = array("secure", 
								 "account",
								 "login", "ebayisapi",
								 "signin", "banking", "confirm");
			if (in_array("uri", $vocab_phishing))
			{
				$result = 1 ;
			}
		return $result;
	}


	public function read_url_symbol($uri){
		$result = 0 ;
		if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $uri))
			{
				$result = 1;
			// one or more of the 'special characters' found in $string
			}
		return $result;
	}


	public function read_url_dot_total($uri){
		$split = explode (".", $uri);
		$dot_in_total = sizeof($split);
		$result = 0;
		if($dot_in_total >= 3){
				$result = 1;
		}
		return $result;
	}

	public function read_brandinfo($file, $uri){
		// check on data uri/url or title
		$dom = new Dom;
		$get_host = parse_url($uri, PHP_URL_HOST); 
		$dom->setOptions([
			'cleanupInput' => false,
			'removeScripts' => true,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($file);
		$title = $dom->find('title');
		$found = false;
		foreach($title as $t){
			$get_value = $t->innerHtml;
			if($get_value == $get_host || strpos($get_value, $get_host) !== false){
				$found = true;
			}
		}

		if($found){
			return 1;
		}else{
			return 0;
		}
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

	public function delete($id){
        $this->db->where('scan_id', $id);
		$this->db->delete('ph_scan');
		redirect(base_url());
	}


}
