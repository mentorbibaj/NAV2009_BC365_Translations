<?php 

require_once('config.php');
require_once('SCRAP.php');
//=============================================
//CONFIG
$xml_scrap_from_nav_2009_file = "8.xml";
//=============================================


//=============================================
//FUNCTIONS
$scrap = new Scrap($conn, $xml_scrap_from_nav_2009_file);
$scrap->scrap_from_nav_2009_file();
echo "Done scrapping!";
//=============================================

$conn->close();