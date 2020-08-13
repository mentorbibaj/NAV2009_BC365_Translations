<?php

require_once('config.php');

//=============================================
//CONFIG
$xml_file_input  = "Sales Reporting.g.xlf";
$xml_file_output = "Sales Reporting_CH.g.xlf";
$target_language = "de-CH";
//=============================================


//=============================================
//FUNCTIONS
$xml          = simplexml_load_file($xml_file_input) or die("Error: Cannot create object");
$xmlDataItems = $xml->file->body->group->{"trans-unit"};

$file_element = $xml->file;
$file_element->attributes()->{"target-language"} =  $target_language;
//$xml->xpath('//file/add[@key="target-language"]')[0]->attributes()['value'] = $target_language;

$dataItems    = array();

foreach($xmlDataItems as $xDI)
{
	$r = selectDE($conn, $xDI->source);
	if ($r != "")
	{
		$child = $xDI->addChild("target", $r);
	}else{
		$child = $xDI->addChild("target", "");
	}
}

$xml->asXML($xml_file_output);
echo "Done! Output file generated: <u>". $xml_file_output. "</u>!";
//=============================================

$conn->close();


function selectDE($conn, $english){

	$sql = "SELECT de FROM translations WHERE en='".$english."' LIMIT 1";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	  $row = $result->fetch_assoc();  
	  return $row['de'];
	}

	return "";
}

?>
