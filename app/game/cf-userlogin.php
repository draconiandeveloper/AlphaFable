<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: cf-userlogin - v0.2
 */

define('BACKEND', 1);
require sprintf("%s/../includes/config.php", __DIR__);

use Alphafable\Core\Core;
$Core = new Core();

use Alphafable\Core\Security;
$Security = new Security();

$Core->makeXML();
$HTTP_RAW_POST_DATA = file_get_contents('php://input');

if (empty($HTTP_RAW_POST_DATA)) {
    $Core->returnXMLError('Invalid Data!', 'Message');
    die();
}

$xml = new \SimpleXMLElement($HTTP_RAW_POST_DATA);
if (empty($xml->strUsername) || empty($xml->strPassword)) {
    $Core->returnXMLError('Error!', 'There was an error communicating with the database.');
    die();
}

$userdata = $Database->safeFetch('SELECT * FROM `df_users` WHERE `name`=? LIMIT 1', [$xml->strUsername]);

if (count($userdata) === 0) {
    $Core->returnXMLError('User Not Found', 'Your username was incorrect, Please check your spelling and try again.');
    die();
}

$curpass = $Security->decode($userdata[0]['pass'], $SiteKey);
if (strcmp($curpass, $xml->strPassword) !== 0) {
    $Core->returnXMLError('Incorrect Password', 'Your password was incorrect, Please check your spelling and try again.');
    die();
}

$CanPlay = $Security->checkAccessLevel($userdata[0]['access'], 0);
switch ($CanPlay) {
    case 'Banned':
        $Core->returnXMLError('Banned!', 'You have been <b>banned</b> from <b>AlphaFable</b>. If you believe this is a mistake, please contact the <b>AlphaFable</b> Staff.');
        die();
    case 'Invalid':
        $Core->returnXMLError('Invalid Rank!', 'Sorry, The server is currently unavailable for your account, this may be due to server testing or upgrades. If you believe this is a mistake, please contact the <b>AlphaFable</b> Staff.');
        die();
    default: case 'OK':
        break;
}

$dob = explode('-', explode('T', $userdata[0]['dob'])[0]);
$dobcheck = sprintf("%s-%s", $dob[0], $dob[1]);

$news = $_SERVER['Settings']['SiteNews'];
if ($dobcheck === sprintf('%s-%s', date('m'), date('j')))
    $news .= '<br /><br /><b>Happy Birthday!</b>';

$Database->safeQuery('UPDATE `df_users` SET `lastLogin`=? WHERE `id`=?', [$_SERVER['Settings']['DateToday'], $userdata[0]['id']]);

$token = md5(md5(strlen($userdata[0]['LoginToken'])) . md5($userdata[0]['LoginToken']) . random_int(1, 100000));
$token = $Security->encodeNinja($token);
$token = md5($Security->encode($token, $SiteKey));
$token = strtoupper($token);

$updated = $Database->safeQuery('UPDATE `df_users` SET `LoginToken`=? WHERE `id`=?', [$token, $userdata[0]['id']]);
if ($updated === 0) {
    $Core->returnXMLError('Error!', 'There was an issue updating your account information.');
    die();
}

$dom = new \DOMDocument();
$domxml = $dom->appendChild($dom->createElement('characters'));
$user = $domxml->appendChild($dom->createElement('user'));

$user->setAttribute('UserId', $userdata[0]['id']);
$user->setAttribute('intCharsAllowed', $userdata[0]['chars_allowed']);
$user->setAttribute('intAccessLevel', $userdata[0]['access']);
$user->setAttribute('intUpgrade', $userdata[0]['upgrade']);
$user->setAttribute('intActivationFlag', $userdata[0]['activation']);
$user->setAttribute('strUsername', $userdata[0]['name']);
$user->setAttribute('strPassword', $userdata[0]['pass']);
$user->setAttribute('strToken', $token);
$user->setAttribute('strNews', $news);
$user->setAttribute('strServerBuild', 'v0.1');
$user->setAttribute('bitAdFlag', $userdata[0]['ad_flag']);
$user->setAttribute('dateToday', $_SERVER['Settings']['DateToday']);
$user->setAttribute('strDOB', $userdata[0]['dob']);

$characters = $Database->safeFetch('SELECT * FROM `df_characters` WHERE `userid`=?', [$userdata[0]['id']]);
foreach ($characters as &$character) {
    $chars = $user->appendChild($dom->createElement('characters'));
    $chars->setAttribute('CharID', $character['id']);
    $chars->setAttribute('strCharacterName', $character['name']);
    $chars->setAttribute('intLevel', $character['level']);
    $chars->setAttribute('intAccessLevel', $character['access']);
    $chars->setAttribute('intDragonAmulet', ($userdata[0]['upgrade'] === 1 || $character['dragon_amulet'] === 1) ? 1 : 0);
    $chars->setAttribute('strRaceName', $character['race']);
    $chars->setAttribute('orgClassID', $character['classid']);

    $class = $Database->safeFetch('SELECT * FROM `df_class` WHERE `ClassID`=?', [$character['classid']])[0];
    $chars->setAttribute('strClassName', $class['ClassName']);
}

echo $dom->saveXML();