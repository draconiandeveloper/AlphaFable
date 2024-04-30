<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: cf-usersignup - v0.2
 */

define('BACKEND', 1);
require sprintf("%s/../includes/config.php", __DIR__);

use Alphafable\Core\Core;
$Core = new Core();

use Alphafable\Core\Security;
$Security = new Security();

if (!filter_has_var(INPUT_POST, 'strUserName')) {
    $Core->sendErrorVar('523.07', 'Bad or missing information!', 'Back', 'Username', 'The information you entered was rejected by the server. Please go back and make sure that you filled out everything properly.');
    die();
}

$sign = [
    'User' => [
        'Name'     => filter_input(INPUT_POST, 'strUserName'),
        'Email'    => filter_input(INPUT_POST, 'strEmail'),
        'Birth'    => filter_input(INPUT_POST, 'strDOB'),
    ]
];

$userinfo = $Database->safeFetch('SELECT * FROM `df_users` WHERE `name`=?', [$sign['User']['Name']]);
if (count($userinfo) > 0) {
    $Core->sendErrorVar('523.14', 'Username already exists!', 'Back', 'Username', 'The Username you have selected is already being used, please use the button below to choose another one. If you are having a hard time finding a unique username you could try using your email address for your username too.');
    die();
}

$passhash = $Security->encode(filter_input(INPUT_POST, 'strPassword'), $_SERVER['Settings']['SiteKey']);
$Database->safeQuery('INSERT INTO `df_users` (`activation`, `pass`, `date_created`, `lastLogin`, `name`, `email`, `dob`) VALUES (?, ?, ?, ?, ?, ?, ?)', [
    0, $passhash,
    $_SERVER['Settings']['DateToday'],
    'Never',
    ...array_values($sign['User'])
]);

$userid = $Database->safeFetch('SELECT `id` FROM `df_users` WHERE `name`=? LIMIT 1', [$sign['User']['Name']])[0];
if (count($userid) === 0) die('Could not fetch new user ID!');

$Core->sendVar('status', 'Success');
$Core->sendVar('strMsg', 'Account Created Successfully');
$Core->sendVar('ID', $userid['id']);
