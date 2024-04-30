<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: cf-interfaceload - v0.2
 */

define('BACKEND', 1);
require sprintf("%s/../includes/config.php", __DIR__);

use Alphafable\Core\Core;
$Core = new Core();

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (empty($HTTP_RAW_POST_DATA)) {
    $Core->returnXMLError('Invalid Data!', 'Message');
}

$xml = new \SimpleXMLElement($HTTP_RAW_POST_DATA);
if (empty($xml->intInterfaceID)) {
    $Core->returnXMLError('Server Error!', 'Could not communicate with client');
}

$interfaces = $Database->safeFetch('SELECT * FROM `df_interface` WHERE `InterfaceID`=?', [$xml->intInterfaceID]);
if (count($interfaces) === 0) {
    $Core->returnXMLError('Server Error!', 'Could not load Interface');
}

$dom = new \DOMDocument();
$xmldom = $dom->appendChild($dom->createElement('intrface'));

$character = $xmldom->appendChild($dom->createElement('intrface'));
$character->setAttribute('InterfaceID', strval($xml->intInterfaceID));
$character->setAttribute('strName', $interfaces[0]['InterfaceName']);
$character->setAttribute('strFileName', $interfaces[0]['InterfaceSWF']);
$character->setAttribute('bitLoadUnder', '0');

$character = $xmldom->appendChild($dom->createElement('status'));
$character->setAttribute('status', 'SUCCESS');
echo $dom->saveXML();