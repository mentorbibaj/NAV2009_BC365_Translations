<?php

class Scrap
{
	private $conn;
	private $file; 
	function __construct($conn, $file)
	{
		$this->conn = $conn;
		$this->file = $file;
	}

	function scrap_from_nav_2009_file(){
		$xml          = simplexml_load_file($this->file) or die("Error: Cannot create object");

		$o = $xml->Report;

		foreach ($o as $oo) {
				
				$this->check_string($oo->Properties->CaptionML);
				$xmlDataItems = $oo->DataItems->DataItem;

				foreach($xmlDataItems as $xDI)
				{
					$this->check_string($xDI->Properties->ReqFilterHeadingML);
					$b = $xDI->Sections->Section;

					foreach ($b as $val) {
						$c = $val->Controls->Control;
						if (is_array($c) || is_object($c)){
							foreach ($c as $val1) {
								$this->check_string($val1->Properties->CaptionML);
							}
						}
					}
				}
			}
	}

	function check_string($captionML){
		
		if(strpos($captionML, 'DEU=') !== false && strpos($captionML, 'ENU=') !== false){
			$langs = explode(";",$captionML);
			$en_lang = "";
			$de_lang = "";
			foreach ($langs as $language) {
				$text = trim($language);
				if(strpos($text, 'DEU=') !== false){
					$de_lang = substr($text, 4, strlen($text) - 4);
				}else if(strpos($text, 'ENU=') !== false){
					$en_lang = substr($text, 4, strlen($text) - 4);
				}
			}
			$this->insert($en_lang,$de_lang);
		}
	}

	function scrap_translated_ch_file(){
		$xml          = simplexml_load_file($this->file) or die("Error: Cannot create object");
		$xmlDataItems = $xml->file->body->group->{"trans-unit"};

		foreach($xmlDataItems as $xDI)
		{
		    if ($xDI->target != "")
		    {
		    	echo $xDI->target."<br>";
				$this->insert($xDI->source,$xDI->target);
			}
		}
	}


	function insert($english, $german){

		$sql = "INSERT INTO translations (`en`, `de`) VALUES('".$english."', '".$german."')";

		if ($this->conn->query($sql) === TRUE) {
			echo "New record created successfully<br>\n";
		} else {
			echo "Error: " . $sql . "<br>" . $this->conn->error."\n";
		}
	}
}