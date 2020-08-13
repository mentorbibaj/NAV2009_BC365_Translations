<?php 

require_once('config.php');
require_once('SCRAP.php');
//=============================================
//CONFIG
$xml_scrap_translated_ch_file = "Subcontracting Poltrona_CH.g.xlf";
//=============================================


//=============================================
//FUNCTIONS
$scrap = new Scrap($conn, $xml_scrap_translated_ch_file);
$scrap->scrap_translated_ch_file();
echo "Done scrapping!";
//=============================================

$conn->close();