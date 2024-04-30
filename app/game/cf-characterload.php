<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: cf-characterload - v0.2
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

$xml = new \SimpleXmlElement($HTTP_RAW_POST_DATA);
if (empty($xml->intCharID) || empty($xml->strToken)) {
    $Core->returnXMLError('Error!', 'There was an error communicating with the database.');
}

$characters = $Database->safeFetch('SELECT * FROM `df_characters` WHERE `id`=? LIMIT 1', [$xml->intCharID]);
if (count($characters) === 0) {
    $Core->returnXMLError('Character Not Found', 'Character information was unable to be requested.');
}

$users = $Database->safeFetch('SELECT * FROM `df_users` WHERE `id`=?', [$characters[0]['userid']]);
if (count($users) === 0) {
    $Core->returnXMLError('Account Not Found', 'Account information was unable to be requested.');
}

$set = [];

$exptolevel = $Core->calcExp($characters[0]['level']);
$newLevel = $characters[0]['level'];
$newEXP = $characters[0]['exp'];

if ($characters[0]['level'] >= $exptolevel && $characters[0]['level'] < $LevelCap) {
    $newLevel++;
    $newEXP = 0;
}

$hairs = $Database->safeFetch('SELECT * FROM `df_hairs` WHERE `HairID`=? AND `Gender`=?', [$characters[0]['hairid'],$characters[0]['gender']]);
$equipment = $Database->safeFetch('SELECT * FROM `df_equipment` WHERE `CharID`=? AND `StartingItem`=1 AND `House`=1 LIMIT 1', [$xml->intCharID]);

$HouseID = 0;
if ($characters[0]['HasHouse'] !== 0 && count($equipment) > 0) {
    $HouseID = $equipment[0]['ItemID'];
}

if ($characters[0]['HomeTownID'] === 0 && $characters[0]['HasHouse'] !== 0 && $HouseID !== 0) {
    $houses = $Database->safeFetch('SELECT * FROM `df_houses` WHERE `HouseID`=?', [$equipment[0]['ItemID']])[0];
    $set['Name'] = 'Home';
    $set['QuestID'] = '0';
    $set['FileName'] = $houses['strFileName'];
    $set['XFileName'] = 'none';
    $set['Extra'] = '';
} else {
    $quests = $Database->safeFetch('SELECT * FROM `df_quests` WHERE `QuestID`=?', [$characters[0]['HomeTownID']]);
    $set['Name'] = 'No Quest';
}

$curdate = sprintf('%s/%s/%s', date('m'), date('j'), date('Y'));
$birthday = (strcmp($curdate, $users[0]['dob']) === 0) ? 1 : 0;
$zones = explode(';', $set['Houses']['Extra']);

foreach ($zones as &$zone) {
    $extra = isset($extra) ? sprintf("%s%s\n", $extra, $zone) : "{$zone}\n";
}

$classes = $Database->safeFetch('SELECT * FROM `df_class` WHERE `ClassID`=?', [$characters[0]['classid']]);

$dom = new \DOMDocument();
$xmldom = $dom->appendChild($dom->createElement('character'));

