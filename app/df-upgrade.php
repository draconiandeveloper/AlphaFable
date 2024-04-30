<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: df-upgrade - v0.2
 */

define('BACKEND', 1);
require './includes/config.php';

function get_cost_html(int $origcost, float $discount = 1.0) : string {
    $cost = intval(round($origcost * $discount));
    $dis = sprintf("(%.0f%% DISCOUNT)", 100.0 - (100.0 * $discount));

    return <<<HTML
    <li class="currency">
                                    <span class="discount">{$dis}</span>
                                    <div class="tooltiptext">Original price: {$origcost} Gold</div>
                                    {$cost} Gold
                                </li>

    HTML;
}

function do_upgrade_character(array $characters, int $charaid, int $cost, int $dcoins, \Alphafable\Core\Database $db, $mode) {
    foreach ($characters as &$character) {
        if ($character['id'] === $charaid) {
            if ($character['dragon_amulet'] === 0) {
                $gold = $character['gold'];
                $coins = $character['Coins'];

                if ($gold >= $cost) {
                    $gold -= $cost;
                    $coins += $dcoins;

                    if (strcmp($mode, 'upgrade') === 0) {
                        $db->safeQuery('UPDATE `df_characters` SET `gold`=?, `Coins`=?, `dragon_amulet`=1 WHERE `id`=?', [$gold, $coins, $charaid]);
                        $db->safeQuery('UPDATE `df_users` SET `upgrade`=1, `chars_allowed`=6 WHERE `id`=?', [$character['userid']]);
                    }

                    if (strcmp($mode, 'dcoins') === 0) {
                        $db->safeQuery('UPDATE `df_characters` SET `gold`=?, `Coins`=? WHERE `id`=?', [$gold, $coins, $charaid]);
                    }
                }
            }
        }
    }
}

use Alphafable\Core\Security;
$Security = new Security();
session_start();

// Preset messages

$msg_login = <<<HTML
<h2>Upgrade Character</h2>
            <p>Please login using your {$_SERVER['Settings']['SiteName']} username and password</p>
            <form method="post">
                <table align="center">
                    <tr>
                        <td><label for="username"><b>Username:</b></label></td>
                        <td><input type="text" name="username" placeholder="Username" /></td>
                    </tr>
                    <tr>
                        <td><label for="password"><b>Password:</b></label></td>
                        <td><input type="password" name="password" placeholder="Password" /></td>
                    </tr>
                </table><br />
                <input type="submit" name="Login" value="Login" />
            </form>

HTML;

$msg_nouser = <<<HTML
<br />
            <strong>Error - Could not find {$_SERVER['Settings']['SiteName']} user!</strong><br />
            <a href="df-upgrade.php">Back</a><br />

HTML;

// Input handling

$isLoggedIn = false;
$loginError = false;
$sessionid  = false;
$characters = [];

if (filter_has_var(INPUT_GET, 'func')) {
    $funcnum = filter_input(INPUT_GET, 'func', FILTER_VALIDATE_INT);
    if ($funcnum === false) die('Invalid Parameters.');

    if ($funcnum === 2) {
        if (!isset($_SESSION['afname']) || !isset($_SESSION['afpass'])) {
            http_response_code(301);
            header('Location: df-upgrade.php');
        }

        $user = $Database->safeFetch('SELECT * FROM `df_users` WHERE `name`=? LIMIT 1', [$_SESSION['afname']]);

        if (count($user) > 0) {
            $_SESSION['id'] = $user[0]['id'];
            $curpass = $Security->decode($user[0]['pass'], $_SERVER['Settings']['SiteKey']);

            if (strcmp($_SESSION['afpass'], $curpass) === 0) {
                $isLoggedIn = true;
                $characters = $Database->safeFetch('SELECT * FROM `df_characters` WHERE `userid`=?', [$_SESSION['id']]);
            } else {
                $loginError = true;
            }
        } else {
            $loginError = true;
        }
    }

    if ($funcnum === 3) {
        session_unset();
        http_response_code(301);
        header('Location: df-upgrade.php');
    }
}

if (filter_has_var(INPUT_POST, 'Login')) {
    $afname = filter_input(INPUT_POST, 'username');
    $afpass = filter_input(INPUT_POST, 'password');

    $_SESSION['afname'] = $afname;
    $_SESSION['afpass'] = $afpass;
    
    http_response_code(301);
    header('Location: df-upgrade.php?func=2');
}

