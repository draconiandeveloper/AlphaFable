<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: cf-changehometown - v0.2
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

$xml = new DOMDocument();
$xml->loadXML($HTTP_RAW_POST_DATA);

$intCharID = $xml->getElementsByTagName('intCharID')->item(0)->nodeValue;
$intTownID = $xml->getElementsByTagName('intTownID')->item(0)->nodeValue;
$strToken  = $xml->getElementsByTagName('strToken')->item(0)->nodeValue;

$character = $Database->safeFetch('SELECT * FROM `df_characters` WHERE `id`=? LIMIT 1', [$intCharID]);
if (count($character) === 0) {
    $Core->returnXMLError('Error!', 'Character not found in database.');
}

$user = $Database->safeFetch('SELECT * FROM `df_users` WHERE `id`=? AND `LoginToken`=? LIMIT 1', [$character[0]['userid'], $strToken]);
if (count($user) === 0) {
    $Core->returnXMLError('Error!', 'User not found in database.');
}

$quest = $Database->safeFetch('SELECT * FROM `df_quests` WHERE `QuestID`=? LIMIT 1', [$intTownID]);
if (count($quest) === 0) {
    $Core->returnXMLError('Error!', 'Invalid Town ID.');
}

$setHome = $Database->safeQuery('UPDATE `df_characters` SET `HomeTownID`=? WHERE `ID`=?', [$intTownID, $intCharID]);
if ($setHome === 0) {
    $Core->returnXMLError('Error!', 'There was an issue updating your character information.');
}

$dom = new DOMDocument();
$xmldom = $dom->appendChild($dom->createElement('newTown'));
$char = $xmldom->appendChild($dom->createElement('newTown'));

$char->setAttribute('intTownID', $intTownID);
$char->setAttribute('strQuestFileName', $quest[0]['FileName']);
$char->setAttribute('strQuestXFileName', $quest[0]['XFileName']);
$char->setAttribute('strExtra', $quest[0]['Extra']);

$status = $xmldom->appendChild($dom->createElement('status'));
$status->setAttribute('status', 'SUCCESS');
echo $dom->saveXML();