$character = $xmldom->appendChild($dom->createElement('character'));
$character->setAttribute('CharID', $xml->intCharID);
$character->setAttribute('strCharacterName', $characters[0]['name']);
$character->setAttribute('dateCreated', $characters[0]['born']);
$character->setAttribute('isBirthday', $birthday);
$character->setAttribute('intHP', $characters[0]['hp']);
$character->setAttribute('intMP', $characters[0]['mp']);
$character->setAttribute('intLevel', $newLevel);
$character->setAttribute('intExp', $newEXP);
$character->setAttribute('intAccessLevel', $characters[0]['access']);
$character->setAttribute('intHouseID', $HouseID);
$character->setAttribute('intSilver', $characters[0]['Silver']);
$character->setAttribute('intGold', $characters[0]['gold']);
$character->setAttribute('intGems', $characters[0]['Gems']);
$character->setAttribute('intCoins', $characters[0]['Coins']);
$character->setAttribute('intMaxBagSlots', $characters[0]['MaxBagSlots']);
$character->setAttribute('intMaxBankSlots', $characters[0]['MaxBankSlots']);
$character->setAttribute('intMaxHouseSlots', $characters[0]['MaxHouseSlots']);
$character->setAttribute('intMaxHouseItemSlots', $characters[0]['MaxHouseItemSlots']);
$character->setAttribute('intDragonAmulet', $characters[0]['dragon_amulet']);
$character->setAttribute('intAccesslevel', $users[0]['access']);
$character->setAttribute('strGender', $characters[0]['gender']);
$character->setAttribute('intColorHair', $characters[0]['colorhair']);
$character->setAttribute('intColorSkin', $characters[0]['colorskin']);
$character->setAttribute('intColorBase', $characters[0]['colorbase']);
$character->setAttribute('intColorTrim', $characters[0]['colortrim']);
$character->setAttribute('intStr', $characters[0]['intSTR']);
$character->setAttribute('intDex', $characters[0]['intDEX']);
$character->setAttribute('intInt', $characters[0]['intINT']);
$character->setAttribute('intLuk', $characters[0]['intLUK']);
$character->setAttribute('intCha', $characters[0]['intCHA']);
$character->setAttribute('intEnd', $characters[0]['intEND']);
$character->setAttribute('intWis', $characters[0]['intWIS']);
$character->setAttribute('intSkillPoints', '0');
$character->setAttribute('intStatPoints', $characters[0]['intStatPoints']);
$character->setAttribute('intCharStatus', '0');
$character->setAttribute('strArmor', $characters[0]['strArmor']);
$character->setAttribute('strSkills', $characters[0]['strSkills']);
$character->setAttribute('strQuests', $characters[0]['strQuests']);
$character->setAttribute('intExpToLevel', $exptolevel);
$character->setAttribute('RaceID', $characters[0]['raceid']);
$character->setAttribute('strRaceName', $characters[0]['race']);
$character->setAttribute('GuildID', '1');
$character->setAttribute('strGuildName', "None");
$character->setAttribute('QuestID', $set['QuestID']);
$character->setAttribute('strQuestName', $set['Name']);
$character->setAttribute('strQuestFileName', $set['FileName']);
$character->setAttribute('strXQuestFileName', $set['XFileName']);
$character->setAttribute('strExtra', $extra);
$character->setAttribute('BaseClassID', $characters[0]['BaseClassID']);
$character->setAttribute('ClassID', $characters[0]['classid']);
$character->setAttribute('PrevClassID', $characters[0]['PrevClassID']);
$character->setAttribute('strClassName', $classes[0]['ClassName']);
$character->setAttribute('strClassFileName', $classes[0]['ClassSWF']);
$character->setAttribute('strArmorName', $classes[0]['ArmorName']);
$character->setAttribute('strArmorDescription', $classes[0]['ArmorDescription']);
$character->setAttribute('strArmorResists', $classes[0]['ArmorResists']);
$character->setAttribute('intDefMelee', $classes[0]['DefMelee']);
$character->setAttribute('intDefRange', $classes[0]['DefRange']);
$character->setAttribute('intDefMagic', $classes[0]['DefMagic']);
$character->setAttribute('intParry', $classes[0]['Parry']);
$character->setAttribute('intDodge', $classes[0]['Dodge']);
$character->setAttribute('intBlock', $classes[0]['Block']);
$character->setAttribute('strWeaponName', $classes[0]['WeaponName']);
$character->setAttribute('strWeaponDescription', $classes[0]['WeaponDescription']);
$character->setAttribute('strWeaponDesignInfo', $classes[0]['WeaponDesignInfo']);
$character->setAttribute('strWeaponResists', $classes[0]['WeaponResists']);
$character->setAttribute('intWeaponLevel', $classes[0]['WeaponLevel']);
$character->setAttribute('strWeaponIcon', $classes[0]['WeaponIcon']);
$character->setAttribute('strType', $classes[0]['Type']);
$character->setAttribute('bitDefault', '1');
$character->setAttribute('bitEquipped', '1');
$character->setAttribute('strItemType', $classes[0]['ItemType']);
$character->setAttribute('intCrit', $classes[0]['Crit']);
$character->setAttribute('intDmgMin', $classes[0]['DmgMin']);
$character->setAttribute('intDmgMax', $classes[0]['DmgMax']);
$character->setAttribute('intBonus', $classes[0]['Bonus']);
$character->setAttribute('strElement', $classes[0]['Element']);
$character->setAttribute('strEquippable', "Sword,Mace,Dagger,Axe,Ring,Necklace,Staff,Belt,Earring,Bracer,Pet,Cape,Wings,Helmet,Armor,Wand,Scythe,Trinket");
$character->setAttribute('strHairFileName', $hairs[0]['HairSWF']);
$character->setAttribute('intHairFrame', $characters[0]['hairframe']);
$character->setAttribute('gemReward', '0');