if (filter_has_var(INPUT_POST, 'character')) {
    $charid = filter_input(INPUT_POST, 'CharID', FILTER_VALIDATE_INT);

    if (is_null($charid) || $charid === false) die('Invalid Character.');
    $_SESSION['CharID'] = explode('|', $charid)[0];

    $sessionid = session_id();
    if ($sessionid === false) {
        session_unset();
        http_response_code(301);
        header('Location: df-upgrade.php');
    }

    $sessionid = $Security->safe_b64encode($sessionid);
    
    http_response_code(301);
    header("Location: df-upgrade.php?CharID={$_SESSION['CharID']}&SessID={$sessionid}");
}

if (filter_has_var(INPUT_GET, 'CharID') && filter_has_var(INPUT_GET, 'mode') && filter_has_var(INPUT_GET, 'SessID')) {
    $charaid = filter_input(INPUT_GET, 'CharID', FILTER_VALIDATE_INT);
    $sessid = filter_input(INPUT_GET, 'SessID');
    $mode = filter_input(INPUT_GET, 'mode');

    $sessid = $Security->safe_b64decode($sessid);
    if (strcmp(session_id(), $sessid) !== 0 || is_null($charaid) || $charaid === false) {
        session_unset();
        http_response_code(301);
        header('Location: df-upgrade.php');
    }

    do_upgrade_character($characters, $charaid, $cost, $dcoins, $DB, $mode);
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <link rel="stylesheet" href="./public/css/style.css" />
        <link rel="shortcut icon" href="./public/favicon.ico" />
        <title><?= $_SERVER['Settings']['SiteName']; ?> | Upgrade</title>
    </head>
    <body>
        <p align="center">
            <a href="index.php"><img src="public/images/logo.png" width="300px" /></a><br />
        </p>
        <div class="panelMsg">
            <table border="0" cellspacing="4" cellpadding="4" align="center">
                <tr>
                    <td><img src="./public/images/upgrade-single.gif" width="200" height="200" alt="Single" /></td>
                    <td align="left">
                        <ul>
                            <li>Get one Amulet for your favorite character!</li>
                            <li>5000 Dragon Coins!</li>
                            <li>Enter Dragon Amulet only areas!</li>
                            <li>Special quests with rare item drops!</li>
                            <li>Use Dragon Amulet items!</li>
                            <li>Make more characters!</li>
                            <li>Re-color your Armor!</li>
                            <li>You pay with in-game gold!</li>
                            <?= get_cost_html($_SERVER['Settings']['DACost'], $_SERVER['Settings']['Discount']); ?>
                        </ul>
                    </td>
                    <td><img src="./public/images/upgrade-coins.gif" width="200" height="200" alt="Coins" /></td>
                    <td align="left">
                        <ul>
                            <li>500 Dragon Coins!</li>
                            <li>You pay with in-game gold!</li>
                            <?= get_cost_html($_SERVER['Settings']['DCCost'], $_SERVER['Settings']['Discount']); ?>
                        </ul>
                    </td>
                </tr>
            </table><br /><br />
            <?php if ($isLoggedIn && !$loginError) { ?>
            <form method="post">
                <h2>Welcome <?= $_SESSION['afname']; ?>!</h2>
                <?php
                if (count($characters) === 0) {
                    echo "No Characters Found";
                } else {
                    echo "Please select a character to upgrade:<br />";
                    echo "<select name=\"CharID\">";

                    foreach ($characters as &$character) {
                        $charId = $character['id'];
                        $charName = $character['name'];
                        echo "<option value=\"{$charId}\" | {$charName}\">{$charName}</option>";
                    }

                    echo "</select>";
                    echo "<input type=\"submit\" name=\"character\" value=\"Upgrade Character\" /><br />";
                }
                ?><br />
                <a href="df-upgrade.php?func=3">Back</a>
            </form>
            <?php
            } elseif (!$isLoggedIn && $loginError) {
                session_unset();
                echo $msg_nouser;
            } else {
                echo $msg_login;
            }
            ?>
        </div><br />
        <section id="linkWindow">
            <span>
                <a href="game/index.php">Play</a> |
                <a href="game/df-signup.php">Register</a> |
                <a href="df-top100.php">Top 100</a> |
                <a href="df-upgrade.php">Upgrade</a>
            </span>
        </section>
    </body>
</html>