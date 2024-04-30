<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: df-signup - v0.2
 */

define('BACKEND', 1);
require sprintf("%s/../includes/config.php", __DIR__);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <link rel="stylesheet" href="../public/css/style.css" />
        <link rel="shortcut icon" href="../public/favicon.ico" />
        <title><?= $_SERVER['Settings']['SiteName']; ?> | Register</title>
    </head>
    <body>
        <p align="center">
            <a href="../index.php"><img src="../public/images/logo.png" width="300px" /></a><br />
        </p>
        <section id="window">
            <section id="outsideWindow">
                <section id="gameWindow">
                    <embed src="<?= $_SERVER['Settings']['SignupSWF']; ?>" FLASHVARS="strFileName=<?= $_SERVER['Settings']['SignupSWF']; ?>" menu="false" allowFullScreen="true" width="<?= $_SERVER['Settings']['GameWidth']; ?>" height="<?= $_SERVER['Settings']['GameHeight']; ?>" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" swLiveConnect="true" />
                </section>
            </section>
        </section>
        <span>
            <a href="index.php">Play</a> |
            <a href="df-signup.php">Register</a> |
            <a href="../df-top100.php">Top 100</a> |
            <a href="../df-upgrade.php">Upgrade</a>
        </span>
    </body>
</html>