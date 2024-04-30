<?php 

/*
 * AlphaFable (DragonFable Private Server)
 * Made by MentalBlank
 * Updated by Dracovian
 * 
 * File: config - v0.2
 */

require_once 'global.php';

/* Autoloader */

spl_autoload_register(function($name) {
    $arr = explode('\\', $name);
    array_shift($arr);

    $fil = sprintf("%s/%s.php", __DIR__, strtolower(join(DIRECTORY_SEPARATOR, $arr)));
    if (!file_exists($fil)) {
        http_response_code(500);
        die('Internal Server Error.');
    }

    require_once $fil;
});

/* Database Configuration */

$mysql_host = "172.18.0.2";
$mysql_port = "3306";

$mysql_user = "alphafable";
$mysql_pass = "alphafable";
$mysql_name = "alphafable";

use Alphafable\Core\Database;
static $Database = new Database("mysql:host=$mysql_host;port=$mysql_port;dbname=$mysql_name", $mysql_user, $mysql_pass);

/* Get Site Configuration from Database */

if (!filter_has_var(INPUT_SERVER, 'Settings')) {
    $SiteInfo = $Database->safeFetch('SELECT * FROM `df_settings` LIMIT 1')[0];

    $_SERVER['Settings'] = [
        'DateToday'  => date('Y\-m\-j\TH\:i\:s\.B'),
        'SiteKey'    => 'MentalBlank',
        'GameWidth'  => 750,
        'GameHeight' => 550,
        'SiteName'   => $SiteInfo['DFSitename'],
        'SiteNews'   => $SiteInfo['news'],
        'SitePromo'  => $SiteInfo['promo'],
        'LoaderSWF'  => $SiteInfo['loaderSWF'],
        'GameSWF'    => $SiteInfo['gameSWF'],
        'SignupSWF'  => $SiteInfo['signupSWF'],
        'AdminEmail' => $SiteInfo['AdminEmail'],
        'LevelCap'   => intval($SiteInfo['levelCap']),
        'GoldMult'   => intval($SiteInfo['GoldMultiplier']),
        'ExpMult'    => intval($SiteInfo['ExpMultiplier']),
        'DACost'     => 250000,
        'DCCost'     => 50000,
        'Discount'   => 0.8,
    ];
}
