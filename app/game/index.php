<?php

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 */

define('BACKEND', 1);
require sprintf("%s/../includes/config.php", __DIR__);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <link rel="stylesheet" href="../public/css/style.css" />
        <link rel="shortcut icon" href="../public/favicon.ico" />
        <title><?= $_SERVER['Settings']['SiteName']; ?> | Game</title>
    </head>
    <body>
        <p align="center">
            <a href="../index.php"><img src="../public/images/logo.png" width="300px" /></a><br />
        </p>
        <section id="window">
            <section id="outsideWindow">
                <section id="gameWindow">
                    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="<?= $_SERVER['Settings']['GameWidth']; ?>" height="<?= $_SERVER['Settings']['GameHeight']; ?>">
                        <param name="allowScriptAccess" value="sameDomain" />
                        <param name="movie" value="gamefiles/<?= $_SERVER['Settings']['LoaderSWF']; ?>" />
                        <param name="menu" value="false" />
                        <param name="allowFullScreen" value="true" />
                        <param name="flashvars" value="strFileName=<?= $_SERVER['Settings']['GameSWF']; ?>" />
                        <param name="bgcolor" value="#530000" />
                        <embed src="gamefiles/<?= $_SERVER['Settings']['LoaderSWF']; ?>" FLASHVARS="strFileName=<?= $_SERVER['Settings']['GameSWF']; ?>" menu="false" allowFullScreen="true" width="<?= $_SERVER['Settings']['GameWidth']; ?>" height="<?= $_SERVER['Settings']['GameHeight']; ?>" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" swLiveConnect="true" />
                    </object>
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