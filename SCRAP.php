<?php

require_once('config.php');

//=============================================
//CONFIG
$xml_scrap_translated_ch_file = "file.xml";
$xml_scrap_from_nav_2009_file = "nav2009_to_scrap.xml";
//=============================================


//=============================================
//FUNCTIONS
//scrap_translated_ch_file($conn,$xml_scrap_translated_ch_file);
scrap_from_nav_2009_file($conn,$xml_scrap_from_nav_2009_file);//te rregullohet edhe per file-at e vjeter nga nav 2009
echo "Done scrapping!";
//=============================================

$conn->close();


function scrap_from_nav_2009_file($conn, $file){
	$xml          = simplexml_load_file($file) or die("Error: Cannot create object");
	$xmlDataItems = $xml->Report->DataItems->DataItem;

	foreach($xmlDataItems as $xDI)
	{
		$b = $xDI->Sections->Section;
		foreach ($b as $val) {
			$c = $val->Controls->Control;
			if (is_array($c) || is_object($c)){
				foreach ($c as $val1) {

					$d = $val1->Properties->CaptionML;
					if(strpos($d, 'DEU=') !== false && strpos($d, 'ENU=') !== false){
						$langs = explode(";",$d);
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
						insert($conn, $en_lang,$de_lang);
					}
				}
			}

		}
	}
}

function scrap_translated_ch_file($conn, $file){
	$xml          = simplexml_load_file($file) or die("Error: Cannot create object");
	$xmlDataItems = $xml->file->body->group->{"trans-unit"};

	foreach($xmlDataItems as $xDI)
	{
	    if ($xDI->target != "")
	    {
	    	echo $xDI->target."<br>";
			insert($conn, $xDI->source,$xDI->target);
		}
	}
}


function insert($conn,$english, $german){

	$sql = "INSERT INTO translations (`en`, `de`) VALUES('".$english."', '".$german."')";

	if ($conn->query($sql) === TRUE) {
		echo "New record created successfully<br>\n";
	} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

?>