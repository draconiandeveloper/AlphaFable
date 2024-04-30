<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: cf-characternew - v0.2
 */

define('BACKEND', 1);
require sprintf("%s/../includes/config.php", __DIR__);

use Alphafable\Core\Core;
$Core = new Core();

use Alphafable\Core\Security;
$Security = new Security();

if (!filter_has_var(INPUT_POST, 'strCharacterName')) {
    $Core->sendError('Invalid Data!', 'none');
}

$sign = [
    'Char' => [
        'Name' => filter_input(INPUT_POST, 'strCharacterName'),
        'Gender' => filter_input(INPUT_POST, 'strGender'),
        'Class' => filter_input(INPUT_POST, 'strClass'),
        'ID' => [
            'User' => filter_input(INPUT_POST, 'intUserID'),
            'Race' => filter_input(INPUT_POST, 'intRaceID'),
            'Hair' => filter_input(INPUT_POST, 'intHairID'),
            'Class' => filter_input(INPUT_POST, 'intClassID'),
            'HairFrame' => filter_input(INPUT_POST, 'intHairFrame'),
        ],
        'Color' => [
            'Base' => filter_input(INPUT_POST, 'intColorBase'),
            'Skin' => filter_input(INPUT_POST, 'intColorSkin'),
            'Trim' => filter_input(INPUT_POST, 'intColorTrim'),
            'Hair' => filter_input(INPUT_POST, 'intColorHair'),
        ]
    ]
];

$changed = $Database->safeQuery('INSERT INTO `df_characters` (`name`, `dragon_amulet`, `gender`, `born`, `userid`, `raceid`, `hairid`, `classid`, `hairframe`, `colorbase`, `colorskin`, `colortrim`, `colorhair`, `BaseClassID`, `PrevClassID`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
    $sign['Char']['Name'], 0,
    $sign['Char']['Gender'],
    $DateToday,
    ...array_values($sign['Char']['ID']),
    ...array_values($sign['Char']['Color']),
    $sign['Char']['ID']['Class'],
    $sign['Char']['ID']['Class'],
]);

if ($changed === 0) {
    $Core->sendError('Unknown Error!', "There has been an error in one or more MySQL Queries; to resolve this issue, please contact the AlphaFable team and include following error.");
}

$Core->sendVar('code', 0);