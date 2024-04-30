<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 */

define('BACKEND', 1);
require './includes/config.php';
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <link rel="stylesheet" href="./public/css/style.css" />
        <link rel="shortcut icon" href="./public/favicon.ico" />
        <title><?= $_SERVER['Settings']['SiteName']; ?> | Homepage</title>
    </head>
    <body>
        <p align="center">
            <a href="index.php"><img src="public/images/logo.png" width="300px" /></a><br />
        </p>
        <section id="window">
            <section id="outsideWindow">
                <section id="gameWindow">
                    <object type="application/x-shockwave-flash" data="./game/splash.swf" width="750" height="550">
                        <param name="movie" value="./game/splash.swf" />
                        <param name="quality" value="high" />
                        <param name="play" value="true" />
                        <param name="loop" value="true" />
                        <param name="wmode" value="window" />
                        <param name="scale" value="showall" />
                        <param name="menu" value="true" />
                        <param name="devicefont" value="false" />
                        <param name="salign" value="" />
                        <param name="allowScriptAccess" value="sameDomain" />
                    </object>
                </section>
            </section>
        </section>
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