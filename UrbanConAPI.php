<?php
	/*This class is a RESTful API. It connects to database and select data depends on HTML request and return the result back in JSON code. */

	class UrbanConAPI{
		
		private $db = NULL;/*init Database*/
		private $UrbanCon = Array();
		private $httpCode = "";
	
		/* init Database connection*/
		public function __construct() {
			$this -> dbConnect() ;					
		}
	
		/*do Database connection*/
		private function dbConnect() {
			$this -> db = mysqli_connect("localhost", "root", "", "world") ;
			if (mysqli_connect_errno() )
			{
				echo "Failed to connect to MySQL: " . mysqli_connect_error() ;
			}
		}
		
		/*get the received http request method*/
		private function getMethod() {
			return $_SERVER['REQUEST_METHOD'];
		}
		
		/*return http request with header and json code*/
		private function json($data) {
			if(is_array($data) ) {
				$data = json_encode($data) ;
			}
			header("HTTP/1.1 " . $this -> httpCode) ;
			header("Content-Type:application/json") ;
			echo $data;
			exit;
		}
		
		/*execute this api. starts from request method selection.*/
		public function runApi() {
			switch ($this -> getMethod() ) {
				case 'POST':
					$this -> rest_post() ;
					break;
				case 'GET':
					$this -> rest_get() ;  
					break;
				default:
					$this -> httpCode = "404 Not Found";
					break;
			}
		}
		
		/*get country list from db by GET*/
		private function rest_get() {
			$CountCountry=0;
			mysqli_query($this -> db, "SET @rank:= 0;") ;
			$AllData = mysqli_query($this -> db, "SELECT @rank := @rank + 1, Name, CityPopulation, CountryPopulation, UrbanConcentration 
				FROM `population` Order by UrbanConcentration Desc; ") ;
			if(mysqli_num_rows($AllData) > 0) {
				while($row = mysqli_fetch_row($AllData) ) {
					$UrbanCon[$CountCountry] = $row;
					$UrbanCon[$CountCountry][1] = iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', $UrbanCon[$CountCountry][1]) ;
					$UrbanCon[$CountCountry][2] = number_format($UrbanCon[$CountCountry][2]) ;
					$UrbanCon[$CountCountry][3] = number_format($UrbanCon[$CountCountry][3]) ;
					$UrbanCon[$CountCountry][4] = number_format($UrbanCon[$CountCountry][4]*100, 2) . "%";
					$CountCountry++;
				}
				$this -> httpCode = "200 OK";
				$this -> json($UrbanCon) ;
			}
			else{
				$this -> httpCode = "204 No Content";
			}
		}
		
		/*find rank in db by POST*/
		private function rest_post() {
			$Name = $_POST["Name"];
			mysqli_query($this -> db, "SET @rank:= 0;") ;
			$Rank = mysqli_query($this -> db, "SELECT Rank FROM (select @rank := @rank + 1 as Rank, Name From`population` Order by UrbanConcentration Desc) as Result WHERE Name = \"" . $Name . "\"") ;
			if(mysqli_num_rows($Rank) > 0) {
				$UrbanCon = mysqli_fetch_row($Rank) ;
				$this -> httpCode = "200 OK";
				$this -> json($UrbanCon) ;
			}
			else{
				$this -> httpCode = "204 No Content";
				$this -> json("204 No Content") ;
			}
		}
	}
	
	$api = new UrbanConAPI;
	$api -> runApi() ;
	
?>