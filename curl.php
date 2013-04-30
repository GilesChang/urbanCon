<?php
	/*This class makes a cURL request to RESTful api and get json result. */
	
	class cURL{
	
		private $curlOption = Array() ;	/*make curl_setopt array*/
		private $URL = "http://localhost:1234/urbanCon/UrbanConAPI.php";	/*request URL default*/
		
		/*make array for curl_setopt_array. Define request URL, get response header and get response content*/
		private function makeOptionArray() {
			$this -> curlOption[CURLOPT_URL] = $this -> URL;
			$this -> curlOption[CURLOPT_HEADER] = true;
			$this -> curlOption[CURLOPT_RETURNTRANSFER] = true;
		}
		
		/*set request method*/
		public function setMethod($method) {
			$this -> curlMethod = $method;
		}
		
		/*execute this curl app. get http response including header and body.
		decode json response*/
		public function curlExecute() {
			$this -> makeOptionArray() ;
			$ch = curl_init() ;
			curl_setopt_array($ch, $this -> curlOption) ;
			$response = curl_exec($ch) ; 
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE) ;
			$body = substr($response, curl_getinfo($ch, CURLINFO_HEADER_SIZE) ) ;
			curl_close($ch) ;
			if($httpCode == 200) {
				return $body;
			}
			else{
				return json_encode(Array("notOK" => True) ) ;
			}
		}
	}
	
	$country_info = new cURL; 
	$result = $country_info -> curlExecute() ;
?>