<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: cf-characterdelete - v0.2
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
if (empty($xml->intCharID) || empty($xml->strToken)) {
    $Core->returnXMLError('Invalid Data!', 'Message');
}

$characters = $Database->safeFetch('SELECT * FROM `df_characters` WHERE `id`=?', [$xml->intCharID]);
if (count($characters) === 0) {
    $Core->returnXMLError('Error!', 'Character information was unable to be requested.');
}

$users = $Database->safeFetch('SELECT * FROM `df_users` WHERE `id`=? AND `LoginToken`=? LIMIT 1', [$characters[0]['userid'], $xml->strToken]);
if (count($users) === 0) {
    $Core->returnXMLError('Error!', 'Account information was unable to be requested.');
}

$rows = $Database->safeQuery('DELETE FROM `df_characters` WHERE `id`=? AND `userid`=?', [$xml->intCharID, $users[0]['id']]);
if ($rows === 0) {
    $Core->returnXMLError("Error!", "There was an issue updating your character information.");
}

echo $xml->saveXML();