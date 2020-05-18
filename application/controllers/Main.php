<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*Load all parser library*/
require "vendor/autoload.php";
use Sunra\PhpSimple\HtmlDomParser; // lib html parser
use stringEncode\Encode; // lib html parser
use PHPHtmlParser\Dom; // lib html parser
use FastSimpleHTMLDom\Document; // lib html parser
class Main extends CI_Controller {

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
		$query = "SELECT * FROM ph_scan ORDER BY scan_id ASC";
		$exec = $this->db->query($query);
		$result = $exec->result();

		$query2 = "SELECT * FROM ph_features ORDER BY feature_id ASC";
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
			$feature_data = array(
				"scan_id" => $id,
				"url_link"=> "".$url,
				"url_protocol" => "".$this->read_url_protocol($url),
				"url_favicon" => "".$this->read_html_favicon($file_path),
				"url_standard_port" => "".$this->read_url_port($url),
				"url_symbol" => "".$this->read_url_symbol($url),
				"url_subdomain"=> "".$this->read_url_subdomain($url),
				"url_length" => "".$this->read_url_length($url),
				"url_dot_total" => "".$this->read_url_dot_total($url),
				"url_sensitive_char" => "".$this->read_special_char($url),
				"html_login" => "".$this->read_html_login($file_path),
				"html_empty_link" => "".$this->read_html_empty_link($file_path),
				"html_length" => "".$this->read_html_filesize($file_path),
				"html_is_consist" => "".$this->read_consistency($file_path, $url),
				"html_js_list" => "".$this->read_html_enabled_js($file_path),
				"html_link_external_list" => "".$this->read_html_external_link($file_path),
				"html_redirect" => "".$this->read_html_redirect($file_path),
				"html_iframe" => "".$this->read_html_iframe($file_path),
				"html_favicon" => "".$this->read_html_favicon($file_path),
				"feature_type" => "0" // ini bukan fitur ini flag 

			);	
			$this->db->insert('ph_features', $feature_data);
			echo 'ok';
		}

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

	public function read_html($content){
		$process = file_get_contents($content);
		echo $process;
	}

	public function read_url_protocol($uri){
		$check_protocol =  parse_url($uri, PHP_URL_SCHEME);
		$result = 0;
		if($check_protocol != 'https'){
			$result = 1;
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

	public function read_url_length($uri){
		$check = strlen($uri);
		$result = 0;
		if($check > 100){
			$result = 1;
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

	/*
		Deteksi special character di URI
	*/
	public function read_url_symbol($uri){
		$result = 0 ;
		if (preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $uri))
			{
				$result = 1;
			// one or more of the 'special characters' found in $string
			}
		return $result;
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

	/*
		Check apakah web memiliki Sensitive vocabulary
	 */

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
	
	/*
		Check apakah web memiliki favicon, kebanyakan web phishing 
		dibuat secara tidak profesional dan tidak menampilkan favicon
	 */
	
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
			if($get_value == 'shortcut icon'){
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


	/*
		Check kalo ad iframe, ini biasany untuk inject view dari luar
		berbahaya
	 */
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

	/*
		Check  ukran file jika lebih besar dr 100
	 */
	public function read_html_filesize($file){
		$result = 0;
		$size = filesize($file);
		if($size < 102400){
			$result = 1;
		}

		return $result;
	}

	/*
		Check kalo a href value ny kosong 
	 */
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

	/*
		Check kalo ado auto redirect 
	*/
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
	

	public function read_html_mouseover($uri){
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


	public function read_html_popup($uri){
		$dom = new Dom;
		$dom->setOptions([
			'cleanupInput' => false,
			'htmlSpecialCharsDecode' => false,
			'strict' => false,
			'whitespaceTextNode' => false
		]);
		$dom->loadFromFile($uri);
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
}
