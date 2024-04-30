<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: core - v0.2
 */

namespace Alphafable\Core;
require_once sprintf("%s/../global.php", __DIR__);

class Core {
    public function makeXML() {
        header("Content-Type: application/xml");
        header("Charset: UTF-8");
    }

    function returnXMLError(string $title, string $message) {
        $dom = new \DOMDocument();
        $xml = $dom->appendChild($dom->createElement('error'));
        $info = $xml->appendChild($dom->createElement('info'));

        $info->setAttribute('code', '526.14');
        $info->setAttribute('reason', $title);
        $info->setAttribute('message', "<p align=\"justify\">$message</p>");
        $info->setAttribute('action', 'None');

        echo $dom->saveXML();
        die();
    }

    function returnCustomXMLMessage(string $element, string $name, $value) {
        $dom = new \DOMDocument();
        $xml = $dom->appendChild($dom->createElement($element));
        $xml->setAttribute($name, $value);
        
        echo $dom->saveXML();
        die();
    }

    function sendVar(string $name, $value) {
        echo "&$name=$value";
    }

    function sendErrorVar(string $code, string $reason, string $btnName, string $btnAction, string $message) {
        $this->sendVar('status', 'Failure');
        $this->sendVar('strErr', "Error Code {$code}");
        $this->sendVar('strReason', $reason);
        $this->sendVar('strButtonName', $btnName);
        $this->sendVar('strButtonAction', $btnAction);
        $this->sendVar('strMsg', $message);
    }

    function sendError(string $reason, string $message) {
        echo "&code=527.07&reason={$reason}&message={$message}&action=none";
        die();
    }

    function calcExp(int $level) : int {
        $exp = [
            0x0000000A, 0x00000014, 0x00000028, 0x00000050, 0x000000A0, 0x00000140, 0x00000280, 0x00000500, 0x00000A00,
            0x00001400, 0x00002328, 0x00002A8A, 0x000032A0, 0x00003B6A, 0x000044E8, 0x00004F1A, 0x00005A00, 0x0000659A, 
            0x000071E8, 0x00007EEA, 0x00008CA0, 0x00009B0A, 0x0000AA28, 0x0000B9FA, 0x0000CA80, 0x0000DBBA, 0x0000EDA8, 
            0x0001004A, 0x000113A0, 0x000127AA, 0x00013C68, 0x000151DA, 0x00016800, 0x00017EDA, 0x00019668, 0x0001AEAA, 
            0x0001C7A0, 0x0001E14A, 0x0001FBA8, 0x000216BA, 0x00023280, 0x00024EFA, 0x00026C28, 0x00028A0A, 0x0002A8A0, 
            0x0002C7EA, 0x0002E7E8, 0x0003089A, 0x00032A00, 0x00034C1A, 0x00036EE8, 0x0003926A, 0x0003B6A0, 0x0003DB8A, 
            0x00040128, 0x0004277A, 0x00044E80, 0x0004763A, 0x00049EA8, 0x0004C7CA, 0x000668A0, 0x0006C728, 0x000725B0, 
            0x00078438, 0x0007E2C0, 0x00084148, 0x00089FD0, 0x0008FE58, 0x00095CE0, 0x0009BB68, 0x001E84C6, 0x001E84C7, 
            0x001E84C8, 0x001E84C9, 0x001E84CA, 0x001E84CB, 0x001E84CC, 0x001E84CD, 0x001E84CE, 0x001E84CF, 0x3B9AC9FF,
        ];

        return $exp[$level % count($exp)];
    }

    function valueCheck(int $value) {
        $charset = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        
        if ($value < 10 || $value > 35) return $value;
        return $charset[$value - 10];
    }
}