$equipments = $Database->safeFetch('SELECT * FROM `df_equipment` WHERE `CharID`=? AND `HouseItem`=0', [$xml->intCharID]);
foreach ($equipments as &$equip) {
    $items = $Database->safeFetch('SELECT * FROM `df_items` WHERE `ItemID`=?', [$equip['ItemID']]);
    if (count($items) > 0) {
        $itm = $character->appendChild($dom->createElement('items'));
        
        foreach ($items as &$item) {
            $itm->setAttribute('ItemID', $item['ItemID']);
            $itm->setAttribute('CharItemID', $item['ItemID']);
            $itm->setAttribute('strItemName', $item['ItemName']);
            $itm->setAttribute('intCount', $equip['count']);
            $itm->setAttribute('intHP', $item['hp']);
            $itm->setAttribute('intMaxHP', $item['hp']);
            $itm->setAttribute('intMP', $item['mp']);
            $itm->setAttribute('intMaxMP', $item['mp']);
            $itm->setAttribute('bitEquipped', $equip['StartingItem']);
            $itm->setAttribute('bitDefault', $equip['StartingItem']);
            $itm->setAttribute('intCurrency', $item['Currency']);
            $itm->setAttribute('intCost', $item['Cost']);
            $itm->setAttribute('intLevel', $item['Level']);
            $itm->setAttribute('strItemDescription', $item['ItemDescription']);
            $itm->setAttribute('bitDragonAmulet', $item['DragonAmulet']);
            $itm->setAttribute('strEquipSpot', $item['EquipSpot']);
            $itm->setAttribute('strCategory', $item['Category']);
            $itm->setAttribute('strItemType', $item['ItemType']);
            $itm->setAttribute('strType', $item['Type']);
            $itm->setAttribute('strFileName', $item['FileName']);
            $itm->setAttribute('intMin', $item['Min']);
            $itm->setAttribute('intCrit', $item['intCrit']);
            $itm->setAttribute('intDefMelee', $item['intDefMelee']);
            $itm->setAttribute('intDefPierce', $item['intDefPierce']);
            $itm->setAttribute('intDodge', $item['intDodge']);
            $itm->setAttribute('intParry', $item['intParry']);
            $itm->setAttribute('intDefMagic', $item['intDefMagic']);
            $itm->setAttribute('intBlock', $item['intBlock']);
            $itm->setAttribute('intDefRange', $item['intDefRange']);
            $itm->setAttribute('intMax', $item['Max']);
            $itm->setAttribute('intBonus', $item['Bonus']);
            $itm->setAttribute('strResists', $item['Resists']);
            $itm->setAttribute('strElement', $item['Element']);
            $itm->setAttribute('intRarity', $item['Rarity']);
            $itm->setAttribute('intMaxStackSize', $item['MaxStackSize']);
            $itm->setAttribute('strIcon', $item['Icon']);
            $itm->setAttribute('bitSellable', $item['Sellable']);
            $itm->setAttribute('bitDestroyable', $item['Destroyable']);
            $itm->setAttribute('intStr', $item['intStr']);
            $itm->setAttribute('intDex', $item['intDex']);
            $itm->setAttribute('intInt', $item['intInt']);
            $itm->setAttribute('intLuk', $item['intLuk']);
            $itm->setAttribute('intCha', $item['intCha']);
            $itm->setAttribute('intEnd', $item['intEnd']);
            $itm->setAttribute('intWis', $item['intWis']);
        }
    }
}

