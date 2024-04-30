<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: df-top100 - v0.2
 */

define('BACKEND', 1);
require './includes/config.php';

function build_top100_listing(array $char, array $classnames, int &$iter) : string {
    $da = ($char['dragon_amulet'] === 1) ? "<font style=\"color:gold;font-weight:bold;\">True</font>" : "False";

    return <<<HTML
    <tr style="display:block;margin-top:8px;">
                                <td>{++$iter}</td>
                                <td><a href="df-chardetail.php?id={$char['id']}">{$char['name']}</a></td>
                                <td>{$char['level']}</td>
                                <td>{$classnames[$char['classid']]}</td>
                                <td>{$char['gold']}</td>
                                <td>{$char['Coins']}</td>
                                <td>{$da}</td>
                            </tr>

    HTML;

}

function is_selected(string $name) {
    return ($name === filter_input(INPUT_GET, 'order')) ? ' selected' : '';
}

$top100_empty = <<<HTML
<tr style="display:block;margin-top:8px;">
                            <td>There are no characters to rank...</td>
                        </tr>

HTML;
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <title><?= $_SERVER['Settings']['SiteName']; ?> | Top 100</title>
        <link rel="stylesheet" href="./public/css/style.css" />
        <link rel="shortcut icon" href="./public/favicon.ico" />
    </head>
    <body>
        <p align="center">
            <a href="index.php"><img src="public/images/logo.png" width="300px" /></a><br />
        </p>
        <div class="panelMsg">
            <form method="get">
                <table border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td><label for="order">Sort by: &nbsp;</label></td>
                        <td>
                            <select name="order">
                                <option value="name"<?= is_selected('name') ?>>Name</option>
                                <option value="level"<?= is_selected('level') ?>>Level</option>
                                <option value="classid"<?= is_selected('classid') ?>>Class ID</option>
                                <option value="gold"<?= is_selected('gold') ?>>Gold</option>
                                <option value="coins"<?= is_selected('coins') ?>>Dragon Coins</option>
                                <option value="dragon_amulet"<?= is_selected('dragon_amulet') ?>>Dragon Amulet</option>
                                <option value="userid"<?= is_selected('userid') ?>>User ID</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><button style="width:100%;height:24px;padding:0;margin-top:8px;" type="submit">Fetch</button></td>
                    </tr>
                    <?php
                    if (filter_has_var(INPUT_GET, 'order')) {
                        $orders = ['name', 'level', 'classid', 'gold', 'coins', 'dragon_amulet', 'userid'];
                        $order = filter_input(INPUT_GET, 'order');
                    
                        if (!in_array($order, $orders))
                            $order = 'level';
                    
                        $chars = $Database->safeFetch('SELECT * FROM `df_characters` ORDER BY ? ASC LIMIT 100', [$order]);
                        
                        if (empty($chars)) {
                            echo $top100_empty;
                        } else {
                            $iter = 0;
                            foreach ($chars as &$char) {
                                $classes = $Database->safeFetch('SELECT `ClassName` FROM `df_class` WHERE `ClassID`=?', [$char['classid']]);
                                echo build_top100_listing($char, $classes, $iter);
                            }
                        }
                    }
                    ?>
                </table>
            </form>
        </div>
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