if ($characters[0]['HasDragon'] === 1) {
    $dragons = $Database->safeFetch('SELECT * FROM `df_dragons` WHERE `CharDragID`=?', [$xml->intCharID])[0];
    $dragonHead = $Database->safeFetch('SELECT `FileName` FROM `df_dragoncustomize` WHERE `CustomID`=? AND `Type`=?', [$dragon['intHeads'], 'Head'])[0];
    $dragonTail = $Database->safeFetch('SELECT `FileName` FROM `df_dragoncustomize` WHERE `CustomID`=? AND `Type`=?', [$dragon['intTails'], 'Tail'])[0];
    $dragonWing = $Database->safeFetch('SELECT `FileName` FROM `df_dragoncustomize` WHERE `CustomID`=? AND `Type`=?', [$dragon['intWings'], 'Wing'])[0];

    $dragon = $character->appendChild($dom->createElement('dragon'));
    $dragon->setAttribute('idCore_CharDragons', $xml->intCharID);
    $dragon->setAttribute('strName', $dragons['strName']);
    $dragon->setAttribute('dateLastFed', $dragons['dateLastFed']);
    $dragon->setAttribute('intTotalStats', $dragons['intTotalStats']);
    $dragon->setAttribute('intHeal', $dragons['intHeal']);
    $dragon->setAttribute('intMagic', $dragons['intMagic']);
    $dragon->setAttribute('intMelee', $dragons['intMelee']);
    $dragon->setAttribute('intBuff', $dragons['intBuff']);
    $dragon->setAttribute('intDebuff', $dragons['intDebuff']);
    $dragon->setAttribute('intColorDskin', $dragons['intColorSkin']);
    $dragon->setAttribute('intColorDeye', $dragons['intColorEye']);
    $dragon->setAttribute('intColorDhorn', $dragons['intColorHorn']);
    $dragon->setAttribute('intColorDwing', $dragons['intColorWing']);
    $dragon->setAttribute('intHeadID', $dragons['intHeads']);
    $dragon->setAttribute('strHeadFilename', $dragonHead);
    $dragon->setAttribute('intWingID', $dragons['intWings']);
    $dragon->setAttribute('strWingFilename', $dragonWing);
    $dragon->setAttribute('intTailID', $dragons['intTails']);
    $dragon->setAttribute('strFileName', $dragons['FileName']);
    $dragon->setAttribute('strTailFilename', $dragonTail);
    $dragon->setAttribute('intMin', $dragons['intMin']);
    $dragon->setAttribute('intMax', $dragons['intMax']);
    $dragon->setAttribute('strType', $dragons['strType']);
    $dragon->setAttribute('strElement', $dragons['strElement']);
    $dragon->setAttribute('intColorDelement', $dragons['intColorDelement']);
}

if ($characters[0]['HasHouse'] !== 0 && !is_null($characters[0]['HasHouse'])) {
    $charitems = $Database->safeFetch('SELECT * FROM `df_equipment` WHERE `CharID`=? AND `HouseItem`!=0', [$xml->intCharID]);
    if (count($charitems) > 0) {
        foreach ($charitems as &$charitem) {
            $houseitems = $Database->safeFetch('SELECT * FROM `df_house_items` WHERE `HouseItemID`=?', [$charitem['ItemID']]);
            if (count($houseitems) > 0) {
                $house = $character->appendChild($dom->createElement('houseitems'));
                foreach ($houseitems as &$houseitem) {
                    $house->setAttribute('HouseItemID', $houseitem['HouseItemID']);
                    $house->setAttribute('CharHouseItemID', $houseitem['HouseItemID']);
                    $house->setAttribute('strItemName', $houseitem['strItemName']);
                    $house->setAttribute('strItemDescription', $houseitem['strItemDescription']);
                    $house->setAttribute('bitVisible', $houseitem['bitVisible']);
                    $house->setAttribute('bitDestroyable', $houseitem['bitDestroyable']);
                    $house->setAttribute('bitEquippable', $houseitem['bitEquippable']);
                    $house->setAttribute('bitRandomDrop', $houseitem['bitRandomDrop']);
                    $house->setAttribute('bitSellable', $houseitem['bitSellable']);
                    $house->setAttribute('bitDragonAmulet', $houseitem['bitDragonAmulet']);
                    $house->setAttribute('intCost', $houseitem['intCost']);
                    $house->setAttribute('intCurrency', $houseitem['intCurrency']);
                    $house->setAttribute('intMaxStackSize', $houseitem['intMaxStackSize']);
                    $house->setAttribute('intRarity', $houseitem['intRarity']);
                    $house->setAttribute('intLevel', $houseitem['intLevel']);
                    $house->setAttribute('intMaxlevel', $houseitem['intMaxlevel']);
                    $house->setAttribute('intCategory', $houseitem['intCategory']);
                    $house->setAttribute('intEquipSpot', $houseitem['intEquipSpot']);
                    $house->setAttribute('intType', $houseitem['intType']);
                    $house->setAttribute('bitRandom', $houseitem['bitRandom']);
                    $house->setAttribute('intElement', $houseitem['intElement']);
                    $house->setAttribute('strType', $houseitem['strType']);
                    $house->setAttribute('strFileName', $houseitem['strFileName']);
                    $house->setAttribute('intEquipSlotPos', $charitem['intEquipSlotPos']);
                    $house->setAttribute('intHoursOwned', "1");
                }
            }
        }
    }
}

echo $dom->saveXML();